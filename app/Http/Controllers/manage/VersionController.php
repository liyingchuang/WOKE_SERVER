<?php

namespace App\Http\Controllers\manage;

use Illuminate\Http\Request;
use App\Http\Controllers\ManageController;
use Illuminate\Support\Facades\DB;

class VersionController extends ManageController {

    public function ios(Request $request){
        $version_number = $request->get('version_number');
        if($version_number){
            DB::table('version')->where('system', 1)->update(['version_number'=>$version_number]);
        }
        $info = DB::table('version')->where('system', 1)->first();
        return view('manage.version.ios')->with('info', $info);
    }
    public function android(Request $request){
        $version_number = $request->get('version_number');
        $version_name = $request->get('version_name');
        $appurl = $request->get('appurl');
        if($version_number){
            DB::table('version')->where('system', 2)->update(['version_number'=>$version_number, 'version_name'=>$version_name, 'appurl'=>$appurl]);
        }
        $info = DB::table('version')->where('system', 2)->first();
        return view('manage.version.android')->with('info', $info);
    }
}