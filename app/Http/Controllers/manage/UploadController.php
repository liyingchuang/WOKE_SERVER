<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;

/**
 * 
 * 管理组公公上传
 */
class UploadController extends ManageController {

    const ACCESSKEY = '5BjPH2vBGYuGVOjPk2r8bv58PBh0w1Mh1VregX4y';
    const SECRETKEY = 'v6O06nwbA7JFqk5DhpHk5SQo7L3BP9WMmRsIuPXw';

    private $auth;



    public function __construct() {
        parent::__construct();
        $this->auth = new Auth(self::ACCESSKEY, self::SECRETKEY);
    }

    /**
     * 文件上传
     * @param \Illuminate\Http\Request $request
     * @return type
     */
    public function store(Request $request) {
        $type = $request->get('type', null);
		 if($type=='simditor'){
           return $this->simditorUpload($request); 
        }
        if (!empty($type)) {
            return $this->_LeanCloud($request);
        }
        return $this->upload($request, 'manage');
    }
	
	/**
     * simditor 上传
     * @param type $param
     */
    private function simditorUpload(Request $request) {
        $uploadMgr = new UploadManager();
        $uploadedFile = $request->file('files');
        $pathname = $uploadedFile->getPathname();
        $fileExtens = $uploadedFile->getClientOriginalExtension();
        $mtime = explode(' ', microtime());
        $starttime = $mtime[1] + $mtime[0];
        $file_name = $starttime . '.' . $fileExtens;
        $debut = env('APP_DEBUG');
        $QINIU_HOST= env('QINIU_HOST');
        if ($debut) {
            $bucket = 'wokedevelop';
            $token = $this->auth->uploadToken($bucket);
            list($ret, $err) = $uploadMgr->putFile($token, $file_name, $pathname);
            if ($err !== null) {
                return response()->json(['success' => FALSE, 'msg' =>'上传失败', 'file_path' =>'']);
            } else {
                $data['fileName'] =$ret['key'];
                $data['url'] =$QINIU_HOST.'/'.$ret['key'];
                return  $data['url'];
              // return response()->json(['success' => true, 'msg' =>'上传成功', 'file_path' => $data['url']]);
            }
        } else {
            $bucket = 'wokerelease';
            $token = $this->auth->uploadToken($bucket);
            list($ret, $err) = $uploadMgr->putFile($token, $file_name, $pathname);
            if ($err !== null) {
                return response()->json(['success' => FALSE, 'msg' =>'上传失败', 'file_path' =>'']);
            } else {
                $data['fileName'] =$ret['key'];
                $data['url'] =$QINIU_HOST.'/'.$ret['key'];
                return  $data['url'];
             //   return response()->json(['success' => true, 'msg' =>'上传成功', 'file_path' => $data['url']]);
            }
        }
    }

    /**
     * LeanCloud文件上传
     */
    private function _LeanCloud(Request $request) {
        $uploadMgr = new UploadManager();
        $uploadedFile = $request->file('files');
        $pathname = $uploadedFile->getPathname();
        $fileExtens = $uploadedFile->getClientOriginalExtension();
        $mtime = explode(' ', microtime());
        $starttime = $mtime[1] + $mtime[0];
        $file_name = $starttime . '.' . $fileExtens;
        $debut = env('APP_DEBUG');
        $QINIU_HOST= env('QINIU_HOST');
        if ($debut) {
            $bucket = 'wokedevelop';
            $token = $this->auth->uploadToken($bucket);
            list($ret, $err)= $uploadMgr->putFile($token, $file_name, $pathname);
            if ($err !== null) {
                return $this->error($err, 'err');
            } else {
                $data['fileName'] =$ret['key'];
                $data['url'] =$QINIU_HOST.'/'.$ret['key'];
                return $this->success($data, 'ok');
            }
        } else {
            $bucket = 'wokerelease';
            $token = $this->auth->uploadToken($bucket);
            list($ret, $err) = $uploadMgr->putFile($token, $file_name, $pathname);
            if ($err !== null) {
                return $this->error($err, 'err');
            } else {
                $data['fileName'] =$ret['key'];
                $data['url'] =$QINIU_HOST.'/'.$ret['key'];
                return $this->success($data, 'ok');
            }
        }
    }

}
