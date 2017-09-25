<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountLog extends Model
{
    protected $table = 'woke_account_log';
    protected $primaryKey = 'log_id';
    public $timestamps = false;
    // change_type 1. 奖励 2.购物消耗 3购物反酒币 4.猜大盘消耗
    protected $fillable =['user_id','user_money','frozen_money','rank_points','pay_points','change_time','change_desc','change_type'];
}
