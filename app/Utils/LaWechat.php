<?php

namespace App\Utils;

use Illuminate\Support\Facades\Cache;
use App\Utils\WeChat;
use Illuminate\Support\Facades\Log;

/**
 *    微信公众平台PHP-SDK, 简单缓存实例
 *  @author binsee@163.com
 *  @link https://github.com/binsee/wechat-php-sdk
 *  @version 0.1
 *  usage:
 *   $options = array(
 *            'token'=>'tokenaccesskey', //填写你设定的key
 *            'encodingaeskey'=>'encodingaeskey', //填写加密用的EncodingAESKey
 *            'appid'=>'wxdk1234567890', //填写高级调用功能的app id
 *            'debug'=>'', //填写缓存目录，默认为当前运行目录的子目录cache下
 *            'logcallback'=>'',
 *            'appsecret'=>'xxxxxxxxxxxxxxxxxxx' //填写高级调用功能的密钥
 *       
 *          
 *        );
 *     $weObj = new EasyWechat($options);
 *   $weObj->valid();
 *   ...
 *
 */
class LaWechat extends WeChat {

    private static $_instance = null;

    public static function getInstance() {
        if (is_null(self::$_instance) || isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $options['token'] = config('wechat.token');
        $options['encodingaeskey'] = config('wechat.aes_key');
        $options['appid'] = config('wechat.appid');
        $options['appsecret'] = config('wechat.secret');
        $options['debug'] ='';
        $options['logcallback'] = 'http:://www.baidu.com';
        parent::__construct($options);
    }

    /**
     * log overwrite
     * @param string|array $log
     */
    protected function log($log) {
        if ($this->debug) {
            if (function_exists($this->logcallback)) {
                if (is_array($log))
                    $log = print_r($log, true);
                return call_user_func($this->logcallback, $log);
            }else {
                Log::error('wechat:' . $log);
                return true;
            }
        }
        return false;
    }

    /**
     * 重载设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired 缓存秒数，如果为0则为长期缓存
     * @return boolean
     */
    protected function setCache($cachename, $value, $expired = 0) {
        $expired = $expired / 60;
        $putData = Cache::add($cachename, $value, $expired);
        if ($putData) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 重载获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename) {
        $cacheData = Cache::get($cachename);
        if ($cacheData) {
            return $cacheData;
        } else {
            return false;
        }
    }

    /**
     * 重载清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename) {
        $remove = Cache::forget($cachename);
        if ($remove) {
            return TRUE;
        }
        return FALSE;
    }

}
