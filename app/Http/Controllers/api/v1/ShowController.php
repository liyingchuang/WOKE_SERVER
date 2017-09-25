<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Show;
use App\ShowTag;
use App\ShowTagLike;
use Illuminate\Support\Facades\DB;
use App\ShowReport;
use App\TagComment;
use App\UserBanned;

class ShowController extends ApiController {

    const ERROR = 2; //正确时的其它情况； 

    /**
     * 统一验证用户
     */

    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getIndex', 'getFind']]);
    }

    /**
     * 添加晒晒
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request) {
        $user_id = $request->get('user_id', null);
        $desc = $request->get('desc');
        $file_height = $request->get('file_height',1);
        $file_width = $request->get('file_width',1);
        $file_height =$file_height<1?1:$file_height ;
        $file_width = $file_width<1?1:$file_width ;
        $file_name = $request->get('file_name');
        if (empty($file_name)) {
            return $this->error(null, '图片不能为空');
        }
        $time = time(); //标签列表
        $banned = UserBanned::where('user_id', $user_id)->where('end_time', '>', $time)->first();
        if (!empty($banned)) {
            $second = $banned->end_time - $time;
            $mes = $this->time2string($second);
            return $this->error(null, '你已被禁言,剩余' . $mes,2);
        }
        $tag_array = json_decode($desc, true); //标签列表
        $tag_arrays = array_unique($tag_array);

        $show_data = ['user_id' => $user_id, 'created_time' => $time, 'file_name' => $file_name, 'file_heigth' => $file_height, 'file_width' => $file_width, 'report_size' => 0, 'tag_size_count' => count($tag_array)];
        $show = Show::create($show_data);
        $tage = [];
        foreach ($tag_arrays as $value) {
            $tage_data['user_id'] = $user_id;
            $tage_data['show_id'] = $show->id;
            $tage_data['tag_name'] = $value;
            $tage_data['size'] = 1;
            $tage[] = new ShowTag($tage_data);
        }
        $return = $show->tags()->saveMany($tage);
        foreach ($return as $value) {
            ShowTagLike::create(['show_id' => $show->id, 'user_id' => $user_id, 'show_tag_id' => $value->id]);
        }

        //执行积分统计操作
        $this->integral_add($user_id,'show',$request->getClientIp());

        $data = $this->_getShow($show->id, $user_id, 0);
        return $this->success($data, '添加成功！');
    }

    /**
     * 晒晒首页
     * @param \Illuminate\Http\Request $request
     */
    public function getIndex(Request $request) {
        $user_id = $request->get('user_id');
        $list = Show::orderBy('id', 'desc')->paginate(5);
        $result = [];
        unset($request);
        foreach ($list as $value) {

            $result[] = $this->_getShow($value->id, $user_id, 0);
        }
        return $this->success($result, 'ok');
    }

    /**
     * 晒晒详情
     * @param \Illuminate\Http\Request $request
     */
    public function getFind(Request $request) {
        $user_id = $request->get('user_id');
        $show_id = $request->get('show_id');
        $return = $this->_getShow($show_id, $user_id);
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

    /**
     * 删除晒晒
     * @param \Illuminate\Http\Request $request
     */
    public function getDelete(Request $request) {
        $user_id = $request->get('user_id');
        $show_id = $request->get('show_id');
        $show = Show::with('tags')->find($show_id);
        if (!empty($show) && $show->user_id == $user_id) {
            DB::table('ecs_collect_show')->where('show_id', $show_id)->delete();
            DB::table('ecs_users_show_message')->where('show_id', $show_id)->delete();
            ShowTagLike::where('show_id', $show_id)->delete();
            TagComment::where('show_id', $show_id)->delete();
            ShowTag::where('show_id', $show_id)->delete();
            $show->delete();
            return $this->success(null, '删除成功');
        }
        return $this->error(null, '删除失败！不是你发的晒晒');
    }

    /**
     * 举报晒晒
     * @param \Illuminate\Http\Request $request
     */
    public function postReport(Request $request) {
        $user_id = $request->get('user_id');
        $show_id = $request->get('show_id');
        $desc = $request->get('desc');
        $show = Show::find($show_id);
        if (empty($show)) {
            return $this->error(null, '晒晒已经被删除！');
        }
        $is = ShowReport::create(['show_id' => $show_id, 'user_id' => $user_id, 'desc' => $desc]);
        if ($is) {
            Show::where('id', $show_id)->increment('report_size');
            return $this->success(null, '举报成功');
        }
        return $this->error(null, '举报失败');
    }

    /**
     * 晒晒收藏
     * @param \Illuminate\Http\Request $request
     */
    public function getCollect(Request $request) {
        $user_id = $request->get('user_id');
        $show_id = $request->get('show_id');
        $collect = $request->get('collect');
        $shows = Show::find($show_id);
        if (empty($shows)) {
            return $this->error(null, '晒晒已经被删除！');
        }
        $show = DB::table('ecs_collect_show')->where('user_id', $user_id)->where('show_id', $show_id)->first();
        if ($collect == 'on') {//收藏
            if (empty($show)) {
                DB::table('ecs_collect_show')->insert(['is_attention' => 1, 'user_id' => $user_id, 'show_id' => $show_id]);
            } else {
                if ($show->is_attention == 0) {
                    DB::table('ecs_collect_show')->where('user_id', $user_id)->where('show_id', $show_id)->update(['is_attention' => 1]);
                }
            }
            return $this->success(null, '收藏成功！');
        }
        if ($collect == 'off') {//取消收藏
            if (!empty($show)) {
                if ($show->is_attention == 1) {
                    DB::table('ecs_collect_show')->where('user_id', $user_id)->where('show_id', $show_id)->update(['is_attention' => 0]);
                }
            }
            return $this->success(null, '取消收藏成功！');
        }
    }

    /**
     * 晒晒收藏列表
     * @param \Illuminate\Http\Request $request
     */
    public function getCollectlist(Request $request) {
        $user_id = $request->get('user_id');
        $list = DB::table('ecs_collect_show')
                        ->rightJoin('ecs_show', 'ecs_collect_show.show_id', '=', 'ecs_show.id')
                        ->select('ecs_show.id', 'ecs_show.file_name', 'ecs_show.file_heigth', 'ecs_show.file_width')
                        ->where('ecs_collect_show.user_id', $user_id)->where('ecs_collect_show.is_attention', 1)->paginate(12)->toArray();
        $result=[];
        foreach ($list['data'] as   $value) {
         $value->file_name=$this->_url($value->file_name);
         $result[]=$value;
        }
        return $this->success($result, 'ok');
    }
    /**
     * url 处理
     * @param type $url
     * @return type
     */
    private function _url($url) {
        $in = strstr($url, 'clouddn.com/');
        if ($in||empty($url)) {
            return $url;
        }
        $ins = strstr($url, '/');
        if ($ins) {
             return url($url);  
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
        return $QINIU_HOST.'/'.$url;  
    }
    /**
     * 查询单个晒晒
     * @param type $user_id
     * @param type $show_id
     * @return boolean
     */
    protected function _getShow($show_id, $user_id = 0, $fun = 1) {
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
                        $query->select('user_id', 'user_name', 'user_rank', 'headimg','flag', 'is_v');
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
                $result[] = $value;
            }
            $show->tags = $result;
            return $show;
        }
        return false;
    }

}
