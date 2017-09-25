<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupInfo extends Model
{
    protected $table = 'woke_group_info';
    protected $fillable = ['goods_id', 'info_id', 'user_id', 'group_id', 'order_sn', 'pay_status', 'supplier_id', 'consignee', 'address', 'tel', 'buy_number', 'pay_name', 'pay_time', 'order_amount', 'integral_amount', 'remarks', 'vat_inv_company_name', 'vat_inv_taxpayer_id'];
    public function user()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
    public function goods()
    {
        return $this->hasOne('App\Goods', 'goods_id', 'goods_id');
    }
}
