<?php

namespace App\Http\Controllers\api\v2;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;
use App\IntegralGoods;

class IntegralController extends ApiController
{
    /**
     * 用户购买列表
     * @param Request $request
     */
    public function getUser(Request $request)
    {
        $user_id = $request->get('user_id');
        $list = IntegralOrder::with('goods')->where('user_id', $user_id)->orderBy('id', 'desc')->paginate(10)->toArray();
        return $this->success($list['data'], '');
    }
}
