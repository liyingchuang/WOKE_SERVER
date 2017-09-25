<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model {

    protected $table = 'woke_advertisement';
    protected $fillable = ['advertisement_category_id', 'ad_name', 'ad_link', 'ad_file', 'start_time', 'end_time','enabled'];
    public function getAdFileAttribute() {
        $in = strstr($this->attributes['ad_file'], 'clouddn.com/');
        if ($in) {
            return $this->attributes['ad_file'];
        }
        $QINIU_HOST=$_ENV['QINIU_HOST'];
        return $QINIU_HOST.'/'.$this->attributes['ad_file'];
    }
}
