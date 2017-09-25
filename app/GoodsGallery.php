<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoodsGallery extends Model
{
    protected $table = 'woke_goods_gallery';
    protected $primaryKey = 'img_id';
    protected $fillable = ['goods_id','img_id','img_url','img_desc'];
    public $timestamps = false;
    public function getImgUrlAttribute() {
        return $_ENV['QINIU_HOST'].'/'.$this->attributes['img_url'];
    }
}
