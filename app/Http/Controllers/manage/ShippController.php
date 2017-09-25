<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;
use App\Cart;

class ShippController extends ManageController {

    /**
     *
     * 首页
     * @return $this
     */
    public function getIndex() {
        $shipp = DB::table('woke_shipp_fee')->where('supplier_id', Session('supplier_id'))->orderBy('is_default','desc')->get();
        foreach($shipp as $item){
            $exte = DB::table('woke_shipp_fee_extends')->where('shipp_fee_id', $item->shipp_fee_id)->where('supplier_id', Session('supplier_id'))->orderBy('is_default','desc')->get();
            $item->extends = $exte;
            foreach ($item->extends as $val){
                $province = explode(",",$val->province);
                $info = DB::table('woke_area_extends')->whereIn('id', $province)->get();
                $val->ine = $info;
            }
        }
        return view('manage.shipp.index')->with(['shipp'=>$shipp]);
    }

    /**
     *
     * 运费管理
     * @param Request $request
     * @return $this
     */
    public function getProvince(Request $request) {
        $area = DB::table('woke_area')->get();  //区域
//        $area_ext = DB::table('woke_area_extends')->where('parent_id', 0)->get(); // 省市
        $shipp = DB::table('woke_shipp_fee')->where('supplier_id', Session('supplier_id'))->get();
        foreach($shipp as $item){
            $exte = DB::table('woke_shipp_fee_extends')->where('shipp_fee_id', $item->shipp_fee_id)->where('supplier_id', Session('supplier_id'))->get();
            $item->extends = $exte;
            foreach ($item->extends as $val){
                $province = explode(",",$val->province);
                $info = DB::table('woke_area_extends')->whereIn('id', $province)->get();
                $val->ine = $info;
            }
        }
        foreach($area as $item) {
            $area_extends = DB::table('woke_area_extends')->where('area_id', $item->area_id)->get(); // 省市
            $item->area_extends = $area_extends;
            foreach($area_extends as $extend){
                $city = DB::table('woke_area_extends')->where('parent_id', $extend->id)->get();
                $extend->city = $city;
            }
        }
        return view('manage.shipp.goods')->with(['area'=>$area,'shipp'=>$shipp]);
    }

    public function postShipp(Request $request) {
        $express = $request->get('express');
        $shipp_fee_name = $request->get('shipp_name');
        $number = $request->get('number',null);
        $price = $request->get('price',null);
        $cnumber = $request->get('cnumber', null);
        $cprice = $request->get('cprice', null);
        $province_l=$request->get('province', null);
        $city=$request->get('city');
        //如果模板不存在设置为默认模板
        $def = DB::table('woke_shipp_fee')->where('is_default', 1)->where('supplier_id',Session('supplier_id'))->first();
        $exp = DB::table('woke_shipp_fee')->select('shipp_name')->where('supplier_id',Session('supplier_id'))->first();
        if(empty($def)){
            $data['is_default'] = 1;
        }
        if($exp){
            return redirect()->back()->withInput()->withErrors($exp->shipp_name.'已存在,请重新选择！');
        }
        $data['shipp_fee_name'] = $shipp_fee_name;
        $data['shipp_name'] = $express;
        $data['supplier_id'] = Session('supplier_id');
        $data['created_at'] = date("Y-m-d H:i:s",time());
        $data['updated_at'] = date("Y-m-d H:i:s",time());
        $shipp_id = DB::table('woke_shipp_fee')->insertGetId($data);
        if($province_l){
            foreach ($province_l as $k=>$v){
                $p=  implode(',',$v);
                $s=  implode('',array_only($city, $v));
                //1.1次一行可能是多个值 也可能是一个
                //1.2求出一行省的值
                //1.3 求出一行市的值
                $info['shipp_fee_id'] = $shipp_id;
                $info['supplier_id'] = Session('supplier_id');
                $info['province'] = $p;
                $info['city'] = rtrim($s, ",");
                $info['number'] = $cnumber[$k];
                $info['price'] = $cprice[$k];
                $info['is_default'] = 0;
                $info['created_at'] = date("Y-m-d H:i:s",time());
                $info['updated_at'] = date("Y-m-d H:i:s",time());
                DB::table('woke_shipp_fee_extends')->where('supplier_id', Session('supplier_id'))->insert($info);
            }
        }
        if($number&&$price){
            $date['shipp_fee_id'] = $shipp_id;
            $date['supplier_id'] = Session('supplier_id');
            $date['number'] = $number;
            $date['price'] = $price;
            $date['is_default'] = 1;
            $date['created_at'] = date("Y-m-d H:i:s",time());
            $date['updated_at'] = date("Y-m-d H:i:s",time());
            DB::table('woke_shipp_fee_extends')->where('supplier_id', Session('supplier_id'))->insert($date);
        }
        return redirect('/manage/shipp');
    }

    public function getEdit(Request $request){
        $type = $request->get("type");
        $ship_id = $request->get("ship_id");
        //更改默认模板
        if($type=="onship"){
            DB::table('woke_shipp_fee')->where('supplier_id',Session('supplier_id'))->update(['is_default'=>0]);
            DB::table('woke_shipp_fee')->where('shipp_fee_id', $ship_id)->update(['is_default'=>1]);
        }
        if($type== "del"){
            DB::table('woke_shipp_fee')->where('shipp_fee_id',$ship_id)->delete();
            DB::table('woke_shipp_fee_extends')->where('shipp_fee_id',$ship_id)->delete();
        }
        if($type== "delcity"){
            DB::table('woke_shipp_fee_extends')->where('id',$ship_id)->delete();
        }
        return redirect('/manage/shipp');
    }

}