<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $connection = 'mysql_beijing';
    protected $table = 'products';
}
