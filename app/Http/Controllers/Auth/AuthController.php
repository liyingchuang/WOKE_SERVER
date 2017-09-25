<?php

namespace App\Http\Controllers\Auth;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuthController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Registration & Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users, as well as the
      | authentication of existing users. By default, this controller uses
      | a simple trait to add these behaviors. Why don't you explore it?
      |
     */

use AuthenticatesAndRegistersUsers,
    ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'email' => 'required|max:255',
                    'password' => 'required|min:5',
        ]);
    }

    /**
     *
     * @return type
     */
    public function getLogin() {
       // echo cookie('username');exit;
        return view('login');
    }

    /**
     * 推出
     */
    public function getLogout(Request $request) {
        $request->session()->flush();
        return redirect('manage/home');
    }

    /**
     * 登陆
     * @param \Illuminate\Http\Request $request
     */
    public function postLogin(Request $request) {
        $rememberl = $request->get('remember');
        $data['password'] = $request->get('password');
        $data['email'] = $request->get('email');
        if(empty($rememberl)){
            cookie('username', $data['email'], time()-3600*24*5);
            cookie('password', $data['password'], time()-3600*24*5);
        }
        $v = $this->validator($data);
        if (!$v->fails()) {
            $admin_user = DB::table('woke_admin_user')->where('email', $data['email'])->orWhere('user_name', $data['email'])->first();
            $info=$this->login($admin_user, $data['password']);
            if ($info) {
                return redirect('manage/home');
            } else {
                return redirect('manage/login')->with('message', '用户信箱和密码不正确!')->withInput();
            }
        } else {
            return redirect('manage/login')->with('message', '用户信箱和密码不正确')->withInput();
        }
    }

    /**
     * 验证用户
     * @param type $adming_user
     */
    private function login($admin_user, $password) {
        if (!empty($admin_user)) {
            if ($admin_user->ec_salt && $admin_user->password == md5(md5($password) . $admin_user->ec_salt)) {
                session(['manage_user_id' => $admin_user->user_id]);
                session(['manage_user_name' => $admin_user->user_name]);

                if (empty($admin_user->suppliers_id)) {
                    session(['manage_user_email' => $admin_user->email]);
                    session(['manage_role' => 'manage']);
                    session(['supplier_id' => 0]);
                    session(['supplier_name' =>'蜗客官方']);
                } else {
                    $store = DB::table('woke_supplier')->where('supplier_id', $admin_user->suppliers_id)->first();
                    session(['manage_role' => 'supplier']);
                    session(['supplier_id' => $admin_user->suppliers_id]);
                    session(['supplier_name' =>$store]);
                    session(['manage_user_email' => $admin_user->email]);
                }
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

}
