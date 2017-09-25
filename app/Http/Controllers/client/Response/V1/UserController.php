<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Advertisement;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\OrderInfo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index','share','order','team'];
    }
    /**
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $time = time();
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $user = User::select('user_rank','user_name','headimg','user_money','frozen_money','credit_line')->Where('user_id', $user_id)->first();
        $info['order']['payment']= OrderInfo::where('user_id', $user_id)->where('order_status', '<>', 2)->where('pay_status', 0)->count();
        $info['order']['deliver']= OrderInfo::where('user_id', $user_id)->where('shipping_status', 0)->where('order_status', '<>', 2)->where('pay_status', 2)->count();
        $info['ads'] = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', 7)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->first();
        //1.订单信息
        $info['order']['tel']="4000191818";
        $user->display=1;
        $info['order']['notifications']="提现功能将在近期开放，请大家期待！";
        $info['order']['user']=$user;
        //2.酒币信息
        return $this->success($info,'ok');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function order(Request $request)
    {
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $type = $request->get('type', 'all'); //用户id 必须填写
        $list=[];
        if($type=='all'){
            $list=OrderInfo::with('ordergoods.goods','store')->where('is_delete',0)->where('order_status',"<>",2)->where('user_id', $user_id)->orderBy('order_id','desc')->paginate(10)->toArray()['data'];
        }
        if($type=='pay'){//待付款
            $list=OrderInfo::with('ordergoods.goods','store')->where('is_delete',0)->where('order_status',1)->where('shipping_status',0)->where('pay_status',0)->where('user_id', $user_id)->orderBy('order_id','desc')->paginate(10)->toArray()['data'];
        }
        if($type=='receipt'){//待收货
            $list=OrderInfo::with('ordergoods.goods','store')->where('is_delete',0)->where('order_status',5)->where('shipping_status',1)->where('pay_status',2)->where('user_id', $user_id)->orderBy('order_id','desc')->paginate(10)->toArray()['data'];
        }
        if($type=='comment'){//待评论 (即订确认收货)
            $list=OrderInfo::with('ordergoods.goods','store')->where('is_delete',0)->where('order_status',5)->where('shipping_status',2)->where('pay_status',2)->where('user_id', $user_id)->orderBy('order_id','desc')->paginate(10)->toArray()['data'];
        }
        $response=[];
        foreach ($list as $k=>$v){
            $response[$k]['order_id']=$v['order_id'];
            $response[$k]['order_sn']=$v['order_sn'];
            $response[$k]['order_status']=$v['order_status'];
            $response[$k]['shipping_status']=$v['shipping_status'];
            $response[$k]['pay_status']=$v['pay_status'];
            $response[$k]['add_time']=$v['add_time'];
            $response[$k]['order_amount']=$v['order_amount'];
            $response[$k]['shipping_express']=$v['shipping_express'];
            $response[$k]['invoice_no']=$v['invoice_no'];
            $response[$k]['goods']=[];
            if($v['order_status']==5&&$v['shipping_status']==2&&$v['pay_status']==2){//待评论(即订确认收货)
                $response[$k]['status']='5';//待评论
            }
            if($v['order_status']==5&&$v['shipping_status']==1&&$v['pay_status']==2){//待收货
                $response[$k]['status']='4';//待收货
            }
            if($v['order_status']==1&&$v['shipping_status']==3&&$v['pay_status']==2){//配货中
                $response[$k]['status']='3';//配货中
            }
            if($v['order_status']==1&&$v['shipping_status']==0&&$v['pay_status']==2){//已付款
                $response[$k]['status']='3';//已付款
            }
            if($v['order_status']==1&&$v['pay_status']==0&&$v['shipping_status']==0){//待付款
                $response[$k]['status']='1';//待付款
            }
            $i=0;
            foreach ($v['ordergoods'] as $kk=>$v){
                $v['goods_img']=$v['goods']['goods_img'];
                unset($v['goods']);
                $response[$k]['goods'][]=$v;
                $i=$i+1;
            }
            $response[$k]['count']=$i;
            $response[$k]['tel']='4000191818';
        }
        $response=array_values($response);
        return $this->success($response,'ok');
    }

    /**
     * 用户信息编辑
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $user_name=$request->get('user_name', null); //用户id 必须填写
        $file=$request->get('file', null); //用户id 必须填写
        if(!empty($file)){
            $headimg=$this->upload($request);//用户id 必须填写
            $data['headimg']=$headimg;
            $result['headimg'] = $_ENV['QINIU_HOST'].'/'.$headimg;
        }else{
            $result['headimg']='';
        }
        if(!empty($user_name)){
            $data['user_name']=$user_name;
        }
        User::Where('user_id', $user_id)->update($data);
        $result['user_id'] = $user_id;
        $result['user_name'] = $user_name;
        return $this->success($result,'ok');
    }

    /**
     *  我的团队
     * @param Request $request
     */
    public function team(Request $request){
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $list = User::select('user_rank','user_name','headimg','user_id')->where('user_rank','>',0)->where('parent_id',$user_id)->orderBy('user_rank','desc')->orderBy('user_id','asc')->get();
        $result=[];
        foreach ($list as $k=>$v){
            $result[$k]['user_id']=$v->user_id;
            $result[$k]['level']=$v->user_rank;
            $result[$k]['user_name']=$v->user_name;
            $result[$k]['headimg']=$v->headimg;
            $result[$k]['direct']=User::where('parent_id',$v->user_id)->where('user_rank','>',0)->count();// 直接有效数
        }
       return $this->success($result,'ok');
    }

    /**
     * 我的分享
     * @param Request $request
     */
    public function share(Request $request){
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $user = User::select('user_rank','mobile_phone','user_name','headimg')->where('user_id', $user_id)->first();
        $result['user']=$user;
        $list = User::select('user_rank','mobile_phone','user_name','headimg')->where('parent_id', $user_id)->orderBy('user_id','desc')->get();
        $result['list']=$list;
        return $this->success($result,'ok');
    }
    /**
     * 我的分享
     * @param Request $request
     */
    public function account(Request $request){
        $user_id = $request->get('user_id', null); //用户id 必须填写
        $list = DB::table('woke_account_log')->orderBy('log_id', 'desc')->where('user_id', $user_id)->get();
        $user_money = DB::table('woke_account_log')->where('user_id', $user_id)->sum('user_money');
        return $this->success(['list'=>$list,'count'=>$user_money],'ok');
    }
}
