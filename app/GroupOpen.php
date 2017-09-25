<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupOpen extends Model
{
    protected $table = 'woke_group_open';
    protected $fillable = ['goods_id', 'group_id', 'user_id', 'supplier_id', 'start_time', 'have', 'group_status'];
    protected $primaryKey = 'group_id';
    protected  $appends=['time'];
    public function user()
    {
        return $this->hasOne('App\User', 'user_id', 'user_id');
    }
    public function getTimeAttribute()
   {
        return strval(($this->attributes['start_time']+86400) -time());
    }
}
