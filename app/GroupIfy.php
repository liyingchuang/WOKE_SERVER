<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupIfy extends Model
{
    protected $table = 'woke_group_classify';
    protected $fillable = ['ify_id','ify_name', 'parent_id', 'created_at', 'updated_at'];

    public function goods()
    {
        return $this->hasOne('App\Goods', 'ify_id', 'ify_id');
    }


}