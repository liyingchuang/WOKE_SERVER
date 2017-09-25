<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OrderAddress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:orderaddress';

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
        $add = DB::table('woke_order_info')->select('order_id', 'address')->get();
        foreach($add as $item) {
            if($item->address){
                if(strpos($item->address,'-')){
                    $exp = explode('-', $item->address);//按照-拆分
                }else{
                    $exp = explode(' ', $item->address);//按照-拆分
                    if(count($exp) > 1){
                        $exp[0] = $exp[0]."省";
                        $exp[1] = $exp[1]."市";
                    }else{
                        $this->info($item->order_id.'Error');
                    }
                }
                if(count($exp) > 1){
                    $exp2 = explode('  ', $exp[2]); //按照两个空格拆分
                    //检测数组长度，如果长度只有 1，则证明是区和地址之间是 1 个空格，则按照一个空格来拆分
                    if (count($exp2) == 1) {
                        $exp2 = explode(' ', $exp[2]);
                    }
                    //检测拆分的数组key=0 是否为上海市
                    if ($exp[0] == "上海市") {
                        if (strpos($exp[1], "区")) {        // 检测key=1 是否包含区
                            $exp[2] = $exp[1] . $exp2[0]; //如果包含了区则将区拼接至key=2
                            $exp2[0] = $exp[1] . $exp2[0];
                        }
                        $exp[1] = "上海市";            //填充key=1为上海市
                    }
                    if (count($exp2) == 1) {
                        $exp2 = strpos($exp[2], "区") ? explode('区', $exp[2], 2) : explode('县', $exp[2], 2);
                        if (strpos($exp[2], "区")) {
                            $exp2[0] = $exp2[0] . "区";
                        }
                        if (strpos($exp[2], "县")) {
                            $exp2[0] = $exp2[0] . "县";
                        }
                    }
                    if(strpos($exp2[0], "区") || strpos($exp2[0], "县")){

                    }else{
                        $exp2[1] = $exp2[0];
                        $exp2[0] = "";
                    }
                    DB::table('woke_order_info')->where("order_id", $item->order_id)->update(['province'=>$exp[0], 'city'=>$exp[1], 'district'=>$exp2[0], 'address'=>$exp2[1]]);
                }else{
                    $this->info($item->order_id.'Error');
                }
            }else{
                $this->info($item->order_id.'Null');
            }

        }
    }
}
