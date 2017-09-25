<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;


use App\Http\Controllers\ApiController;
use App\User;
class ChatController extends ApiController
{
    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => []]);
    }
    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function postUser(Request $request)
    {
        $users=$request->get('user_ids','');
        $user_ids=explode(',',$users);
        $list=User::select('user_id','user_name','headimg', 'is_v')->whereIn('user_id',$user_ids)->get();
        return  $this->success($list,'');
    }
}
