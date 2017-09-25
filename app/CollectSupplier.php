<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollectSupplier extends Model
{
    protected $table = 'woke_collect_supplier';
    protected $primaryKey = 'rec_id';
    public $timestamps = false;
    protected $fillable = ['rec_id', 'user_id', 'supplier_id', 'is_attention', 'add_time', 'created_at', 'updated_at'];

}