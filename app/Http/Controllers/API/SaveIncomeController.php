<?php

namespace App\Http\Controllers\API;

use App\Income;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SaveIncomeController extends Controller
{
    public function index(){
        $incomes = Income::where('user_id',Auth::id())->get(['id','month','amount']);
        if(count($incomes)>0){
            $response = ['data'=> $incomes];
            return response($response, 200);
        }else{
            $response = ['data'=> []];
            return response($response, 200);
        }
    }
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'month' => 'required',
            'amount' => 'required|integer',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $result = Income::create(['user_id'=> Auth::id(),'month'=>$request->month,'amount'=>$request->amount]);
        if($request){
            return response(['message'=>'Your Imcome is Saved!'], 201);
        }else{
            return response(['message'=>'Something Wrong'],402);
        }
        
    }
    public function incomInfo(){
        $incomes = Income::where('user_id',Auth::id())->get('month');

        $months =["01","02","03","04","05","06","07","08","09","10","11","12"];
        $res = [];
        if(count($incomes) < 12){
            foreach($incomes as $k=>$v){
                if(in_array($v['month'],$months)){
                    unset($months[array_search($v['month'],$months)]);
                }
            }
        }else{
            $months = [];
        }
        if(count($months)>0){
            foreach($months as $k=>$value){
                $res[] = ['month'=>$value,'name'=>date("F", mktime(null, null, null, $value))];
            }
        }
        return response(['data'=>$res], 200); 
    }
}
