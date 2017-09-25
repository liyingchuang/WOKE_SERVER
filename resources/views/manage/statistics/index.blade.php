@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/flot/0.8.3/jquery.flot.min.js"></script>
    <script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
    <link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <div class="panel  panel-info">
        <div class="panel-heading">
            统计管理
        </div>
        <div class="panel-body">
            <form action="{{URL::to('manage/statistics')}}" method="get">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-9">
                            <div class="col-sm-2">
                                <input type="text" class="form-control required date-picker minView: 2" name="start_time" id="start_time"  title="开始时间" placeholder="请选择开始时间段"
                                       data-fv-date="true" required=""
                                       data-fv-date-message="请选择开始时间段"
                                       data-bv-notempty-message="选择开始时间段"
                                       data-fv-date-format="YYYY-MM-DD" />
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control required date-picker" name="end_time" id="end_time"  title="结束时间" placeholder="请选择结束时间段"
                                       data-fv-date="true" required=""
                                       data-fv-date-message="请选择结束时间段"
                                       data-bv-notempty-message="选择结束时间段"
                                       data-fv-date-format="YYYY-MM-DD" />
                            </div>
                            <div class="col-xs-3"><button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i>搜索</button></div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="col-xs-3"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li @if($type=='show') class="active"
                    @else class="tab-red" @endif >
                    <a href="{{URL::to('manage/statistics')}}?type=show">
                        订单统计
                    </a>
                </li>
                <li @if($type=='playlist')
                    class="active" @else class="tab-red" @endif >
                    <a href="{{URL::to('manage/statistics')}}?type=playlist">
                        订单榜
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                @if($type=='show')
                    <div id="show" class="tab-pane in active">
                        <div id="selectable-chart" class="chart chart-lg"></div>
                        <div class="alert"></div>
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th>时间段</th>
                            <th>酒币总数</th>
                            <th>消费酒币</th>
                            </thead>
                            <tbody>
                            @foreach ($stac as $info)
                                <tr>
                                    <td class="center ">
                                        {{ date('Y-m-d H:i', $info->yesterday).' - '.date('Y-m-d H:i', $info->now) }}
                                    </td>
                                    <td class="center ">
                                        {{ $info->sum_money }}
                                    </td>
                                    <td class="center ">
                                        {{ $info->integral_money }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if($type=='playlist')
                    <div id="playlist" class="tab-pane in active">
                        <form action="{{URL::to('manage/statistics')}}" method="get">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-3"></div>
                                    <div class="col-xs-9">
                                        <div class="col-xs-9"></div>
                                        <div class="col-sm-2">
                                            <input type="hidden" value="{{ $type }}" name="type">
                                            <input type="text" class="form-control required date-picker" name="created_at" id="created_at"  title="选择时间" placeholder="请选择时间"
                                                   data-fv-date="true" required=""
                                                   data-fv-date-message="请选择时间"
                                                   data-bv-notempty-message="选择时间"
                                                   data-fv-date-format="YYYY-MM-DD" />
                                        </div>
                                        <div class="col-xs-1"><button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i>搜索</button></div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th>编号</th>
                            <th>用户</th>
                            <th>头像</th>
                            </thead>
                            <tbody>
                            @foreach ($result_message as $result_message)
                                <tr>
                                    <td class="center ">
                                        {{ $result_message->order }}
                                    </td>
                                    <td class="center ">
                                        {{ $result_message->user->user_name }}
                                    </td>
                                    <td>
                                        <a href="{{ $result_message->user->headimg }}" target="_break" >
                                            @if($result_message->user->headimg)
                                                <img src="{{ $result_message->user->headimg }}?imageView2/2/w/50" alt="..." class="img-thumbnail"></a>
                                        @else
                                            <img src="/assets/images/nophoto.png" width="4%" alt="..." class="img-thumbnail">
                                        @endif
                                        <label for="disabledSelect"  class="col-sm-2 control-label">今日订单次数：<font color="red">{{ $result_message->today  }}</font></label><br>
                                        <label for="disabledSelect"  class="col-sm-2 control-label">总盘订单数：<font color="red">{{ $result_message->user->count }}</font></label>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        <div class="panel-footer">

        </div>
    </div>
    <script type="text/javascript">
        $('.date-picker').datetimepicker({ minView: "month",format: 'yyyy-mm-dd'});
        @if ($type == 'show')
        //晒晒统计
        $(function() {
            if (typeof $.fn.prop != 'function') {
                $.fn.prop = $.fn.attr;
            }
            var data = [{
                label: "订单数",
                data: {{$showdata['show']}}
	}, {
                label: "订单数",
                data:{{$showdata['likes']}}
	},{
                label: "订单数",
                data: {{$showdata['tags']}}
	},{
                label: "订单人数",
                data: {{$showdata['show_user']}}
	},{
                label: "订单人数",
                data: {{$showdata['like_user']}}
	},{
                label: "订单人数",
                data: {{$showdata['tag_user']}}
	}];
            var options = {
                series: {
                    lines: {
                        show: true
                    },
                    points: {
                        show: true
                    }
                },
                legend: {
                    noColumns: 2
                },
                xaxis: {
                    tickDecimals: 0
                },
                yaxis: {
                    min: 0
                },
                selection: {
                    mode: "x"
                },
                colors: ["#F00", "#F60", "#FF0", "#0C0", "#699", "#06C"],
            };
            var placeholder = $("#selectable-chart");
            var plot = $.plot(placeholder, data, options);
        });
        @endif
    </script>
@stop