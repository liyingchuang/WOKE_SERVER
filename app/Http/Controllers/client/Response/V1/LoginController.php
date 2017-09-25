<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Events\IntegralEvent;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\User;
use App\UserToken;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class LoginController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index', 'sendcode', 'CheckCode', 'register','sendsms', 'sendResetCode', 'reset','wxlogin','wxregister'];
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxlogin(Request $request)
    {
        $type = $request->get('type');
        $open_id = $request->get('openid');
        if($type == 'qq') {
            $users = User::where('qq_id', $open_id)->first();
        }
        if($type == 'weixin') {
            $users = User::where('union_id', $open_id)->first();
        }
        if ($users) {
            $result['token'] = $this->getUserToken($users->user_id);
            $result['user_id'] = $users->user_id;
            $result['mobile_phone'] = $users->mobile_phone;
            $result['user_name'] = $users->user_name;
            $result['headimg'] = $users->headimg;
            $result['last_login'] = $users->last_login;
            return $this->success($result, '登陆成功！');
        } else {
            return $this->error(null, '微信号/QQ号尚未注册请注册!');
        }
    }

    /**
     * 微信注册
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function wxregister(Request $request){
        $type = $request->get('type');            //获取登录类型
        $open_id = $request->get('openid');      //获取OpenID
        $headimg = $request->get('headimg');   //获取微信头像
        $code = $request->get('code');
        $mobile_phone = $request->get('mobile_phone');
        $nickname = $request->get('nickname'); //获取微信name
        $sex = $request->get('sex');
//        $sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
//        if (empty($sms)) {
//            return $this->error(null, '验证码不匹配请检查手机号！'); //手机号跟验证码不匹配
//        }
        $phone = User::where('mobile_phone', $mobile_phone)->first();
        if($phone){
            if($type == 'weixin') {
                if (empty($phone->union_id)) {
                    $date['union_id'] = $open_id;
                    User::where('mobile_phone', $mobile_phone)->update($date);
                }else{
                    return  $this->error(1, '该手机号已绑定微信！');
                }
            }
            if($type == 'qq') {
                if (empty($phone->qq_id)) {
                    $date['qq_id'] = $open_id;
                    User::where('mobile_phone', $mobile_phone)->update($date);
                }else{
                    return  $this->error(1, '该手机号已绑定QQ！');
                }
            }
            $res['token'] = $this->getUserToken($phone->user_id);
            $res['user_id'] = $phone->user_id;
            $res['user_name'] = $phone->user_name;
            $res['mobile_phone'] = $mobile_phone;
            $res['headimg'] = $headimg;
            $res['sex'] = $sex;
            $res['last_login'] = $phone->last_login;
            return $this->success($res, '绑定成功！');
        }else {
            $user = User::Where('mobile_phone', '18888888888')->first();
            if($type == 'weixin'){
                if(!empty($open_id)){
                    $date['union_id'] = $open_id;
                }
            }else{
                $date['qq_id'] = $open_id;
            }
            $data['parent_id'] = $user->user_id;
            $data['user_name'] = $nickname;
            $data['mobile_phone'] = $mobile_phone;
            $data['reg_time'] = time();
            $data['headimg'] = $headimg;
            $data['sex'] = $sex;
            $users = User::create($data);
            $result['token'] = $this->getUserToken($users->user_id);
            $result['user_id'] = $users->user_id;
            $result['mobile_phone'] = $mobile_phone;
            $result['headimg'] = $headimg;
            $result['sex'] = $sex;
            $result['last_login'] = $users->last_login;
            return $this->success($result, '注册成功！');
        }
    }

    /**
     * 发送第三方注册短信
     *
     * @return \Illuminate\Http\Response
     */
    public function sendsms(Request $request)
    {
        $ip = $request->getClientIp();
        $code = rand(100000, 999999);
        $mobile_phone = $request->get('mobile_phone');
        return $this->send($mobile_phone, $ip, $code);
    }

    /**
     * 发送注册短信
     *
     * @return \Illuminate\Http\Response
     */
    public function sendcode(Request $request)
    {
        $ip = $request->getClientIp();
        $code = rand(100000, 999999);
        $mobile_phone = $request->get('mobile_phone');
        //检查手机号是否已经注册
        $user = User::Where('mobile_phone', $mobile_phone)->first();
        if (!empty($user)) {
            return $this->error(null, '手机号已经注册过系统帐号,请去登陆!');
        }
        return $this->send($mobile_phone, $ip, $code);
    }

    private function send($mobile_phone, $ip, $code)
    {
        $data['mobile_phone'] = $mobile_phone;
        $data['code'] = $code;
        $data['ip'] = $ip;
        $data['add_time'] = time();
        //1个ip 1小时分钟只能发送6次
        $count_sms = DB::table('woke_user_sms')->where('ip', '=', $ip)->where('add_time', '<=', time())->where('add_time', '>=', time() - 3600)->count();
        if ($count_sms > 5) {
            return $this->error(null, '请不要频繁请求发短信!');
        }
        //1个手机号1分钟只能发送一次
        $info_sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->where('add_time', '>', time() - 60)->first();
        if (!empty($info_sms)) {
            return $this->error(null, '1分钟只能发送一次'); //发送失败
        }
        $msg = "【酒股宝】亲，手机验证码：" . $code . "，有效期为两分钟，打死都不能告诉别人哦";
        $info = $this->sendMicroMessage($mobile_phone, $msg);
        $body = json_decode($info->body);
        if (!empty($body) && $body->returnstatus == 'Success') { //发送成功
            $info_sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->first();
            if (empty($info_sms)) {
                DB::table('woke_user_sms')->insert($data);
            } else {
                DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->update($data);
            }
            return $this->success(null, '发送成功'); //绑定失败
        }
        return $this->error(null, '发送失败'); //发送失败
    }

    /**
     * @发送重置短信
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetCode(Request $request)
    {
        $ip = $request->getClientIp();
        $code = rand(100000, 999999);
        $mobile_phone = $request->get('mobile_phone');
        //检查手机号是否已经注册
        $user = User::Where('mobile_phone', $mobile_phone)->first();
        if (empty($user)) {
            return $this->error(null, '用户不存在!');
        }
        return $this->send($mobile_phone, $ip, $code);
    }

    /**
     * 用户注册
     * @return mixed
     */
    public function register(Request $request)
    {
        $parent_code = $request->get('parent_code', null);
        $code = $request->get('code');
        $mobile_phone = $request->get('mobile_phone');
        $pw = $request->get('pwd');
        $nickname = $request->get('nickname', 'Woke');
        if ($mobile_phone ==$parent_code) {
            return $this->error(null, '推荐码不能填写自己！');
        }
        if(empty($parent_code)){
            $parent_code='18888888888';
        }
        $sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
        if (!empty($sms)) {
            $user = User::Where('mobile_phone', $mobile_phone)->first();
            if (!empty($user))
                return $this->error(null, '手机号已存在!');
        } else {
            return $this->error(null, '验证码不匹配请检查手机号！'); //手机号跟验证码不匹配
        }
        $user = User::Where('mobile_phone', $parent_code)->first();
        if (!empty($user)) {
            $data['parent_id'] = $user->user_id;
        } else {
            return $this->error(null, '推荐人不正确！');
        }
        $data['user_name'] = $nickname;
        $data['mobile_phone'] = $mobile_phone;
        $salt ='wokecn';
        $data['password'] = md5(md5($pw) . $salt);
        $data['ec_salt'] = $salt;
        $data['reg_time'] = time();
        $data['last_login'] =0;
        $users=User::create($data);// DB::table('woke_users')->insertGetId($data);
        $result['token'] = $this->getUserToken($users->user_id);
        $result['user_id'] = $users->user_id;
        $result['mobile_phone'] = $mobile_phone;
        $result['headimg'] = '';
        $result['last_login'] = $users->last_login;
        return $this->success($result, '注册成功！');
    }

    /**
     * token 统一处理
     * @param type $user_id
     */
    private function getUserToken($user_id)
    {
        $token = Crypt::encrypt($user_id);
        $usertoken = UserToken::where('user_id', $user_id)->first();
        if (empty($usertoken)) {
            UserToken::create(['user_id' => $user_id, 'token' => $token]);
        } else {
            $token = $usertoken->token;
        }
        return $token;
    }

    /**
     *检查验证码是否正确
     */
    public function CheckCode(Request $request)
    {
        $code = $request->get('code');
        $mobile_phone = $request->get('mobile_phone');
        //检查手机号是否已经注册
        $user = User::Where('mobile_phone', $mobile_phone)->first();
        if (!empty($user)) {
            return $this->error(null, '手机号已经注册过,请去登陆!');
        }
        $sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
        if (!empty($sms)) {
            return $this->success(null, '验证成功!');
        } else {
            return $this->error(null, '验证码不正确！'); //手机号跟验证码不匹配
        }
    }

    /**
     * 首次登陆返酒币
     * @param User $user
     */
    public function redpacket(Request $request){
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $user = User::Where('user_id', $user_id)->first();
        if($user->last_login!=1){
            $num=rand(5,8);
//            event(new IntegralEvent($user->user_id,$num,'亲爱的用户您好，您红包领取酒币',1));
            $user->	last_login=1;
            $user->save();
            return $this->success($num, '红包领取成功！');
        }else{
            return $this->error(null, '用户已领取红包!');
        }
    }

    /**
     * 登陆
     * @return array
     */
    public function index(Request $request)
    {
        $mobile = $request->get('mobile_phone');
        $pw = $request->get('pwd');
        $user = User::Where('mobile_phone', $mobile)->first();
        if (empty($user)) {
            return $this->error(null, '用户不存在!');
        }
        if ($user->password == md5(md5($pw) . $user->ec_salt)) {
            $result['token'] = $this->getUserToken($user->user_id);
            $result['user_id'] = $user->user_id;
            $result['mobile_phone'] = $mobile;
            $result['user_name'] = $user->user_name;
            $result['headimg'] = $user->headimg;
            $result['last_login'] = $user->last_login;
            return $this->success($result, '登陆成功！');
        } else {
            return $this->error(null, '手机号或者密码不正确!');
        }
    }

    /**
     * 重置密码
     * @param Request $request
     */
    public function reset(Request $request)
    {
        $code = $request->get('code');
        $mobile_phone = $request->get('mobile_phone');
        $pw = $request->get('pwd');
        $sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
        if (!empty($sms)) {
            $user = User::Where('mobile_phone', $mobile_phone)->first();
            if (empty($user))
                return $this->error(null, '用户不存在!');
            $password = md5(md5($pw) . $user->ec_salt);
            if ($user->password == $password)
                return $this->error(null, '新密码不能和旧密码一致!');
            $user->password = $password;
            $user->save();
            return $this->success(null, '密码重置成功');
        } else {
            return $this->error(null, '验证码不匹配！');
        }
    }
}
