<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\ManageController;
use App\Show;
use App\ShowTagLike;
use App\ShowTag;
use App\ShowTagStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\TagComment;
use App\ShowReport;
use App\UserShowMessage;
use App\Http\Controllers\ApiController;

class ShowController extends ManageController {

    /**
     * 晒晒管理
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $keyword = $request->get('keyword', null);
        $user_id = $request->get('id', 0);
        if ($keyword) {
            $user = User::select('user_id')->where('mobile_phone', 'like', "%$keyword%")->orWhere('user_id', 'like', "%$keyword%")->orWhere('user_name', 'like', "%$keyword%")->take(30)->get();
        }
        $list = [];
        if ($user_id) {
            $list = Show::with([
                'tags','user' => function($query) {
                    $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
                }, 'report'])->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(10);
        } else if (!empty($user)) {
            $list = Show::with([
                'tags','user' => function($query) {
                    $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
                }, 'report'])->whereIn('user_id', $user->toArray())->orderBy('id', 'desc')->paginate(10);
        } else {
            $list = Show::with([
                'tags','user' => function($query) {
                    $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
                }, 'report'])->orderBy('id', 'desc')->paginate(10);
        }
        return view('manage/show/index')->with(['list' => $list, 'id' => $user_id, 'keyword' => $keyword]);
    }

    /**
     * 标签详情
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getTag($id) {
        $tag_id = $id? : 0;
        $tag = ShowTag::with(['user' => function($q) {
                $q->select('user_id', 'user_name', 'headimg');
            }])->find($tag_id);
        $list = TagComment::with(['user' => function($qq) {
                $qq->select('user_id', 'user_name', 'headimg');
            }])->where('show_tag_id', $id)->paginate(20);
        return view('manage/show/tagcomment')->with(['list' => $list, 'tag' => $tag, 'id' => $tag_id]);
    }

    /**
     * 删除标签评论
     * @param type $id
     */
    public function getCommentDel($id) {
        $tagcomm = TagComment::find($id);
        $this->integral_del($tagcomm->user_id,'comment');//标签评论删除积分
        $tagcomm->delete();
        echo 'ok';
        $tag = ShowTag::find($tagcomm->show_tag_id);
       $sdata['types'] = 6;
       $sdata['show_tag_id'] =$tagcomm->show_tag_id;
       $sdata['from_user_id'] = 0;
       $sdata['user_id'] = $tagcomm->user_id;
       $sdata['show_id'] = $tagcomm->show_id;
       $sdata['message'] = '您在'.$tag->tag_name.'"标签下发布的评论"'.$tagcomm->desc.'"违反版规已被管理员删除';
       $this->sendUserShowMessage($sdata);
        exit;
    }

    /**
     * 举报晒晒 
     */
    public function create() {
        $list = Show::with([
                    'user' => function($query) {
                $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
            }, 'report'])->has('report')->orderBy('id', 'desc')->paginate(10);
        return view('manage/show/index')->with(['list' => $list, 'id' => 0, 'keyword' => null]);
    }

    /**
     * 晒晒详情
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $show = Show::with(['tags.user', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
            }, 'report.user'])->find($id);
        return view('manage/show/view')->with(['info' => $show]);
    }

    /**
     * 删除标签／投诉
     * @param type $id
     */
    public function getDel($id, Request $request) {
        $type = $request->get('type', null);
        if (!empty($type)) {//删投诉
            ShowReport::where('id',$id)->delete();
            echo 'ok';
            exit;
        }
        //删除标签
        $tag = ShowTag::find($id);
        if (!empty($tag)) {
            $this->integral_del($tag->user_id,'tag');//标签删除积分
            ShowTagLike::where('show_tag_id', $id)->delete();
            TagComment::where('show_tag_id', $id)->delete();
            DB::table('ecs_users_show_message')->where('show_tag_id', $id)->where('show_id', $tag->show_id)->delete();
            $tag->delete();
            //推送 删除标签
            $sdata['types'] = 6;
            $sdata['show_tag_id'] = 0;
            $sdata['from_user_id'] = 0;
            $sdata['user_id'] = $tag->user_id;
            $sdata['show_id'] = $tag->show_id;
            $sdata['message'] = $tag->tag_name;
            $this->sendUserShowMessage($sdata);
        }
        echo 'ok';
        exit;
    }

    /**
     * 批量删除晒晒
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function store(Request $request) {
        $ids = $request->get('ids');
        foreach ($ids as $value) {
            $this->_del($value);
        }
        return redirect('manage/show');
    }

    /**
     * 显示否
     * @param type $id
     */
    public function destroy($id) {
        $show = Show::find($id);
        if (!empty($show) && $show->is_show) {
            $show->is_show = 0;
        } else {
            $show->is_show = 1;
        }
        $show->save();
        exit;
    }

    /**
     * 显示是否推荐
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function offon(Request $request) {
        $id = $request->get('id');
        $is_recommend = Show::find($id);
        if (!empty($is_recommend) && $is_recommend->is_recommend ==1 ) {
            
            $user_detailed = DB::table('ecs_integral_user_detailed')
                ->select('id')
                ->where('user_id',$is_recommend->user_id)
                ->where('desc','recommend')
                ->where(DB::raw("FROM_UNIXTIME(create_time,'%Y-%m-%d')"),date('Y-m-d',time()))
                ->first();
           if(empty($user_detailed)){
               //积分添加
                $integral = new ApiController();
                $integral->integral_add($is_recommend->user_id,'recommend',$request->getClientIp());
           }

            $is_recommend->is_recommend = 2;
            $is_recommend->recommend_time = time();
            //推送用户推荐
            $sdata['types'] = 6;
            $sdata['show_tag_id'] = 0;
            $sdata['from_user_id'] = 0;
            $sdata['user_id'] = $is_recommend->user_id;
            $sdata['show_id'] = $id;
            $sdata['message'] = '您发布的晒晒已经被管理员添加到推荐页面';
            $this->sendUserShowMessage($sdata);
        } else {
            $is_recommend->is_recommend = 1;
        }
        $is_recommend->save();

        exit;
    }

    /**
     *  删除单个晒晒
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $this->_del($id);
        echo 'ok';
        exit;
    }

    /**
     * 删除单个晒晒
     * @param type $id
     */
    private function _del($id) {
        $show = Show::with('tags')->find($id);
        if (!empty($show)) {
            $this->integral_del($show->user_id,'show');//晒晒删除积分
            DB::table('ecs_collect_show')->where('show_id', $id)->delete();
            DB::table('ecs_users_show_message')->where('show_id', $id)->delete();
            TagComment::where('show_id', $id)->delete();
            ShowTagLike::where('show_id', $id)->delete();
            ShowTag::where('show_id', $id)->delete();
            $show->delete();

            //推送用户删除的信息
            $sdata['types'] = 6;
            $sdata['show_tag_id'] = 0;
            $sdata['from_user_id'] = 0;
            $sdata['user_id'] = $show->user_id;
            $sdata['show_id'] = 0;
            $sdata['message'] = '您发布的晒晒由于违反了版规已被管理员删除';
            $this->sendUserShowMessage($sdata);
        }
        return true;
    }

    /**
     * 统计操作后的分数
     * @return type
     */
    private function integral_del($user_id,$type) {
        $integral = DB::table('ecs_integral')->select('id', 'name', 'min', 'max', 'type', 'integral')->where('enname',$type)->first();
        $oper = DB::table('ecs_integral_detailed')->select('id')->where('user_id',$user_id)->where('integral_id',$integral->id)->first();
        if(!empty($oper)){
            DB::table('ecs_integral_detailed')->where('id',$oper->id)->delete();
            $count = DB::table('ecs_integral_detailed')
                ->where('user_id',$user_id)->where('integral_id',$integral->id)
                ->where(DB::raw("FROM_UNIXTIME(create_time,'%Y-%m-%d')"),date('Y-m-d',time()))
                ->count();
            if($count+1 >= $integral->min){
                if($count < $integral->min){
                    $this->integral_user_detail($user_id,$type,$integral->integral);
                    $integral_count = $this->integral($user_id);
                    $data_user_detailed_update['integral']  = $integral_count;
                    $data_user_detailed_update['create_time'] = time();
                    DB::table('ecs_integral_user')->where('user_id',$user_id)->update($data_user_detailed_update);
                }
            }
        }
    }

    /**
     * 统计用户积分
     * @return $user_id
     */
    private function integral($user_id) {
        $integral_add = DB::table('ecs_integral_user_detailed')->where('user_id', $user_id)->where('type', 1)->groupBy('user_id')->sum('size');
        $integral_consumption = DB::table('ecs_integral_user_detailed')->where('user_id', $user_id)->where('type', 0)->groupBy('user_id')->sum('size');
        $integral = $integral_add - $integral_consumption;
        if($integral >= 0)
            return $integral;
        else
            return 0;
    }

    /**
     * 用户积分详情
     * @return $user_id
     */
    private function integral_user_detail($user_id,$enname,$integral) {
        $desc = "违反规则，未达到 $enname 的需求，减 $integral 分";
        $data_integral_user_detailed['size'] = $integral;
        $data_integral_user_detailed['user_id'] = $user_id;
        $data_integral_user_detailed['type'] = 0;
        $data_integral_user_detailed['desc'] = $desc;
        $data_integral_user_detailed['create_time'] = time();
        DB::table('ecs_integral_user_detailed')->insert($data_integral_user_detailed);
    }

    /**
     * 晒晒推荐
     * @return \Illuminate\Http\Response
     */
    public function recommend(Request $request) {
        $keyword = $request->get('keyword', null);
        $user_id = $request->get('id', 0);
        if ($keyword) {
            $user = User::select('user_id')->where('mobile_phone', 'like', "%$keyword%")->orWhere('user_id', 'like', "%$keyword%")->orWhere('user_name', 'like', "%$keyword%")->take(30)->get();
        }
        $list = [];
        if ($user_id) {
            $list = Show::with([
                'user' => function($query) {
                    $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
                }, 'report'])->where('is_recommend', 2)->where('is_show', 1)->where('user_id', $user_id)->orderBy('recommend_time', 'desc')->paginate(10);
        } else if (!empty($user)) {
            $list = Show::with([
                'user' => function($query) {
                    $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
                }, 'report'])->where('is_recommend', 2)->where('is_show', 1)->whereIn('user_id', $user->toArray())->orderBy('recommend_time', 'desc')->paginate(10);
        } else {
            $list = Show::with([
                'user' => function($query) {
                    $query->select('user_id', 'user_name', 'mobile_phone', 'user_rank', 'headimg', 'is_v');
                }, 'report'])->where('is_recommend', 2)->where('is_show', 1)->orderBy('recommend_time', 'desc')->paginate(10);
        }
        return view('manage/show/recommend')->with(['list' => $list, 'id' => $user_id, 'keyword' => $keyword]);
    }

    /**
     * 标签搜索
     * @return \Illuminate\Http\Response
     */
    public function baskinstore(Request $request) {
        $keyword = $request->get('keyword', null);
        if($keyword)
            $list = ShowTagStatistics::select('id', 'size', 'tag_name', 'thumb','search_sort_order')->where("tag_name", 'like', "%{$keyword}%")->orderBy('size', 'desc')->paginate(20);
        else
            $list = ShowTagStatistics::select('id', 'size', 'tag_name', 'thumb','search_sort_order')->orderBy('size', 'desc')->paginate(20);
        foreach($list as $key => $val){
            $val->user_tags = DB::table('ecs_user_tags')->where('tag_name', '=', $val->tag_name)->count();
        }
        return view('manage/show/baskinstore')->with(['list' => $list, 'keyword' => $keyword]);
    }

    /**
     * 标签搜索 图片入库
     * @return \Illuminate\Http\Response
     */
    public function baskin_image(Request $request) {
        $tag_name = $request->get('baskin_name');
        $thumb = $request->get('thumb');
        DB::table('ecs_show_tag_statistics')->where('tag_name', $tag_name)->update(array('thumb' => $thumb));
        DB::table('ecs_show_tag')->where('tag_name', $tag_name)->update(array('thumb' => $thumb));
        return redirect("manage/show/baskinstore");
    }

    /**
     * 发送删除晒晒消息
     * @param type $data
     * @return boolean
     */
    private function sendUserShowMessage($data) {
        UserShowMessage::create($data);
        $result = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('user_id', $data['user_id'])->count());
        $sys = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('types', 6)->where('user_id', $data['user_id'])->count());
        $this->sendMessage($data['user_id'], ['type' => 'personal_tips', 'body' => $result, 'TYPE_PUSH_SYS_MSG' => $sys], '', 1);
        return true;
    }
    /**
     * 晒晒盘玩榜排序
     * @return \Illuminate\Http\Response
     */
    public function search_sort_order(Request $request) {
        $tag_name = $request->get('pk');
        $search_sort_order = $request->get('value');
        DB::table('ecs_show_tag')->where('tag_name', $tag_name)->update(array('search_sort_order' => $search_sort_order));
        DB::table('ecs_show_tag_statistics')->where('tag_name', $tag_name)->update(array('search_sort_order' => $search_sort_order));
        $result = ['status' => "success", 'msg' => '修改成功'];
        return response()->json($result);
    }

}
