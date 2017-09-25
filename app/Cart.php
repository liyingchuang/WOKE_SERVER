<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'woke_cart';
    protected $primaryKey = 'rec_id';
    public $timestamps = false;
    public function goods(){
        return $this->hasOne('App\Goods','goods_id','goods_id');
    }

}
