<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class Controller extends BaseController {

    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

    /**
     * 返回成功
     * @param type $data
     * @param type $message
     * @return type
     */
    protected function success($data, $message = '', $code = 0) {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
        return response()->json(['status' => true,'code' => $code, 'msg' => $message, 'data' => $data]);
    }
    /**
     * 返回失败
     * @param type $data
     * @param type $message
     * @return type
     */
    protected function error($data, $message = '', $code = 1) {
        header("Access-Control-Allow-Origin: *");
        header('Access-Control-Allow-Methods:POST,GET,OPTIONS,DELETE');
        return response()->json(['status' => false,'code' => $code, 'msg' => $message, 'data' => $data]);
    }
    /**
     * 时间
     * @param type $second
     * @return type
     */
    protected function time2string($second) {
        $day = floor($second / (3600 * 24));
        if ($day > 1) {
            return $day . '天';
        } else {
            $hour = ceil($second / 3600);
            return $hour . '小时';
        }
    }

    /**
     * 公共文件上传
     * @param \Illuminate\Http\Request $request
     */
    protected function upload(Request $request, $path) {

        if ($request->hasFile('files') && $request->isMethod('post')) {
            $allowed_extensions = ["png", "jpg", "gif", "jpeg", 'JPG', 'JPEG'];
            $max_size = 5120000; //5m
            $upload_path = public_path() . "/uploads/$path/";
            $uploadedFile = $request->file('files');
            $fileSize = $uploadedFile->getClientSize();
            $fileExtens = $uploadedFile->getClientOriginalExtension();
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777);
            }
            if (in_array($fileExtens, $allowed_extensions) && $fileSize < $max_size) {
                $mtime = explode(' ', microtime());
                $starttime = $mtime[1] + $mtime[0];
                $name = date('YmdHis') . $starttime;
                $fileName = $name . '.' . $fileExtens;
                $message = $uploadedFile->move($upload_path, $fileName);
                if ($message) {
                    $data['fileName'] = "/uploads/$path/" . $fileName;
                    $data['url'] = url("/uploads/$path/" . $fileName);
                    return $this->success($data, $message, 0);
                } else {
                    return $this->error(null, $message, 1);
                }
            } else {
                $error = '文件格式不对或者文件太大！' . $fileExtens;
                return $this->error(null, $error, 1);
            }
        } else {
            $error = 'Bad request!';
            return $this->error(null, $error, 1);
        }
    }

}
