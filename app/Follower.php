<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follower extends Model {

    protected $table = 'ecs_users_follower';
    protected $fillable = ['user_id', 'follower_id'];
    public function user() {
           return $this->hasOne('App\User', 'user_id','follower_id');
    }

}
 