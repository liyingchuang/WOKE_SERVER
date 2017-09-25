<?php

namespace App\Http\Middleware;

use App\InterfaceLog;
use App\UserLogs;
use Closure;

class UserLog
{
    const API_V1_LIST = [
        'v1.login' => '登录',
        'v1.device' => '注册设备'
    ];

    /**
     *
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $stime = microtime(true);
        $response = $next($request);
        $etime = microtime(true);
        $total = $etime - $stime;
        $method = $request->input("method");
        $token = $request->header('token', '');
        $ip = $request->getClientIp();
        $body = $response->getContent();
        if (empty($method)) {
            $method = "paywebhook";
            $token = '';
        }
        $token=$this->isdevice().$token;
        //1.增加接口统计 后期考虑异步处理
        //   print_r(['api_name'=>$method,'ip'=>$ip ,'reauest_body'=>json_encode($request->all()), 'reauest_header'=>$token, 'response'=>$body, 'input_time'=>$stime, 'run_time'=>$total, 'output_time'=>$etime]);exit;
        InterfaceLog::create(['api_name' => $method, 'ip' => $ip, 'reauest_body' => json_encode($request->all()), 'reauest_header' => $token, 'response' => $body, 'input_time' => $stime, 'run_time' => $total, 'output_time' => $etime]);
        return $response;
    }

    private function isdevice()
    {
        $t = '';
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'iPhone') || strpos($user_agent, 'iPad')) {
            $t = 'IOS';
        } else if (strpos($user_agent, 'Android')) {
            $t = 'Android';
        } else {
            $t = 'OTHER';
        }
        if (strpos($user_agent, 'MicroMessenger'))
            $t = $t. '-微信';
        return $t;
    }

}
