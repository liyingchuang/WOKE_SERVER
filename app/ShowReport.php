<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowReport extends Model {

    protected $table = 'ecs_show_report';
    protected $fillable = ['user_id', 'show_id', 'desc'];
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }

}
