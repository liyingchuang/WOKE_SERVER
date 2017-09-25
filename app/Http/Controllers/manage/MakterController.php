<?php

namespace App\Http\Controllers\manage;

use App\Http\Controllers\ManageController;
use App\StockCalendar;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Market as Markets;
class MakterController extends ManageController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $ymd=$request->get('ymd')?$request->get('ymd'):date('Y-m-d');
        $time=$this->getbefore($this->getCalendar($ymd));
        $list=Markets::where('time',$time)->get();
        $one['price']=Markets::where('option',1)->where('time',$time)->sum('price');
        $one['count']=Markets::where('option',1)->where('time',$time)->count();
        $two['price']=Markets::where('option',2)->where('time',$time)->sum('price');
        $two['count']=Markets::where('option',2)->where('time',$time)->count();
        $three['price']=Markets::where('option',3)->where('time',$time)->sum('price');
        $three['count']=Markets::where('option',3)->where('time',$time)->count();
        return view('manage.makter.index')->with(['list'=>$list,'one'=>$one,'two'=>$two,'three'=>$three,'time'=>$time]);
    }
    /**
     * 获取前一个交易日
     */
    private function getbefore($ymd){
        $sc = StockCalendar::where('calendarDate','<', $ymd)->where('isOpen', 1)->orderBy('calendarDate','desc')->first();
        return $sc->calendarDate;
    }
    /**
     * 获取下一个交易日
     * @return mixed
     */
    private function getCalendar($ymd)
    {

        $sc = StockCalendar::where('calendarDate', $ymd)->where('isOpen', 1)->first();
        if (!empty($sc)) {//是交易日判断是上午还是下午
            $t = date("H:i:s");
            if ($t > '14:00:00') {
                $sc = StockCalendar::where('calendarDate', '>', $ymd)->where('isOpen', 1)->first();
                $sc = StockCalendar::where('calendarDate', '>', $sc->calendarDate)->where('isOpen', 1)->first();
            } else {
                $sc = StockCalendar::where('calendarDate', '>', $ymd)->where('isOpen', 1)->first();
            }
            return $sc->calendarDate;
        } else {
            $sc = StockCalendar::where('calendarDate', '>', $ymd)->where('isOpen', 1)->first();
            return $sc->calendarDate;
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
