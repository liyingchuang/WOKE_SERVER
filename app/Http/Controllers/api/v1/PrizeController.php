<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use App\PrizeGoods;
use App\Prize;
use App\PrizeGoodsRelation;
use App\PrizeUser;
use App\PrizeRecord;
use App\User;
use App\PrizeExtract;
use App\IntegralUserAddress;
use App\IntegralOrder;
use App\PrizeShare;
class PrizeController extends ApiController
{

    /**
     * 统一验证用户
     */
    public function __construct()
    {
        $this->middleware('api_guest', ['except' => ['getList']]);
    }
    public function getShare(Request $request){
        $user_id = $request->get('user_id');
        $prizeShare=PrizeShare::where('user_id',$user_id)->first();
        if(!empty($prizeShare)){
            $myShare=PrizeShare::where('user_id',$user_id)->where('status',0)->where(DB::raw("FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y%m%d')"), date('Ymd', time()))->first();
            if(!empty($myShare)){
                $myShare->size=1;
                $myShare->status=1;
                $myShare->save();
            }
        }else{
            PrizeShare::create(['user_id'=>$user_id,'size'=>1,'status'=>1]);//分享啦
        }
        return $this->success(null, '成功');
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
        $prize_id = $request->get('prize_id');
        $good = PrizeUser::where('goods_id', $goods_id)->where('user_id',$user_id)->where('num', '>', 0)->first();
        if (empty($good)) {
            return $this->error(null, '你领取完了你抽到的物品!');
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
        $order = IntegralOrder::create(['user_id' => $user_id, 'prize'=>$prize_id,'goods_id' => $goods_id, 'integral' =>0, 'username' => $name, 'order_sn' => $this->build_order_no(), 'address' => $address, 'mobile' => $mobile]);
        $good->num=$good->num-1;
        $good->save();
        return $this->success($order, '兑换成功');
    }
    private function build_order_no()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
    /**
     * 抽奖页面展示
     * @param Request $request
     */
    public function getIndex(Request $request)
    {
        $user_id = $request->get('user_id');
        $result = [];
        $prize = Prize::select('id','name','size' ,'image','is_show','is_delete',DB::raw("FROM_UNIXTIME(UNIX_TIMESTAMP(start_time),'%m.%d') as start_time"),DB::raw("FROM_UNIXTIME(UNIX_TIMESTAMP(end_time),'%m.%d') as end_time"),'created_time')->where('is_show', 1)->first();
        if(empty($prize)){
            return $this->error(null, '暂时没有活动发布！');
        }
        /**** 抽奖流程 ****/
        $array =  $this->Executedraw($prize,$user_id);
        if(!$array)
            return $this->error(null, '抽奖系统出现错误！');
        $result['whether'] = $array['whether'];//0:可以抽奖 -1：积分不够 大于0：还剩几次免费抽奖的机会
        $prize_extract = $array['prize_extract'];
        $result['extract'] = $array['extract'];
        $result['integral'] = $array['integral'];//用户当前积分
        /**** 判断抽中的是否是碎片，是的话检测兑换的商品的数量是否存在 ****/
        if($result['whether'] >= 0){
            $goods_trade_goods = PrizeGoods ::select('id','name','num','fragment','trade_goods','image','type')->where('id', $prize_extract->goods_id)->where('type', 2)->first();
            if($goods_trade_goods){
                $trade_goods_num = PrizeGoods ::select('id','name','num')->where('id', $goods_trade_goods->trade_goods)->first();
                if($trade_goods_num->num <= 0 ){
                    $goods =  $this->Goods($prize->id,$trade_goods_num->id);
                }else{
                    $goods =  $this->Goods($prize->id);
                }
            }else{
                $goods =  $this->Goods($prize->id);
            }
        }else{
            $goods =  $this->Goods($prize->id);
            $result['extract'] = (object)null;
        }
        $user =  DB::table('ecs_prize_record')
            ->select('ecs_users.user_name' ,'ecs_prize_goods.name','ecs_prize_goods.fragment' )
            ->leftJoin('ecs_users', 'ecs_prize_record.user_id', '=', 'ecs_users.user_id')
            ->leftJoin('ecs_prize_goods', 'ecs_prize_record.goods_id', '=', 'ecs_prize_goods.id')
            ->where('ecs_prize_record.type',1)
            ->orderBy('ecs_prize_record.id', 'desc')
            ->limit(10)
            ->get();
        $result['user'] = $user;
        $result['prize'] = $prize;
        $result['goods'] = $goods;
        return $this->success($result, 'ok');
    }

    /*
   * 选取奖励池物品
   * */
    private function Goods($prize_id,$trade_id = 0) {
        $host=$_ENV['QINIU_HOST'].'/';
        $goods =  DB::table('ecs_prize_goods_relation')
                ->select('ecs_prize_goods.name',DB::raw("concat('$host',ecs_prize_goods.image) as image"),'ecs_prize_goods.type')
                ->leftJoin('ecs_prize_goods', 'ecs_prize_goods_relation.goods_id', '=', 'ecs_prize_goods.id')
                ->where('ecs_prize_goods_relation.prize_id',$prize_id)
                //->where('ecs_prize_goods.num','>',0)
                ->where('ecs_prize_goods.id','!=',$trade_id)
                ->where('ecs_prize_goods.is_show',1)
                ->where('ecs_prize_goods.is_delete',1)
                ->get();
        return $goods;
    }

    /**
     * 用户抽奖
     * @param Request $request
     */
    public function getShow(Request $request)
    {
        $obtain = 1;
        $host=$_ENV['QINIU_HOST'].'/';
        $user_id = $request->get('user_id');
        $prize_id = $request->get('prize_id');
        $result= [];
        $prize_size = Prize ::where('id', $prize_id)->first();
        $num = PrizeRecord::where('user_id', $user_id) ->where(DB::raw("FROM_UNIXTIME(created_time,'%Y-%m-%d')"), date('Y-m-d', time()))->count();
        $prize_extracts = PrizeExtract ::where('user_id', $user_id)->where('type', 0)->where('prize_id', $prize_id)->first();
        if(!$prize_extracts)
            return $this->error(null, '系统错误！');

        $share = PrizeShare::where('user_id', $user_id)->where('size','>',0)->where(DB::raw("FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y%m%d')"), date('Ymd', time()))->first();
        if(!empty($share)){
            $count = 1;
            $share->size = $share->size - 1;
            $share->save();
        }else{
            $count = 1-$num;
        }
        if($count <= 0){
            $user = DB::table('ecs_integral_user')->where('user_id', $user_id)->first();
            $integral = $user->integral - $prize_size->size;
            DB::table('ecs_integral_user')->where('user_id', $user_id)->update(['integral' => $integral]);
            $desc = '参加抽奖减' . $prize_size->size . '分积分';
            DB::table('ecs_integral_user_detailed')->insert(['user_id' => $user_id, 'size' => $prize_size->size, 'desc' => $desc, 'create_time' => time(), 'type' => 0]);
        }
        $prize_extracts->type = 1;
        $prize_extracts->save();
        $result_extract =PrizeGoods ::select('id','name','num','fragment',  DB::raw("concat('$host',image) as image"),'type','trade_goods')->where('id', $prize_extracts->goods_id)->first();
        if(!empty($result_extract)){
            /**** 用户中奖记录 ****/
            $data_prize['user_id'] = $user_id;
            $data_prize['goods_id'] = $prize_extracts->goods_id;
            $data_prize['prize_id'] = $prize_id;
            $data_prize['num'] = $obtain;
            $data_prize['type'] = $result_extract->type;
            $data_prize['use'] = 0;
            $data_prize['created_time'] = time();
            $user_goods =PrizeUser ::select('id','num')->where('user_id', $user_id)->where('goods_id', $prize_extracts->goods_id)->where('type',$result_extract->type)->where('use',0)->first();
            if(!empty($user_goods)){
                $user_goods->num = $user_goods->num+$obtain;
                $user_goods->save();
            }else{
                PrizeUser::create($data_prize);
            }
            $this->PrizeLog($user_id,$prize_id,$prize_extracts->goods_id,1);
            /**** 用户中奖碎片够了减库存****/
            if($result_extract->type == 2){
                $goods_prestore = PrizeGoods ::select('id','fragment')->where('type',2)->where('id', $result_extract->id)->first();
                $user_prestore =PrizeUser ::select('id','num','prestore')->where('user_id', $user_id)->where('goods_id', $prize_extracts->goods_id)->where('type',2)->where('use',0)->first();
                if($goods_prestore->fragment <= $user_prestore->num){
                    $user_prestore->prestore = $user_prestore->prestore +1;
                    $user_prestore->num = $user_prestore->num - $goods_prestore->fragment;
                    $user_prestore->save();

                    $goods_prestore_num = PrizeGoods ::select('id','fragment','num')->where('id', $result_extract->trade_goods)->first();
                    $goods_prestore_num->num = $goods_prestore_num->num - $obtain;
                    $goods_prestore_num->save();
                }
            }
            /**** 用户中奖积分项 ****/
            if($result_extract->type == 5){
                $user = DB::table('ecs_integral_user')->where('user_id', $user_id)->first();
                $integral = $user->integral + $prize_extracts->prize_num;
                DB::table('ecs_integral_user')->where('user_id', $user_id)->update(['integral' => $integral]);
                $desc = '参加抽奖抽中' . $prize_extracts->prize_num . '分积分';
                DB::table('ecs_integral_user_detailed')->insert(['user_id' => $user_id, 'size' => $prize_extracts->prize_num, 'desc' => $desc, 'create_time' => time(), 'type' => 1]);
            }
            if($count > 0){
                $result['whether'] = $count;
            }else{
                $user_prize = DB::table('ecs_integral_user')->where('user_id', $user_id)->first();
                if($user_prize->integral < $prize_extracts->size){
                    $result['whether'] = -1;//0:可以抽奖 -1：积分不够
                }else{
                    $result['whether'] = 0;//0:可以抽奖 -1：积分不够
                }
            }
            return $this->success($result, '恭喜中奖');
        }else{
            $this->PrizeLog($user_id,$prize_id,$prize_extracts->goods_id,0);
            return $this->success(null, '没有中奖');
        }
    }


    /*
     * 抽奖随机抽取
     * */
    private function Executedraw($prize,$user_id) {
        $obtain = 1;
        $host=$_ENV['QINIU_HOST'].'/';
        $prize_goods = PrizeGoodsRelation::select('goods_id' ,'probability')->where('prize_id', $prize->id)->get();
        $prize_array_id = [];
        foreach($prize_goods as $key=>$val){
            $prize_array_id[$val->goods_id] = $val->goods_id;
        }

        /**** 剔除商品数量低于0的 ****/
        $prize_goods_id = PrizeGoods::select('id')->where('num','<=',0)->whereIn('id',$prize_array_id)->get();
        foreach($prize_goods_id as $key=>$val){
            if($val->id == $prize_array_id[$val->id]) unset($prize_array_id[$val->id]);
        }

        $prize_goods_arr = PrizeGoods::select('id','num','fragment','type','trade_goods')->where('type', 2)->where('num','>',0)->whereIn('id',$prize_array_id)->get();
        if(!empty($prize_goods_arr)){
            foreach($prize_goods_arr as $k => $v){
                $arr_goods =  DB::table('ecs_prize_goods_relation')
                    ->select('ecs_prize_goods_relation.goods_id','ecs_prize_goods_relation.probability','ecs_prize_goods.num','ecs_prize_goods.trade_goods')
                    ->leftJoin('ecs_prize_goods', 'ecs_prize_goods_relation.goods_id', '=', 'ecs_prize_goods.id')
                    ->where('ecs_prize_goods.id',$v->trade_goods)
                    ->where('ecs_prize_goods.is_delete',1)
                    ->first();
                if(empty($arr_goods))
                    return false;
                if($arr_goods->num <= 0){
                    if($v->id == $prize_array_id[$v->id]) unset($prize_array_id[$v->id]);
                }
            }
        }
        $prize_goods_array = PrizeGoodsRelation::select('goods_id' ,'probability')->where('prize_id', $prize->id)->whereIn('goods_id',$prize_array_id)->get();
        $prize_sum = PrizeGoodsRelation::where('prize_id', $prize->id)->groupBy('prize_id')->sum('probability');
        $prize_count = PrizeGoodsRelation::select('goods_id' ,'probability')->where('prize_id', $prize->id)->count();
        $arr = [];
        $prize_arr = [];
        $array= [];
        foreach($prize_goods_array as $key =>$val){
            $arr[$key+1] = $val->probability;
            $prize_arr[$key+1] = $val->goods_id;
        }
        $arr[$prize_count+1] = 100-$prize_sum;
        $prize_arr[$prize_count+1] = 0;
        $prize_res = $this->getRand($arr);
        $res = $prize_arr[$prize_res];
        $num = PrizeRecord::where('user_id', $user_id) ->where(DB::raw("FROM_UNIXTIME(created_time,'%Y-%m-%d')"), date('Y-m-d', time()))->count();
        $share = PrizeShare::select('size')->where('user_id', $user_id)->where('size','>',0)->where(DB::raw("FROM_UNIXTIME(UNIX_TIMESTAMP(updated_at),'%Y%m%d')"), date('Ymd', time()))->first();
        if(!empty($share)){
            if($num > 0)
                $count = $share->size ;
            else
                $count = 1 + $share->size ;
        }else{
            $count = 1-$num;
        }
        $user_prize = DB::table('ecs_integral_user')->where('user_id', $user_id)->first();
        /**** 返回用户当前积分 ****/
        if(empty($user_prize)){
            $whether = -1;//0:可以抽奖 -1：积分不够
            $array['integral'] = 0;
        }else{
            if($user_prize->integral < $prize->size){
                $whether = -1;//0:可以抽奖 -1：积分不够
            }else{
                $whether = 0;//0:可以抽奖 -1：积分不够
            }
            $array['integral'] = $user_prize->integral;
        }
        if($count > 0){
            $whether = $count;
        }
        $prize_extract =  DB::table('ecs_prize_extract')
            ->select('ecs_prize_extract.id','ecs_prize_extract.user_id','ecs_prize_extract.goods_id','ecs_prize_extract.prize_id','ecs_prize_goods.name',DB::raw("concat('$host',ecs_prize_goods.image) as image"),'ecs_prize_goods.type','ecs_prize_extract.prize_num')
            ->leftJoin('ecs_prize_goods', 'ecs_prize_extract.goods_id', '=', 'ecs_prize_goods.id')
            ->where('ecs_prize_extract.user_id', $user_id)
            ->where('ecs_prize_extract.type', 0)->where('prize_id', $prize->id)
            ->orderBy('ecs_prize_extract.created_time', 'desc')
            ->first();
        $extract = (object)null;
        if($whether >= 0 ){
            if(!$prize_extract){
                $data_extractg['user_id'] = $user_id;
                $data_extractg['goods_id'] = $res;
                $data_extractg['prize_id'] = $prize->id;
                $data_extractg['type'] = 0;
                $gtype = PrizeGoods ::select('id','type','min','max')->where('id', $res)->first();
                if($gtype)
                    $data_extractg['prize_num'] = rand($gtype->min,$gtype->max);
                else
                    $data_extractg['prize_num'] = 0;
                $data_extractg['created_time'] = time();
                $prize_extract_add = PrizeExtract::create($data_extractg);
                $prize_extract =  DB::table('ecs_prize_extract')
                    ->select('ecs_prize_extract.id','ecs_prize_extract.user_id','ecs_prize_extract.goods_id','ecs_prize_extract.prize_id','ecs_prize_goods.name',DB::raw("concat('$host',ecs_prize_goods.image) as image"),'ecs_prize_goods.type','ecs_prize_extract.prize_num')
                    ->leftJoin('ecs_prize_goods', 'ecs_prize_extract.goods_id', '=', 'ecs_prize_goods.id')
                    ->where('ecs_prize_extract.id', $prize_extract_add->id)
                    ->first();

            }
            $result_extract =PrizeGoods ::select('id','name','num','fragment','image','type')->where('id', $prize_extract->goods_id)->first();
            if(!empty($result_extract)){
                $extract = $prize_extract;
                if($prize_extract->type == 5)
                    $extract->name = "$extract->prize_num 积分";
                unset($extract->prize_num);
                unset($prize_extract->prize_num);
                /**** 更新库存 ****/
                if($result_extract->type == 2)
                    $result_extract->num = $result_extract->num-$obtain;
                else
                    $result_extract->num = $result_extract->num-$result_extract->fragment;
                $result_extract->save();
            }
        }
        $array['prize_extract'] = $prize_extract;
        $array['whether'] = $whether;
        $array['extract'] = $extract;
        return $array;
    }

    /*
     * 抽奖随机抽取
     * */
    private function getRand($proArr) {
        $data = '';
        $proSum = array_sum($proArr);
        foreach ($proArr as $k => $v) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $v) {
                $data = $k;
                break;
            } else {
                $proSum -= $v;
            }
        }
        unset($proArr);
        return $data;
    }

    /*
     *记录抽奖日志
     * */
    private function PrizeLog($user_id,$prize_id,$res,$type){
        if($type == 0)
            $data_log['goods_id'] = 0;
        else
            $data_log['goods_id'] = $res;
        $data_log['type'] = $type;
        $data_log['user_id'] = $user_id;
        $data_log['prize_id'] = $prize_id;
        $data_log['created_time'] = time();
        PrizeRecord::create($data_log);
    }

    /**
     * 用户私有用品
     * @param Request $request
     */
    public function getUser(Request $request)
    {
        $host=$_ENV['QINIU_HOST'].'/';
        $page = $request->get('page',1);
        $result = [];
        if( $page>1 )
            return $this->success($result, 'ok');
        $user_id = $request->get('user_id');
        $type = $request->get('type',1);
        $prize = Prize::select('id')->where('is_show', 1)->first();
        if($type == 1)
            $type = array(1,4);
        else
            $type = array($type);
        if(in_array(2,$type)){
            $result = DB::table('ecs_prize_user')
                ->select('ecs_prize_user.user_id','ecs_prize_user.prestore','ecs_prize_user.goods_id','ecs_prize_user.num','ecs_prize_goods.type','ecs_prize_goods.name','ecs_prize_goods.fragment','ecs_prize_goods.created_time',DB::raw("concat('$host',ecs_prize_goods.image) as image"))
                ->leftJoin('ecs_prize_goods', 'ecs_prize_user.goods_id', '=', 'ecs_prize_goods.id')
                ->where(DB::raw("FROM_UNIXTIME(ecs_prize_user.created_time,'%Y-%m-%d')"),'<=', date('Y-m-d', time()))
                ->where(DB::raw("FROM_UNIXTIME(ecs_prize_user.created_time,'%Y-%m-%d')"),'>', date('Y-m-d',  strtotime("-2 months", time())))
                ->whereIn('ecs_prize_user.type',$type)
                ->where('ecs_prize_user.num','>',0)
                ->where('ecs_prize_user.user_id',$user_id)->where('ecs_prize_user.use', 0)
                ->orderBy('ecs_prize_user.created_time', 'desc')
                ->get();
            foreach($result as $key=>$val){
                $result[$key]->start_time = date("Y.m.d", $val->created_time);
                $result[$key]->end_time = date("Y.m.d.", strtotime("+2 months", $val->created_time));
                $result[$key]->prize = $prize->id;
                $result[$key]->prize_num = 0;
            }
        }else if(in_array(5,$type)){
            $result = DB::table('ecs_prize_extract')
                ->select('ecs_prize_extract.user_id','ecs_prize_extract.goods_id','ecs_prize_goods.type','ecs_prize_goods.name','ecs_prize_goods.fragment','ecs_prize_goods.created_time',DB::raw("concat('$host',ecs_prize_goods.image) as image"),'ecs_prize_extract.prize_num')
                ->leftJoin('ecs_prize_goods', 'ecs_prize_extract.goods_id', '=', 'ecs_prize_goods.id')
                ->where('ecs_prize_extract.type',1)
                ->whereIn('ecs_prize_goods.type',$type)
                ->orderBy('ecs_prize_extract.created_time', 'desc')
                ->get();
            foreach($result as $key=>$val){
                $result[$key]->prestore = 0;
                $result[$key]->num = 1;
                $result[$key]->start_time = date("Y.m.d", $val->created_time);
                $result[$key]->end_time = date("Y.m.d.", strtotime("+2 months", $val->created_time));
                $result[$key]->prize = $prize->id;
            }
        }else{
            $pre = DB::table('ecs_prize_user')
                ->select('ecs_prize_user.user_id','ecs_prize_user.prestore','ecs_prize_user.goods_id','ecs_prize_user.num','ecs_prize_goods.type','ecs_prize_goods.name','ecs_prize_goods.fragment','ecs_prize_goods.created_time',DB::raw("concat('$host',ecs_prize_goods.image) as image"))
                ->leftJoin('ecs_prize_goods', 'ecs_prize_user.goods_id', '=', 'ecs_prize_goods.id')
                ->where(DB::raw("FROM_UNIXTIME(ecs_prize_user.created_time,'%Y-%m-%d')"),'<=', date('Y-m-d', time()))
                ->where(DB::raw("FROM_UNIXTIME(ecs_prize_user.created_time,'%Y-%m-%d')"),'>', date('Y-m-d',  strtotime("-2 months", time())))
                ->whereIn('ecs_prize_user.type',$type)
                ->where('ecs_prize_user.num','>',0)
                ->where('ecs_prize_user.user_id',$user_id)->where('ecs_prize_user.use', 0)
                ->orderBy('ecs_prize_user.created_time', 'desc')
                ->get();
            $result = [];

            foreach($pre as $key=>$val){
                $array = $this->userArray($val);
                $pre[$key]->num = 1;
                $pre[$key]->start_time = date("Y.m.d", $val->created_time);
                $pre[$key]->end_time = date("Y.m.d.", strtotime("+2 months", $val->created_time));
                $pre[$key]->prize = $prize->id;
                $pre[$key]->prize_num = 0;
                $result = array_merge($array , $result);
            }
        }
        return $this->success($result, 'ok');
    }

    private function userArray($arr){
        $are = [];
        for($i=1;$i<=$arr->num;$i++){
            //$arr->num = 1;
            $are[] = $arr;
        }
        return $are;
    }
    /**
     * 用户点击合成(碎片)
     * @param Request $request
     */
    public function postSynthesis(Request $request)
    {
        $user_id = $request->get('user_id');
        $goods_id = $request->get('goods_id');
        $type = 2;
        $goods_num = PrizeGoods ::select('id','name','num','fragment','trade_goods')->where('id', $goods_id)->where('type',$type)->first();
        $user_goods =PrizeUser ::select('id','num','prestore')->where('goods_id', $goods_id)->where('type',$type)->where('use',0)->first();
        if($goods_num->fragment > $user_goods->num && $user_goods->prestore < 1 )
            return $this->success(null, '尊敬的用户，您的碎片不足以兑换本次物品！');
        $goods_trade = PrizeGoods ::select('id','type','name','fragment')->where('id', $goods_num->trade_goods)->first();
        $user_goods_prestore =PrizeUser ::select('id','num','prestore')->where('goods_id', $goods_num->id)->first();
        if($user_goods_prestore->prestore>0){
            $user_goods_prestore->prestore = $user_goods_prestore->prestore - 1;
            $user_goods_prestore->save();
        }else{
            /**** 兑换物品扣除碎片 ****/
            $user_goods->num = $user_goods->num - $goods_num->fragment;
            $user_goods->save();
        }
        /**** 兑换物品 ****/
        $user_goods_save =PrizeUser ::select('id','num')->where('goods_id', $goods_num->trade_goods)->where('type',4)->where('use',0)->first();
        if(!empty($user_goods_save)) {
            $user_goods_save->num = $user_goods_save->num + 1;
            $user_goods_save->save();
        }else{
            $data_prize['user_id'] = $user_id;
            $data_prize['goods_id'] = $goods_trade->id;
            $data_prize['num'] = 1;
            $data_prize['type'] = 4;
            $data_prize['use'] = 0;
            $data_prize['created_time'] = time();
            PrizeUser::create($data_prize);
        }
        $result = [];
        $result['goods'] = $goods_trade->name;
        return $this->success($result, '兑换成功');
    }

    /**
     * 分享链接用户展示
     */
    public function getList() {
        $host=$_ENV['QINIU_HOST'].'/';
        $result = [];
        $prize = Prize::select('id','name','size' ,'image','is_show','is_delete',DB::raw("FROM_UNIXTIME(UNIX_TIMESTAMP(start_time),'%m.%d') as start_time"),DB::raw("FROM_UNIXTIME(UNIX_TIMESTAMP(end_time),'%m.%d') as end_time"),'created_time')->where('is_show', 1)->first();
        if(empty($prize)){
            return $this->error(null, '暂时没有活动发布！');
        }
        $result['whether'] = 1;
        $extract =  DB::table('ecs_prize_goods_relation')
            ->select('ecs_prize_goods.name',DB::raw("concat('$host',ecs_prize_goods.image) as image"),'ecs_prize_goods.type','ecs_prize_goods.id as goods_id')
            ->leftJoin('ecs_prize_goods', 'ecs_prize_goods_relation.goods_id', '=', 'ecs_prize_goods.id')
            ->where('ecs_prize_goods_relation.prize_id',$prize->id)
            ->where('ecs_prize_goods.type',1)
            ->where('ecs_prize_goods.is_show',1)
            ->where('ecs_prize_goods.is_delete',1)
            ->first();
        $extract->id = 0;
        $extract->user_id = 0;
        $extract->prize_id = $prize->id;
        $result['extract'] = $extract;
        $result['integral'] = 0;
        $result['user'] =  DB::table('ecs_prize_record')
            ->select('ecs_users.user_name' ,'ecs_prize_goods.name','ecs_prize_goods.fragment' )
            ->leftJoin('ecs_users', 'ecs_prize_record.user_id', '=', 'ecs_users.user_id')
            ->leftJoin('ecs_prize_goods', 'ecs_prize_record.goods_id', '=', 'ecs_prize_goods.id')
            ->where('ecs_prize_record.type',1)
            ->where('ecs_prize_record.prize_id',$prize->id)
            ->orderBy('ecs_prize_record.id', 'desc')
            ->limit(10)
            ->get();
        $result['prize'] = $prize;
        $result['goods'] = $this->Goods($prize->id);//$result['extract']
        return $this->success($result, 'ok');
    }
}
