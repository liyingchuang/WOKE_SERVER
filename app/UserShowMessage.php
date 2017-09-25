<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserShowMessage extends Model {
    protected $table = 'ecs_users_show_message';
    protected $fillable = ['user_id', 'from_user_id', 'show_id', 'show_tag_id', 'types', 'is_read','message'];
    public function tags() {
        return $this->hasOne('App\ShowTag','id','show_tag_id');
    }
    public function tag() {
        return $this->hasOne('App\ShowTag','id','show_tag_id');
    }

    public function show() {
        return $this->hasOne('App\Show', 'id', 'show_id');
    }
    public function user() {
        return $this->hasOne('App\User', 'user_id', 'from_user_id');
    }
}
