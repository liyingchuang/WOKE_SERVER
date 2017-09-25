<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use App\Advertisement;
use App\AdvertisementCategory;

class AdvertisementController extends ManageController {

    /**
     *  
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $id = $request->get('id');
        $info = AdvertisementCategory::find($id);
        $list = Advertisement::where('advertisement_category_id', $id)->orderBy('id', 'desc')->paginate(15);
        return view('manage.ads.list')->with('list', $list)->with('info', $info);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $end_time = strtotime($request->get('end_time'));
        $start_time = strtotime($request->get('start_time'));
        $ad_file = $request->get('file_name');
        $ad_link = $request->get('ad_link');
        $ad_name = $request->get('ad_name');
        $id = $request->get('id');
        $advertisement_category_id = $request->get('advertisement_category_id');
        $ads=Advertisement::find($id);
        if(!empty($ads)){
            $ads->end_time=$end_time;
            $ads->start_time=$start_time;
            $ads->ad_file=$ad_file;
            $ads->ad_link=$ad_link;
            $ads->ad_name=$ad_name;
            $ads->save();
        }else{
         Advertisement::create(['advertisement_category_id' => $advertisement_category_id, 'end_time' => $end_time, 'start_time' => $start_time, 'ad_file' => $ad_file, 'ad_link' => $ad_link, 'ad_name' => $ad_name]);   
        }        
        return redirect('manage/ads?id=' . $advertisement_category_id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $id = $request->get('id');
        $category_id = $request->get('category_id');
        $show = Advertisement::find($id);
        if (!empty($show)) {
            $show->delete();
        }
        return redirect('manage/ads?id=' . $category_id);
    }

    public function edit($id) {
        $ad = Advertisement::find($id);
        if (!empty($ad) && $ad->enabled) {
            $ad->enabled = 0;
        } else {
            $ad->enabled = 1;
        }
        $ad->save();
        echo 'ok';
        exit;
    }

}
