<?php

namespace App\Http\Controllers\client\Response\V1;

use App\CollectGoods;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use Illuminate\Http\Request;



class CollectController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = $request->get('user_id');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user_id = $request->get('user_id');
        $goods_id = $request->get('goods_id');
        $collectGoods=CollectGoods::where('user_id',$user_id)->where('goods_id',$goods_id)->first();
        $is_collect=0;
        if(!empty($collectGoods)){
            if($collectGoods->is_attention==1){
                CollectGoods::where('user_id',$user_id)->where('goods_id',$goods_id)->update(['is_attention'=>0]);
                $is_collect=0;
            }else{
                CollectGoods::where('user_id',$user_id)->where('goods_id',$goods_id)->update(['is_attention'=>1]);
                $is_collect=1;
            }
        }else{
            CollectGoods::create(['user_id'=>$user_id,'goods_id'=>$goods_id,'add_time'=>time(),'is_attention'=>1]);
            $is_collect=1;
        }
        return $this->success(['collect'=>$is_collect],'');
    }

}
