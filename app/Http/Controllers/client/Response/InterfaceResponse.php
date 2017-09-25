<?php
namespace App\Http\Controllers\client\Response;
use Illuminate\Http\Request;

/**
 * api接口类
 * @author Flc <2016-7-31 13:44:19>
 */
Interface InterfaceResponse
{
    /**
     * 执行接口
     * @return array 
     */
    public function index(Request $request);

    /**
     * 返回接口名称
     * @return string 
     */
    public function getMethod();
}