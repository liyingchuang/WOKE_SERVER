<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\UserShowMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\User;
use App\ShowTag;
use App\UserShowOff;

class UserController extends Controller {

    /**
     * 统一验证用户
     */
    public function __construct() {
        $this->middleware('api_guest', ['except' => ['getShare', 'getVlist']]);
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
        $list = UserShowMessage::with(['tags', 'show', 'user' => function($query) {
                $query->select('user_id', 'user_name', 'user_rank', 'headimg', 'is_v', 'flag');
            }])->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(12)->toArray();
        if (!empty($list)) {
            return $this->success($list['data'], '');
        } else {
            return $this->success('', '');
        }
    }

    /**
     * 大师分享
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function getShare(Request $request) {
        $user_id = $request->get('user_id');
        $user = User::with(['photos' => function($q) {
                $q->take(2)->get();
            }])->select('user_id', 'user_name', 'user_rank', 'user_name', 'about','image', 'alias', 'headimg', 'is_v', 'flag', 'show_view_number')->where('user_id', $user_id)->first();

        if (!empty($user)) {
            $supplier = DB::table('ecs_supplier')->select('supplier_id')->where('status', 1)->where('user_id', $user_id)->first();
            if (!empty($supplier)) {
                $supplier_id = $supplier->supplier_id;
            } else {
                $supplier_id = '-1';
            }
            $goods = DB::table('ecs_goods')
                            ->select('ecs_goods.goods_id', 'ecs_goods.goods_name', 'ecs_goods.click_count', 'ecs_goods.shop_price', 'ecs_goods.add_time', 'ecs_goods.goods_sn', 'ecs_goods.shop_price', 'ecs_goods.market_price', 'ecs_goods.goods_thumb', 'ecs_goods.goods_number', 'ecs_goods.goods_type as goods_sales')
                            ->where('ecs_goods.is_on_sale', 1)->where('ecs_goods.is_delete', 0)->where('ecs_goods.supplier_id', $supplier_id)
                            ->take(2)->orderBy('add_time', 'desc')->get();
            $user->goods = $goods;
            return $this->success($user, 'ok');
        }
        return $this->error(null, '大师不存在');
    }

    /**
     * 用户晒晒侧滑接口
     * @param \Illuminate\Http\Request $request
     */
    public function getHome(Request $request) {
        $user_id = $request->get('user_id');
        $top = $request->get('top', 9);
        //$tags = ShowTag::select(DB::raw('sum(size) AS size'), 'tag_name')->groupBy('tag_name')->orderBy('size', 'desc')->take($top)->get()->toArray();
        $user = User::where('user_id', $user_id)->select('user_id', 'user_rank', 'user_name', 'user_name as nickname', 'mobile_phone', 'email', 'pay_points', 'headimg', 'is_v', 'flag', 'signature', 'follow_size', 'show_view_number')->first();
        $result = $user->toArray();
        $result['like_size'] = strval(DB::table('ecs_users_show_message')->where('types', '<', 3)->where('user_id', $user_id)->count());
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
        $result['tags'] = $tags;
        return $this->success($result);
    }

    /**
     * 我不喜欢
     * @param \Illuminate\Http\Request $request
     */
    public function getShowoff(Request $request) {
        $user_id = $request->get('user_id');
        $show_id = $request->get('show_id');
        $showoff = UserShowOff::where('user_id', $user_id)->first();
        if (!empty($showoff)) {
            $showcount = explode(',', $showoff->desc);
            if (!in_array($show_id, $showcount)) {
                array_push($showcount, $show_id);
                $showoff->desc = implode(',', $showcount);
                $showoff->save();
            }
        } else {
            UserShowOff::create(['user_id' => $user_id, 'desc' => $show_id]);
        }
        return $this->success(null, '已不喜欢这条晒晒');
    }

}
