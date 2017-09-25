<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderAction extends Model
{
    protected $table = 'woke_order_action';
    protected $primaryKey = 'action_id';
    public $timestamps = false;
    protected $fillable = ['action_id','order_id','action_user', 'order_status', 'shipping_status','pay_status','action_place','action_note','log_time'];
}
