<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagComment extends Model {

    protected $table = 'ecs_show_tag_comment';
    protected $fillable = ['user_id', 'show_tag_id', 'show_id', 'desc'];

    public function show() {
        return $this->belongsTo('App\Show', 'show_id');
    }

    public function tag() {
        return $this->hasOne('App\ShowTag', 'id', 'show_tag_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }

}
