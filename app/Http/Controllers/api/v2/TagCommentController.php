<?php

namespace App\Http\Controllers\api\v2;

use Illuminate\Http\Request;
use App\TagComment;
use App\Http\Controllers\api\v1\TagCommentController as BaseTagCommentController;
use App\ShowTag;
use App\UserBanned;

class TagCommentController extends BaseTagCommentController {

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => []]);
    }

    /**
     * 添加评论
     * @param \Illuminate\Http\Request $request
     */
    public function postAdd(Request $request) {
        $user_id = $request->get('user_id');
        $to_user_id = $request->get('to_user_id');
        $at_user_name = $request->get('at_user_name');
        $desc = $request->get('desc');
        $show_id = $request->get('show_id');
        $tag_id = $request->get('tag_id');
        $time = time(); //标签列表
        $banned = UserBanned::where('user_id', $user_id)->where('end_time', '>', $time)->first();
        if (!empty($banned)) {
            $second = $banned->end_time - $time;
            $mes = $this->time2string($second);
            return $this->error(null, '你已被禁言,剩余' . $mes, 2);
        }
        $tag = ShowTag::find($tag_id);
        if (empty($tag)) {
            return $this->error(null, '标签已经被删除！');
        }
        $data['user_id'] = $user_id;
        $data['show_tag_id'] = $tag_id;
        $data['show_id'] = $show_id;
        if (empty($to_user_id)) {//发评论没@
            $data['desc'] = $desc;
        } else {
            $data['desc'] = '@' . $at_user_name . ' ' . $desc;
        }
        TagComment::create($data);
        if (empty($to_user_id)) {//发评论没@
            if ($tag->user_id != $user_id) {
                $sdata['types'] = 4;
                $sdata['show_tag_id'] = $tag->id;
                $sdata['from_user_id'] = $user_id;
                $sdata['user_id'] = $tag->user_id;
                $sdata['show_id'] = $show_id;
                $sdata['message'] = $desc;
                $this->sendUserShowMessage($sdata);
                //foreach ($tag->like as  $value) {   //关注此标签的人都收到消息
                //    $sdata['user_id'] = $value->user_id;
                //    $this->sendUserShowMessage($sdata);
                //}
            }
        } else {//发评论有@
            $sdata['types'] = 5;
            $sdata['show_tag_id'] = $tag->id;
            $sdata['from_user_id'] = $user_id;
            $sdata['user_id'] = $to_user_id;
            $sdata['show_id'] = $show_id;
            $sdata['message'] = $desc;
            $this->sendUserShowMessage($sdata);
        }
        //执行积分统计操作
        $this->integral_add($user_id,'comment',$request->getClientIp());
        return $this->success(null, "评论成功");
    }

}
