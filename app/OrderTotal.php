<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTotal extends Model
{
    protected $table = 'woke_order_total';
    protected $primaryKey = 'order_total_id';
    public $timestamps = false;
    protected $fillable = ['order_total_id', 'user_id', 'order_id', 'order_total_sn', 'integral_money', 'integral', 'bonus', 'order_total_price', 'add_time'];
    public function orderinfo(){
        return $this->hasMany('App\OrderInfo','order_id','order_id');
    }
}
