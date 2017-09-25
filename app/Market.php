<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $table = 'woke_market';
    protected $fillable = ['user_id','price','option', 'input_time', 'ip','profit','status','time'];
}