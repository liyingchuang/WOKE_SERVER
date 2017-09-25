<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\ManageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PrizeGoods;
use App\Prize;
use App\PrizeGoodsRelation;
use App\PrizeOrders;


class PrizeController extends ManageController {

    /*
    * 奖励池商品列表显示
    * @param \Illuminate\Http\Request $request
    */

    public function getIndex(Request $request) {
        $keyword = $request->get('keyword', null);
        if ($keyword)
            $prize = PrizeGoods::select('id','name' ,'desc','image','num','fragment','min','max','type','is_show','is_delete','created_time')->where('is_delete',1)->where('name', 'like', "%$keyword%")->paginate(10);
        else
            $prize = PrizeGoods::select('id','name' ,'desc','image','num','fragment','min','max','type','is_show','is_delete','created_time')->where('is_delete',1)->paginate(10);
        foreach($prize as $k => $v){
            $prize[$k]->created_time = date("Y年m月d日", $v->created_time);
        }
        return view('manage.prize.index')->with(['prize' => $prize, 'keyword' => $keyword,]);
    }

    /*
    *显示奖励池添加页面
    * @param \Illuminate\Http\Request $request
    */
    public function getAddshow(Request $request) {
        return view('manage.prize.prize_create');
    }

    /*
   *验证商品名唯一性
   * @param \Illuminate\Http\Request $request
   */
    public function postOnly(Request $request) {
        $name = $request->get('name');
        $info = PrizeGoods::select('id')->where('name',$name)->first();
        if(!empty($info))
            return 1;//可以添加
    }

    /**
     * 关联碎片兑换商品
     * @return Response
     */
    public function getFragment(Request $request) {
        $prize = PrizeGoods::select('id','name')->where('num','>',0)->where('type',1)->where('is_delete',1)->get();
        if(!empty($prize)){
            $info = "<option value=''>请选择</option>";
            foreach ($prize as $key => $val) {
                $info.="<option value='" . $val->id . "'  > $val->name </option>";
            }
            echo $info;
        }else{
            $info = "<option value=''>请添加实物</option>";
            echo $info;
        }
        exit;

    }

    /*
   *添加奖励池商品
   * @param \Illuminate\Http\Request $request
   */

    public function postAdd(Request $request) {
        $data['name'] = $request->get('name');
        $data['fragment'] = $request->get('fragment');
        $data['num'] = $request->get('num');
        $data['type'] = $request->get('type');
        $data['min'] = $request->get('min');
        $data['max'] = $request->get('max');
        $data['trade_goods'] = $request->get('trade_goods');
        $data['image'] = $request->get('idcard_front');
        $data['is_show'] = $request->get('is_show');
        $data['desc'] = $request->get('editorValue');
        $data['created_time'] = time();
        $data['is_delete'] = 1;
        PrizeGoods::create($data);
        return redirect("manage/prize/index");
    }

    /*
    *删除积分商品
    * @param $id
    */

    public function getDelete($id) {
        $prize_goods = PrizeGoods::where('id', $id)->first();
        $prize_goods->is_delete = 0;
        $prize_goods->save();
    }

    /*
    *跳转商品编辑页面
    * @param $id
    */
    public function getUpdateshow($id) {
        $prize_goods = PrizeGoods::where('id', $id)->where('is_delete',1)->first();
        $prize = PrizeGoods::select('id','name')->where('num','>',0)->where('type',1)->where('is_delete',1)->get();
        return view('manage.prize.prize_edit')->with(['prize_goods' => $prize_goods,'prize' => $prize]);
    }

    /*
      *修改保存商品
      * @param \Illuminate\Http\Request $request
      */
    public function postSave(Request $request) {
        $str = $request->get('idcard_front');
        $ins = strstr($str, '/');
        if ($ins) {
            $image = substr($str,strripos($str,"/")+1);
        }
        ELSE{
            $image = $str;
        }
        $prize_goods = PrizeGoods::where('id', $request->get('id'))->first();
        $prize_goods->name = $request->get('name');
        $prize_goods->fragment = $request->get('fragment');
        $prize_goods->num = $request->get('num');
        $prize_goods->type = $request->get('type');
        $prize_goods->trade_goods = $request->get('trade_goods');
        $prize_goods->is_show = $request->get('is_show');
        $prize_goods->image = $image;
        $prize_goods->desc = $request->get('editorValue');
        $prize_goods->save();
        return redirect("manage/prize/index");
    }

    /*
   * 发布活动列表显示
   * @param \Illuminate\Http\Request $request
   */

    public function getShow(Request $request) {
        $keyword = $request->get('keyword', null);
        if ($keyword)
            $activity = Prize::select('id','name','size' ,'image','is_show','is_delete','start_time','end_time','created_time')->where('is_delete',1)->where('name', 'like', "%$keyword%")->paginate(10);
        else
            $activity = Prize::select('id','name','size' ,'image','is_show','is_delete','start_time','end_time','created_time')->where('is_delete',1)->paginate(10);
        foreach($activity as $k => $v){
            $activity[$k]->created_time = date("Y年m月d日", $v->created_time);
        }
        return view('manage.prize.prize_activity')->with(['activity' => $activity, 'keyword' => $keyword,]);
    }

    /*
   *删除积分商品
   * @param $id
   */

    public function getAcdelete($id) {
        $prize_activity = Prize::where('id', $id)->first();
        $prize_activity->is_delete = 0;
        $prize_activity->save();
    }

    /*
    *是否发布活动
    * @param Request $request
    */

    public function postPrice(Request $request) {
        $id = $request->get('id');
        $now = $request->get('now');
        if($now == 0){
            $prize = Prize::where('is_show',1)->where('is_delete',1)->first();
            if(!empty($prize)){
                return 1;
            }else{
                $prize_goods = PrizeGoodsRelation::where('prize_id',$id)->first();
                if(empty($prize_goods)){
                    return 0;
                }else{
                    $prizes = Prize::where('id', $id)->first();
                    $prizes->is_show = 1;
                    $prizes->save();
                    return 2;
                }
            }
        }else{
            $prize = Prize::where('id', $id)->first();
            $prize->is_show = 0;
            $prize->save();
            return 2;
        }
    }

    /*
   *显示活动添加页面
   * @param \Illuminate\Http\Request $request
   */
    public function getShowactivity(Request $request) {
        $prize_goods = PrizeGoods::select('id','name','image' ,'num','fragment','type')->where('num','>', 0)->where('is_delete',1)->get();
        return view('manage.prize.prize_activity_create')->with(['prize_goods' => $prize_goods]);
    }

    /*
  *添加活动入库
  * @param \Illuminate\Http\Request $request
  */
    public function postAddactivity(Request $request) {
        $data['name'] = $request->get('name');
        $data['size'] = $request->get('size');
        $data['start_time'] = $request->get('start_time');
        $data['end_time'] = $request->get('end_time');
        $data['image'] = $request->get('idcard_front');
        $data['created_time'] = time();
        $data['is_show'] = 0;
        $data['is_delete'] = 1;
        $prize_id = Prize::create($data);

        $id = $request->get('chk_role',null);
        if($id){
            $probability = $request->get('probability');
            foreach($id as $key){
                $relation['prize_id'] = $prize_id->id;
                $relation['goods_id'] = $key;
                $relation['probability'] = $probability[$key];
                PrizeGoodsRelation::create($relation);
            };
        }
        return redirect("manage/prize/show");
    }

    /*
    *跳转商品编辑页面
    * @param $id
    */
    public function getUpdateshowactivity($id) {
        $goods_list = DB::table('ecs_integral_goods')->where('is_delete',0)->where('id',$id)->first();
        $prize = Prize::find($id);
        $prize_goods = PrizeGoods::select('id','name','image' ,'num','fragment','type')->where('num','>', 0)->where('is_delete',1)->get();
        $prize_goods_relation = PrizeGoodsRelation::select('prize_id','goods_id' ,'probability')->where('prize_id', $id)->get();
        $probability = [];
        $array = [];
        foreach ($prize_goods_relation as $k => $v) {
            $array[$k]['goods_id'] = $v->goods_id;
            $probability[$v->goods_id] = $v->probability;
        }
        $prize_array = array_column($array, 'goods_id');
        return view('manage.prize.prize_activity_edit')->with(['prize' => $prize,'goods_list' => $goods_list,'prize_goods' => $prize_goods,'prize_array' => $prize_array,'probability' => $probability]);
    }

    /*
      *修改保存活动
      * @param \Illuminate\Http\Request $request
      */
    public function postSaveactivity(Request $request) {
        $str = $request->get('idcard_front');
        $ins = strstr($str, '/');
        if ($ins) {
            $image = substr($str,strripos($str,"/")+1);
        }
        ELSE{
            $image = $str;
        }
        $prize_id = $request->get('id');
        $prize_goods = Prize::where('id', $prize_id)->first();
        $prize_goods->name = $request->get('name');
        $prize_goods->size = $request->get('size');
        $prize_goods->image = $image;
        $prize_goods->start_time = $request->get('start_time');
        $prize_goods->end_time = $request->get('end_time');
        $prize_goods->save();

        PrizeGoodsRelation::where('prize_id',$prize_id)->delete();
        $id = $request->get('chk_role',null);
        if($id){
            $probability = $request->get('probability');
            foreach($id as $key){
                $relation['prize_id'] = $prize_id;
                $relation['goods_id'] = $key;
                $relation['probability'] = $probability[$key];
                PrizeGoodsRelation::create($relation);
            };
        }
        return redirect("manage/prize/show");
    }

    /*
   * 兑换发送商品订单显示
   * @param \Illuminate\Http\Request $request
   */

    public function getOrdershow(Request $request) {
        $keyword = $request->get('keyword', null);
        if ($keyword)
            $prize_orders = PrizeOrders::select('id','user_id','goods_id','prize_id','created_time','username','address','mobile','shipping_status','order_sn','shipping_name','shipping_time','shipping_number')->where('name', 'like', "%$keyword%")->paginate(10);
        else
            $prize_orders = PrizeOrders::select('id','user_id','goods_id','prize_id','created_time','username','address','mobile','shipping_status','order_sn','shipping_name','shipping_time','shipping_number')->paginate(10);
        foreach($prize_orders as $k => $v){
            $prize_orders[$k]->created_time = date("Y年m月d日", $v->created_time);
        }
        return view('manage.prize.prize_order')->with(['prize_orders' => $prize_orders, 'keyword' => $keyword,]);
    }

    /**
     * 积分订单订单列表查看
     * @param type $id
     */
    public function getPrizeordershow($id) {
        $info_prize_orders = PrizeOrders::select('id','user_id','goods_id','prize_id','created_time','username','address','mobile','shipping_status','order_sn','shipping_name','shipping_time','shipping_number')->where('id', "$id")->first();
        $info_prize_orders_goods =  PrizeGoods::select('id','name','image' ,'num','fragment','type')->where('id',$info_prize_orders->goods_id)->get();
        foreach($info_prize_orders_goods as $k => $v){
            $info_prize_orders_goods[$k]->created_time = date("Y年m月d日", $v->created_time);
        }
        return view('manage.prize.prize_order_view')->with(['info_prize_orders' => $info_prize_orders, 'info_prize_orders_goods' => $info_prize_orders_goods]);
    }

    /**
     * 积分订单发货
     * @param type $id
     */
    public function postOperation(Request $request) {
        $id = $request->get('id');
        $info_prize_orders = PrizeOrders::where('id', $id)->first();
        $info_prize_orders->shipping_number = $request->get('shipping_number');
        $info_prize_orders->shipping_name = $request->get('shipping_express');
        $info_prize_orders->shipping_status = 1;
        $info_prize_orders->shipping_time = date("Y-m-d H:i:s");
        $info_prize_orders->save();
    }
}
