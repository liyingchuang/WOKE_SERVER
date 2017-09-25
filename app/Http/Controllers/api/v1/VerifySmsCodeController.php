<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Utils\LeancloudSend;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class VerifySmsCodeController extends Controller {

    /**
     * leancloud发送短信
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function send(Request $request) {
        $ip = $request->getClientIp();
		$code = rand(100000, 999999);
        $mobile_phone = $request->get('mobile_phone');
        $data['mobile_phone'] = $mobile_phone;
        $data['code'] = $code;
        $data['ip'] = $ip;
        $data['add_time'] = time();
        $auth = new LeancloudSend();
        $message = $auth->leancloudMessage($mobile_phone,$code);
        $message_main = json_decode($message, true);
        if ($message_main) {
            return $this->error(null, $message_main['error']); //发送失败
        } else {
            $info_sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->first(); //发送成功
            if (empty($info_sms)) {
                $count_sms = DB::table('ecs_sms_send')->where('ip', '=', $ip)->where('add_time', '<=', time())->where('add_time', '>=', time() - 3600)->count();
                if ($count_sms <= 5) {
                    DB::table('ecs_sms_send')->insert($data);
                } else {
                    return $this->error(null, '同一个ip一小时只能请求5次'); //同一个ip一小时只能请求5次
                }
            } else {
                DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->update($data);
            }
        }
        return $this->success(null, '发送成功'); //绑定失败
    }

    /**
     * submail发送短信
     * @param Request $request
     */
    public function getSendCode(Request $request){
        $ip = $request->getClientIp();
        $code = rand(100000, 999999);
        $mobile_phone = $request->get('mobile_phone');
        $data['mobile_phone'] = $mobile_phone;
        $data['code'] = $code;
        $data['ip'] = $ip;
        $data['add_time'] = time();
        //1个ip 1小时分钟只能发送6次
        $count_sms = DB::table('ecs_sms_send')->where('ip', '=', $ip)->where('add_time', '<=', time())->where('add_time', '>=', time() - 3600)->count();
        if ($count_sms > 5) {
            return $this->error(null, '频繁请求'); //发送失败
        }
        //1个手机号1分钟只能发送一次
        $info_sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->where('add_time', '>', time()-60)->first();
        if(!empty($info_sms)){
            return $this->error(null, '1分钟只能发送一次'); //发送失败
        }
        $leancloudSend= new LeancloudSend();
        $info=$leancloudSend->sendSMS($mobile_phone,$code);
        if(!empty($info)&&$info->status=='success'){ //发送成功
            $info_sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->first();
            if (empty($info_sms)) {
               DB::table('ecs_sms_send')->insert($data);
            } else {
                DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->update($data);
            }
            return $this->success(null, '发送成功'); //绑定失败
        }
        return $this->error(null, '发送失败'); //发送失败
    }

    /**
     *
     * sumail 短信发送失败
     * web hook
     */
    public function webhook(Request $request){
        $mobile_phone=$request->get('address');
        $ip = $request->getClientIp();
        $code = rand(100000, 999999);
        $d['mobile_phone'] = $mobile_phone;
        $d['code'] = $code;
        $d['ip'] = $ip;
        $d['add_time'] = time();
        $auth = new LeancloudSend();
        $message = $auth->leancloudMessage($mobile_phone,$code);
        $message_main = json_decode($message, true);
        if ($message_main) {
            //return $this->error(null, $message_main['error']); //发送失败
        }else{
            $info_sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->first();
            if (empty($info_sms)) {
                DB::table('ecs_sms_send')->insert($d);
            } else {
                unset($d['mobile_phone']);
                DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->update($d);
            }
        }
       // Log::info($request->all());
       // return $this->success(null, '发送成功'); //绑定失败
    }
}
