<?php

namespace App\Http\Controllers\API;

use App\Wish;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishListController extends Controller
{
    public function index(){
        $wishlists = Wish::where('user_id',Auth::id())->get(['id','item','price']);
        if(count($wishlists)>0){
            $response = ['data'=> $wishlists];
            return response($response, 200);
        }else{
            $response = ['data'=> []];
            return response($response, 200);
        }
    }
    public function uncomplete(){
        $uncomplete = Wish::where('user_id',Auth::id())->where('status','=',0)->get(['id','item','price']);
        if(count($uncomplete)>0){
            $response = ['data'=> $uncomplete];
            return response($response, 200);
        }else{
            $response = ['data'=> []];
            return response($response, 200);
        }
    }
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'item' => 'required|string',
            'price' => 'required|integer',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $result = Wish::create(['user_id'=> Auth::id(),'item'=>$request->item,'price'=>$request->price]);
        if($request){
            return response(['message'=>'Your Wish Item is Saved!'], 201);
        }else{
            return response(['message'=>'Something Wrong'],402);
        }
        
    }
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'item' => 'required|string',
            'price' => 'required|integer',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $wish = Wish::where('id',$request->id)->where('user_id',Auth::id())->first();
        $wish->item = $request->item;
        $wish->price = $request->price;
        $wish->save();

        if($wish){
            return response(['message'=>'Your Wish is Updated!'], 200);
        }else{
            return response(['message'=>'Something Wrong'],402);
        }
        
    }
    public function delete($id){
        $res = Wish::where('id',$id)->where('user_id',Auth::id())->delete();
        if($res){
            return response(['message'=>'Your Wish Item id Deleted!'], 200);
        }else{
            return response(['message'=>'Something Wrong'],402);
        }
    }
}
