<?php

namespace App\Console\Commands;

use App\Events\IntegralEvent;
use App\Events\SendMessageEvent;
use Illuminate\Console\Command;
use App\Market as Markets;
class Market extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:market';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     *
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
        //1.计算上一期猜大盘    --怎么知道是上一期
        $ymd = date('Y-m-d');
        $list=Markets::where('status',1)->where('time',$ymd)->get();
        if(!empty($list)){
            $json=file_get_contents("https://gupiao.baidu.com/api/rails/stockbasicbatch?from=pc&os_ver=1&cuid=xxx&vv=100&format=json&stock_code=sh000300");
            $r = json_decode($json,true);
            $h3=$r['data'][0]['netChangeRatio'];
        }
        foreach ($list as  $k=>$v){
            $v->status=2;
            $v->input_time=date("Y-m-d H:i");
            //2.怎么查询出上一期的涨跌%比
            if($v->option==1&&$h3>=0.04){
                $v->profit=$v->price*2;
            }
            if($v->option==2&&($h3>-0.04&&$h3<0.04)){
                $v->profit=$v->price*4;
            }
            if($v->option==3&&$h3<=-0.04){
                $v->profit=$v->price*2;
            }
            if($v->profit>0){
                event(new IntegralEvent($v->user_id,$v->profit,'用户您好，您竞猜第'.$ymd.'期沪深300指数，竞猜成功 ，获得酒币',5));
            }else{
                event(new IntegralEvent($v->user_id,0,'亲爱的用户您好，您参与的第'.$ymd.'期沪深300指，竞猜失败，获得酒币',6));
            }
            $v->save();
        }
        //通知相关人员
        $this->info('ok');
    }
}
