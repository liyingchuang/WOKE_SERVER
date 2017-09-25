<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ExpressController extends Controller {

    private $EBusinessID = '1255528';
    private $AppKey = "8889ebdb-b35b-417c-8cd3-3aa2904bc0b2";
    private $ReqURL = "http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx";

    /**
     * 快递查询接口
     *
     * @return \Illuminate\Http\Response
     */
    public function getInfo(Request $request) {

        $order_sn = $request->get('order_sn');
        $order = DB::table('ecs_order_info')->select('shipping_express', 'invoice_no')->where('order_sn', $order_sn)->first();
        if (!empty($order) && $order->shipping_express && $order->invoice_no) {
            return $this->get_express($order->shipping_express, $order->invoice_no);
        } else {
            return $this->error(null, '订单信息不完整');
        }
    }

    /**
     * 快递数据
     * @return type
     */
    private function get_express($express, $nu) {
        $expres = $this->get_express_number($express);
        $json = $this->getOrderTracesByJson($expres, $nu);
        $data = json_decode($json);
        if ($data && $data->Success) {
            $state = '无';
            switch ($data->State) {
                case 1:
                    $state = '已取件';
                    break;
                case 2:
                    $state = '在途中';
                    break;
                case 3:
                    $state = '签收';
                    break;
                case 4:
                    $state = '退件/问题件';
                    break;
                case 5:
                    $state = '待取件';
                    break;
                case 6:
                    $state = '待派件';
                    break;
            }
            return $this->success(['express_state' => $state, 'express_name' => $express, 'express_number' => $expres, 'express_sn' => $nu, 'list' => array_reverse($data->Traces)], '数据读取成功');
        } else {
            if ($data) {
                return $this->error(null, $data->Reason);
            }
            return $this->error(null, '无法差到数据');
        }
    }

    private function getOrderTracesByJson($shipperCode, $logisticCode) {
        $requestData = "{\"OrderCode\":\"\",\"ShipperCode\":\"" . $shipperCode . "\",\"LogisticCode\":\"" . $logisticCode . "\"}";
        $datas = array(
            'EBusinessID' => $this->EBusinessID,
            'RequestType' => '1002',
            'RequestData' => urlencode($requestData),
            'DataType' => '2',
        );
        $datas['DataSign'] = $this->encrypt($requestData, $this->AppKey);
        $result = $this->sendPost($this->ReqURL, $datas);
        return $result;
    }

    /**
     *  post提交数据 
     * @param  string $url 请求Url
     * @param  array $datas 提交的数据 
     * @return url响应返回的html
     */
    private function sendPost($url, $datas) {
        $temps = array();
        foreach ($datas as $key => $value) {
            $temps[] = sprintf('%s=%s', $key, $value);
        }
        $post_data = implode('&', $temps);
        $url_info = parse_url($url);
        $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
        $httpheader.= "Host:" . $url_info['host'] . "\r\n";
        $httpheader.= "Content-Type:application/x-www-form-urlencoded\r\n";
        $httpheader.= "Content-Length:" . strlen($post_data) . "\r\n";
        $httpheader.= "Connection:close\r\n\r\n";
        $httpheader.= $post_data;
        $fd = fsockopen($url_info['host'], 80);
        fwrite($fd, $httpheader);
        $gets = "";
        $headerFlag = true;
        while (!feof($fd)) {
            if (($header = @fgets($fd)) && ($header == "\r\n" || $header == "\n")) {
                break;
            }
        }
        while (!feof($fd)) {
            $gets.= fread($fd, 128);
        }
        fclose($fd);

        return $gets;
    }

    /**
     * 电商Sign签名生成
     * @param data 内容   
     * @param appkey Appkey
     * @return DataSign签名

     */
    private function encrypt($data, $appkey) {
        return urlencode(base64_encode(md5($data . $appkey)));
    }

    /**
     * 获取快递昵称
     * @param type $express
     * @return string
     */
    private function get_express_number($express) {
        switch ($express) {
            case "EMS"://ecshop后台中显示的快递公司名称
                $postcom = 'EMS'; //快递公司代码
                break;
            case "中国邮政":
                $postcom = 'EMS';
                break;
            case "申通快递":
                $postcom = 'STO';
                break;
            case "圆通速递":
                $postcom = 'YTO';
                break;
            case "顺丰速运":
                $postcom = 'SF';
                break;
            case "天天快递":
                $postcom = 'HHTT';
                break;
            case "韵达快递":
                $postcom = 'YD';
                break;
            case "中通速递":
                $postcom = 'ZTO';
                break;
            case "宅急送":
                $postcom = 'ZJS';
                break;
            case "百世汇通":
                $postcom = 'HTKY';
                break;
            case "全峰快递":
                $postcom = 'QFKD';
                break;
            case "优速快递":
                $postcom = 'UC';
                break;

            default:
                $postcom = '';
        }
        return $postcom;
    }

}
