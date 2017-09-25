<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShowTagStatistics extends Model {

    protected $table = 'ecs_show_tag_statistics';
    protected $fillable = ['user_id','show_id','tag_name','size','thumb','search_sort_order'];
    public function getThumbAttribute() {
            $QINIU_HOST=$_ENV['QINIU_HOST'];
        if($this->attributes['thumb'] == ''){
            return $QINIU_HOST."/1465286688.3069.png";
        }else{
            return $QINIU_HOST."/".$this->attributes['thumb'];
        }
    }
}
