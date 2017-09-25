<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Qiniu\Auth;
use Illuminate\Http\Request;

class QiniuController extends Controller {

    /**
     * 统一验证用户
     */
    public function __construct() {
       // $this->middleware('api_guest');
    }

    public function getIndex() {
        $accessKey = 'NXQah4imdQnJ8Ctxj6AyTSeN-HJ-795QJnAD27cl';
        $secretKey = 'EttNtB5Df9U130IALS97LP86VkDT8vkqMia5xvJF';
        $auth = new Auth($accessKey, $secretKey);
        $debut = env('APP_DEBUG');
        if ($debut) {
            $bucket = '377123test';
        } else {
            $bucket = '377123';
        }
        $token = $auth->uploadToken($bucket);
        return $this->success($token, '');
    }

    /**
     *
     * token
     * @return \App\Http\Controllers\type
     */
    public function getToken() {
        $accessKey = 'NXQah4imdQnJ8Ctxj6AyTSeN-HJ-795QJnAD27cl';
        $secretKey = 'EttNtB5Df9U130IALS97LP86VkDT8vkqMia5xvJF';
        $auth = new Auth($accessKey, $secretKey);
        $bucket = 'chatimagestorage';
        $token = $auth->uploadToken($bucket);
        return $this->success($token, '');
    }

    /**
     * 图片下载
     * @param Request $request
     * @return \App\Http\Controllers\type
     */
    public function postDownload(Request $request) {
        $accessKey = 'NXQah4imdQnJ8Ctxj6AyTSeN-HJ-795QJnAD27cl';
        $secretKey = 'EttNtB5Df9U130IALS97LP86VkDT8vkqMia5xvJF';
        $auth = new Auth($accessKey, $secretKey);
        $urls=$request->get('urls','');
        $images=explode(',',$urls);
        $result=[];
        foreach ($images as $v){
            $result[]=$auth->privateDownloadUrl('http://o9xw8vwvf.bkt.clouddn.com/'.$v);
        }
        return $this->success($result, '');
    }

}
