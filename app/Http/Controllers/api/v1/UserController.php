<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Follow;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Show;
use App\Follower;
use App\UserShowMessage;
use App\UserPhoto;
use App\UserBanned;
class UserController extends ApiController {

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getShow', 'getVlist', 'getTop']]);
    }

    /**
     * 用户信息接口
     * @param \Illuminate\Http\Request $request
     */
    public function getInfo(Request $request) {
        $user_id = $request->get('user_id');
        $result = [];
        $user = User::where('user_id', $user_id)->select('user_id', 'user_rank', 'user_name', 'user_name as nickname', 'mobile_phone', 'email', 'pay_points', 'headimg', 'is_v', 'flag', 'signature', 'follow_size', 'show_view_number')->first();
        if (!empty($user)) {
            $result = $user->toArray();
            $result['payment'] = strval(DB::table('ecs_order_info')->where('user_id', $user_id)->where('order_status', '<>', 2)->where('pay_status', 0)->count());
            $result['deliver'] = strval(DB::table('ecs_order_info')->where('user_id', $user_id)->where('shipping_status', 0)->where('order_status', '<>', 2)->where('pay_status', 2)->count());
            $result['receipt'] = strval(DB::table('ecs_order_info')->where('user_id', $user_id)->where('shipping_status', 1)->where('order_status', '<>', 2)->where('pay_status', 2)->count());
            $result['quan'] = DB::table('ecs_order_info')->where('user_id', $user_id)->count();
            if ($result['quan'] && $result['quan'] > 99) {
                $result['quan'] = '...';
            } else {
                $result['quan'] = strval($result['quan']);
            }
            $result['back'] = strval(DB::table('ecs_back_order')->where('user_id', $user_id)->where('status_refund', '<>', 1)->count());
            $result['message'] = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('user_id', $user_id)->count());
            $result['like_size'] = strval(DB::table('ecs_users_show_message')->where('types', '<', 3)->where('user_id', $user_id)->count());
            return $this->success($result, '');
        } else {
            return $this->error(null, '用户信息获取失败，请重新登陆');
        }
    }

    /**
     * 点赞排行
     * @param \Illuminate\Http\Request $request
     */
    public function getTop(Request $request) {
        $number = $request->get('number', '50');
        $page = $request->get('page', 1);
        $type = $request->get('type', null);
        $date = date('Y-m-d');
        $enddate = date('Y-m-d',strtotime("+1 day"));
        $list = [];
        $result = [];
        $in = array_flatten(UserBanned::select('user_id')->where('end_time','>',time())->get()->toArray());
        if ($page < 2) {
            if (empty($type))
                $list = DB::table('ecs_users_show_message')->select('user_id', DB::raw('count(*) as today'))->where('types', '<', '3')->where('created_at', '>=', $date)->where('created_at', '<', $enddate)->whereNotIn('user_id',$in)->groupBy('user_id')->orderBy(DB::raw('count(*)'), 'desc')->take($number)->get();
            else
                $list = DB::table('ecs_users_show_message')->select('from_user_id', DB::raw('count(*) as today'))->where('types', '<', '3')->where('created_at', '>=', $date)->where('created_at', '<', $enddate)->whereNotIn('from_user_id',$in)->where('from_user_id','<>',0)->groupBy('from_user_id')->orderBy(DB::raw('count(*)'), 'desc')->take($number)->get();
        }
        foreach ($list as $key => $value) {
            if (empty($type)) {
                $u = User::where('user_id', $value->user_id)->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v')->first();
                $count = UserShowMessage::where('user_id', $value->user_id)->where('types', '<', '3')->count();
            } else {
                $u = User::where('user_id', $value->from_user_id)->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v')->first();
                $count = UserShowMessage::where('from_user_id', $value->from_user_id)->where('types', '<', '3')->count();
            }
            $u->count = $count;
            $value->user = $u;
            $value->order = $key + 1;
            $result[$key] = $value;
        }
        return $this->success($result, '');
    }

    /**
     * 大师列表
     * @param \Illuminate\Http\Request $request
     */
    public function getVlist(Request $request) {
        $list = User::where('is_v', 1)->where('status', 1)->select('user_id', 'user_name', 'headimg', 'is_v', 'flag', 'signature', 'alias', 'follow_size', 'show_view_number')->orderBy('sort_order', "asc")->orderBy('show_view_number', 'desc')->paginate(12)->toArray();
        foreach ($list['data'] as $key => $value) {
            if (empty($value['alias'])) {
                $list['data'][$key]['alias'] = '擅长:信息未完善，敬请期待';
            } else {
                $list['data'][$key]['alias'] = '擅长:' . $value['alias'];
            }
        }
        return $this->success($list['data'], '');
    }

    /**
     * 关注取消关注
     *
     * @return \Illuminate\Http\Response
     */
    public function getFollow(Request $request) {
        $user_id = $request->get('user_id');
        $follow_user_id = $request->get('like_user_id'); //关注的人的ID
        if ($follow_user_id == '2003034') {
            return $this->success(null, '该帐号无法关注！');
        }
        $user = User::where('user_id', $follow_user_id)->first();
        if (empty($user)) {
            return $this->success(null, '此人不存在！');
        }
        $like = $request->get('like');
        $follow = Follow::where('user_id', $user_id)->where('follow_id', $follow_user_id)->first();
        if ($like == 'on') {//关注
            if (empty($follow)) {
                //1.存我关注的人列表里面
                Follow::create(['user_id' => $user_id, 'follow_id' => $follow_user_id]);
                User::where('user_id', $user_id)->increment('like_size', 1);
                //2.告诉他我是他的粉丝了
                Follower::create(['user_id' => $follow_user_id, 'follower_id' => $user_id]);
                User::where('user_id', $follow_user_id)->increment('follow_size', 1);
                $d = ['user_id' => $follow_user_id, 'from_user_id' => $user_id];
                $this->changeUserShowMessage($d);
            }
            //执行积分统计操作
            $this->integral_add($follow_user_id,'fans',$request->getClientIp());
            $this->integral_add($user_id,'follow',$request->getClientIp());
            return $this->success(null, '关注成功');
        }
        if ($like == 'off') {//取消关注
            if (!empty($follow)) {//
                //1.删除我关注的人列表
                $follow->delete();
                User::where('user_id', $user_id)->where('like_size', '>', 0)->increment('like_size', -1);
                //2.告诉他我是他粉丝了
                Follower::where('user_id', $follow_user_id)->where('follower_id', $user_id)->delete();
                User::where('user_id', $follow_user_id)->where('follow_size', '>', 0)->increment('follow_size', -1);
            }
            return $this->success('', '取消关注');
        }
    }

    /**
     * 上传头像
     * @param \Illuminate\Http\Request $request
     */
    public function postEdit(Request $request) {
        $user_id = $request->get('user_id');
        $type = $request->get('type');
        $desc = $request->get('desc');
        switch ($type) {
            case 'avatar'://头像
                User::where('user_id', $user_id)->update(['headimg' => $desc]);
                break;
            case 'background'://背景
                User::where('user_id', $user_id)->update(['bg' => $desc]);
                break;
            case 'signature'://个性签名
                User::where('user_id', $user_id)->update(['signature' => $desc]);
                break;
        }
        return $this->success(null, '修改成功');
    }

    /**
     * 大师编辑
     * @param \Illuminate\Http\Request $request
     */
    public function postSave(Request $request) {
        $data = $request->get('photos', null); //图片数据
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $image = $request->get('image');
        $about = $request->get('about');
        User::where('user_id', $user_id)->update(['image' => $image, 'about' => $about]);
        if (!empty($data)) {
            $data_array = json_decode($data, true);
            UserPhoto::where('user_id', $user_id)->delete();
            foreach ($data_array as $value) {
                UserPhoto::create(['user_id' => $user_id, 'file_name' => $value]);
            }
        }
        return $this->success(null, '修改成功！');
    }

    /**
     * 晒晒用户首页接口
     * @param type 
     */
    public function getShow(Request $request) {
        $user_id = $request->get('user_id');
        $uid = $request->get('uid');
        $type = $request->get('type');
        $head = $request->get('head');
        $statistic = $request->get('statistic');
        $user = User::where('user_id', $uid)->select('user_id', 'image', 'day_view_number', 'day_max_view_number', 'user_name', 'bg', 'headimg', 'is_v', 'flag', 'signature', 'about', 'follow_size', 'like_size', 'alias','show_view_number')->first();
        if (!empty($user)) {
            if (!empty($statistic) && $statistic == 'on') {
                if ($user->day_view_number < $user->day_max_view_number) {
                    User::where('user_id', $uid)->increment('show_view_number', 1);
                    User::where('user_id', $uid)->increment('day_view_number', 1);
                }
            }
            if (empty($head)) {
                $user->shows_size = Show::where('user_id', $uid)->count();
                $follow = Follow::where('user_id', $user_id)->where('follow_id', $uid)->first();
                if (!empty($follow)) {//关注过
                    $user->like = 1;
                } else {
                    $user->like = 0;
                }
                $supplier = DB::table('ecs_supplier')->select('supplier_id')->where('status', 1)->where('user_id', $uid)->first();
                if (!empty($supplier)) {
                    $user->supplier_id = $supplier->supplier_id;
                } else {
                    $user->supplier_id = '0';
                }
            }
            switch ($type) {
                case 'show':
                    $user->shows = $this->_getShow($uid);
                    break;
                case 'likes':
                    $user->likes = $this->_getLikes($uid, $user_id,$request);
                    break;
                case 'fans':
                    $user->fans = $this->_getFans($uid, $user_id);
                    break;
                case 'home':
                    $supplier = DB::table('ecs_supplier')->select('supplier_id')->where('status', 1)->where('user_id', $uid)->first();
                    if (!empty($supplier)) {
                        $goods = DB::table('ecs_goods')
                                        ->select('goods_id', 'goods_name', 'market_price', 'shop_price', 'goods_thumb', DB::raw("CONCAT('http://www.377123.org/' , goods_thumb) AS goods_thumb"), 'goods_name', 'goods_number')
                                        ->where('supplier_id', $supplier->supplier_id)->where('is_show_user', 1)->where('is_on_sale', 1)->where('is_delete', 0)->where('goods_number', '>', 0)->take(4)->orderBy('goods_id', 'desc')->get();

                        $home['goods'] = $goods;
                    }
                    $home['photos'] = $user->photos;
                    $home['about'] = $user->about;
                    $home['image'] = $user->image;

                    $alias = !empty($user->alias) ? $user->alias : '信息未完善，敬请期待';
                    $home['alias'] = $alias;

                    $user->home = $home;
                    unset($user->photos);
                    unset($user->about);

                    break;
            }
            return $this->success($user, 'ok');
        }
        return $this->error(null, '用户不存在！');
    }

    /**
     * 晒晒点赞列表
     * @param \Illuminate\Http\Request $request
     */
    public function getMessage(Request $request) {
        $user_id = $request->get('user_id');
        $page = $request->get('page', 1);
        if ($page < 2) {
            DB::table('ecs_users_show_message')->where('user_id', $user_id)->update(['is_read' => 1]);
        }
        $list = UserShowMessage::with(['tags', 'show', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'flag', 'is_v');
            }])->where('types', '<', 3)->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(12)->toArray();
        if (!empty($list)) {
            return $this->success($list['data'], '');
        } else {
            return $this->success('', '');
        }
    }

    /**
     * 用户关注列表
     * @param type 
     */
    private function _getLikes($uid, $user_id,$request) {
        $page=$request->get('page');
        if($page<0){
            $lists = Follow::with(['user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'signature', 'flag', 'is_v');
            }])->where('user_id', $uid)->get()->toArray();
            $list['data']= $lists;
        }else{
            $list = Follow::with(['user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'signature', 'flag', 'is_v');
            }])->where('user_id', $uid)->paginate(12)->toArray();
        }
        $result = [];
        foreach ($list['data'] as $value) {
            if ($this->_checkLike($user_id, $value['follow_id'])) {
                $value['like'] = 1;
            } else {
                $value['like'] = 0;
            }
            $result[] = $value;
        }
        return $result;
    }

    /**
     * 用户粉丝列表
     * @param \Illuminate\Http\Request $request
     */
    private function _getFans($uid, $user_id) {
        $list = Follower::with(['user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'signature', 'flag', 'is_v');
            }])->where('user_id', $uid)->paginate(12)->toArray();
        $result = [];
        foreach ($list['data'] as $value) {
            if ($this->_checkLike($user_id, $value['follower_id'])) {
                $value['like'] = 1;
            } else {
                $value['like'] = 0;
            }
            $result[] = $value;
        }
        return $result;
    }

    /**
     * 检查用户关注与否
     * @param type $user_id
     * @param type $like_id
     */
    private function _checkLike($user_id, $like_id) {
        $follow = Follow::where('user_id', $user_id)->where('follow_id', $like_id)->first();
        if (!empty($follow)) {
            return true;
        }
        return false;
    }

    /**
     * 当前用户的晒晒
     * @param type $uid
     */
    private function _getShow($uid) {
        $list = Show::where('user_id', $uid)->orderBy('id', 'desc')->paginate(18)->toArray();
        return $list['data'];
    }

    /**
     * 通知
     * @param type $data
     * @param type $action
     * @return boolean
     */
    private function changeUserShowMessage($d) {
        $data['types'] = 3;
        $data['show_tag_id'] = 0;
        $data['from_user_id'] = $d['from_user_id'];
        $data['user_id'] = $d['user_id'];
        $data['show_id'] = 0;
        UserShowMessage::create($data);
        $result = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('user_id', $data['user_id'])->count());

        $fans = strval(DB::table('ecs_users_show_message')->where('is_read', 0)->where('types', 3)->where('user_id', $data['user_id'])->count());
        $this->sendMessage($data['user_id'], ['type' => 'personal_tips', 'body' => $result, 'TYPE_PUSH_FANS_MSG' => $fans], '', 1);
        return true;
    }

}
