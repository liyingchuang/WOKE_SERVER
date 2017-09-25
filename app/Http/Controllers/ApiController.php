<?php

namespace App\Http\Controllers;
use upush\sdk;
use Illuminate\Support\Facades\DB;
use App\UserShowMessage;

class ApiController extends Controller {
    private $key = "58c618b56e27a433190004e9";
    private $secret = "cqunzkfzvimhvfch3wnusxqgns8kwye5";
    private $akey = "58b8e776c62dca37cd000eb5";
    private $asecret = "zz0hyj7bfp6xrjjm46cyepsysxnhgnig";

    private $debug=false;
    protected function sendMessage($user_id, $option,$title, $alert = '', $available = 1) {
        $debut = env('APP_DEBUG');
        if($debut){
            $this->debug=false;
        }else{
            $this->debug=true;

        }
        $sdk = new sdk($this->key, $this->secret, $this->akey, $this->asecret, $this->debug);
     return   $sdk->sendMessage($user_id, $option,$title, $alert, 1, $available);
    }
}
