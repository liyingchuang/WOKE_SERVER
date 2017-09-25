<?php
namespace App\Utils;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
class  MobelNumber {
    /**
     * 电话号码
     * @return type
     */
     public static function createMobelNumber() {
        try {
            $order_sn = File::get(storage_path() . '/framework/cache/' . date('Ymd'));
        } catch (FileNotFoundException $exception) {
            $order_sn = 0;
        }
        $bytes_written = File::put(storage_path() . '/framework/cache/' . date('Ymd'), $order_sn + 1);
        return date('Ymd'). str_pad($order_sn, 3, 0, STR_PAD_LEFT);
    }
}
