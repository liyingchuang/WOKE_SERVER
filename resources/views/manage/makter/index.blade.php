@extends('_layouts.master')
@section('content')
    <script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
    <link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <div class="panel  panel-info">
        <div class="panel-heading">
            猜大盘统计
        </div>
        <div class="panel-body">
            <blockquote>
                <p>截止{{date('Y年m月d日H点i分')}}购买猜大盘第{{$time}}期情况</p>
                <p>买涨:{{$one['price']}}元 {{$one['count']}}人</p>
                <p>买平:{{$two['price']}}元 {{$two['count']}}人</p>
                <p>买跌:{{$three['price']}}元 {{$three['count']}}人</p>
                <div class="form-group">
                <form action="{{URL("manage/makter")}}" method="get">
                    <div class="row">
                        <div class="col-xs-3 col-xs-3  col-sm-3">
                                <input type="text" class="form-control required date-picker" name="ymd" id="end_time"  title="请选择日期" placeholder="请选择日期"
                                       data-fv-date="true" required=""
                                       data-fv-date-message="请选择日期"
                                       data-bv-notempty-message="请选择日期"
                                       data-fv-date-format="YYYY-MM-DD" />

                        </div>
                        <div class="col-xs-3 col-xs-3  col-sm-3">
                            <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i>搜索</button>
                        </div>
                        <div  class="col-xs-6 col-xs-6  col-sm-6">

                        </div>
                    </div>
                </form>
                </div>
            </blockquote>
            <table class="table  table-bordered table-striped">
                <thead>
                <th>编号</th>
                <th>竞猜人</th>
                <th>产品名称</th>
                <th>竞猜类型</th>
                <th>投资金额(元)</th>
                <th>收益(元)</th>
                <th>创建时间</th>
                </thead>
                <tbody>
                @foreach($list as $k=>$v)
                    <tr>
                        <td>{{$k++}}</td>
                        <td><a href="{{URL::to('manage/user')}}?keyword={{$v->user_id}}&type=all" target="_blank">{{$v->user_id}}</a></td>
                        <td>沪深300-{{$v->time}}</td>
                        <td>@if($v->option==1)涨@endif @if($v->option==2)平@endif @if($v->option==3)跌@endif</td>
                        <td>{{$v->price}}</td>
                        <td>{{$v->profit}}</td>
                        <td>{{$v->created_at}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="row text-right" style="padding-right: 10px" >

            </div>
        </div>
    </div>
    <script>
        $('.date-picker').datetimepicker({ minView: "month",format: 'yyyy-mm-dd'});
    </script>
@stop