<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOrderAwardLog extends Model
{
    protected $table = 'woke_user_order_award_log';
    protected $fillable = ['order_id','user_id','bonus', 'recommendation_user_id', 'recommendation_price', 'academy_user_id', 'academy_price', 'manager_user_id', 'manager_price', 'breeding_user_id', 'breeding_price'];
}
