<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\OrderInfo;


class FinanceController extends ManageController
{

    /**
     * 统计管理
     *
     */
    public function index(Request $request)
    {
        $start_time = $request->get('start_time', null);
        $end_time = $request->get('end_time', null);
        $goods_name = $request->get('goods_name', null);
        $result = [];
        if($start_time && $end_time && $goods_name){
            $info = DB::table('woke_order_info')->join('woke_order_goods', 'woke_order_info.order_id', '=', 'woke_order_goods.order_id')->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->where('goods_name', 'like', "%$goods_name%")->orderBy('pay_time', 'desc')->paginate(15);
            $num = DB::table('woke_order_info')->join('woke_order_goods','woke_order_info.order_id','=','woke_order_goods.order_id')->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->where('goods_name', 'like', "%$goods_name%")->sum('goods_price');
        }else if($start_time && $end_time){
            $info = OrderInfo::with('ordergoods')->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->where('pay_status',2)->where('shipping_status', 0)->orderBy('pay_time', 'desc')->paginate(15);
            foreach ($info as $key=>$value){
                $result[$key]['orderinfo'] = ['order_id'=>$value->order_id,'order_sn'=>$value->order_sn,'consignee'=>$value->consignee,'address'=>$value->address,'goods_amount'=>$value->goods_amount,'order_amount'=>$value->order_amount,'integral_money'=>$value->integral_money,'pay_name'=>$value->pay_name,'vat_inv_company_name'=>$value->vat_inv_company_name,'vat_inv_taxpayer_id'=>$value->vat_inv_taxpayer_id, 'pay_time'=>$value->pay_time];
                foreach($value->ordergoods as $k=>$v){
                    $result[$key]['ordergoods'][$k] = ['order_id'=>$v->order_id,'goods_name'=>$v->goods_name,'goods_sn'=>$v->goods_sn,'goods_number'=>$v->goods_number,'goods_price'=>$v->goods_price,'goods_attr'=>$v->goods_attr];
                }
            }
            $num = DB::table('woke_order_info')->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->sum('goods_amount');
        }else if($goods_name){
            $info = DB::table('woke_order_info')->join('woke_order_goods','woke_order_info.order_id','=','woke_order_goods.order_id')->where('goods_name', 'like', "%$goods_name%")->orderBy('pay_time', 'desc')->paginate(15);
            $num = DB::table('woke_order_info')->join('woke_order_goods','woke_order_info.order_id','=','woke_order_goods.order_id')->where('goods_name', 'like', "%$goods_name%")->sum('goods_price');
        }else{
            //查出所有订单号
            $info =  OrderInfo::with('ordergoods')->where('pay_status',2)->where('shipping_status', 0)->orderBy('order_id', 'desc')->paginate(15);
            foreach ($info as $key=>$value){
                $result[$key]['orderinfo'] = ['order_id'=>$value->order_id,'order_sn'=>$value->order_sn,'consignee'=>$value->consignee,'address'=>$value->address,'goods_amount'=>$value->goods_amount,'order_amount'=>$value->order_amount,'integral_money'=>$value->integral_money,'pay_name'=>$value->pay_name,'vat_inv_company_name'=>$value->vat_inv_company_name,'vat_inv_taxpayer_id'=>$value->vat_inv_taxpayer_id, 'pay_time'=>$value->pay_time];
                foreach($value->ordergoods as $k=>$v){
                    $result[$key]['ordergoods'][$k] = ['order_id'=>$v->order_id,'goods_name'=>$v->goods_name,'goods_sn'=>$v->goods_sn,'goods_number'=>$v->goods_number,'goods_price'=>$v->goods_price,'goods_attr'=>$v->goods_attr];
                }
            }

            $num = DB::table('woke_order_info')->sum('goods_amount');
        }
        return view('manage.finance.index')->with(['info'=>$info, 'num'=>$num, 'result'=>$result, 'goods_name'=>$goods_name]);
    }

    /**
     * 销售排行
     * @param Request $request
     * @return $this
     */
    public function index_deta(Request $request)
    {
        $start_time = $request->get('start_time', null);
        $end_time = $request->get('end_time', null);
        $goods_name = $request->get('goods_name', null);
        if( $start_time && $end_time && $goods_name){
            $info = DB::table('woke_order_goods')->join('woke_order_info', 'woke_order_goods.order_id','=','woke_order_info.order_id')->select('goods_id', 'goods_name', 'goods_sn', 'goods_number', 'goods_price', DB::raw('SUM(goods_number) as goods_number'), DB::raw('SUM(goods_number)*goods_price as goods_all_price'))->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->where('goods_name', 'like', "%$goods_name%")->groupBy('goods_id')->orderBy('goods_number', 'desc')->paginate(15);
            $sum_price = DB::table('woke_order_goods')->join('woke_order_info', 'woke_order_goods.order_id','=','woke_order_info.order_id')->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->sum(DB::raw('goods_number * goods_price'));
        }else if( $start_time && $end_time ) {
            $info = DB::table('woke_order_goods')->join('woke_order_info', 'woke_order_goods.order_id','=','woke_order_info.order_id')->select('goods_id', 'goods_name', 'goods_sn', 'goods_number', 'goods_price', DB::raw('SUM(goods_number) as goods_number'), DB::raw('SUM(goods_number)*goods_price as goods_all_price'))->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->groupBy('goods_id')->orderBy('goods_number', 'desc')->paginate(15);
            $sum_price = DB::table('woke_order_goods')->join('woke_order_info', 'woke_order_goods.order_id','=','woke_order_info.order_id')->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->sum(DB::raw('goods_number * goods_price'));
        }else {
            $info = DB::table('woke_order_goods')->select('goods_id', 'goods_name', 'goods_sn', 'goods_number', 'goods_price', DB::raw('SUM(goods_number) as goods_number'), DB::raw('SUM(goods_number)*goods_price as goods_all_price'))->groupBy('goods_id')->orderBy('goods_number', 'desc')->paginate(15);
            $sum_price = DB::table('woke_order_goods')->sum(DB::raw('goods_number * goods_price'));
        }
        return view('manage.finance.index_deta')->with(['info' => $info, 'sum_price' => $sum_price]);
    }

    /**
     * 导出销售明细
     *
     */
//    public function deta_excel(Request $request)
//    {
//        $start_time = $request->input('start_time', null);
//        $end_time = $request->get('end_time', null);
//        $goods_name = $request->get('goods_name', null);
//        $result=[];
//        if($start_time && $end_time && $goods_name) {
//            $info = DB::table('woke_order_goods')->join('woke_order_info', 'woke_order_goods.order_id','=','woke_order_info.order_id')->where('pay_status', 2)->where('shipping_status', 0)->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->where('goods_name', 'like', "%$goods_name%")->get();
//            $result[] = ['订单号', '商品名', '商品编号', '购买数量', '商品价格', '购买日期'];
//            foreach ($info as $key => $value) {
//                $result[] = [$value->order_sn . ' ', $value->goods_name, $value->goods_sn, $value->goods_number, $value->goods_price, date('Y-m-d H:i', $value->pay_time)];
//            }
//        }else if($start_time && $end_time){
//            $info =  OrderInfo::with('ordergoods')->where('pay_status',2)->where('shipping_status', 0)->where('pay_time','>=',strtotime($start_time))->where('pay_time','<=',strtotime($end_time))->orderBy('order_id', 'desc')->get();
//            $result[]=['订单号','收货人','收货地址','总金额（元）','付款金额（元）','酒币金额（元）','付款方式','发票抬头','企业税号','购买日期'];
//            foreach ($info as $key=>$value){
//                $result[] = [$value->order_sn.' ',$value->consignee,$value->address,$value->goods_amount,$value->order_amount-$value->integral_money,$value->integral_money,$value->pay_name,$value->vat_inv_company_name,$value->vat_inv_taxpayer_id,date('Y-m-d H:i', $value->pay_time)];
//                $result[] = ['商品名','商品编号','商品数量','商品价格（元）','商品类别','','',''];
//                foreach ($value->ordergoods as $k=>$v){
//                    $result[] = [$v->goods_name,$v->goods_sn,$v->goods_number,$v->goods_price,$v->goods_attr];
//                }
//            }
//        }else{
//            $info =  OrderInfo::with('ordergoods')->where('pay_status',2)->where('shipping_status', 0)->orderBy('order_id', 'desc')->get();
//            $result[]=['订单号/商品名','收货人/商品编号','收货地址/商品数量','总金额/商品价格（元）','付款金额（元）','酒币金额（元）','付款方式','发票抬头','企业税号','购买日期','所属店铺'];
//            foreach ($info as $key=>$value){
//                $supplier = DB::table('woke_supplier')->select('supplier_name')->where('supplier_id',$value->supplier_id)->first();
//                $result[] = [$value->order_sn.' ',$value->consignee,$value->address,$value->order_amount,$value->order_amount-$value->integral_money,$value->integral_money,$value->pay_name,$value->vat_inv_company_name,$value->vat_inv_taxpayer_id,date('Y-m-d H:i', $value->pay_time),$supplier->supplier_name];
//                foreach ($value->ordergoods as $k=>$v){
//                    $result[] = [$v->goods_name,$v->goods_sn,$v->goods_number,$v->goods_price];
//                }
//            }
//        }
//        Excel::create($start_time.'到'.$end_time.'订单明细',function($excel) use ($result){
//            $excel->sheet('订单明细', function($sheet) use ($result){
//                $sheet->rows($result);
//            });
//        })->export('xls');
//
//    }

    /**
     *
     * 导出销售排行
     *
     * @param Request $request
     */
//    public function rank_excel(Request $request)
//    {
//        $start_time = $request->input('start_time', null);
//        $end_time = $request->get('end_time', null);
//        $result = [];
//        if($start_time && $end_time){
//            $rank = DB::table('woke_order_goods')->join('woke_order_info','woke_order_goods.order_id','=','woke_order_info.order_id')->select('goods_id', 'goods_name', 'goods_sn', 'goods_number', 'goods_price', DB::raw('SUM(goods_number) as goods_number'), DB::raw('SUM(goods_number)*goods_price as goods_all_price'))->groupBy('goods_id')->where('pay_time','>=',strtotime($start_time))->where('pay_time','<=',strtotime($end_time))->orderBy('goods_number', 'desc')->get();
//            $result[] = ['商品ID','商品名','商品编号','销售数量','商品单价（元）','金额（元）'];
//            foreach($rank as $key=>$value){
//                $result[] = [$value->goods_id,$value->goods_name,$value->goods_sn,$value->goods_number,$value->goods_price,$value->goods_all_price];
//            }
//        }else{
//            $rank = DB::table('woke_order_goods')->select('goods_id', 'goods_name', 'goods_sn', 'goods_number', 'goods_price', DB::raw('SUM(goods_number) as goods_number'), DB::raw('SUM(goods_number)*goods_price as goods_all_price'))->groupBy('goods_id')->orderBy('goods_number', 'desc')->get();
//            $result[] = ['商品ID','商品名','商品编号','销售数量','商品单价（元）','金额（元）'];
//            foreach($rank as $key=>$value){
//                $result[] = [$value->goods_id,$value->goods_name,$value->goods_sn,$value->goods_number,$value->goods_price,$value->goods_all_price];
//            }
//        }
//        Excel::create($start_time.'到'.$end_time.'订单明细',function($excel) use ($result){
//            $excel->sheet('订单明细', function($sheet) use ($result){
//                $sheet->rows($result);
//            });
//        })->export('xls');
//
//    }

}