<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/**
 * 商品相关
 */
class GoodsController extends Controller {

    /**
     * 
     * 根据商品数量算出价格
     * @param \Illuminate\Http\Request $request
     */
    public function postPrice(Request $request) {
        $data = $request->get('data');
        $data_array=json_decode($data, true);
        $result['total_market_price'] = 0;
        $result['total_shop_price'] = 0;
        foreach ($data_array as $v) {
            $goods = DB::table('ecs_goods')->select('goods_id', 'shop_price', 'market_price')->where('goods_id', $v['goods_id'])->first();
            if (empty($goods)) {
                return $this->error(null, $v['goods_id'] . ' goods_id商品不存在');
            }
            $result['total_market_price']+=$goods->market_price * $v['val'];
            $result['total_shop_price']+=$goods->shop_price * $v['val'];
        }
        $result['total_shop_price'] = number_format(floatval($result['total_shop_price']), 2, '.', '');
        $result['total_market_price'] = number_format(floatval($result['total_market_price']), 2, '.', '');
        $result['save_rate'] = 0;
        if ($result['total_market_price']) {
            $result['save_rate'] = round(($result['total_market_price'] - $result['total_shop_price']) * 100 / $result['total_market_price']) . '%';
        }
        return $this->success($result, '返回成功');
    }

}
