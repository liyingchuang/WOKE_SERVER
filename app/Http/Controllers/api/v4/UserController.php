<?php

namespace App\Http\Controllers\api\v4;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\UserShowMessage;
use App\UserReport;
class UserController extends Controller
{
    public function __construct() {
      $this->middleware('api_guest', ['except' => []]);
    }

    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function getMessage(Request $request) {
        $user_id = $request->get('user_id');
        $type= $request->get('type');
        $page = $request->get('page', 1);

         DB::table('ecs_users_show_message')->where('types', $type)->where('user_id', $user_id)->update(['is_read' => 1]);

        if($type<3){//点赞//添加标签
            $list = UserShowMessage::select('user_id', 'from_user_id', 'show_id', 'show_tag_id', 'types', 'is_read','created_at','message',DB::raw('count(*) as news'))->with(['tags', 'show', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag');
            }])->where('user_id', $user_id)->where('types', $type)->groupBy('show_id')->orderBy('id', 'desc')->paginate(12)->toArray();
            foreach ($list['data'] as $k=>$v) {
                if($v['news']>1){
                    $tags=UserShowMessage::with('tags')->where('user_id', $user_id)->where('from_user_id',$v['from_user_id'])->where('show_id',$v['show_id'])->where('types',$v['types'])->get();
                    $list['data'][$k]['tags']=array_pluck($tags->toArray(), 'tags');
                }
                if($v['news']==1&&!empty($v['tags'])){
                    $list['data'][$k]['tags']=[$v['tags']];
                }
                continue;
            }
            if (!empty($list)) {
                return $this->success($list['data'], '');
            } else {
                return $this->success('', '');
            }
        }
        if($type==3) {//关注
            $list = UserShowMessage::select('user_id', 'from_user_id', 'show_id', 'show_tag_id', 'types', 'is_read','created_at','message')->with(['tags', 'show', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag');
            }])->where('user_id', $user_id)->where('types', 3)->orderBy('id', 'desc')->paginate(12)->toArray();

            if (!empty($list)) {
                return $this->success($list['data'], '');
            } else {
                return $this->success('', '');
            }
        }
        if($type==4||$type==5) {//type＝4 评论 type＝5 回复评论

            DB::table('ecs_users_show_message')->whereIn('types',[4,5])->where('user_id', $user_id)->update(['is_read' => 1]);

            $list = UserShowMessage::select('user_id', 'from_user_id', 'show_id', 'show_tag_id', 'types', 'is_read','created_at','message')->with(['tag', 'show', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag');
            }])->where('user_id', $user_id)->whereIn('types',[4,5])->orderBy('id', 'desc')->paginate(12)->toArray();
            if (!empty($list)) {
                return $this->success($list['data'], '');
            } else {
                return $this->success('', '');
            }
        }
        if($type==6) {//系统消息
            $list = UserShowMessage::select('user_id', 'from_user_id', 'show_id', 'show_tag_id', 'types', 'is_read','created_at','message',DB::raw('count(*) as news'))->with(['tags', 'show', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag');
            }])->where('user_id', $user_id)->where('types', 6)->groupBy([DB::raw('IF(show_id =0,id,show_id)'),DB::raw('IF(show_tag_id !=0,id,show_id)')])->orderBy('id', 'desc')->paginate(12)->toArray();
            foreach ($list['data'] as $k=>$v) {
                if($v['show_id']!=0&&$v['show_tag_id']!=0){
                   // $tags=UserShowMessage::with('tags')->where('user_id', $user_id)->where('show_tag_id',$v['show_tag_id'])->where('show_id',$v['show_id'])->where('types',6)->get();
                  $list['data'][$k]['tags']=[$v['tags']];
                    $list['data'][$k]['types']='602';
                    continue;
                }

                if($v['show_id']!=0){
                    $tags=UserShowMessage::select('message as tag_name')->where('user_id', $user_id)->where('show_id',$v['show_id'])->where('show_tag_id',0)->where('types',6)->get();
                    $list['data'][$k]['tags']=$tags->toArray();
                    $list['data'][$k]['types']='601';
                    $list['data'][$k]['message']='您发布的以下标签违反版规已被管理员删除';
                }
                if($v['show_id']==0&&$v['show_tag_id']==0){
                    $list['data'][$k]['types']=strstr($v['message'],'由于')?'6':'603';
                }
                continue;
            }
            if (!empty($list)) {
                return $this->success($list['data'], '');
            } else {
                return $this->success('', '');
            }
        }

    }

    public function postReport(Request $request){
        $user_id = $request->get('user_id');
        $from_user_id= $request->get('report_user_id');
        $desc = $request->get('desc');
        $is = UserReport::create(['from_user_id' =>$user_id, 'user_id' => $from_user_id, 'desc' => $desc]);
        if ($is) {
            //Show::where('id', $show_id)->increment('report_size');
            return $this->success(null, '举报成功');
        }
        return $this->error(null, '举报失败');
    }


}
