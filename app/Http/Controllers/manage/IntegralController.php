<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;
use App\PrizeGoods;

class IntegralController extends ManageController {

    /**
     * 
     * 积分管理
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex() {
        $integral = DB::table('ecs_integral')->orderBy('sort_order', 'asc')->get();
        foreach($integral as $k => $v){
            $integral[$k]->create_at = date("Y年m月d日", $v->create_at);
        }
        return view('manage.integral.index')->with(['integral' => $integral]);
    }

    /**
     * 积分项目编辑保存
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postStore(Request $request) {
        $id = $request->get('id');
        $data['name'] = $request->get('name');
        $data['type'] = $request->get('type');
        $data['min'] = $request->get('min');
        $data['sort_order'] = $request->get('sort_order');
        $data['integral'] = $request->get('integral');
        $data['create_at'] = time();
        DB::table('ecs_integral')->where('id', $id)->update($data);
        return redirect("manage/integral");
    }

    /*
    * 兑换商品列表显示
    * @param \Illuminate\Http\Request $request
    */

    public function getShow(Request $request) {
        $keyword = $request->get('keyword', null);
        if ($keyword)
            $goods_list = DB::table('ecs_integral_goods')->where('is_delete',0)->where('name', 'like', "%$keyword%")->orderBy('id', 'desc')->paginate(15);
        else
            $goods_list = DB::table('ecs_integral_goods')->where('is_delete',0)->orderBy('id', 'desc')->paginate(15);
        return view('manage.integral.integral_list')->with(['goods_list' => $goods_list, 'keyword' => $keyword,]);
    }

    /*
    *是否显示
    * @param \Illuminate\Http\Request $request
    */
    public function getEdit(Request $request) {
        $id =  $request->get('id');
        $now = $request->get('now');
        if ($now != 1)
            $now = 1;
        else
            $now = 0;
        DB::table('ecs_integral_goods')->where('id', $id)->update(array('is_show' => $now));
    }

    /*
   *删除积分商品
   * @param $id
   */
    public function getDelete($id) {
        $data['is_delete'] = 1;
        $data['created_at'] = date("Y:m:d H:i:s");
        DB::table('ecs_integral_goods')->where('id', $id)->update($data);
    }

    /*
    *显示积分添加页面
    * @param \Illuminate\Http\Request $request
    */
    public function getAddshow(Request $request) {
        return view('manage.integral.integral_create');
    }


    /*
    *验证商品名航唯一性
    * @param \Illuminate\Http\Request $request
    */
    public function postOnly(Request $request) {
        $name = $request->get('name');
        $info = DB::table('ecs_integral_goods')->where('name',$name)->first();
        if(!empty($info))
            return 1;//可以添加
    }

    /*
   *添加商品
   * @param \Illuminate\Http\Request $request
   */

    public function postAdd(Request $request) {
        $data['name'] = $request->get('name');
        $data['integral'] = $request->get('integral');
        $data['sort_order'] = $request->get('sort_order');
        $data['is_show'] = $request->get('is_show');
        $data['goods_thumb'] = $request->get('idcard_front');
        $data['goods_number'] = $request->get('goods_all_number');
        $data['goods_all_number'] = $request->get('goods_all_number');
        $data['goods_desc'] = $request->get('editorValue');
        $data['created_at'] = date("Y:m:d H:i:s");
        DB::table('ecs_integral_goods')->insert($data);
        return redirect("manage/integral/show");
    }

    /*
  *跳转商品编辑页面
  * @param $id
  */
    public function getUpdateshow($id) {
        $goods_list = DB::table('ecs_integral_goods')->where('is_delete',0)->where('id',$id)->first();
        return view('manage.integral.integral_edit')->with(['goods_list' => $goods_list]);
    }

    /*
   *修改保存商品
   * @param \Illuminate\Http\Request $request
   */
    public function postSave(Request $request) {
        $id = $request->get('id');
        $data['name'] = $request->get('name');
        $data['integral'] = $request->get('integral');
        $data['sort_order'] = $request->get('sort_order');
        $data['is_show'] = $request->get('is_show');
        $data['goods_thumb'] = $request->get('idcard_front');
        $data['goods_number'] = $request->get('goods_all_number');
        $data['goods_all_number'] = $request->get('goods_all_number');
        $data['goods_desc'] = $request->get('editorValue');
        $data['updated_at'] = date("Y:m:d H:i:s");
        DB::table('ecs_integral_goods')->where('id',$id)->update($data);
        return redirect("manage/integral/show");
    }

    /*
    * 兑换发送商品订单显示
    * @param \Illuminate\Http\Request $request
    */

    public function getOrdershow(Request $request) {
        $keyword = $request->get('keyword', null);
        if ($keyword)
            $goods_order = DB::table('ecs_integral_orders')->where('username', 'like', "%$keyword%")->orderBy('id', 'desc')->paginate(15);
        else
            $goods_order = DB::table('ecs_integral_orders')->orderBy('id', 'desc')->paginate(15);
        return view('manage.integral.integral_order')->with(['goods_order' => $goods_order, 'keyword' => $keyword,]);
    }

    /**
     * 积分订单订单列表查看
     * @param type $id
     */
    public function integral_order_view($id,$prize) {
        $info_integral =  DB::table('ecs_integral_orders')->where('id', "$id")->where('prize', "$prize")->first();
        if($prize == 0){
            $info_integral_goods =  DB::table('ecs_integral_goods')->where('id', "$info_integral->goods_id")->get();
        }
        else{
            $info_integral_goods =  PrizeGoods::select('id','name','image' ,'num','fragment','type')->where('id', "$info_integral->goods_id")->get();
            foreach($info_integral_goods as $k => $v){
                $info_integral_goods[$k]->created_time = date("Y年m月d日", $v->created_time);
            }
        }
        return view('manage.integral.integral_order_view')->with(['info_integral' => $info_integral, 'info_integral_goods' => $info_integral_goods, 'prize' => $prize]);
    }

    /**
     * 积分订单发货
     * @param type $id
     */
    public function postOperation(Request $request) {
        $id = $request->get('id');
        $data['shipping_number'] = $request->get('shipping_number');
        $data['shipping_name'] = $request->get('shipping_express');
        $data['shipping_status'] = 1;
        $data['shipping_time'] = date("Y:m:d H:i:s");
        DB::table('ecs_integral_orders')->where('id', $id)->update($data);
    }
}
