<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ShowTag;
class UpdateShowTageImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:show_tage_images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '必须在24点前之执行';

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
     
    }
}
