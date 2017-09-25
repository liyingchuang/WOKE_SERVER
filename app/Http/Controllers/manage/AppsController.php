<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;

class AppsController extends ManageController {

    /**
     * 
     * 首页店铺管理
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex() {
        $list = DB::table('ecs_supplier_app_headImg')->orderBy('sort_order', 'asc')->paginate(15);
        return view('manage.apps.index')->with(['list' => $list]);
    }

    /**
     * 添加店铺
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postStore(Request $request) {
        $title = $request->get('title');
        $url = $request->get('url');
        $sort_order = $request->get('sort_order');
        $images = $request->get('images');
        DB::table('ecs_supplier_app_headImg')->insert([
            ['title' => $title, 'url' => $url, 'files' => $images, 'sort_order' => $sort_order, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ]);
        return redirect('manage/apps');
    }

    /**
     * 删除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDelete(Request $request) {
        $id = $request->get('id');
        $header = DB::table('ecs_supplier_app_headImg')->where('id', $id)->first();
        if (!empty($header)) {
           DB::table('ecs_supplier_app_headImg')->where('id', $id)->delete();
           @unlink(public_path().$header->files);
           return redirect('manage/apps');
        }
        return redirect('manage/apps');
    }
    

}
