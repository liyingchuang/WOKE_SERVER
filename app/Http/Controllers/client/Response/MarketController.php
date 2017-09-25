<?php

namespace App\Http\Controllers\client\Response\V1;

use App\Events\IntegralEvent;
use App\Http\Controllers\client\Response\BaseResponse;
use App\Http\Controllers\client\Response\InterfaceResponse;
use App\Market;
use App\Products;
use App\S399300;
use App\StockCalendar;
use App\User;
use Illuminate\Http\Request;


class MarketController extends BaseResponse implements InterfaceResponse
{
    public function __construct()
    {
        $this->except = ['index'];
    }

    /**
     * 猜大盘首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $millisecond = $this->getMillisecond();
        $ymdhi = $request->get('latestTimelineStamp', 0);
        if ($ymdhi == 0) {
            $json = file_get_contents("https://gupiao.baidu.com/api/stocks/stocktimeline?from=pc&os_ver=1&cuid=xxx&vv=100&format=json&stock_code=sh000300&timestamp=" . $millisecond);
            $r = json_decode($json, true);
            $time=$this->getCalendar();
            $up = Market::where('status', 1)->where('option', 1)->where('time',$time)->count();
            $down = Market::where('status', 1)->where('option', 3)->where('time',$time)->count();
            $fall = Market::where('status', 1)->where('option', 2)->where('time',$time)->count();
            $r['up']=$up;
            $r['down']=$down;
            $r['fall']=$fall;
            $time1 = strtotime($time.'15:30:00');
            $time3 = $time1 - time();
            $r['end']=$this->time2string($time3);
            $json=file_get_contents("https://gupiao.baidu.com/api/rails/stockbasicbatch?from=pc&os_ver=1&cuid=xxx&vv=100&format=json&stock_code=sh000300&timestamp=" . $millisecond);
            $s = json_decode($json,true);
            $r['low']=$s;
        } else {
            $json = file_get_contents("https://gupiao.baidu.com/api/stocks/stocktimeline?from=pc&os_ver=1&cuid=xxx&vv=100&format=json&stock_code=sh000300&local_timeline_timestamp=" . $ymdhi . "&timestamp=" . $millisecond);
            $r = json_decode($json, true);
        }
        return $this->success($r, 'ok');
        /*
        $ymd = date('Y-m-d');
        $day = date('n月j日');
        $morning = S399300::where('date', $ymd)->where('time', '>=', '09:30:00')->where('time', '<=', '11:30:00')->get()->toArray();
        $afternoon = S399300::where('date', $ymd)->where('time', '>=', '13:00:00')->where('time', '<=', '15:00:00')->get()->toArray();
        $list = array_merge($morning, $afternoon);
        $last = end($list);
        $h1 = $last['price'];
        $h2 = $last['price']-$last['pre_close'] ;
        $h3 = $h2/$last['pre_close']*100;

        return $this->success(['title' => '沪深300', 'day' => $day, 'list' => $list, 'h1' => $h1, 'h2' => $h2, 'h3' => $h3], 'ok');*/
    }

    /**
     * 计算返回
     * @param $second
     * @return string
     */
    private function time2string($second){
            $hour = floor($second/3600);
            $second = $second%3600;//除去整小时之后剩余的时间
            $minute = floor($second/60);
            return $hour.'时'.$minute.'分';
    }
    private function time2stringtwo($second){
        $hour = floor($second/3600);
        $second = $second%3600;//除去整小时之后剩余的时间
        $minute = floor($second/60);
        $second = $second%60;//除去整分钟之后剩余的时间
        return $hour.':'.$minute.':'.$second;
    }
    /**
     *  参与猜大盘
     * @param Request $request
     */
    public function home(Request $request)
    {
        $user_id = $request->get('user_id', null); //pay_name 必须填写
        $user = User::select('user_money')->where('user_id', $user_id)->first();
        $ymd=$this->getCalendar();
        $time= $this->getbefore($ymd);
        $time1 = strtotime($time.'14:00:00');
        $time3 = $time1 - time();
        $response['u']=$user;
        $response['time']=$time3;
        return $this->success($response, 'ok');
    }

    /**
     * 获取前一个交易日
     */
    private function getbefore($ymd){
        $sc = StockCalendar::where('calendarDate','<', $ymd)->where('isOpen', 1)->orderBy('calendarDate','desc')->first();
        return $sc->calendarDate;
    }
    /**
     * 获取下一个交易日
     * @return mixed
     */
    private function getCalendar()
    {
        $ymd = date('Y-m-d');
        $sc = StockCalendar::where('calendarDate', $ymd)->where('isOpen', 1)->first();
        if (!empty($sc)) {//是交易日判断是上午还是下午
            $t = date("H:i:s");
            if ($t > '14:00:00') {
                $sc = StockCalendar::where('calendarDate', '>', $ymd)->where('isOpen', 1)->first();
                $sc = StockCalendar::where('calendarDate', '>', $sc->calendarDate)->where('isOpen', 1)->first();
            } else {
                $sc = StockCalendar::where('calendarDate', '>', $ymd)->where('isOpen', 1)->first();
            }
            return $sc->calendarDate;
        } else {
            $sc = StockCalendar::where('calendarDate', '>', $ymd)->where('isOpen', 1)->first();
            return $sc->calendarDate;
        }
    }

    /**
     *  参与猜大盘
     * @param Request $request
     */
    public function add(Request $request)
    {
        $user_id = $request->get('user_id', null); //pay_name 必须填写
        $price = $request->get('price', 0); //用户id 必须填写
        if($price<=0||$price % 10!=0){
            return $this->error(null, '请输入正确金额！');
        }
        $opt = $request->get('opt', null); //用户id 必须填写
        $user = User::select('user_money')->where('user_id', $user_id)->first();
        if ($price > $user->user_money) {
            return $this->error(null, '酒币不足！');
        } else {
            $ip = $request->getClientIp();
            $time=$this->getCalendar();
            $market = Market::create(['user_id' => $user_id, 'price' => $price, 'time' => $time, 'option' => $opt, 'input_time' => '', 'ip' => $ip]);
            event(new IntegralEvent($user_id, -$price, '亲爱的用户您好，您已成功下注竞猜第'.$time.'期猜大盘，预祝您竞猜成功 消耗酒币', 4));
            return $this->success($market, 'ok');
        }
    }

    /**
     * 历史
     * @param Request $request
     */
    public function lists(Request $request)
    {
        $user_id = $request->get('user_id', null); // 必须填写
        $opt = $request->get('opt', null); //用户id 必须填写
        if ($opt == 1) {//待出结果
            $list = Market::where('user_id', $user_id)->where('status', 1)->orderBy('id', 'desc')->get();
        }
        if ($opt == 2) {//已完成
            $list = Market::where('user_id', $user_id)->where('status', 2)->orderBy('id', 'desc')->get();
        }
        return $this->success($list, 'ok');
    }
}
