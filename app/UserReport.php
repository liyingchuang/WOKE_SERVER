<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReport extends Model
{
    protected $table = 'ecs_user_report';
    protected $fillable = ['user_id', 'from_user_id', 'desc'];
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
    public function from() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
}
