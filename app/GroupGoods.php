<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupGoods extends Model
{
    protected $table = 'woke_group_goods_extends';
    protected $fillable = ['goods_id', 'ify_id','ex_number', 'ex_have', 'group_price', 'start_time', 'end_time', 'supplier_id', 'examine_status','recommend','describe','group_file','head_free'];

    public function goods()
    {
        return $this->hasOne('App\Goods', 'goods_id', 'goods_id');
    }
    public function ify()
    {
        return $this->hasOne('App\GroupIfy', 'ify_id', 'ify_id');
    }
    public function group()
    {
        $time = time()-86400;
        return $this->hasMany('App\GroupOpen', 'goods_id', 'goods_id')->where('have','>',0)->where('start_time','>',$time)->where('group_status','<>',1);
    }
    public function store(){
       return $this->hasOne('App\Supplier', 'supplier_id', 'supplier_id');
    }
    public function getGroupFileAttribute() {
        $in = strstr($this->attributes['group_file'], 'http');
        if ($in) {
            return $this->attributes['group_file'];
        }
        return  $_ENV['QINIU_HOST'].'/'.$this->attributes['group_file'];
    }

}