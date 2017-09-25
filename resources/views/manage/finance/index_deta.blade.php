@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/flot/0.8.3/jquery.flot.min.js"></script>
    <script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
    <link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <div class="panel  panel-info">
        <div class="panel-heading">
            销售排行
        </div>
        <div class="panel-body">
            <form action="" method="get">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-9">
                            <div class="col-sm-2">
                                <input type="text" class="form-control required date-picker minView: 0" name="start_time" id="start_time"  title="开始时间" placeholder="请选择开始时间段"
                                       {{--data-fv-date="true" required=""--}}
                                       data-fv-date-message="请选择开始时间段"
                                       data-bv-notempty-message="选择开始时间段"
                                       data-fv-date-format="YYYY-MM-DD HH:II" />
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control required date-picker" name="end_time" id="end_time"  title="结束时间" placeholder="请选择结束时间段"
                                       {{--data-fv-date="true" required=""--}}
                                       data-fv-date-message="请选择结束时间段"
                                       data-bv-notempty-message="选择结束时间段"
                                       data-fv-date-format="YYYY-MM-DD HH:II" />
                            </div>
                            <div class="col-xs-3"><button type="submit" formaction="{{URL::to('manage/finance/index_deta')}}" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i>查询</button></div>
                            <div class="col-xs-3"><button type="submit" formaction="{{URL::to('manage/finance/rank_excel')}}" class="btn btn-danger"> <i class="glyphicon glyphicon-floppy-save"></i> 导出销售详情</button></div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="col-xs-3"></div>
                    </div>
                </div>
            </form>
        </div>
            <div class="tabbable">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active">
                        <a href="">
                            销售排行
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="show" class="tab-pane in active">
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th>商品ID</th>
                            <th>商品名</th>
                            <th>商品编号</th>
                            <th>销售数量</th>
                            <th>商品单价（元）</th>
                            <th>总金额（元）</th>
                            </thead>
                            <tbody>
                            @foreach ($info as $vat)
                                <tr>
                                    <td class="center ">
                                        {{ $vat->goods_id }}
                                    </td>
                                    <td class="center ">
                                        {{ $vat->goods_name }}
                                    </td>
                                    <td class="center ">
                                        {{ $vat->goods_sn }}
                                    </td>
                                    <td class="center ">
                                        {{ $vat->goods_number }}
                                    </td>
                                    <td class="center ">
                                        ￥{{ $vat->goods_price }}
                                    </td>
                                    <td class="center ">
                                        ￥{{ $vat->goods_all_price }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    <div class="panel-footer">
        <span style="color:red;font-size:18px;font-weight: 600;">总计: ￥{{  $sum_price }}</span>
        <div class="row text-left" >
            <div class="col-xs-5">
            </div>
            <div class="col-xs-7 text-right">
                {!! $info->render() !!}
            </div>
        </div>
    </div>

    </div>
    <script type="text/javascript">
        $('.date-picker').datetimepicker({ minView: "0",format: 'yyyy-mm-dd hh:ii'});
    </script>
@stop