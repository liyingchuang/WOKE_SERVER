<?php

namespace App\Utils;

class LeancloudSend
{

    /**
     * 接受发送的手机号码
     * @return type
     */
    public function leancloudMessage($phone, $code)
    {
        header("content-type:text/html;charset=utf-8");
        $header = array("X-LC-Id: NEiVridtJCVF6niE2G07WSOQ-gzGzoHsz",
            "X-LC-Key: HUyMT8RtuEPBriVwiB0Jb3I0",
            "Content-Type: application/json");
        $url1 = 'https://api.leancloud.cn/1.1/requestSmsCode';
        $rs = $this->request($url1, 'POST', '{"mobilePhoneNumber": "' . $phone . '","template":"377123code","var": "' . $code . '"}', $header);
        return $rs;
    }

    private function request($url, $method, $postfields = NULL, $headers = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        switch ($method) {
            case 'POST' :
                curl_setopt($curl, CURLOPT_POST, TRUE);
                if ($postfields) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
                }
                break;
            case 'DELETE' :
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if ($postfields) {
                    $url = "{$url}?{$postfields}";
                }
                break;
        }
        //$headers[] = 'API-RemoteIP: ' . fetch_ip();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLINFO_HEADER_OUT, TRUE);
        if (substr($url, 0, 8) == 'https://') {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    /**
     *
     * submail 发短信
     * @param $phone
     * @param $code
     */
    public function sendSMS($phone, $code)
    {
        $url = "https://api.submail.cn/message/xsend";
        $appId = "10560";
        $signature = "88a8faa0ad7a40ac5f362f639a38c40c";
        $vars["code"] = $code;
        $data = ['appid' => $appId, 'to' => $phone, 'project' => '0sG8f4', 'vars' => json_encode($vars), 'signature' => $signature];
        $response =json_decode($this->postSMS($url,$data));
        return $response;
    }

    /**
     * submial 专用
     * @param $url
     * @param string $data
     * @return string
     */
    private function postSMS($url,$data=''){
        $row = parse_url($url);
        $host = $row['host'];
        $port = !empty($row['port'])? $row['port']:80;
        $file = $row['path'];
        $post='';
        while (list($k,$v) = each($data))
        {
            $post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
        }
        $post = substr( $post , 0 , -1 );
        $len = strlen($post);
        $fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
        if (!$fp) {
            return "$errstr ($errno)\n";
        } else {
            $receive = '';
            $out = "POST $file HTTP/1.1\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $len\r\n\r\n";
            $out .= $post;
            fwrite($fp, $out);
            while (!feof($fp)) {
                $receive .= fgets($fp, 128);
            }
            fclose($fp);
            $receive = explode("\r\n\r\n",$receive);
            unset($receive[0]);
            return implode("",$receive);
        }
    }

}
