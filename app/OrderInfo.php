<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInfo extends Model
{
    protected $table = 'woke_order_info';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    protected $fillable = ['order_id','parent_id' ,'user_id','supplier_id' ,'order_sn','order_status','shipping_status','pay_status','shipping_fee','consignee','address','tel','mobile','shipping_express','bonus','integral','pay_name','goods_amount','discount','add_time','order_amount','froms','pay_time','integral_money','postscript','vat_inv_company_name','vat_inv_taxpayer_id','inv_money','extension_code','pack_fee','province','city','district'];
    public function ordergoods(){
        return $this->hasMany('App\OrderGoods','order_id','order_id');
    }
    public function user(){
        return $this->hasOne('App\User' ,'user_id','user_id');
    }
    public function my(){
        return $this->belongsTo('App\User', 'user_id', 'user_id');
    }
    public function store()
    {
        return $this->hasOne('App\Supplier', 'supplier_id','supplier_id');
    }
    public function order($order_id, $user_id, $bonus, $order_total_price){
        $this->fillable['order_id'] = $order_id;
        $this->fillable['user_id'] = $user_id;
        $this->fillable['bonus'] = $bonus;
        $this->fillable['order_amount'] = $order_total_price;
        $this->fillable['goods_amount'] = $bonus;
    }
}
