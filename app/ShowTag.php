<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowTag extends Model {

    protected $table = 'ecs_show_tag';
    protected $fillable = ['user_id','show_id','tag_name','size','thumb','search_sort_order'];
    public function show() {
        return $this->belongsTo('App\Show', 'id', 'show_id');
    }
    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
    public function like() {
          return $this->hasMany('App\ShowTagLike','show_tag_id', 'id');
    }
    public function comment() {
          return $this->hasMany('App\TagComment','show_tag_id', 'id');
    }
    public function getThumbAttribute() {
            $QINIU_HOST=$_ENV['QINIU_HOST'];
        if($this->attributes['thumb'] == ''){
            return $QINIU_HOST."/1465286688.3069.png";
        }else{
            return $QINIU_HOST."/".$this->attributes['thumb'];
        }
    }
}
