<?php
namespace App\Http\Controllers\client\Response;

use App\UserToken;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use upush\sdk;
use Qiniu\Http\Client;

/**
 * api基础类
 * @author Flc <2016-7-31 13:44:07>
 */
abstract class BaseResponse
{
    private $key = "58c618b56e27a433190004e9";
    private $secret = "cqunzkfzvimhvfch3wnusxqgns8kwye5";
    private $akey = "58b8e776c62dca37cd000eb5";
    private $asecret = "zz0hyj7bfp6xrjjm46cyepsysxnhgnig";
    // 七牛
    const ACCESSKEY = '5BjPH2vBGYuGVOjPk2r8bv58PBh0w1Mh1VregX4y';
    const SECRETKEY = 'v6O06nwbA7JFqk5DhpHk5SQo7L3BP9WMmRsIuPXw';
    /**
     * 接口名称
     *
     * @var [type]
     */
    protected $method;
    /**
     * 不检查utoken 和uid
     * @var array
     */
    protected $except = [];
    /**
     *
     * 经纬度地址
     * @var
     */
    protected $la;
    protected $lo;
    protected $al;
    protected $ad;

    /**
     * 返回接口名称
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * 用户token 检查
     * @param $request
     * @param $except
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function checkToken($request)
    {
        $method = $request->input("method");
        $methods = explode('.', $method);
        $methodName = 'index';
        if (count($methods) == 3)
            $methodName = last($methods);
        if (in_array($methodName, $this->except)) {
            return true;
        }
        $token = $request->header('token', 0);
        $token = empty($token) ? $request->get('token', 0) : $token;
        $user_id = $request->get('user_id');
        $user = UserToken::where('user_id', $user_id)->first();

        if (empty($user)) {
            return false;
        } else {
            if (empty($token) || $token != $user->token) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return mixed
     */
    protected function getFormatPrice($price)
    {
        return number_format(floatval($price), 2, '.', '');
    }

    /**
     *
     * 返回成功
     * @param $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function success($data, $message = '', $code = '200')
    {
        return ['status' => true, 'code' => $code, 'msg' => $message, 'data' => $data];
    }

    /**
     * 返回失败
     * @param $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function error($data, $message = '', $code = '200')
    {
        return ['status' => false, 'code' => $code, 'msg' => $message, 'data' => $data];
    }

    /**
     * 发送推送
     * @param $user_id
     * @param $option
     * @param string $alert
     * @param int $available
     */
    protected function sendMessage($user_id, $option,$title, $alert = '', $available = 1) {
        $debut = env('APP_DEBUG');
        if($debut){
            $this->debug=false;
        }else{
            $this->debug=true;

        }
        $sdk = new sdk($this->key, $this->secret, $this->akey, $this->asecret, $this->debug);
        $sdk->sendMessage($user_id, $option,$title, $alert, 1, $available);
    }
    protected  function getMillisecond() {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
    /**
     * 发送短信
     * @param $mobile_phone
     * @param $code
     */
    protected function sendMicroMessage($mobile_phone, $message)
    {
        $body = array(
            "userid" => "CD00009",
            "account" => "CD00009",
            "password" => "CD0000900",
            "mobile" => $mobile_phone,
            "content" => $message,
            "action" => "send"
        );
        $resp = $this->_http('http://dx.ipyy.net/smsjson.aspx', $body);
        return $resp;
    }

    /**
     * http 请求
     * @param $url
     * @param $body
     * @param array $header
     * @param string $method
     * @return mixed|string
     */
    protected function _http($url, $body, $header = array(), $method = "POST")
    {
        $response = "";
        if ($method == "POST") {
            $response = Client::post($url, $body, $header);
        }
        if ($method == "GET") {
            $response = Client::get($url, $header);
        }
        return $response;
    }

    protected function upload($request)
    {
        $auth = new Auth(self::ACCESSKEY, self::SECRETKEY);
        $uploadMgr = new UploadManager();
        $pathname = base64_decode($request->get('file'));
        $mtime = explode(' ', microtime());
        $starttime = $mtime[1] + $mtime[0];
        $file_name = $starttime . '.jpg';
        $debut = env('APP_DEBUG');
        $QINIU_HOST = env('QINIU_HOST');
        if ($debut) {
            $bucket = 'wokedevelop';
            $token = $auth->uploadToken($bucket);
            list($ret, $err) = $uploadMgr->put($token, $file_name, $pathname);
            if ($err !== null) {
                return $this->error($err, 'err');
            } else {
                $data['fileName'] = $ret['key'];
                $data['url'] = $QINIU_HOST . '/' . $ret['key'];
                return $ret['key'];
            }
        } else {
            $bucket = 'wokerelease';
            $token = $auth->uploadToken($bucket);
            list($ret, $err) = $uploadMgr->put($token, $file_name, $pathname);
            if ($err !== null) {
                return $this->error($err, 'err');
            } else {
                $data['fileName'] = $ret['key'];
                $data['url'] = $QINIU_HOST . '/' . $ret['key'];
                return $ret['key'];
            }
        }
    }
}