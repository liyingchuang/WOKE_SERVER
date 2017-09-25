<?php

namespace App\Http\Middleware;

use Closure;
use App\UserToken;

class ApiGuest {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $token = $request->header('token',0);
        $token=empty($token)?$request->get('token',0):$token;
        $user_id = $request->get('user_id');
        $user = UserToken::where('user_id', $user_id)->first();
        if (empty($user)) {
            return $this->error(null, '请重新登录！');
        } else {
            if (empty($token) || $token != $user->token) {
                return $this->error(null, '请重新登录！');
            }
        }
        return $next($request);
    }

    /**
     * 返回错误
     * @param type $data
     * @param type $message
     * @param type $code
     * @return type
     */
    private function error($data, $message = '', $code = 1) {
        return response()->json(['code' => $code, 'info' => $message, 'data' => $data]);
    }

}
