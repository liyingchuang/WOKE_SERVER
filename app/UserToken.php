<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserToken extends Model {

    protected $table = 'woke_user_token';
    protected $fillable = ['user_id', 'token'];
    public $timestamps = false;

}
