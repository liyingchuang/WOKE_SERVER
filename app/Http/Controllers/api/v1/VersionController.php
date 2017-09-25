<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * 版本相关
 */
class VersionController extends Controller {

    public function getVersion(Request $request) {
        $type = $request->get('type');
        if ($type==='ios') {
            $data=['forceV' => '2.0.2','forceVurl'=>'https://appsto.re/cn/kK3P-.i','forceVmsg' => '需更新,修复严重版本问题', 'V' => '1.1.0', 'Vurl' =>"", 'Vmsg' => ''];
            return $this->success($data, '查询成功');
        }
        if ($type==='android') {
            $data=['versionCode' => '31', 'versionName' => '2.0.5', 'forcedUpdateCode' => '27', 'size' =>14852911, 'md5' => '67b522d3fa436faa5b3501146a030090', 'fileUrl' => 'https://coding.net/u/apphack/p/377123_download/git/raw/master/377123.apk', "log" => "* 新增积分系统\n* 修改发表晒晒时标签上限为十个\n* 修复视频播放时回退不关闭问题\n* 修复若干小问题"];
            return $this->success($data, '查询成功');
        }
        return $this->error(null, '非法请求！');
    }

}
