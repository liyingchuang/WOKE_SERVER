<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\LeancloudSend;
use Illuminate\Support\Facades\DB;

class LeancloudController extends Controller {

    /**
     * 第三方登录手机信息发送
     * @param \Illuminate\Http\Request $request
     */
    public function getIndex(Request $request) {
        $ip = $request->getClientIp();
        $mobile_phone = $request->get('mobile_phone');
        $data['mobile_phone'] = $mobile_phone;
        $data['code'] = rand(100000, 999999);
        $data['ip'] = $ip;
        $data['add_time'] = time();


        $auth = new LeancloudSend();
        $message = $auth -> leancloudMessage($mobile_phone);
        $message_main = json_decode($message,true);
        if($message_main){
            return $this->error(null, $message_main['error']);//发送失败
        }else{
            $info_sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->first();//发送成功
            if(empty($info_sms)){
                $count_sms = DB::table('ecs_sms_send')->where('ip', '=', $ip)->where('add_time', '<=', time())->where('add_time', '>=', time()-3600)->count();
                if($count_sms<=5){
                    DB::table('ecs_sms_send')->insert($data);
                }else{
                    return $this->error(null, '同一个ip一小时只能请求5次');//同一个ip一小时只能请求5次
                }
            }else{
                DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->update($data);
            }
        }
        return $this->success(null, '发送成功');//绑定失败
    }

    /**
     * 第三方信息验证
     * @param \Illuminate\Http\Request $request
     */
    public function getInfo(Request $request) {
        $mobile_phone = $request->get('mobile_phone');
        $type = $request->get('type');
        $token = $request->get('token');
        $code = $request->get('code');
        $info_sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
        if (!empty($info_sms)) {
            $info_users = DB::table('ecs_users')->where('mobile_phone', $mobile_phone)->first();
            if (!empty($info_users)) {
                if ($type == 'sina_id')
                    $data['sina_id'] = $token;
                else if ($type == 'weixin_id')
                    $data['weixin_id'] = $token;
                else
                    $data['qq_id'] = $token;
                DB::table('ecs_users')->where('user_id', $info_users->user_id)->update($data);
                return $this->success(null, '成功登录页面！'); //用户存在，跳转页面
            }else {
                return $this->error(null, '绑定本站内账号', 2); //绑定本站内账号
            }
        } else {
            return $this->error(null, '手机号跟验证码不匹配'); //手机号跟验证码不匹配
        }
    }

    /**
     * 第三方绑定账号
     * @return \Illuminate\Http\Response
     */
    public function getAdd(Request $request) {
        $type = $request->get('type');
        $token = $request->get('token');
        $user_name = $request->get('user_name');
        $mobile_phone = $request->get('mobile_phone');
        if ($type == 'sina_id')
            $data['sina_id'] = $token;
        else if ($type == 'weixin_id')
            $data['weixin_id'] = $token;
        else
            $data['qq_id'] = $token;
        $data['user_name'] = $user_name;
        $data['mobile_phone'] = $mobile_phone;
        $data['password'] = $request->get('password');
        $info_users_phone = DB::table('ecs_users')->where('mobile_phone', $mobile_phone)->first();
        if (empty($info_users_phone)) {
            $info_users = DB::table('ecs_users')->where('user_name', $user_name)->get();
            if (!empty($info_users)) {
                return $this->error(null, '用户名重复，重新填写', -1); //用户名重复，重新填写
            } else {
                $is_users = DB::table('ecs_users')->insert($data);
                if ($is_users) {
                    return $this->success(null, '成功登录页面!'); //绑定成功
                } else {
                    return $this->error(null, '绑定失败', -2); //绑定失败
                }
            }
        } else {
            return $this->error(null, '手机号已经注册', 2); //手机号已经注册
        }
    }

}
