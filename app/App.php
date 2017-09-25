<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $table = 'woke_apps';
    protected $fillable = [
        'app_id', 'app_secret', 'app_name', 'app_desc', 'status', 'created_at', 'updated_at'
    ];
}
