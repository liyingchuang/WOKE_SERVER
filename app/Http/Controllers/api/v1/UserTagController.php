<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\UserTag;

class UserTagController extends ApiController {

    public function __construct() {
        $this->middleware('api_guest', ['except' => ['postFollow']]);
    }

    /**
     * 关注取消关注标签
     *
     * @return \Illuminate\Http\Response
     */
    public function postFollow(Request $request) {
        $user_id = $request->get('user_id');
        $ac = $request->get('ac');
        $tag_name = $request->get('tag_name');
        $userTag = UserTag::where('user_id',$user_id)->where('tag_name', $tag_name)->first();
        switch ($ac) {
            case "follow":
                if (empty($userTag))
                    UserTag::create(['user_id' => $user_id, 'tag_name' => $tag_name]);
                return $this->success(null, '关注标签成功!');
                break;
            case "unfollow":
                if (!empty($userTag))
                    $userTag->delete();
                return $this->success(null, '取消关注标签成功!');
                break;
        }
        return $this->error(null, '非法请求!');
    }

}
