<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use upush\sdk;

class ManageController extends Controller
{

    private $key = "58b8e776c62dca37cd000eb5";
    private $secret = "8a58b5e990ebf2e913fb021a31caa267";
    private $akey = "5603c61867e58e5e8f000cb6";
    private $asecret = "xxfhpgw89psgsf25zuss8we9tkjqgnqv";
    private $debug = false;

    protected function sendMessage($user_id, $option, $alert = '', $available = 1)
    {
        $debut = env('APP_DEBUG');
        if ($debut) {
            $this->debug = false;
        } else {
            $this->debug = true;
        }
        $sdk = new sdk($this->key, $this->secret, $this->akey, $this->asecret, $this->debug);
        $sdk->sendMessage($user_id, $option, $alert, 0, $available);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        view()->share('manage_role', session('manage_role'));
        view()->share('supplier_id', session('supplier_id'));
        view()->share('manage_user_name', session('manage_user_name'));
        view()->share('manage_user_email', session('manage_user_email'));
        view()->share('manage_user_id', session('manage_user_id'));
        $role_id = DB::table('role_user')->select('role_id')->where('user_id', session('manage_user_id'))->first();
        $role = DB::table('roles')->where('id',$role_id->role_id )->first();
        $url = Route::currentRouteName();
        view()->share('url', $url);
        $debut = env('APP_DEBUG');
        if ($debut) {
            $main_rbac = true;
        } else {
            $main_rbac = $this->rbac($url);
        }
        if ($main_rbac) {
            view()->share('menus', config($role->config_menu));
        } else {
            abort(403, '没权限');
        }
    }

    private function rbac($url)
    {
        $uid=session('manage_user_id');
        $role_user = DB::table('role_user')->select('role_id')->where('user_id', $uid)->first();//查出登录用户的角色ID
        $permission = DB::table('permission_role')->where('role_id', $role_user->role_id)->lists('permission_id');//根据角色ID查出角色权限ID
        $is_permission = DB::table('permissions')->where('name',$url)->whereIn('id',$permission)->first();//根据角色权限ID查出来用户权限
        if($is_permission){
            return true;
        }else{
            return false;
        }
    }

}
