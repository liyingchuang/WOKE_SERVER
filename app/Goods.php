<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'woke_goods';
    protected $primaryKey = 'goods_id';
    protected $fillable = ['goods_id','cat_id','supplier_id','is_real','is_on_sale','goods_name','goods_sn' ,'goods_name_style','goods_number','market_price','shop_price','goods_img','goods_thumb','goods_desc'];
    public $timestamps = false;
    public function getGoodsImgAttribute() {
        $in = strstr($this->attributes['goods_img'], 'http');
        if ($in) {
            return $this->attributes['goods_img'];
        }
        return  $_ENV['QINIU_HOST'].'/'.$this->attributes['goods_img'];
    }
    public function getGoodsThumbAttribute() {
        $in = strstr($this->attributes['goods_thumb'], 'http');
        if ($in) {
            return $this->attributes['goods_thumb'];
        }
        return  $_ENV['QINIU_HOST'].'/'.$this->attributes['goods_thumb'];
    }
    public function attr(){
        return $this->hasMany('App\GoodsAttr','goods_id','goods_id');
    }
    public function item(){
        return $this->hasMany('App\GoodsItem','goods_id','goods_id');
    }
    public function gallery(){
        return $this->hasMany('App\GoodsGallery','goods_id','goods_id');
    }
    public function store()
    {
        return $this->hasOne('App\Supplier', 'supplier_id','supplier_id');
    }
    public function groupgoods()
    {
        return $this->hasOne('App\GroupGoods', 'goods_id','goods_id');
    }
}
