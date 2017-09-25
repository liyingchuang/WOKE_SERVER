<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserShowOff extends Model {

    protected $table = 'ecs_user_show_off';
    protected $fillable = ['user_id', 'desc'];

}
