<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $table = 'woke_order_goods';
    protected $primaryKey = 'rec_id';
    public $timestamps = false;
    protected $fillable = ['rec_id','order_id','goods_id', 'goods_name', 'order_sn','goods_sn','goods_number','market_price','goods_price','goods_attr','goods_attr_id'];
    public function goods(){
        return $this->hasOne('App\Goods','goods_id','goods_id');
    }
}