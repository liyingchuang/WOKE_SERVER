<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\ManageController;
use App\OrderAction;
use App\OrderInfo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends ManageController
{
    /**
     * 订单列表首页显示
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function index(Request $request)
    {
        $keyword = $request->get('keyword', null);
        $status = $request->get('status', null);
        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        if ($status == 1) {
            $state = "order_status";
            $operation = 0;
        } else if ($status == 2) {
            $state = "pay_status";
            $operation = 0;
        } else if ($status == 3) {
            $state = "shipping_status";
            $operation = 0;
        } else if ($status == 4) {
            $state = "order_status";
            $operation = 1;
        } else if ($status == 5) {
            $state = "pay_status";
            $operation = 2;
        } else if ($status == 6) {
            $state = "order_status";
            $operation = 2;
        } else if ($status == 7) {
            $state = "order_status";
            $operation = 3;
        } else if ($status == 8) {
            $state = "order_status";
            $operation = 4;
        }
        $supplier_id = session('supplier_id');
        if ($keyword && $status && $start_time && $end_time) {
            $m = User::select('user_id')->where('mobile_phone', $keyword)->first();
            if (empty($m))
                $m = '';
            else
                $m = $m->user_id;
            $info = OrderInfo::with('user')->where('order_sn', $keyword)->where('supplier_id', $supplier_id)->orWhere('consignee', $keyword)->orWhere('mobile', $keyword)->orWhere('user_id', $m)->where($state, $operation)->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->orderBy('order_id', 'desc')->paginate(15);
        } else if ($keyword && $start_time && $end_time) {
            $m = User::select('user_id')->where('mobile_phone', $keyword)->first();
            if (empty($m))
                $m = '';
            else
                $m = $m->user_id;
            $info = OrderInfo::with('user')->where('order_sn', $keyword)->where('supplier_id', $supplier_id)->orWhere('consignee', $keyword)->orWhere('mobile', $keyword)->orWhere('user_id', $m)->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->orderBy('order_id', 'desc')->paginate(15);
        } else if ($status && $start_time && $end_time) {
            $info = OrderInfo::with('user')->where($state, $operation)->where('supplier_id', $supplier_id)->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->orderBy('order_id', 'desc')->paginate(15);
        } else if ($start_time && $end_time) {
            $info = OrderInfo::with('user')->where('supplier_id', $supplier_id)->where('pay_time', '>=', strtotime($start_time))->where('pay_time', '<=', strtotime($end_time))->orderBy('order_id', 'desc')->paginate(15);
        } else if ($keyword && $status) {
            $m = User::select('user_id')->where('mobile_phone', $keyword)->first();
            if (empty($m))
                $m = '';
            else
                $m = $m->user_id;
            $info = OrderInfo::with('user')->where('order_sn', $keyword)->where('supplier_id', $supplier_id)->orWhere('consignee', $keyword)->orWhere('mobile', $keyword)->orWhere('user_id', $m)->where($state, $operation)->orderBy('order_id', 'desc')->paginate(15);
        } else if ($keyword) {
            $m = User::select('user_id')->where('mobile_phone', $keyword)->first();
            if (empty($m))
                $m = '';
            else
                $m = $m->user_id;
            $info = OrderInfo::with('user')->where('order_sn', $keyword)->where('supplier_id', $supplier_id)->orWhere('consignee', $keyword)->orWhere('mobile', $keyword)->orWhere('user_id', $m)->orderBy('order_id', 'desc')->paginate(15);
        } else if ($status) {
            $info = OrderInfo::with('user')->where($state, $operation)->where('supplier_id', $supplier_id)->orderBy('order_id', 'desc')->paginate(15);
        } else {
            $info = OrderInfo::with('user')->orderBy('order_id', 'desc')->where('supplier_id', $supplier_id)->paginate(15);
        }

        return view('manage.order.index')->with(['info' => $info, 'keyword' => $keyword, 'start_time'=>$start_time, 'end_time'=>$end_time, 'status' => $status]);
    }

    /**
     * 订单详情
     * @param type $id
     */
    public function show($id)
    {
        $info_basic = DB::table('woke_order_info')->where('order_id', $id)->orderBy('order_id', 'desc')->first();
        $info_basic->add_time = date("Y-m-d H:i:s", $info_basic->add_time);
        $info_basic->pay_time = date("Y-m-d H:i:s", $info_basic->pay_time);
        $info_basic->shipping_time = date("Y-m-d H:i:s", $info_basic->shipping_time);
        $info_shop = DB::table('woke_order_goods')->where('order_id', "$id")->get();
        foreach ($info_shop as $key => $val) {
            $goods_number = DB::table('woke_goods')->where('goods_id', "$val->goods_id")->first();
            $info_shop[$key]->surplus = $goods_number->goods_number;
            $info_shop[$key]->subtotal = $val->goods_price * $val->goods_number;
        }
        $info_order = DB::table('woke_order_action')->where('order_id', $id)->orderBy('action_id', 'desc')->get();
        foreach ($info_order as $key => $val) {
            $info_order[$key] = $val;
            $info_order[$key]->log_time = date("Y-m-d H:i:s", $val->log_time);
        }
        return view('manage.order.view')->with(['info_basic' => $info_basic, 'info_shop' => $info_shop, 'info_order' => $info_order]);
    }

    public function store(Request $request)
    {
        $type = $request->get('type');
        $remarks = $request->get('remarks');
        $order_id = $request->get('order_id');
        $invoice_no = $request->get('invoice_no');
        $shipping_express = $request->get('shipping_express');
        $order = OrderInfo::where('order_id', $order_id)->first();
        if ($type == 'express') {
            OrderInfo::where('order_id', $order_id)->update(['shipping_status' => 1, 'order_status' => 5, 'invoice_no' => $invoice_no, 'shipping_express' => $shipping_express, 'shipping_time' => time()]);
            OrderAction::create(['order_id' => $order_id, 'action_note' => $remarks, 'pay_status' => 2, 'shipping_status' => 1, 'order_status' => 5, 'action_user' => session('manage_user_name'), 'log_time' => time()]);
            $this->sendMessage($order->user_id, [], '订单发货', '亲爱的用户您好 您在蜗客商城购买的商品已经发货了', 0);
        }
        if ($type == 'cancel') {
            OrderInfo::where('order_id', $order_id)->update(['shipping_status' => 0, 'order_status' => 2]);
            OrderAction::create(['order_id' => $order_id, 'action_note' => $remarks, 'pay_status' => 0, 'shipping_status' => 0, 'order_status' => 2, 'action_user' => session('manage_user_name'), 'log_time' => time()]);
            $this->sendMessage($order->user_id, [], '订单发货', '亲爱的用户您好 您在蜗客商城的订单取消了', 0);
        }
    }

    public function home(Request $request)
    {
        $supplier_id = session('supplier_id');
        $info = DB::table('woke_order_info')->where('shipping_status', 1)->where('supplier_id', $supplier_id)->orderBy('order_id', 'desc')->paginate(15);
        return view('manage.order.index')->with(['info' => $info, 'keyword' => '', 'start_time'=>"", 'end_time'=>"", 'status' => 'manage.order.home']);
    }

    /**
     * 导出所有支付未发货的订单
     */
    public function export(Request $request)
    {
        $start_time = $request->get('start_time', null);
        $end_time = $request->get('end_time', null);
        // $ymd=date("Y-m-d");
        // $time=strtotime($ymd.'0:00:00');
        // $list =  OrderInfo::with('ordergoods')->where('pay_status',2)->where('add_time','>',$time)->where('shipping_status', 0)->orderBy('order_id', 'desc')->get();
        if ($start_time && $end_time) {
            $list = OrderInfo::with('ordergoods')->where('pay_status', 2)->where('shipping_status', 0)->where('add_time', '>=', strtotime($start_time))->where('add_time', '<=', strtotime($end_time))->orwhere('extension_code', 'group_success')->where('add_time', '>=', strtotime($start_time))->where('add_time', '<=', strtotime($end_time))->orderBy('order_id', 'desc')->get();
        } else {
            $list = OrderInfo::with('ordergoods')->where('pay_status', 2)->where('shipping_status', 0)->orwhere('extension_code', 'group_success')->orderBy('order_id', 'desc')->get();
        }
        // $list =  OrderInfo::with('ordergoods')->where('pay_status',2)->orderBy('order_id', 'desc')->get();
        $result = [];
        $result[] = ['订单号', '支付方式', '下单时间', '收货人', '电话', '省', '市', '区', '地址', '团购状态', '状态', '订单总价', '快递费', '备注', '发票抬头', '商家名称', '购买商品名称', '购买数量', '商品价格', '酒币使用', '付款金额'];
        foreach ($list as $k => $v) {
            if ($v->extension_code == 'group_success') {
                $group_type = "团购-已成团";
            } else {
                $group_type = "";
            }
            foreach ($v->ordergoods as $kk => $vv) {
                $supplier_id = DB::table('woke_goods')->select('supplier_id')->where('goods_id', $vv->goods_id)->first();
                $supplier_name = DB::table('woke_supplier')->select('supplier_name')->where('supplier_id', $supplier_id->supplier_id)->first();
                $result[] = [$v->order_sn . ' ', $v->pay_name, date('Y-m-d H:i', $v->add_time), $v->consignee, $v->mobile, $v->province, $v->city, $v->district, $v->address, $group_type, '已付款', $v->order_amount, $v->shipping_fee, $v->discount, $v->vat_inv_company_name, $supplier_name->supplier_name, $vv->goods_name . $vv->goods_attr, $vv->goods_number, $vv->goods_price, $v->integral_money, $v->order_amount - $v->integral_money];
            }
        }
        Excel::create('已支付未发货订单明细', function ($excel) use ($result) {
            $excel->sheet('订单明细', function ($sheet) use ($result) {
                $sheet->rows($result);
            });
        })->export('xls');
    }

    /**
     * 导出所有支付的订单
     */
    public function exportorder(Request $request)
    {
        $start_time = $request->get('start_time', null);
        $end_time = $request->get('end_time', null);
        //$ymd=date("Y-m-d");
        //$time=strtotime($ymd.'0:00:00');
        //$list =  OrderInfo::with('ordergoods')->where('pay_status',2)->where('add_time','>',$time)->where('shipping_status', 0)->orderBy('order_id', 'desc')->get();
        if ($start_time && $end_time) {
            $list = OrderInfo::with('ordergoods')->where('pay_status', 2)->where('add_time', '>=', strtotime($start_time))->where('add_time', '<=', strtotime($end_time))->orwhere('extension_code', 'group_success')->where('add_time', '>=', strtotime($start_time))->where('add_time', '<=', strtotime($end_time))->orderBy('order_id', 'desc')->get();
        } else {
            $list = OrderInfo::with('ordergoods')->where('pay_status', 2)->orwhere('extension_code', 'group_success')->where('pay_status', 2)->orderBy('order_id', 'desc')->get();
        }
        // $list =  OrderInfo::with('ordergoods')->where('pay_status',2)->orderBy('order_id', 'desc')->get();
        $result = [];
        $result[] = ['订单号', '发货单号', '支付方式', '下单时间', '收货人', '电话', '地址', '团购状态', '状态', '订单总价', '快递费', '备注', '发票抬头', '企业税号', '购买商品名称', '商家名称', '购买数量', '商品价格', '酒币使用', '付款金额'];

        foreach ($list as $k => $v) {
            if ($v->extension_code == 'group_success') {
                $group_type = "团购-已成团";
            } else {
                $group_type = "";
            }


            foreach ($v->ordergoods as $kk => $vv) {
                $kk++;
                $supplier_id = DB::table('woke_goods')->select('supplier_id')->where('goods_id', $vv->goods_id)->first();
                $supplier_name = DB::table('woke_supplier')->select('supplier_name')->where('supplier_id', $supplier_id->supplier_id)->first();
                if ($kk == 1) {
                    $result[] = [$v->order_sn . ' ', $v->invoice_no . ' ', $v->pay_name, date('Y-m-d H:i', $v->add_time), $v->consignee, $v->mobile, $v->address, $group_type, '已付款', $v->order_amount, $v->shipping_fee, $v->discount, $v->vat_inv_company_name, $v->vat_inv_taxpayer_id . ' ', $vv->goods_name . $vv->goods_attr, $supplier_name->supplier_name, $vv->goods_number, $vv->goods_price, $v->integral_money, $v->order_amount - $v->integral_money];
                } else {
                    $result[] = ['', '', '', '', '', '', '', '', '', '', '', '', '', '', $vv->goods_name . $vv->goods_attr, $supplier_name->supplier_name, $vv->goods_number, $vv->goods_price, '', ''];
                }
            }
        }
        Excel::create('已支付订单订单明细', function ($excel) use ($result) {
            $excel->sheet('订单明细', function ($sheet) use ($result) {
                $sheet->rows($result);
            });
        })->export('xls');
    }
}
