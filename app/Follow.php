<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model {
    protected $table = 'ecs_users_follow';
    protected $fillable = ['user_id', 'follow_id'];
    public function user() {
           return $this->hasOne('App\User', 'user_id','follow_id');
    }
    
}
