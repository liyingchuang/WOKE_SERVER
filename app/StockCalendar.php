<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockCalendar extends Model
{
    protected $connection = 'mysql_beijing';
    protected $table = 'stock_calendar';
}
