<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsItem extends Model
{
    protected $table = 'woke_goods_item';
    protected $fillable = ['goods_id','key','value','stor'];
}
