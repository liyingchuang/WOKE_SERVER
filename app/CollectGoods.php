<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectGoods extends Model
{
    protected $table = 'woke_collect_goods';
    protected $primaryKey = 'rec_id';
    public $timestamps = false;
    protected $fillable = ['rec_id', 'user_id', 'goods_id', 'is_attention','add_time'];
}
