<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

    protected $table = 'woke_supplier';
    protected $primaryKey = 'goods_id';
    protected $fillable = ['user_id','supplier_id' ,'supplier_name','guimo','tel', 'supplier_img', 'content'];
    protected  $appends=['sell_size','goods_size'];
    public function goods()
    {
        return $this->hasMany('App\Goods', 'supplier_id', 'supplier_id');
    }
    public function orders()
    {
        return $this->hasMany('App\OrderInfo', 'supplier_id', 'supplier_id');
    }
    public function getGoodsSizeAttribute() {
        return  strval($this->goods()->where('is_on_sale', 1)->count());
    }
    public function  getSellSizeAttribute()
    {
        $size=OrderGoods::whereIn('order_id',$this->orders()->select('order_id')->get())->sum('goods_number');
        return  $size;
    }
}