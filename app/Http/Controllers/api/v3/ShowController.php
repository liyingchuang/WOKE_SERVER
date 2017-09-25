<?php

namespace App\Http\Controllers\api\v3;

use App\Http\Controllers\api\v2\ShowController as ShowBaseController;
use App\Show;
use Illuminate\Http\Request;
use App\UserShowOff;
use App\Follow;
use App\UserTag;
use App\ShowTag;

class ShowController extends ShowBaseController {
    private $userTags=[];

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getRecommend']]);
    }

    /**
     * 晒晒 推荐｜关注｜标签
     * @param \Illuminate\Http\Request $request
     */
    public function getRecommend(Request $request) {
       
        $type= $request->get('type', 'new');
        $keyword= $request->get('keyword');
        $user_id = $request->get('user_id');
        $time = $request->get('time', null);
        $list = $this->_getlist($type, $time, $user_id,$keyword);
        $result = [];
        unset($request);
        foreach ($list as $value) {
            $show = $this->_getShow($value->id, $user_id, 0);
            if ($type==='like') {
                $in = array_flatten(Follow::select('follow_id')->where('user_id', $user_id)->get()->toArray());
                $show->follow_by = in_array($show->user_id, $in) ? 'person' : 'tag';
                if($show->follow_by=='tag'&&!empty($this->userTags)){
                    $show->tag_name=ShowTag::where('show_id',$value->id)->whereIn('tag_name', $this->userTags)->first();
                }else{
                    $show->tag_name=function(){};
                }
            }
            if($type==='recommend'){
              $show->created_time=$show->recommend_time;
              $show->created_at=  date('Y-m-d H:i:s',$show->recommend_time);
            }
            $result[] = $show;
        }
        return $this->success($result, '');
    }

    /**
     * 获取晒晒
     * @param type $keyword
     * @param type $time
     * @return type
     */
    private function _getlist($type, $time = null, $user_id = 0,$keyword) {
        $list = [];
        switch ($type) {
            case "new":
                if (empty($time)) {
                    $list = Show::where('is_show', 1)->orderBy('created_time', 'desc')->take(5)->get();
                } else {
                    $list = Show::where('is_show', 1)->where('created_time', '<', $time)->orderBy('created_time', 'desc')->take(5)->get();
                }
                break;
            case "like":
                $off = UserShowOff::where('user_id', $user_id)->first();
                $desc=!empty($off)?$off->desc:'';
                $notin = explode(',', $desc);
                $in = Follow::select('follow_id')->where('user_id', $user_id)->get()->toArray();
                $inUser = array_flatten($in);
                $tags = array_flatten(UserTag::select('tag_name')->where('user_id', $user_id)->get()->toArray());
                $this->userTags=$tags;
                if (empty($time)) {
                     $list = Show::where(function($q) use($tags,$inUser, $notin) {
                        $q->whereHas('tags', function($query) use ($tags, $notin) {
                                $query->whereIn('tag_name', $tags)->whereNotIn('id', $notin);
                            })->orWhere(function ($query)use($inUser, $notin) {
                                $query->whereIn('user_id', $inUser)->whereNotIn('id', $notin);
                            });
                    })->whereNotIn('id', $notin)->where('is_show', 1)->orderBy('created_time', 'desc')->take(5)->get();
                } else {
                    $list = Show::where(function($q) use($tags,$inUser, $notin) {
                        $q->whereHas('tags', function($query) use ($tags, $notin) {
                                $query->whereIn('tag_name', $tags)->whereNotIn('id', $notin);
                            })->orWhere(function ($query)use($inUser, $notin) {
                                $query->whereIn('user_id', $inUser)->whereNotIn('id', $notin);
                            });
                    })->whereNotIn('id', $notin)->where('is_show', 1)->where('created_time', '<', $time)->orderBy('created_time', 'desc')->take(5)->get();
                }
                break;
            case "recommend":
                if (empty($time)) {
                    $list = Show::where('is_show', 1)->where('is_recommend', 2)->orderBy('recommend_time', 'desc')->take(5)->get();
                } else {
                    $list = Show::where('is_show', 1)->where('is_recommend', 2)->where('recommend_time', '<', $time)->orderBy('recommend_time', 'desc')->take(5)->get();
                }
                break;
            case "keyword":
                if (empty($time)) {
                    $list = Show::whereHas('tags', function($query) use ($keyword) {
                                $query->where('tag_name', 'like', "$keyword%");
                            })->orderBy('created_time', 'desc')->take(5)->get();
                } else {
                    $list = Show::whereHas('tags', function($query) use ($keyword) {
                                $query->where('tag_name', 'like', "$keyword%");
                            })->where('created_time', '<', $time)->orderBy('created_time', 'desc')->take(5)->get();
                }
                break;
        }
        return $list;
    }

}
