<?php

namespace App\Http\Controllers\api\v3;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\UserShowMessage;

class UserController extends Controller {

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => []]);
    }

    /**
     * 用户消息
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getMessage(Request $request) {
        $user_id = $request->get('user_id');
        $page = $request->get('page', 1);
        if ($page < 2) {
            DB::table('ecs_users_show_message')->where('user_id', $user_id)->update(['is_read' => 1]);
        }
        $list = UserShowMessage::select('user_id', 'from_user_id', 'show_id', 'show_tag_id', 'types', 'is_read','created_at','message',DB::raw('count(*) as news'))->with(['tags', 'show', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag');
            }])->where('user_id', $user_id)->whereNotIn('types',[7,8,9])->groupBy([DB::raw('IF(types in(1,2),from_user_id,id)'),DB::raw('IF(types in(1,2),show_id,id)'),DB::raw('IF(types in(1,2),types,id)')])->orderBy('id', 'desc')->paginate(12)->toArray();
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

}
