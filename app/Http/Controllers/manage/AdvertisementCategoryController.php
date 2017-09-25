<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use App\AdvertisementCategory;

class AdvertisementCategoryController extends ManageController {

    /**
     *  广告分类管理
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $list = AdvertisementCategory::orderBy('id', 'desc')->paginate(15);
        return view('manage.ads.index')->with('list', $list);
    }

    /**
     * 保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $category_desc = $request->get('category_desc');
        $ad_width = $request->get('ad_width');
        $ad_height = $request->get('ad_height');
        $category_name = $request->get('category_name');
        AdvertisementCategory::create(['category_name'=>$category_name,'category_desc'=>$category_desc,'ad_width'=>$ad_width,'ad_height'=>$ad_height]);
        return redirect('manage/adCategory');
    }
 

}
