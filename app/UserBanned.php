<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBanned extends Model {

    protected $table = 'ecs_user_banned';
    protected $fillable = ['user_id', 'start_time', 'end_time', 'desc'];
     public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }

}
