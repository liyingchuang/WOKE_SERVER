<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Show;
use App\ShowTag;
use App\ShowTagLike;
use Illuminate\Support\Facades\DB;
//use xiaokus\aliyunopensdk\GreeRequest;
use App\UserShowMessage;
use App\TagComment;
use App\UserBanned;
class TagController extends ApiController {


    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getHot']]);
    }

    /**
     * 添加标签
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postAdd(Request $request) {
        $user_id = $request->get('user_id');
        $tag_name = $request->get('tag_name');
        $show_id = $request->get('show_id');
        $time= time(); //标签列表
        $banned=UserBanned::where('user_id',$user_id)->where('end_time','>',$time)->first();
        if(!empty($banned)){
            $second = $banned->end_time - $time;
            $mes = $this->time2string($second);
            return $this->error(null, '你已被禁言,剩余' . $mes,2);
        }
        $show = Show::find($show_id);
        if (empty($show)) {
            return $this->error(null, '晒晒已经被删除！');
        }
        $tag = ShowTag::where('tag_name', $tag_name)->where('show_id', $show_id)->first();
        if (!empty($tag)) {
            return $this->error(null, '标签已存在！');
            /*   $show_tag_like = ShowTagLike::where('user_id', $user_id)->where('show_id', $show_id)->where('show_tag_id', $tag->id)->first();
              if (!empty($show_tag_like)) {

              } else {
              ShowTag::where('id', $tag->id)->increment('size'); //修改数量
              }
             * 
             */
        } else {
            $tage_data['user_id'] = $user_id;
            $tage_data['show_id'] = $show_id;
            $tage_data['tag_name'] = $tag_name;
            $tage_data['size'] = '1';
            $tag = ShowTag::create($tage_data);
        }
        ShowTagLike::create(['show_id' => $show_id, 'user_id' => $user_id, 'show_tag_id' => $tag->id]);
        Show::where('id', $show_id)->increment('tag_size_count');
        //----添加消息
        //1.添加标签 如果是自己的晒晒就不发消息
        if ($user_id != $show->user_id) {
            $data['types'] = 2;
            $data['show_tag_id'] = $tag->id;
            $data['from_user_id'] = $user_id;
            $data['user_id'] = $show->user_id;
            $data['show_id'] = $show_id;
            $this->changeUserShowMessage($data,2); //添加标签就在消息标添加消息
        }
        //执行积分统计操作
        $this->integral_add($user_id,'tag',$request->getClientIp());
        //-----ebd
        $tag->like = '1';
        $tag->id = strval($tag->id);
          //推荐专用
        if($show->is_recommend==2) {
            $t=time()-$show->recommend_time;
            if($t>3600){
                $show->recommend_time = time();  
                $show->save(); 
            }
        }
        return $this->success($tag, '添加成功！');
    }

    /**
     * 取消关键词或者删除
     * 
     * @param \Illuminate\Http\Request $request
     */
    public function getDelete(Request $request) {
        $tag_id = $request->get('tag_id');
        $user_id = $request->get('user_id');
        $show_id = $request->get('show_id');
        $type = $request->get('type');
        $show = Show::find($show_id);
        if (empty($show)) {
            return $this->error(null, '晒晒已经被删除！');
        }
        $tag = ShowTag::find($tag_id);
        if (empty($tag)) {
            return $this->error(null, '标签已经被删除！');
        }
        $show_tag_like = ShowTagLike::where('user_id', $user_id)->where('show_id', $show_id)->where('show_tag_id', $tag_id)->first();
        if ($type == 'like') {//赞
            if (empty($show_tag_like)) {
                Show::where('id', $show_id)->increment('tag_size_count'); 
                ShowTag::where('id', $tag_id)->increment('size'); //修改数量
                ShowTagLike::create(['show_id' => $show_id, 'user_id' => $user_id, 'show_tag_id' => $tag_id]);
                if ($show->user_id != $tag->user_id) {
                    //----消息 1.发给晒晒所有者
                    if ($user_id != $show->user_id) {
                        $data['types'] = 1;
                        $data['show_tag_id'] = $tag_id;
                        $data['from_user_id'] = $user_id;
                        $data['user_id'] = $show->user_id;
                        $data['show_id'] = $show_id;
                        $this->changeUserShowMessage($data);
                    }
                    //---- 2.发给标签所有者
                    if ($user_id != $tag->user_id) {
                        $data['types'] = 1;
                        $data['show_tag_id'] = $tag_id;
                        $data['from_user_id'] = $user_id;
                        $data['user_id'] = $tag->user_id;
                        $data['show_id'] = $show_id;
                        $this->changeUserShowMessage($data);
                    }
                    //-----end
                } else {
                    if ($user_id != $tag->user_id) {
                        $data['types'] = 1;
                        $data['show_tag_id'] = $tag_id;
                        $data['from_user_id'] = $user_id;
                        $data['user_id'] = $tag->user_id;
                        $data['show_id'] = $show_id;
                        $this->changeUserShowMessage($data);
                    }
                }


                //推荐专用
                if($show->is_recommend==2) {
                    $t = time() - $show->recommend_time;
                    if ($t > 3600) {
                        $show->recommend_time = time();
                        $show->save();
                    }
                }
                //执行积分统计操作
                $this->integral_add($user_id,'like',$request->getClientIp());
                return $this->success($tag->size + 1, '点赞成功！');
            } else {
                return $this->success($tag->size + 0, '点赞成功！');
            }
        }
        if ($type == 'unlike') {//取消点赞
            if (empty($show_tag_like)) {
                $size = !empty($tag) ? $tag->size : 0;
                return $this->success($size + 0, '取消点赞成功！');
            } else {
                $show_tag_like->delete();
                Show::where('id', $show_id)->where('tag_size_count', '>', 0)->increment('tag_size_count', -1);
                $count = ShowTagLike::where('show_id', $show_id)->where('show_tag_id', $tag_id)->count();
                if ($count) {//不是最后一个
                    ShowTag::where('id', $tag_id)->where('size', '>', 0)->increment('size', -1); //修改数量
                    DB::table('ecs_users_show_message')->where('from_user_id', $user_id)->where('types', 1)->where('show_tag_id', $tag_id)->where('show_id', $show_id)->delete();
                    return $this->success($tag->size - 1, '取消点赞成功！');
                } else {//是最后一个 删除
                    TagComment::where('show_tag_id',$tag_id)->delete();
                    $tag->delete();
                    DB::table('ecs_users_show_message')->where('show_tag_id', $tag_id)->where('show_id', $show_id)->delete();
                    //-----ebd
                    return $this->success(0, '删除成功！');
                }
            }
        }
    }

    /**
     * 热门标签
     * @param \Illuminate\Http\Request $request
     */
    public function getHot(Request $request) {
        $top = $request->get('top', 6);
        $type=$request->get('type');
        if(empty($type)){
            /*
            $tags = ShowTag::select(DB::raw('sum(size) AS size'), 'tag_name')->groupBy('tag_name')->orderBy('size', 'desc')->take($top)->get()->toArray();
            $r = [['size' => 1, 'tag_name' => '完美包浆'], ['size' => 1, 'tag_name' => '晒宝'], ['size' => 1, 'tag_name' => 'DIY'], ['size' => 1, 'tag_name' => '手串']];
            $result = array_merge($r, $tags);
            $info=$this->assoc_unique($result,'tag_name');
             
            return $this->success(array_values($info), '热门标签！');
           
             */
          $tags = [
                ['size' => '1', 'tag_name' => '橄榄核雕'],
                ['size' => '1', 'tag_name' => '雕工精细'],
                ['size' => '1', 'tag_name' => '完美包浆'],
                ['size' => '1', 'tag_name' => '橄榄核'],
                ['size' => '1', 'tag_name' => '松石'],
                ['size' => '1', 'tag_name' => '蜜蜡'],
                ['size' => '1', 'tag_name' => '星月'],
                ['size' =>'1', 'tag_name' => '南红'],
                ['size' => '1', 'tag_name' => '琥珀'],
                ['size' => '1', 'tag_name' => '金刚']
                ];
            return $this->success($tags, '热门标签！'); 
            
        }else{
              $tags = [
                ['size' => '1', 'tag_name' => '橄榄核雕'],
                ['size' => '1', 'tag_name' => '雕工精细'],
                ['size' => '1', 'tag_name' => '完美包浆'],
                ['size' => '1', 'tag_name' => '橄榄核'],
                ['size' => '1', 'tag_name' => '松石'],
                ['size' => '1', 'tag_name' => '蜜蜡'],
                ['size' => '1', 'tag_name' => '星月'],
                ['size' =>'1', 'tag_name' => '南红'],
                ['size' => '1', 'tag_name' => '琥珀'],
                ['size' => '1', 'tag_name' => '金刚']
                ];
              //$tags = ShowTag::select(DB::raw('sum(size) AS size'), 'tag_name')->groupBy('tag_name')->orderBy('size', 'desc')->take($top)->get()->toArray();
            return $this->success($tags, '热门标签！'); 
        }
    }

    /**
     * 添加删除晒晒消息表
     * @param type $user_id
     * @param type $n
     */
    private function changeUserShowMessage($data, $action = 'insert') {
        UserShowMessage::create($data);
        $result = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('user_id',$data['user_id'])->count());
        if ($action == 2) {
            $main = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('types', 2)->where('user_id',$data['user_id'])->count());
            $this->sendMessage($data['user_id'], ['type'=>'personal_tips','body'=>$result,'TYPE_PUSH_TAG_MSG'=>$main],'',1);
        } else {
            $main = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('types', 1)->where('user_id',$data['user_id'])->count());
            $this->sendMessage($data['user_id'], ['type'=>'personal_tips','body'=>$result,'TYPE_PUSH_FAVOUR_MSG'=>$main],'',1);
        }

        return true;
    }
/**
 * 去除重复
 * @param type $arr
 * @param type $key
 * @return type
 */
   private function assoc_unique($arr, $key) {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }
        return $arr;
    }

}
