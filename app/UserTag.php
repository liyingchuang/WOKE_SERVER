<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTag extends Model {

    protected $table = 'ecs_user_tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'tag_name'];

}
