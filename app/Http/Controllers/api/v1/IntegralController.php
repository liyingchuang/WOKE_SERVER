<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use App\IntegralGoods;
use App\IntegralOrder;
use App\IntegralUserAddress;
use App\PrizeGoods;
class IntegralController extends ApiController
{

    /**
     * 统一验证用户
     */
    public function __construct()
    {
        //$this->middleware('api_guest', ['except' => ['getView','getIndex']]);
    }

    /**
     * 获取用户地址
     * @param Request $request
     */
    public function getAddress(Request $request)
    {
        $user_id = $request->get('user_id');
        $address = IntegralUserAddress::select('username', 'address', 'mobile')->where('user_id', $user_id)->first();
        if (empty($address)) {
            $user_address = DB::table('ecs_user_address')->where('user_id', $user_id)->where('is_default', 1)->first();
            if (!empty($user_address)) {
                $province = DB::table('ecs_region')->select('region_name')->where('region_id', $user_address->province)->first(); //省
                $city = DB::table('ecs_region')->select('region_name')->where('region_id', $user_address->city)->first(); //市
                $district = DB::table('ecs_region')->select('region_name')->where('region_id', $user_address->district)->first(); //区
                $addres = !empty($province) ? $province->region_name : '';
                $addres .= !empty($city) ? $city->region_name : '';
                $addres .= !empty($district) ? $district->region_name : '';
                $address['address'] = $addres . $user_address->address;
                $address['username'] = $user_address->consignee;
                $address['mobile'] = $user_address->mobile;
            } else {
                return $this->success(null, '无地址');
            }
        }
        return $this->success($address, '');
    }

    /**
     * 积分商城首页
     * @param Request $request
     */
    public function getIndex(Request $request)
    {
        $user_id = $request->get('user_id',-1);
        $result = [];
        $user = DB::table('ecs_integral_user')->where('user_id', $user_id)->first();
        if (!empty($user)) {
            $result['user_info'] = $user->integral;
        } else {
            $result['user_info'] = '0';
        }
        $list = IntegralGoods::where('is_delete', 0)->where('is_show', 0)->orderBy('sort_order', 'asc')->orderBy('id', 'desc')->paginate(8)->toArray();
        $result['goods'] = $list['data'];
        return $this->success($result, '');
    }

    /**
     * 商品详情
     * @param Request $request
     */
    public function getView(Request $request)
    {
        $goods_id = $request->get('goods_id', 0);
        $info = IntegralGoods::where('is_delete', 0)->find($goods_id);
        if (empty($info)) {
            return $this->error(null, '商品不存在');
        } else {
            return $this->success($info, '');
        }
    }

    /**
     * 用户购买列表
     * @param Request $request
     */
    public function getUser(Request $request)
    {
        $user_id = $request->get('user_id');
        $list = IntegralOrder::with('goods')->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(10)->toArray();
        foreach ($list['data'] as $k=> $v){
            if($v['prize']>0){
                $g=PrizeGoods::select('name' ,'image','created_at')->where('type',1)->where('id',$v['goods_id'])->first();
                $g->integral='0';
                $g->goods_thumb=$g->image;
                $v['goods']=$g;
            }
            $list['data'][$k]['goods']=$v['goods'];
        }
        return $this->success($list['data'], '');
    }

    /**
     * 用户购买
     * @param Request $request
     */
    public function postOrder(Request $request)
    {
        $user_id = $request->get('user_id');
        $goods_id = $request->get('goods_id', 0);
        $name = $request->get('name');
        $address = $request->get('address');
        $mobile = $request->get('mobile');
        $good = IntegralGoods::where('is_delete', 0)->where('goods_number', '>', 1)->find($goods_id);
        if (empty($good)) {
            return $this->error(null, '商品已下架或者无库存!');
        }
        $user = DB::table('ecs_integral_user')->where('user_id', $user_id)->first();
        if (empty($user)) {
            return $this->error(null, '用户积分不足!');
        } else {
            if ($user->integral < $good->integral) {
                return $this->error(null, '用户积分不足!');
            }
        }
        //地址处理
        $user_address = IntegralUserAddress::where('user_id', $user_id)->first();
        if (empty($user_address)) {
            IntegralUserAddress::create(['username' => $name, 'user_id' => $user_id, 'address' => $address, 'mobile' => $mobile]);
        } else {
            $user_address->username = $name;
            $user_address->address = $address;
            $user_address->mobile = $mobile;
            $user_address->save();
        }
        //1.log   2.减积分 3.生成订单 4.减库存
        $integral = $user->integral - $good->integral;
        DB::table('ecs_integral_user')->where('user_id', $user_id)->update(['integral' => $integral]);
        $desc = '换购 ' . $good->name . '减' . $good->integral . '分';
        DB::table('ecs_integral_user_detailed')->insert(['user_id' => $user_id, 'size' => $good->integral, 'desc' => $desc, 'create_time' => time(), 'type' => 0]);
        $order = IntegralOrder::create(['user_id' => $user_id, 'goods_id' => $goods_id, 'integral' =>$good->integral, 'username' => $name, 'order_sn' => $this->build_order_no(), 'address' => $address, 'mobile' => $mobile]);
        $good->goods_number=$good->goods_number-1;
        $good->save();
        return $this->success($order, '兑换成功');
    }

    private function build_order_no()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * 积分管理
     * @return \Illuminate\Http\Response
     */
    public function getAdd(Request $request)
    {
        //《用户项目明细表》操作--用户发送请求
        $enname = $request->get('enname');
        $user_id = $request->get('user_id');
        $ip = $request->getClientIp();
        switch ($enname) {
            case 'appstore':
                $versions = $request->get('versions');
                $edition = DB::table('ecs_edition')->select('id', 'edition')->where('edition', "$versions")->orderBy('id', 'desc')->first();
                if (empty($edition)) {
                    return $this->error(null, '请联系客服，版本不存在.');
                }
                $edition_user = DB::table('ecs_edition_user')->select('id', 'edition_id')->where('edition_id', $edition->id)->where('user_id', $user_id)->orderBy('id', 'desc')->first();
                if (empty($edition_user)) {
                    $this->integral_add($user_id, $enname, $ip);
                    DB::insert('insert into ecs_edition_user ( user_id, edition_id, created_at) values ("' . $user_id . '", "' . $edition->id . '", "' . date("Y-m-d H:i:s") . '")');
                    return $this->success(null, '执行成功');
                } else {
                    $edition_new = DB::table('ecs_edition')->select('id', 'edition')->orderBy('id', 'desc')->first();
                    if ($edition_new->id == $edition_user->edition_id)
                        return $this->error(null, '您已经评论过！详情请看规则.');
                    else
                        return $this->success(null, '您可以去AppStore下载新的版本');
                }
                break;
            default:
                $result = $this->integral_add($user_id, $enname, $ip);
                if ($enname == 'sign')
                    return $this->success($result, '执行成功');
                else
                    return $this->success(null, '执行成功');
                break;
        }
    }

    /**
     * 积分完成情况
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function getShow(Request $request)
    {
        $user_id = $request->get('user_id');//token
        $result = [];
        $integral = DB::table('ecs_integral')->select('id', 'name', 'min', 'max', 'type', 'integral')->where('enname','!=', 'sign')->where('is_show',1)->orderBy('sort_order', 'asc')->get();
        $result = $this->integral_progress($integral, $user_id);

        $versions = $request->get('versions',null);
        if($versions){
            $edition = DB::table('ecs_edition')->select('id', 'edition')->where('edition', "$versions")->orderBy('id', 'desc')->first();
            if (empty($edition)) {
                $result['whether'] = 0;//没有评论
            }else{
                $edition_user = DB::table('ecs_edition_user')->select('id', 'edition_id')->where('edition_id', $edition->id)->where('user_id', $user_id)->orderBy('id', 'desc')->first();
                if (empty($edition_user)) {
                    $result['whether'] = 0;//没有评论
                }else{
                    $edition_new = DB::table('ecs_edition')->select('id', 'edition')->orderBy('id', 'desc')->first();
                    if ($edition_new->id == $edition_user->edition_id)
                        $result['whether'] = 1;//评论过
                    else
                        $result['whether'] = 0;//没有评论
                }
            }
        }else{
            $result['whether'] = 0;//没有评论
        }

        return $this->success($result, '返回成功');
    }

    /**
     * 积分进度查询
     * @return \Illuminate\Http\Response
     */

    private function integral_progress($integral, $id)
    {
        $result = [];
        foreach ($integral as $k => $v) {
            if ($v->type == 0) {
                $count = DB::table('ecs_integral_detailed')
                    ->where('user_id', $id)->where('integral_id', $v->id)
                    ->where(DB::raw("FROM_UNIXTIME(create_time,'%Y-%m-%d')"), date('Y-m-d', time()))
                    ->count();
                $v->count = $count;
                $result['everyday'][] = $v;//综合
            } else {
                $count = DB::table('ecs_integral_detailed')
                    ->where('user_id', $id)->where('integral_id', $v->id)
                    ->count();
                $v->count = $count;
                $result['separate'][] = $v;//每日
            }
        }
        $integral_new = $this->integral($id);
        $result['separate_integral'] = $integral_new;
        $user_new =  DB::table('ecs_integral_user')->where('user_id', $id)->first();
        if(empty($user_new)){
            $data['user_id'] = $id;
            $data['integral'] = $integral_new;
            $data['create_time'] = time();
            DB::table('ecs_integral_user')->insert($data);
        }else{
            DB::table('ecs_integral_user')->where('user_id', $id)->update(array('integral' => $integral_new));
        }
        $result['everyday_integral'] = DB::table('ecs_integral_user_detailed')->where('user_id', $id)->where(DB::raw("FROM_UNIXTIME(create_time,'%Y-%m-%d')"), date('Y-m-d', time()))->groupBy('user_id')->sum('size');
        $sign = DB::table('ecs_integral')->select('id', 'name', 'min', 'max', 'type', 'integral')->where('enname', 'sign')->where('is_show',1)->orderBy('sort_order', 'asc')->first();
        $count = DB::table('ecs_integral_detailed')->where('user_id', $id)->where('enname', 'sign')->where(DB::raw("FROM_UNIXTIME(create_time,'%Y-%m-%d')"), date('Y-m-d', time()))->count();
        if ($count >= 1)
            $sign->count = 1;
        else
            $sign->count = 0;
        $result['sign'] = $sign;
        return $result;
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
}
