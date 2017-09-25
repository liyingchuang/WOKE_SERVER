<?php

namespace App\Http\Controllers\api\v2;

use Illuminate\Http\Request;
use App\Show;
use App\Http\Controllers\api\v1\ShowController as ShowBaseController;
use App\User;
use App\ShowTag;
use Illuminate\Support\Facades\DB;
use App\ShowTagLike;
use App\TagComment;
use App\UserTag;

class ShowController extends ShowBaseController {

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getIndex', 'getSearch', 'getList', 'getFind']]);
    }

    /**
     * 晒晒首页
     * @param \Illuminate\Http\Request $request
     */
    public function getIndex(Request $request) {
        $user_id = $request->get('user_id');
        $time = $request->get('time', null);
        $list = [];
        if (empty($time)) {
            $list = Show::where('is_show', 1)->orderBy('created_time', 'desc')->take(5)->get();
        } else {
            $list = Show::where('is_show', 1)->where('created_time', '<', $time)->orderBy('created_time', 'desc')->take(5)->get();
        }
        $result = [];
        unset($request);
        foreach ($list as $value) {
            $result[] = $this->_getShow($value->id, $user_id, 0);
        }
        return $this->success($result, 'ok');
    }

    /**
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function getSearch(Request $request) {
        $type = $request->get('type', 'user');
        $keyword = trim($request->get('keyword', null));
        switch ($type) {
            case "user":
                if ($keyword != null) {
                    $list = User::select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag', 'show_view_number')->where("user_name", 'like', "{$keyword}%")->paginate(20)->toArray();
                    return $this->success($list['data']);
                } else {
                    $list = User::select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag', 'show_view_number')->orderBy('search_sort_order', 'asc')->orderBy('show_view_number', 'desc')->take(20)->skip(0)->get();
                }
                break;
            case "tag":
                $host=$_ENV['QINIU_HOST'].'/';
                if ($keyword != null) {
                    $list= DB::table('ecs_show_tag_statistics')->select('size','tag_name',DB::raw("concat('$host',thumb) as thumb"))->where('tag_name', 'like', "{$keyword}%")->paginate(20)->toArray();
                   // $list = ShowTag::select(DB::raw('sum(size) AS size'), 'tag_name', 'thumb')->where('tag_name', 'like', "{$keyword}%")->groupBy('tag_name')->paginate(20)->toArray();
                    return $this->success($list['data'],'');
                } else {
                    $list= DB::table('ecs_show_tag_statistics')->select('size','tag_name',DB::raw("concat('$host',thumb) as thumb"))->orderBy('search_sort_order', 'asc')->orderBy('size', 'desc')->take(20)->get();
                   // $list = ShowTag::select(DB::raw('sum(size) AS size'), 'tag_name', 'thumb')->groupBy('tag_name')->orderBy('search_sort_order', 'asc')->orderBy('size', 'desc')->take(20)->get();
                }
                break;
        }
        return $this->success($list);
    }

    /**
     * 关键词晒晒列表
     * @param \Illuminate\Http\Request $request
     */
    public function getList(Request $request) {
        $keyword = trim($request->get('keyword', null));
        $user_id = $request->get('user_id');
        $list = Show::with(['user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v');
            }])->whereHas('tags', function($query)use ($keyword) {
                    $query->where('tag_name', 'like', "$keyword");
                })->orderBy('created_time', 'desc')->paginate(15)->toArray();
        $data = [];
        $tag = ShowTag::where('tag_name', $keyword)->first();
        $count = UserTag::where('tag_name', $keyword)->count();
        $userTag = UserTag::where('user_id', $user_id)->where('tag_name', $keyword)->first();
        foreach ($list['data'] as $key => $value) {
            $supplier = DB::table('ecs_supplier')->select('supplier_id')->where('status', 1)->where('user_id', $value['user_id'])->first();
            if (!empty($supplier)) {
                $value['supplier_id'] = $supplier->supplier_id;
            } else {
                $value['supplier_id'] = '0';
            }
            $data[$key] = $value;
        }
        $result['total'] = $list['total'];
        $result['thumb'] = !empty($tag) ? $tag->thumb : "";
        $result['tag'] = $tag;
        $result['like_size'] = $count;
        $result['is_like'] = empty($userTag) ? "0" : "1";
        $result['list'] = $data;
        return $this->success($result);
    }

    /**
     * 晒晒详情2有评论
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getFind(Request $request) {
        $user_id = $request->get('user_id');
        $show_id = $request->get('show_id');
        $return = $this->_getShow_v2($show_id, $user_id);
        if ($return) {
            $show = DB::table('ecs_collect_show')->where('user_id', $user_id)->where('show_id', $show_id)->where('is_attention', 1)->first();
            if (!empty($show)) {
                $return->collect = 1;
            } else {
                $return->collect = 0;
            }
            return $this->success($return, '显示成功');
        }
        return $this->success(null, '此晒晒用户删除了！', self::ERROR);
    }

    protected function _getShow_v2($show_id, $user_id = 0, $fun = 1) {
        $show = Show::with(array('user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v');
            }))->find($show_id);
        if (!empty($show)) {
            $supplier = DB::table('ecs_supplier')->select('supplier_id')->where('status', 1)->where('user_id', $show->user_id)->first();
            if (!empty($supplier)) {
                $show->supplier_id = $supplier->supplier_id;
            } else {
                $show->supplier_id = '0';
            }
            if ($fun) {
                $showTags = ShowTag::with(['user' => function($query) {
                        $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v');
                    }])->where('show_id', $show_id)->orderBy('size', 'desc')->orderBy('updated_at', 'desc')->paginate(30)->toArray();
            } else {
                $showTags = ShowTag::with(['user' => function($query) {
                        $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v');
                    }])->where('show_id', $show_id)->orderBy('size', 'desc')->orderBy('updated_at', 'desc')->take(30)->get();
                $showTags['data'] = $showTags->toArray();
            }
            $result = [];
            foreach ($showTags['data'] as $value) {
                $show_tag_like = ShowTagLike::where('user_id', $user_id)->where('show_id', $show_id)->where('show_tag_id', $value['id'])->first();
                if (!empty($show_tag_like)) {
                    $value['like'] = 1;
                } else {
                    $value['like'] = 0;
                }
                $count = TagComment::where('show_tag_id', $value['id'])->count();
                $skip = $count > 5 ? $count - 5 : 0;
                $comment = TagComment::with(['user' => function($query) {
                        $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v');
                    }])->where('show_tag_id', $value['id'])->skip($skip)->take(5)->get();
                $value['comment'] = $comment;
                $value['count'] = strval($count);
                $result[] = $value;
            }
            $show->tags = $result;
            return $show;
        }
        return false;
    }

}
