<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class BackgoodOutController extends Controller {

    private $status_back = 5; //0:审核通过,2:寄出 5审核中 6申请被拒绝 7:管理员取消,8:用户自己取消 
    private $status_refund = 1; //1 已退款 0 没退款 3 退款中

    /**
     * 用户退货列表
     *
     * @return \Illuminate\Http\Response
     */

    public function getList(Request $request) {
        $user_id = $request->get('user_id');
        $goods = DB::table('ecs_back_order')
                        ->leftJoin('ecs_back_goods', 'ecs_back_order.back_id', '=', 'ecs_back_goods.back_id')
                        ->select('ecs_back_order.order_id','ecs_back_order.back_type', 'ecs_back_order.back_id', 'ecs_back_order.back_id', 'ecs_back_goods.back_goods_number', 'ecs_back_goods.back_goods_price', 'ecs_back_order.supplier_id', 'ecs_back_order.goods_id', 'ecs_back_order.status_back', 'ecs_back_order.status_refund')
                        ->where('ecs_back_order.user_id', $user_id)->orderBy('ecs_back_order.back_id', 'desc')->paginate(10);
        $result = [];
        foreach ($goods as $k => $value) {
            $result[$k] = $this->get_goods($value->goods_id, $value->order_id);
            if ($value->supplier_id) {
                $supplier = DB::table('ecs_supplier')->select('supplier_name', 'tel')->where('supplier_id', $value->supplier_id)->first();
                $supplier_name = '供货商：' . $supplier->supplier_name;
                $result[$k]->supplier_id = $value->supplier_id;
                $result[$k]->supplier_name = $supplier_name;
                $result[$k]->supplier_tel = $supplier->tel;
            } else {
                $result[$k]->supplier_id = '0';
                $result[$k]->supplier_name = '蜗客自营';
                $result[$k]->supplier_tel = '55';
            }
            $result[$k]->back_id = $value->back_id;
            if($value->status_refund==3){
                $value->status_refund=0; 
            }
            $result[$k]->status_back = $value->status_back;
            if ($value->status_back == 6) {
                $back_action = DB::table('ecs_back_action')
                        ->where('ecs_back_action.back_id', $value->back_id)
                        ->where('ecs_back_action.status_back', 6)
                        ->orderBy('log_time', 'desc')
                        ->first();
                $result[$k]->status_desc = $back_action->action_note;
            } else {
                $result[$k]->status_desc = '';
            }
            $result[$k]->back_type = $value->back_type;
            $result[$k]->status_refund = strval($value->status_refund);
            $result[$k]->back_goods_number = $value->back_goods_number;
            $result[$k]->back_goods_price = $value->back_goods_price;
            $result[$k]->order_amount = $this->get_price($result[$k]->back_goods_price * $result[$k]->back_goods_number);
        }
        return $this->success($result);
    }

    /**
     * 申请退货
     * @return \Illuminate\Http\Response
     */
    public function getAdd(Request $request) {
        //$user_id = $request->get('user_id');
        $order_id = $request->get('order_id');
        $goods_id = $request->get('goods_id');
        $goods = $this->get_goods($goods_id, $order_id);
        return $this->success($goods, 'ok');
    }

    /**
     * 退货进度
     *
     * @return \Illuminate\Http\Response
     */
    public function getShow(Request $request) {
        $user_id = $request->get('user_id');
        $back_id = $request->get('back_id');
        $back_order = DB::table('ecs_back_order')
                ->where('user_id', $user_id)
                ->where('back_id', $back_id)
                ->first();
        $back_action = DB::table('ecs_back_action')->where('back_id', $back_id)->orderBy('action_id', 'asc')->get();
        if (!empty($back_order)) {
            $time=$back_order->add_time+28800;
            $result[0]['time'] = date('n.j G:i',$time);
            $result[0]['title'] = '退货申请';
            $result[0]['desc'] ='买家发起退款申请！原因:<br/>'.$back_order->back_reason;
            $result[0]['user'] = '';
            $supplier=null;
            if ($back_order->supplier_id) {
                $supplier = DB::table('ecs_supplier')->select('supplier_name','company_name','address','tel','province','city','district')->where('supplier_id', $back_order->supplier_id)->first();
                $supplier_name = $supplier->supplier_name;
            } else {
                $supplier_name='蜗客';
            }
            foreach ($back_action as $k=>$value) {
                if($value->status_back==5){
                   if(empty($back_order->how_oos)){
                       if($back_order->supplier_id){
                         $province_name=   DB::table('ecs_region')->select('region_name')->where('region_id', $supplier->province)->first();
                         $city_name=  DB::table('ecs_region')->select('region_name')->where('region_id', $supplier->city)->first();
                         $district_name=  DB::table('ecs_region')->select('region_name')->where('region_id', $supplier->district)->first();
                         $back_order->how_oos=$province_name->region_name.' '.$city_name->region_name.$district_name->region_name.$supplier->address.' '.$supplier->company_name.' 电话'.$supplier->tel;
                       }else{
                          $back_order->how_oos='北京市东城区金鱼池中区24号 电话:010-67011004';  
                       }
                   }
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '同意申请';
                    $result[$k+1]['desc'] ='卖家同意退货申请！并提供退货地址<br/>'.$back_order->how_oos;
                    $result[$k+1]['user'] = $supplier_name; 
                    if ($back_order->back_type==4) {
                        $result[$k+1]['desc']='卖家同意退货申请！请等待卖家退款...';
                    }
                }
                if($value->status_back==2&&$value->status_refund==0){
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '填写快递单号';
                    $result[$k+1]['desc'] ='快递单号 <br/>'.$value->action_note;
                    $result[$k+1]['user'] = '';
                }
                if($value->status_back==1&&$value->status_refund==0){
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '收到寄回的商品';
                    $result[$k+1]['desc'] ='卖家收到你寄回的商品！待退款 <br/>'.$value->action_note;
                    $result[$k+1]['user'] = $supplier_name;
                }
                if($value->status_back==2&&$value->status_refund==1){
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '退款完成';
                    $result[$k+1]['desc'] ='退款已完成，退款金额将在7个工作日内退还至原支付账户，请注意查收，如需其他帮助请与客服中心联系。<br/>'.$value->action_note;
                    $result[$k+1]['user'] =$supplier_name;
                }
                 if($value->status_back==4&&$value->status_refund==1){
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '退款完成';
                    $result[$k+1]['desc'] ='退款已完成，退款金额将在7个工作日内退还至原支付账户，请注意查收，如需其他帮助请与客服中心联系。 <br/>'.$value->action_note;
                    $result[$k+1]['user'] =$supplier_name;
                }
                if($value->status_back==2&&$value->status_refund==3){
                    $result[$k+1]['time'] =  date('n.j H:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '正在退款';
                    $result[$k+1]['desc'] ='卖家已经同意退款！请稍后 <br/>'.$value->action_note;
                    $result[$k+1]['user'] =$supplier_name;
                }
               if($value->status_back==3&&$value->status_refund==1){
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '完成退货退款';
                    $result[$k+1]['desc'] ='完成退货退款  <br/>'.$value->action_note;
                    $result[$k+1]['user'] = $supplier_name;
                }
                if($value->status_back==6){
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '申请被拒绝';
                    $result[$k+1]['desc'] ='卖家拒绝退货 原因如下 <br/>'.$value->action_note;
                    $result[$k+1]['user'] = $supplier_name;
                }  
                if($value->status_back==10){
                    $result[$k+1]['time'] =  date('n.j G:i',$value->log_time+28800);
                    $result[$k+1]['title'] = '从新发起退货申请';
                    $result[$k+1]['desc'] ='买家新发起退款申请！原因:<br/>'.$value->action_note;
                    $result[$k+1]['user'] = '';
                }
            }
            $result=array_values($result);
           
            return $this->success($result, 'ok');
        }
        return $this->error(null, '退货单不存在！');
    }

    /**
     * 确定退货
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postStore(Request $request) {
        $user_id = $request->get('user_id');
        $order_id = $request->get('order_id');
        $goods_id = $request->get('goods_id');
        $desc = $request->get('desc');
        $number = $request->get('number', 1);
        $goods = DB::table('ecs_order_goods')->where('goods_id', $goods_id)->where('order_id', $order_id)->first();
        $order = DB::table('ecs_order_info')->where('order_id', $order_id)->where('user_id', $user_id)->first();
        if (!empty($order) && !empty($goods)) {
            //1.检查是否超过了申请数
            $is_search = DB::table('ecs_back_order')
                    ->where('ecs_back_order.user_id', $user_id)
                    ->where('ecs_back_order.goods_id', $goods_id)
                    ->where('ecs_back_order.order_id', $order_id)
                    ->first();
            if (!empty($is_search)) {
               if($is_search->status_back==6){//用户从新申请
                DB::table('ecs_back_order')
                ->where('back_id', $is_search->back_id)
                ->where('status_back', 6)
                ->update(['status_back' =>5,'refund_money_1'=> $this->get_price($goods->goods_price * $number)]);
                 DB::table('ecs_back_goods')->where('back_id', $is_search->back_id)->update(['status_back' =>5,'back_goods_number'=>$number]); 
                    $GoodsData['back_id']=$is_search->back_id;
                    $GoodsData['action_user']='客户';
                    $GoodsData['status_back']=10;
                    $GoodsData['action_note']=$desc;
                    $GoodsData['log_time']=time() - 28800;
                 DB::table('ecs_back_action')->insert($GoodsData);
                 return $this->success(null, '申请成功！');  
                }
               return $this->error(null, '错误！');
            }
            $back_type=1;
            if($order->shipping_status==0){
                $back_type=4;
            }
            $is = $this->_saveBackOrder($order, $goods, $user_id, $order_id, $goods_id, $desc, $number,$back_type);
            if ($is) {
                return $this->success(null, '申请成功！');
            } else {
                return $this->error(null, '申请失败情稍后在试试！');
            }
        } else {
            return $this->error(null, '对不起，此订单不存在！');
        }
    }

    /**
     * 获取寄回商品地址
     * @param \Illuminate\Http\Request $request
     */
    public function getAddress(Request $request) {
        $back_id = $request->get('back_id');
        $user_id = $request->get('user_id');
        $back_order = DB::table('ecs_back_order')->where('back_id', $back_id)->where('user_id', $user_id)->first();
        if (!empty($back_order)) {
            if(empty($back_order->how_oos)){
                 if($back_order->supplier_id){
                       $supplier = DB::table('ecs_supplier')->select('supplier_name','company_name','address','tel','province','city','district')->where('supplier_id', $back_order->supplier_id)->first();
                         $province_name=   DB::table('ecs_region')->select('region_name')->where('region_id', $supplier->province)->first();
                         $city_name=  DB::table('ecs_region')->select('region_name')->where('region_id', $supplier->city)->first();
                         $district_name=  DB::table('ecs_region')->select('region_name')->where('region_id', $supplier->district)->first();
                         $back_order->how_oos=$province_name->region_name.' '.$city_name->region_name.$district_name->region_name.$supplier->address.' '.$supplier->company_name.' 电话'.$supplier->tel;
                       }else{
                          $back_order->how_oos='北京市东城区金鱼池中区24号 电话:010-67011004';  
                       }
                
               $return['back_address'] =$back_order->how_oos ;    
                
                
            }else{
               $return['back_address'] =$back_order->how_oos ;  
            }
            $return['back_id'] = $back_id;
            return $this->success($return, '成功！');
        }
        return $this->error(null, '退货单不存在或者状态不对！');
    }

    /**
     * 填写退货快递信息
     * @param \Illuminate\Http\Request $request
     */
    public function postExpress(Request $request) {
        $invoice_no = $request->get('invoice_no');
        $shipping_name = $request->get('shipping_name');
        $back_id = $request->get('back_id');
        $user_id = $request->get('user_id');
        DB::table('ecs_back_order')
                ->where('back_id', $back_id)
                ->where('user_id', $user_id)
                ->where('status_back', 0)
                ->update(['invoice_no' => $invoice_no, 'shipping_name' => $shipping_name,'status_back' =>2]);
        DB::table('ecs_back_goods')->where('back_id', $back_id)->update(['status_back' =>2]);
        $GoodsData['back_id']=$back_id;
        $GoodsData['action_user']='客户';
        $GoodsData['status_back']=2;
        $GoodsData['action_note']=$shipping_name.' '.$invoice_no;
        $GoodsData['log_time']=time() - 28800;
        DB::table('ecs_back_action')->insert($GoodsData);
        return $this->success(null, '快递填写成功！');
    }

    private function _saveBackOrder($order, $goods, $user_id, $order_id, $goods_id, $desc, $number,$back_type=1) {
        //1.ecs_back_order表组建数据
        $OrderData['order_sn'] = $order->order_sn;
        $OrderData['order_id'] = $order_id;
        $OrderData['add_time'] = time() - 28800;
        $OrderData['user_id'] = $user_id;
        $OrderData['consignee'] = $order->consignee;
        $OrderData['address'] = $order->address;
        $OrderData['country'] = 1;
        $OrderData['province'] = $order->province;
        $OrderData['city'] = $order->city;
        $OrderData['district'] = $order->district;
        $OrderData['mobile'] = $order->mobile;
        $OrderData['shipping_fee'] = $order->shipping_fee;
        $OrderData['supplier_id'] = $order->supplier_id;
        $OrderData['goods_name'] = $goods->goods_name;
        $OrderData['goods_id'] = $goods_id;
        $OrderData['back_reason'] = $desc;
        $OrderData['refund_money_1'] = $this->get_price($goods->goods_price * $number);
        $OrderData['status_back'] = 5;
        $OrderData['back_pay'] = 2;
        $OrderData['back_type'] = $back_type;
        $BackOrderID = DB::table('ecs_back_order')->insertGetId($OrderData);
        //2.ecs_back_goods 组建数据
        $GoodsData['back_id'] = $BackOrderID;
        $GoodsData['goods_id'] = $goods_id;
        $GoodsData['goods_name'] = $goods->goods_name;
        $GoodsData['goods_sn'] = $goods->goods_sn;
        $GoodsData['back_goods_number'] = $number;
        $GoodsData['back_goods_price'] = $goods->goods_price;
        $GoodsData['status_back'] = 5;
        $ok = DB::table('ecs_back_goods')->insert($GoodsData);
        if ($BackOrderID && $ok) {
            return true;
        }
        return false;
    }

    /**
     * 获取商品信息
     * @param type $goods_id
     * @param type $order_id
     * @return type
     */
    private function get_goods($goods_id, $order_id) {
        $goods = DB::table('ecs_order_goods')
                ->leftJoin('ecs_goods', 'ecs_goods.goods_id', '=', 'ecs_order_goods.goods_id')
                ->select('ecs_goods.goods_id', 'ecs_order_goods.goods_name', 'ecs_order_goods.order_id', 'ecs_order_goods.goods_number', 'ecs_order_goods.goods_price', 'ecs_goods.goods_thumb')
                ->where('ecs_order_goods.goods_id', $goods_id)
                ->where('ecs_order_goods.order_id', $order_id)
                ->first();
        return $goods;
    }

    /**
     * 给出给出钱数
     * @param type
     * @return type
     */
    private function get_price($price) {
        $price = number_format(floatval($price), 2, '.', '');
        return $price;
    }

}
