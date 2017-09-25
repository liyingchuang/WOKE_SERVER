<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Events\IntegralEvent;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ShareController extends BaseResponse implements InterfaceResponse
{

    public function __construct()
    {
        $this->except = ['index'];
    }

    /**
     * 邀请好友返酒币
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $parent_code = $request->get('user_id', null);
        $code = $request->get('code');
        $mobile_phone = $request->get('mobile_phone');
        $pw = $request->get('pwd');
        if (empty($parent_code)) {
            return $this->error(null, '推荐人不能为空！');
        }
        if (empty($pw)) {
            return $this->error(null, '密码不能为空！');
        }
        $sms = DB::table('woke_user_sms')->where('mobile_phone', $mobile_phone)->where('code', $code)->first();
        if (!empty($sms)) {
            $user = User::Where('mobile_phone', $mobile_phone)->first();
            if (!empty($user))
                return $this->error(null, '手机号已存在!');
        } else {
            return $this->error(null, '验证码不匹配请检查手机号！'); //手机号跟验证码不匹配
        }
        $user = User::Where('user_id', $parent_code)->first();
        if (!empty($user)) {
            $data['parent_id'] =$parent_code;
        } else {
            return $this->error(null, '推荐人不正确！');
        }
        $data['user_name'] = 'Woke';
        $data['mobile_phone'] = $mobile_phone;
        $salt ='wokecn';
        $data['password'] = md5(md5($pw) . $salt);
        $data['ec_salt'] = $salt;
        $data['reg_time'] = time();
        $data['last_login'] = 0;
        $users = User::create($data);
        $result['user_id'] = $users->user_id;
        $result['mobile_phone'] = $mobile_phone;
        $num = rand(5, 8);
        //todo 6月18号以后关闭此活动
       // event(new IntegralEvent($user->user_id, $num, '用户您好，您推荐的用户已成功注册为蜗客会员，获得酒币', 1));
        $result['num'] = $num;
        return $this->success($result, '注册成功！');
    }

    /**
     * 返回接口名称
     * @return string
     */
    public function getMethod()
    {
        // TODO: Implement getMethod() method.
    }
}
