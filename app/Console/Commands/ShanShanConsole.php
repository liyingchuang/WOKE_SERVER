<?php

namespace App\Console\Commands;

use App\Events\SendMessageEvent;
use App\StockCalendar;
use Illuminate\Console\Command;
use App\Market as Markets;
class ShanShanConsole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:shanshan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ymd=date('Y-m-d');
        $time=$this->getbefore($this->getCalendar($ymd));
        $one['price']=Markets::where('option',1)->where('time',$time)->sum('price');
        $one['count']=Markets::where('option',1)->where('time',$time)->count();
        $two['price']=Markets::where('option',2)->where('time',$time)->sum('price');
        $two['count']=Markets::where('option',2)->where('time',$time)->count();
        $three['price']=Markets::where('option',3)->where('time',$time)->sum('price');
        $three['count']=Markets::where('option',3)->where('time',$time)->count();
        $t=date('Y年m月d日H点i分');
        event(new SendMessageEvent(10016,'姗姗我是你的秘书','现在向你报告截止'.$t.'购买沪深300-'.$time.'期买涨:'. $one['price'].'元'. $one['count'].'人,买平:'. $two['price'].'元'. $two['count'].'人,买跌:'.$three['price'].'元'. $three['count'].'人。',1));
        event(new SendMessageEvent(10016,'姗姗我是你的秘书','现在向你报告截止'.$t.'购买沪深300-'.$time.'期买涨:'. $one['price'].'元'. $one['count'].'人,买平:'. $two['price'].'元'. $two['count'].'人,买跌:'.$three['price'].'元'. $three['count'].'人。',1));
        event(new SendMessageEvent(10016,'姗姗我是你的秘书','重要的事说三遍截止'.$t.'购买沪深300-'.$time.'期买涨:'. $one['price'].'元'. $one['count'].'人,买平:'. $two['price'].'元'. $two['count'].'人,买跌:'.$three['price'].'元'. $three['count'].'人。',1));
        $this->info('现在向你报告截止'.$t.'购买沪深300-'.$time.'期买涨:'. $one['price'].'元'. $one['count'].'人,买平:'. $two['price'].'元'. $two['count'].'人,买跌:'.$three['price'].'元'. $three['count'].'人。');
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
    private function getCalendar($ymd)
    {

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
}
