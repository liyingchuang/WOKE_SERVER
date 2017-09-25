<?php

namespace App\Http\Controllers\manage;

use App\Events\IntegralEvent;
use App\Http\Controllers\ManageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\User;
use App\Events\SendMessageEvent;

class TransferController extends ManageController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $id = $request->get('id');
        $phone = $request->get('phone', null);
        $type = $request->get('type', null);
        $alipay_id = $request->get('alipay_id', null);
        $operator = session('manage_user_name');
        $action = $request->get('action', null);
        $reason = $request->get('reason', null);
        if($type == 1){
            $field = 'type';
            $value = '2';
        }elseif($type == 2){
            $field = 'type';
            $value = '3';
        }elseif($type == 3){
            $field = 'type';
            $value = '4';
        }else{
            $field = 'type';
            $value = '0';
        }
        if($phone && $type){
            $info = DB::table('woke_user_alipay')->where('id', $phone)->orwhere('mobile_phone', 'like', "%$phone%")->where($field, $value)->paginate(15);
        }else if($type){
            $info = DB::table('woke_user_alipay')->where($field, $value)->paginate(15);
        }else if($phone){
            $info = DB::table('woke_user_alipay')->where('id', $phone)->orwhere('mobile_phone', 'like', "%$phone%")->paginate(15);
        }else{
            $info = DB::table('woke_user_alipay')->orderBy('alipay_time', 'desc')->paginate(15);
        }
        if( $action == 1 ){
            DB::table('woke_user_alipay')->where('id', $id)->update(['type' => 3, 'flowing'=>$alipay_id, 'operator'=>$operator, 'oper_time'=>time()]);
            $aliinfo=DB::table('woke_user_alipay')->where('id', $id)->first();
            if(!empty($aliinfo)) {
                $user = DB::table('woke_users')->select('user_id')->where('mobile_phone', $aliinfo->mobile_phone)->first();
                event(new SendMessageEvent($user->user_id, '提醒', '恭喜您的酒币提现成功了！', 1));
            }
            exit;
        }
        if( $action == 2 ){
            DB::table('woke_user_alipay')->where('id', $request->id)->update(['reason'=>$reason, 'type' => 4, 'operator'=>$operator, 'oper_time'=>time()]);
            $user_phone = DB::table('woke_user_alipay')->select('mobile_phone')->where('id', $request->id)->first();
            User::where('mobile_phone', $user_phone->mobile_phone)->update(['alipay_id'=>null]);
            $aliinfo=DB::table('woke_user_alipay')->where('id', $request->id)->first();
            if(!empty($aliinfo)){
                $user = DB::table('woke_users')->select('user_id')->where('mobile_phone', $aliinfo->mobile_phone)->first();
                event(new IntegralEvent($user->user_id,  $aliinfo->alipay_money, '用户您好，您的提现驳回金额为'.$aliinfo->alipay_money.'，请检查支付宝账号和姓名是否一致!，获得酒币', 11));
            }
            exit;
        }
        return view('manage.transfer.index')->with(['info'=>$info, 'phone'=>$phone, 'type'=>$type]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $debut = env('APP_DEBUG');
        if($debut){
            $path= storage_path('pingxx/develop.pem');
        }else{
            $path= storage_path('pingxx/release.pem');
        }
        \Pingpp\Pingpp::setApiKey(env('PINGXX_API_KEY'));
        //\Pingpp\Pingpp::setAppId(env('PINGXX_API_ID'));                                           // 设置 APP ID
       // \Pingpp\Pingpp::setPrivateKeyPath($path);
        try {
            $tr = \Pingpp\Transfer::create(
                array(
                    'amount'    => 100,// 订单总金额, 人民币单位：分（如订单总金额为 1 元，此处请填 100,企业付款最小发送金额为 1 元）
                    'order_no'  => date('mdHis') . mt_rand(1, 9999),// 企业转账使用的商户内部订单号。wx(新渠道)、wx_pub 规定为 1 ~ 50 位不能重复的数字字母组合、unionpay 为不 1~16 位数字
                    'currency'  => 'cny',
                    'channel'   => 'wx',// 目前支持 wx(新渠道)、 wx_pub、unionpay
                    'app'       => array('id' => env('PINGXX_API_ID')),
                    'type'      => 'b2c',// 付款类型，当前仅支持 b2c 企业付款。
                    'recipient' => 'o9zpMs9jIaLynQY9N6yxcZ',// 接收者 id， 为用户在 wx(新渠道)、wx_pub 下的 open_id
                    'description' => 'testing',
                    'extra' => array(
                        'user_name' => 'User Name', //收款人姓名。当该参数为空，则不校验收款人姓名，选填
                        'force_check' => false// 是否强制校验收款人姓名。仅当 user_name 参数不为空时该参数生效，选填
                    )
                )
            );
            echo $tr;// 输出 Ping++ 返回的企业付款对象 Transfer
        } catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            echo($e->getHttpBody());
        }
    }

    /**
     *用户提现详情
     *
     * @param Request $request
     * @return $this
     */
    public function show($id) {
        $ali = DB::table('woke_user_alipay')->where('id', $id)->first();
        $user = User::where('mobile_phone', $ali->mobile_phone)->first();
        return view('manage.transfer.dateils')->with(['ali'=>$ali, 'user'=>$user]);

    }

}
