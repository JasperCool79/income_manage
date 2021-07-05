<?php

namespace App\Http\Controllers\API;

use App\Wish;
use App\Income;
use App\Record;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ManageIncomeController extends Controller
{
    public function add(Request $request){
        try{
            $record = Record::where('user_id',Auth::id())->where('month',$request->month)->first();
            if($record){
                $record->wish_id = $record->wish_id.",".$request->id;
                $record->save();
                $wishItem = Wish::where('id',$request->id)->where('user_id',Auth::id())->first();
                $wishItem->status = 1;
                $wishItem->save();
            }else{
                Record::create(['user_id'=>Auth::id(),'month'=>$request->month,'wish_id'=>$request->id]);
                $wishItem = Wish::where('id',$request->id)->where('user_id',Auth::id())->first();
                $wishItem->status = 1;
                $wishItem->save();
            }
            $response = ['message'=> "Add Record Success"];
            return response($response, 200);
        }catch(Exception $e){
            $response = ['message'=> $e->getMessage()];
            return response($response, 200);
        }

        
        
    }
    public function getRecords(){
        $records = Record::where('user_id',Auth::id())->get(['id','wish_id','month']);
        $res =[];
        if(count($records)>0){
            foreach($records as $key=>$value){
                $wish_items = [];
                foreach(explode(",",$value['wish_id']) as $k=>$item){
                    $wish = Wish::where('id',$item)->where('user_id',Auth::id())->first();
                    $wish_items[$k] = ['item'=>$wish->item,'price'=>$wish->price];
                }
                $res[$value['month']] = ['id'=>$value['id'],'wish_items'=>$wish_items,'month'=>$value['month']];
            }
        }
        if(count($res)>0){
            $response = ['data'=> $res];
            return response($response, 200);
        }else{
            $response = ['data'=> []];
            return response($res, 200);
        }
    }
    public function getTotal(){
        $incomes = Income::where('user_id',Auth::id())->sum('amount');
        $response = ['sum'=>$incomes];
        return response($response,200);
    }
}
