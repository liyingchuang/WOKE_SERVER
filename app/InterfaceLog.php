<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterfaceLog extends Model
{
    protected $table = 'woke_interface_logs';
    protected $fillable = [
        'api_name', 'reauest_body','ip','reauest_header', 'response', 'input_time', 'run_time', 'output_time'
    ];
}
