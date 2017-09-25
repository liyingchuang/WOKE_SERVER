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
                            <div class="col-sm-2">
                                <input type="text"  class="form-control" id="goods_name" name="goods_name" placeholder="请输入商品名查询详情"/>
                            </div>
                            <div class="col-xs-3"><button type="submit" formaction="{{URL::to('manage/finance')}}" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i>搜索</button></div>
                            <div class="col-xs-3"><button type="submit" formaction="{{URL::to('manage/finance/deta_excel')}}" class="btn btn-warning"> <i class="glyphicon glyphicon-floppy-save"></i> 导出销售详情</button></div>
                            <div class="col-xs-2"></div>
                        </div>
                        <div class="col-xs-3"></div>
                    </div>
                </div>
            </form>
        </div>
        @if(empty($goods_name))
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a href="">
                        销售额/销售量
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                    <div id="show" class="tab-pane in active">
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th>订单号</th>
                            <th>收货人</th>
                            <th>收货地址</th>
                            <th>总金额 （元）</th>
                            <th>付款金额</th>
                            <th>酒币金额</th>
                            <th>付款方式</th>
                            <th>发票抬头</th>
                            <th>企业税号</th>
                            <th>购买日期</th>
                            </thead>
                                @foreach($result as $value)
                            <tbody>
                                <tr>
                                    <td class="center " >

                                        @if(!empty($value['ordergoods']))
                                        <table class="table  table-bordered">
                                            <thead>
                                            <th style="color:blue">{{ $value['orderinfo']['order_sn'] }}</th>
                                            </thead>
                                            <thead>
                                            <th>　-- 商品名</th>
                                            </thead>
                                            @foreach( $value['ordergoods'] as $val)
                                            <tbody>
                                                <td>　　{{ $val['goods_name'] }}</td>
                                            </tbody>
                                            @endforeach
                                        </table>
                                        @endif
                                    </td>
                                    <td class="center " >
                                        @if(!empty($value['ordergoods']))
                                            <table class="table  table-bordered">
                                                <thead>
                                                <th style="color:blue">{{ $value['orderinfo']['consignee'] }}</th>
                                                </thead>
                                                <thead>
                                                <th>-- 商品编号</th>
                                                </thead>
                                                @foreach( $value['ordergoods'] as $val)
                                                    <tbody>
                                                    <td>　　{{ $val['goods_sn'] }}</td>
                                                    </tbody>
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="center " >
                                            @if(!empty($value['ordergoods']))
                                            <table class="table  table-bordered">
                                                <thead>
                                                <th style="color:blue">{{ $value['orderinfo']['address'] }}</th>
                                                </thead>
                                            <thead>
                                                <th>-- 购买数量</th>
                                            </thead>
                                                    @foreach( $value['ordergoods'] as $val)
                                                        <tbody>
                                                <td>　　{{ $val['goods_number'] }}</td>
                                            </tbody>
                                                    @endforeach
                                        </table>

                                        @endif
                                    </td>
                                    <td class="center " >
                                        @if(!empty($value['ordergoods']))
                                            <table class="table  table-bordered">
                                                <thead>
                                                <th style="color:blue">￥{{ $value['orderinfo']['order_amount'] }}</th>
                                                </thead>
                                                <thead>
                                                <th>-- 商品价格</th>
                                                </thead>
                                                @foreach( $value['ordergoods'] as $val)
                                                    <tbody>
                                                    <td>　　{{ $val['goods_price'] }}</td>
                                                    </tbody>
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="center " >
                                        @if(!empty($value['ordergoods']))
                                            <table class="table  table-bordered">
                                                <thead>
                                                <th style="color:blue">{{ $value['orderinfo']['order_amount'] - $value['orderinfo']['integral_money'] }}</th>
                                                </thead>
                                                <thead>
                                                <th>-- 商品类别</th>
                                                </thead>
                                                @foreach( $value['ordergoods'] as $val)
                                                    <tbody>
                                                    <td>　　@if($val['goods_attr']){{ $val['goods_attr'] }}@else其他@endif</td>
                                                    </tbody>
                                                @endforeach
                                            </table>
                                        @endif
                                    </td>
                                    <td class="center " >
                                            <table class="table  table-bordered">
                                                <thead>
                                                <th style="color:blue">{{ $value['orderinfo']['integral_money'] }}</th>
                                                </thead>
                                            </table>
                                    </td>
                                    <td class="center " >
                                        <table class="table  table-bordered">
                                            <thead>
                                            <th style="color:blue">@if($value['orderinfo']['pay_name'] == 'woke')酒币@else支付宝@endif</th>
                                            </thead>
                                        </table>
                                    </td>
                                    <td class="center " >
                                        <table class="table  table-bordered">
                                            <thead>
                                            <th style="color:blue">@if($value['orderinfo']['vat_inv_company_name']){{ $value['orderinfo']['vat_inv_company_name'] }}@else　@endif</th>
                                            </thead>
                                        </table>
                                    </td>
                                    <td class="center " >
                                        <table class="table  table-bordered">
                                            <thead>
                                            <th style="color:blue">@if($value['orderinfo']['vat_inv_taxpayer_id']){{ $value['orderinfo']['vat_inv_taxpayer_id'] }}@else　@endif</th>
                                            </thead>
                                        </table>
                                    </td>
                                    <td class="center " >
                                        <table class="table  table-bordered">
                                            <thead>
                                            <th style="color:blue">{{ date('Y-m-d H:i', $value['orderinfo']['pay_time'] ) }}</th>
                                            </thead>
                                        </table>
                                    </td>

                                </tr>
                            @endforeach
                        </table>
                 </div>
            </div>
        </div>
        @else
            <div class="tabbable">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active">
                        <a href="">
                            商品详情
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="show" class="tab-pane in active">
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th>所属单号</th>
                            <th>商品名</th>
                            <th>商品编号</th>
                            <th>购买数量</th>
                            <th>商品单价（元）</th>
                            <th>购买日期</th>
                            </thead>
                            <tbody>
                            @foreach ($info as $name)
                                <tr>
                                    <td class="center ">
                                        {{ $name->order_sn }}
                                    </td>
                                    <td class="center ">
                                        {{ $name->goods_name }}
                                    </td>
                                    <td class="center ">
                                        {{ $name->goods_sn }}
                                    </td>
                                    <td class="center ">
                                        {{ $name->goods_number }}
                                    </td>
                                    <td class="center">
                                        ￥{{ $name->goods_price }}
                                    </td>
                                    <td class="center ">
                                        {{ date('Y-m-d H:i:s', $name->pay_time) }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
        <div class="panel-footer">
            <span style="color:red;font-size:18px;font-weight: 600;">总计: ￥{{  $num }}</span>
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

//        $('.deta').click(function(){
//            var start_time = $("#start_time").val();
//            var end_time = $("#end_time").val();
//            $.ajax({
//                url:"/manage/finance/deta_excel",
//                data:{start_time:start_time, end_time:end_time},
//                type:"get",
//                success:function() {
//                    alert(1);
//                }
//            });
//        });

    </script>
@stop