<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsAttr extends Model
{
    protected $table = 'woke_goods_attr';
    protected $fillable = ['goods_id', 'attr_name','attr_value','market_price','shop_price','goods_number','group_price'];

}
