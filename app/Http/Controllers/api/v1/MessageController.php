<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class MessageController extends Controller {

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getNote']]);
    }

    /**
     * 消息
     * @param \App\Http\Controllers\api\v1\Request $request
     */
    public function getIndex(Request $request) {
        $user_id = $request->get('user_id', 0);
        $result = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('user_id', $user_id)->count());
        return $this->success($result, '');
    }

    /**
     *
     * 每类消息数字提醒
     * @param Request $request
     * @return \App\Http\Controllers\type
     */
    public function getNote(Request $request){
        $user_id=$request->get('user_id');
        $result['TYPE_PUSH_SYS_MSG'] =strval(DB::table('ecs_users_show_message')->where('types', 6)->where('is_read', 0)->where('user_id', $user_id)->count());
        $result['TYPE_PUSH_TAG_MSG'] = strval(DB::table('ecs_users_show_message')->where('types', 2)->where('is_read', 0)->where('user_id', $user_id)->count());
        $result['TYPE_PUSH_FAVOUR_MSG'] =strval(DB::table('ecs_users_show_message')->where('types',1)->where('is_read', 0)->where('user_id', $user_id)->count());
        $result['TYPE_PUSH_COMMENT_MSG'] =strval(DB::table('ecs_users_show_message')->whereIn('types',[4,5])->where('is_read', 0)->where('user_id', $user_id)->count());
        $result['TYPE_PUSH_FANS_MSG'] =strval(DB::table('ecs_users_show_message')->where('types', 3)->where('is_read', 0)->where('user_id', $user_id)->count());
        return $this->success($result, '');
    }

}
