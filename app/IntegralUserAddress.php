<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntegralUserAddress extends Model
{
    protected $table = 'ecs_integral_user_address';
    protected $fillable = ['user_id','username' ,'address','mobile'];

}
