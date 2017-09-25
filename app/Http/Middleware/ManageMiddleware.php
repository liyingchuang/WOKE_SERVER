<?php
namespace App\Http\Middleware;
use Closure;
class ManageMiddleware {
    /**
     * 管理员认证
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $id = session('manage_user_id');
        $email = session('manage_user_email');
        if ($id && $email) {
            return $next($request);
        } else {

            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('manage/login');
            }
        }
    }

}
