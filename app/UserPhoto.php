<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPhoto extends Model {

    protected $table = 'ecs_user_photo';
    protected $fillable = ['user_id', 'file_name'];

    public function getFileNameAttribute() {
        $in = strstr($this->attributes['file_name'], 'clouddn.com/');
        if ($in) {
            return $this->attributes['file_name'];
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
        return $QINIU_HOST.'/'.$this->attributes['file_name'];
    }
}
