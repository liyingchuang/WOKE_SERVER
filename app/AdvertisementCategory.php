<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdvertisementCategory extends Model {
    protected $table = 'woke_advertisement_category';
    protected $fillable = ['ad_height', 'ad_width', 'category_name', 'category_desc'];
    public function ads(){
         return $this->hasMany('App\Advertisement', 'advertisement_category_id','id');
    }
}
