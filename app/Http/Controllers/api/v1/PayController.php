<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class PayController extends Controller {

    /**
     * 选择支付方式
     *
     * @return \Illuminate\Http\Response
     */
    public function postIndex(Request $request) {
        //$user_id=$request->get('user_id');
        $order_id=$request->get('order_id');
        $pay_id=$request->get('pay_id');
        $pay_name = DB::table('ecs_payment')->select('pay_name','pay_code','pay_id')->where('pay_id', $pay_id)->where('enabled', 1)->first();
        DB::table('ecs_order_info')->where('order_id', $order_id)->update(['pay_id' => $pay_id,'pay_name'=>$pay_name->pay_name]);
        return $this->success(null, '修改成功');
    }

}
