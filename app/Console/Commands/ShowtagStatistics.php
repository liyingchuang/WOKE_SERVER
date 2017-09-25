<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\ShowTag;

class ShowtagStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'showtag:statistics';
    //php artisan showtag:statistics

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User tag table statistics';

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
     *tinyblob
     * @return mixed
     */
    public function handle()
    {
        $delete = DB::table('ecs_show_tag_statistics')->delete();
        $list = ShowTag::select('user_id','show_id', DB::raw('sum(size) AS size'), 'tag_name', 'thumb','search_sort_order')->where('size','>',1)->groupBy('tag_name')->orderBy('size', 'desc')->get();
        foreach($list as $key => $val) {
            $detailed['user_id'] = $val->user_id;
            $detailed['show_id'] = $val->show_id;
            $detailed['tag_name'] = $val->tag_name;
            $detailed['thumb'] = trim(strrchr($val->thumb, '/'),'/');
            $detailed['search_sort_order'] = $val->search_sort_order;
            $detailed['size'] = $val->size;
            $statistics = DB::table('ecs_show_tag_statistics')->insert($detailed);
        }
    }
}
