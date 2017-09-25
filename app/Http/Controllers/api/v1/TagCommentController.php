<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\TagComment;
use App\ShowTag;
use App\UserShowMessage;
use Illuminate\Support\Facades\DB;
use App\ShowTagLike;
use App\UserBanned;
class TagCommentController extends ApiController {

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getShow']]);
    }

    /**
     * 评论详情
     * @param \Illuminate\Http\Request $request
     */
    public function getShow(Request $request) {
        $tag_id = $request->get('tag_id');
        $user_id = $request->get('user_id');
        $tag = ShowTag::with(['user' => function($q) {
               $q->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v');
            }, 'comment' => function($qs) {
                $qs->with(['user' => function($qq) {
                $qq->select('user_id', 'user_name', 'user_rank', 'headimg','flag',  'is_v');
            }])->paginate(15);
            }])->find($tag_id);
        if (empty($tag)) {
            return $this->error(null, '标签已经被删除！');
        }
        $tag->likes = ShowTagLike::with(['user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg','flag',  'is_v');
            }])->where('show_tag_id', $tag_id)->get();
        $in=[];
        foreach ($tag->likes as $key => $value) {
            $in[]=$value->user_id;
            $tag->likes[$key] = $value->user;
        }
        if (!empty($user_id) &&in_array($user_id, $in)){
            $tag->like = 1; 
        }else{
            $tag->like = 0;
        }
        $tag->user->count = strval(DB::table('ecs_users_show_message')->where('types', '<', 3)->where('user_id', $tag->user_id)->count());
        return $this->success($tag, '');
    }
    /**
     * 添加评论
     * @param \Illuminate\Http\Request $request
     */
    public function postAdd(Request $request) {
        $user_id = $request->get('user_id');
        $to_user_id = $request->get('to_user_id');
        $desc = $request->get('desc');
        $show_id = $request->get('show_id');
        $tag_id = $request->get('tag_id');
        $time= time(); //标签列表
        $banned=UserBanned::where('user_id',$user_id)->where('end_time','>',$time)->first();
        if(!empty($banned)){
            $second = $banned->end_time - $time;
            $mes = $this->time2string($second);
            return $this->error(null, '你已被禁言,剩余' . $mes,2);
        }
        $tag = ShowTag::with('like')->find($tag_id);
        if (empty($tag)) {
            return $this->error(null, '标签已经被删除！');
        }
        $data['user_id'] = $user_id;
        $data['show_tag_id'] = $tag_id;
        $data['show_id'] = $show_id;
        $data['desc'] = $desc;
        TagComment::create($data);
        if (empty($to_user_id)) {//发评论没@
            if($tag->user_id!=$user_id){
                $sdata['types'] = 4;
                $sdata['show_tag_id'] = $tag->id;
                $sdata['from_user_id'] = $user_id;
                $sdata['message'] = $desc;
                $sdata['user_id'] = $tag->user_id;
                $sdata['show_id'] = $show_id;
                //$this->sendUserShowMessage($sdata);
                foreach ($tag->like as  $value) {   //关注此标签的人都收到消息
                    $sdata['user_id'] = $value->user_id;
                    $this->sendUserShowMessage($sdata);
                }
            }
            
        } else {//发评论有@
            $sdata['types'] = 5;
            $sdata['message'] = $desc;
            $sdata['show_tag_id'] = $tag->id;
            $sdata['from_user_id'] = $user_id;
            $sdata['user_id'] = $to_user_id;
            $sdata['show_id'] = $show_id;
            $this->sendUserShowMessage($data);
        }
        //执行积分统计操作
        $this->integral_add($user_id,'comment',$request->getClientIp());
        return $this->success(null, "评论成功");
    }
    /**
     * 发送消息
     * @param type $data
     * @return boolean
     */
    protected function sendUserShowMessage($data) {
        UserShowMessage::create($data);
        $result = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('user_id',$data['user_id'])->count());
        $comment = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->whereIn('types',[4,5])->where('user_id',$data['user_id'])->count());
        $this->sendMessage($data['user_id'], ['type' => 'personal_tips', 'body' =>$result, 'TYPE_PUSH_COMMENT_MSG' =>$comment], '', 1);
        return true;
    }

}
