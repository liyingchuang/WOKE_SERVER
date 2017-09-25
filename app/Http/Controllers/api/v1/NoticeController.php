<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Advertisement;

class NoticeController extends Controller {

    /**
     * 统一验证用户
     */
    public function __construct() {
       $this->middleware('api_guest', ['except' => ['getIndex']]);
    }
    public function getIndex() {
        $time = time();
        $b = 9;
        $banner = Advertisement::select('id', 'ad_name', 'ad_link', 'ad_file')->where('advertisement_category_id', $b)->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1)->orderBy('updated_at', 'desc')->first();
        return $this->success($banner);
    }

}
