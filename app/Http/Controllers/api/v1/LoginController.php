<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\UserToken;
use App\User;
use App\Http\Controllers\ApiController;

class LoginController extends ApiController {
    public function __construct()
    {
        $this->middleware('api_guest', ['except' => ['postValidation','postSignin','getValidationcode','postRegister']]);
    }
    /**
     * 第三方信息验证
     * @param \Illuminate\Http\Request $request
     */
    public function postValidation(Request $request) {
        $mobile_phone = $request->get('mobile_phone');
        $type = $request->get('type');
        $token = $request->get('openid');
        $code = $request->get('code');
        $info_sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
        if (!empty($info_sms)) {
            $info_users = DB::table('ecs_users')->where('mobile_phone', $mobile_phone)->first();
            if (!empty($info_users)) {
                if ($type == 'sina')
                    $data['sina_id'] = 'sina_' . $token;
                else if ($type == 'weixin')
                    $data['weixin_id'] = 'weixin_' . $token;
                else
                    $data['qq_id'] = 'qq_' . $token;
                DB::table('ecs_users')->where('user_id', $info_users->user_id)->update($data);
                $result['token'] = $this->getUserToken($info_users->user_id);
                $result['user_id'] = $info_users->user_id;
                $result['email'] = $info_users->email;
                $result['mobile_phone'] = $info_users->mobile_phone;
                $result['user_name'] = $info_users->user_name;
                $result['nickname'] = '';
                return $this->success($result, '绑定成功！'); //用户存在，跳转页面
            }else {
                return $this->error(null, '请完善信息！', 2); //绑定本站内账号
            }
        } else {
            return $this->error(null, '验证码不匹配请检查手机号！'); //手机号跟验证码不匹配
        }
    }

    /**
     * 第三方绑定账号
     * @return \Illuminate\Http\Response
     */
    public function postSignin(Request $request) {
        $type = $request->get('type');
        $token = $request->get('openid');
        $user_name = $request->get('user_name');
        $mobile_phone = $request->get('mobile_phone');
        $password = $request->get('password');
        $invite_code = $request->get('invite_code', null);
        if ($type == 'sina')
            $data['sina_id'] = 'sina_' . $token;
        else if ($type == 'weixin')
            $data['weixin_id'] = 'weixin_' . $token;
        else
            $data['qq_id'] = 'qq_' . $token;
        $data['user_name'] = $user_name;
        $data['mobile_phone'] = $mobile_phone;
        $data['ec_salt'] = substr(uniqid(rand()), -6);
        $data['password'] = md5(md5($password) . $data['ec_salt']);
        $info_users_phone = DB::table('ecs_users')->where('mobile_phone', $mobile_phone)->first();
        if (empty($info_users_phone)) {
            $info_users = DB::table('ecs_users')->where('user_name', $user_name)->get();
            if (!empty($info_users)) {
                return $this->error(null, '用户名已存在，请重新填写'); //用户名重复，重新填写
            } else {
                if($invite_code){
                    $verification_code = DB::table('ecs_verification_code')->select('user_id')->where('verifycode',$invite_code)->first();
                    if(empty($verification_code)){
                        return $this->error(null, '你的邀请码填写不正确！');
                    }
                    $user_id=DB::table('ecs_users')->insertGetId($data);
                    $info_invitation_user = $this->info_invitation_user($user_id,$verification_code->user_id,$request->getClientIp());
                    if(!$info_invitation_user)
                        return $this->error(null, '不能填写自己且每个人只能被邀请一次！');
                }else{
                    $user_id=DB::table('ecs_users')->insertGetId($data);
                }
               // $user = User::create($data);
                $result['token'] = $this->getUserToken($user_id);
                $result['user_id'] = strval($user_id);
                $result['email'] = '';
                $result['mobile_phone'] = $data['mobile_phone'];
                $result['user_name'] = $data['user_name'];
                $result['nickname'] = '';
                return $this->success($result, '成功登录页面!'); //绑定成功
            }
        } else {
            return $this->error(null, '手机号已经注册'); //手机号已经注册
        }
    }



    /**
     *验证验证码跟用户唯一性
     * @param \Illuminate\Http\Request $request
     */
    public function getValidationcode(Request $request){
        $code = $request->get('code');
        $type = $request->get('type','');
        $mobile = $request->get('mobile');
        $info_sms = $this -> only_mobile($code,$mobile);
        if (!empty($info_sms)) {
            if($type=='reset'){
                return $this->success(null, '验证成功！');
            }
            $info_user = DB::table('ecs_users')->where('mobile_phone', $request->get('mobile'))->first();
            if (!empty($info_user))
                return $this->error(null, '手机号已存在，请重新填写！');
            else
                return $this->success(null, '验证成功！');
        } else {
            return $this->error(null, '验证码不匹配请检查手机号！'); //手机号跟验证码不匹配
        }
    }

    /*
     * 验证手机是否被注册
     * */
    private function only_mobile($code,$mobile){
        $sms = DB::table('ecs_sms_send')->where('mobile_phone', $mobile)->where('code', $code)->first();
        return $sms;
    }

    /**
     * 用户注册
     * @param \Illuminate\Http\Request $request
     */
    public function postRegister(Request $request) {
        $ec_salt = substr(uniqid(rand()), -6);
        $invite_code = $request->get('invite_code', null);
        $code = $request->get('code');
        $data_user['user_name'] = $request->get('nickname');
        $data_user['mobile_phone'] = $request->get('mobile_phone');
        $info_sms = $this -> only_mobile($code,$request->get('mobile_phone'));
        
        if (!empty($info_sms)) {
            $info_user_name = DB::table('ecs_users')->where('user_name', $request->get('nickname'))->orWhere('mobile_phone', $request->get('mobile_phone'))->first();
            if (!empty($info_user_name))
                return $this->error(null, '用户名或手机号已存在，请重新填写');
        } else {
            return $this->error(null, '验证码不匹配请检查手机号！'); //手机号跟验证码不匹配
        }
        $data_user['password'] = md5(md5($request->get('pwd')).$ec_salt);
        $data_user['ec_salt'] = $ec_salt;
        $data_user['reg_time'] = time();
        if($invite_code){
            $verification_code = DB::table('ecs_verification_code')->select('user_id')->where('verifycode',$invite_code)->first();
            if(empty($verification_code)){
                return $this->error(null, '你的邀请码填写不正确！');
            }
            $user_id = DB::table('ecs_users')->insertGetId($data_user);
            $info_invitation_user = $this->info_invitation_user($user_id,$verification_code->user_id,$request->getClientIp());
            if(!$info_invitation_user)
                return $this->error(null, '不能填写自己且每个人只能被邀请一次！');
        }else{
            $user_id = DB::table('ecs_users')->insertGetId($data_user);
        }
        $info_users = DB::table('ecs_users')->where('user_id', $user_id)->first();
        $result['token'] = $this->getUserToken($info_users->user_id);
        $result['user_id'] = $info_users->user_id;
        $result['email'] = $info_users->email;
        $result['mobile_phone'] = $info_users->mobile_phone;
        $result['user_name'] = $info_users->user_name;
        $result['nickname'] = '';
        return $this->success($result, '注册成功！');
    }

    /**
     * 生成邀请码以及验证码验证
     * @param \Illuminate\Http\Request $request
     */
    public function postInvitation(Request $request) {
        $type = $request->get('type'); //type=1 验证用户； type=0 生成验证码
        $user_id = $request->get('user_id');
        if($type==1){
            $verifycode = $request->get('verifycode');
            $info_user = DB::table('ecs_users')->select('user_id')->where('user_id',$user_id)->where('reg_time','>',strtotime("-2 month"))->first();
            if(empty($info_user)){
                return $this->error(null, '你的账号邀请时间已过期！');
            }
            $verification_code = DB::table('ecs_verification_code')->select('user_id')->where('verifycode',$verifycode)->first();
            if(empty($verification_code)){
                return $this->error(null, '你的邀请码填写不正确！');
            }
            $info_invitation_user = $this->info_invitation_user($user_id,$verification_code->user_id,$request->getClientIp());
            if($info_invitation_user)
                return $this->success(null, '成功填写邀请人！');
            else
                return $this->error(null, '不能填写自己且每个人只能被邀请一次！');
        }else{
            //生成验证码
            $data_verification['user_id'] = $user_id;
            $data_verification['verifycode'] = $this->getRandomString(7);
            $data_verification['dateline'] = time();
            $data_verification['getip'] = $request->getClientIp();
            $verification_code = DB::table('ecs_verification_code')->where('user_id',$user_id)->first();
            if(empty($verification_code)){
                $verification_id = DB::table('ecs_verification_code')->insertGetId($data_verification);
                $verifycode = DB::table('ecs_verification_code')->select('verifycode')->where('id',$verification_id)->first();
                $result['verifycode'] = $verifycode->verifycode;
                if($result)
                    return $this->success($result, '验证码生成成功！');
                else
                    return $this->error(null, '验证码生成失败！');
            }else{
                $result['verifycode'] = $verification_code->verifycode;
                return $this->success($result, '此用户已存在验证码！');
            }
        }
    }

    /**
     * 邀请人，被邀请人统计加分
     * @param $user_id
     * @param $invitation_id
     * @param $ip
     */
    protected function info_invitation_user($user_id,$invitation_id,$ip){
        $invitation_list = DB::table('ecs_invitation_list')->select('invitation_id')->where('invitation_id',$invitation_id)->first();
        if(!empty($invitation_list)){
            return false;
        }else{
            if($invitation_id == $user_id)
                return false;
            $this->integral_add($invitation_id,'users',$ip);    //邀请的人加分
            $this->integral_add($user_id,'users',$ip);    //成功邀请人后，自己加分
            DB::insert('insert into ecs_invitation_list (user_id, invitation_id) values ("' . $user_id . '", "' . $invitation_id . '")');   //记录邀请人的id
            return true;
        }
    }

    /**
     * 随机验证码生成
     * @param \Illuminate\Http\Request $request
     */
    protected function getRandomString($len, $chars=null)
    {
        if (is_null($chars))
            $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
            $str .= $chars[mt_rand(0, $lc)];
        }
        $verification = DB::table('ecs_verification_code')->where('verifycode',$str)->first();
        if(empty($verification))
            return $str;
        else
            $this->getRandomString(7);
    }
}
