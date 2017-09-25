<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegralOrder extends Model
{
    protected $table = 'ecs_integral_orders';
    protected $fillable = ['user_id','goods_id' ,'prize','integral','username' ,'address','mobile'];
    public function goods() {
        
        return $this->hasOne('App\IntegralGoods','id','goods_id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
}
