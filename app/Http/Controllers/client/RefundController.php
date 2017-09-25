<?php
namespace App\Http\Controllers\client;


use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;


class RefundController extends ApiController
{
    /**
     * 自动退款处理
     */
    public function webhook()
    {
        $event = json_decode(file_get_contents("php://input"));
        if (!isset($event->type)) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
            exit("fail");
        }
        switch ($event->type) {
            case "refund.succeeded":
                $order_sn = $event->data->object->charge_order_no;//订单号
                $reason = $event->data->object->description;//退款原因
                $created = $event->data->object->created;//退款时间
                // 开发者在此处加入对退款异步通知的处理代码
                $group = DB::table('woke_group_info')->select('user_id', 'group_id', 'order_amount', 'pay_status')->where('order_sn', $order_sn)->first();
                if($group->pay_status != 3){
                        $data['user_id'] = $group->user_id;
                        $data['refund_price'] = $group->order_amount;
                        $data['refund_time'] = $created;
                        $data['reason'] = $reason;
                    DB::table('woke_group_refund')->insert($data);
                    DB::table('woke_group_info')->where('order_sn', $order_sn)->update(['pay_status'=>3]);
                    DB::table('woke_order_info')->where('order_sn', $order_sn)->update(['extension_code'=>"refund",'pay_status'=>0]);
                    DB::table('woke_group_open')->where('group_id', $group->group_id)->update(['group_status'=>3]);
                }
                header($_SERVER['SERVER_PROTOCOL'] . ' 200 OK');
                break;
            default:
                header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request');
                break;
        }
    }
}
