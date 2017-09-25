<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Show extends Model {

    protected $table = 'ecs_show';
    protected $fillable = ['user_id','created_time' ,'recommend_time','	is_recommend' ,'is_show','file_name','is_recommend','file_heigth', 'file_width', 'tag_size_count', 'report_size'];

    public function tags() {
        return $this->hasMany('App\ShowTag', 'show_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
    public function report(){
         return $this->hasMany('App\ShowReport', 'show_id');
    }
    public function getFileNameAttribute() {
        $in = strstr($this->attributes['file_name'], 'clouddn.com/');
        if ($in) {
            return $this->attributes['file_name'];
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
        return $QINIU_HOST.'/'.$this->attributes['file_name'];
    }
    /**
    public function getCreatedTimeAttribute() {
      $this->attributes['created_time']=$this->attributes['is_recommend']==2?$this->attributes['recommend_time']:$this->attributes['created_time'];
      return $this->attributes['created_time'];
    }
     * 
     */
}
