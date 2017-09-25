<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\User;
use App\UserToken;
use Illuminate\Http\Request;
use App\Events\IntegralEvent;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;
class AlipayController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index', 'testphone', 'addinfo'];
    }

    /**
     * 检测用户是否第一次支付宝提现
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request){
        $user_id = $request->get('user_id');
        $user = User::select('alipay_id', 'user_money', 'mobile_phone')->where('user_id', $user_id)->first();
        if($user->alipay_id){
            $info = DB::table('woke_user_alipay')->select('alipay_id', 'user_name', 'mobile_phone')->where('alipay_id', $user->alipay_id)->first();
            return $this->success(['user_money' => $user->user_money, 'alipay_id' => $info->alipay_id, 'user_name' => $info->user_name, 'mobile_phone' => $info->mobile_phone], '您的支付宝账号已绑定');
        }else{
            return $this->error(['user_money' => $user->user_money, 'mobile_phone' => $user->mobile_phone], '您是第一次提现');
        }
    }

    /**
     * 检测手机验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testphone(Request $request){
        $mobile_phone = $request->get('mobile_phone');
        $code = $request->get('code');
        $sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
        if (empty($sms)) {
            return $this->error(null, '您的手机号与验证码不匹配！'); //手机号跟验证码不匹配
        }else{
            return $this->success( 1, '验证成功！');
        }
    }

    /**
     * 添加申请提现信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addinfo(Request $request){
        $alipay_id = $request->get('alipay_id');
        $user_id = $request->get('user_id');
        $user_name = $request->get('user_name');
        $ali_money = $request->get('ali_money');
        $mobile_phone = $request->get('mobile_phone');
        $type = $request->get('type'); //提现状态1.2.3
        $mode = $request->get('mode');//提现类型1.2
        $money = User::select('user_money')->where('user_id', $user_id)->first();
        if(($money->user_money - $ali_money) >= 0){
            $result['alipay_id'] = $alipay_id;
            $result['user_name'] = $user_name;
            $result['alipay_money'] = $ali_money;
            $result['mobile_phone'] = $mobile_phone;
            $result['type'] = $type;
            $result['mode'] = $mode;
            $result['alipay_time'] = time();
            $result['user_id'] =$user_id;
            $data['alipay_id'] = $alipay_id;
            User::where('user_id', $user_id)->update($data);
            DB::table('woke_user_alipay')->insert($result);
            $user = DB::table('woke_user_alipay')->where('alipay_id', $alipay_id)->orderBy('id', 'desc')->first();//查出最后一次提现的信息
            event(new IntegralEvent($user_id, - $ali_money, '恭喜您申请提现成功！', 10));
            return $this->success($user, '您的申请已提交');
        }else{
            return $this->error(0, '请确认您的提现金额');
        }
    }
}











?>