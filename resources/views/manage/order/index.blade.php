@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/flot/0.8.3/jquery.flot.min.js"></script>
    <script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
    <link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <div class="panel  panel-info">
        <div class="panel-heading">
            订单管理
        </div>
        <div class="panel-body">
            <div class="row ">
                <div class="col-xs-10">
                    <div class="form-group">
                        <form action="{{ URL::to('/manage/order') }}" method="get">
                            <div class="col-sm-2">
                                <input type="text" class="form-control" name="keyword" placeholder="输入 订单号、收货人 查询">
                            </div>
                            <div class="col-sm-2">
                                <select name="status" class="form-control">
                                    <option value="" selected>订单状态请选择</option>
                                    <option value="3">待发货</option>
                                    <option value="4">已完成</option>
                                    <option value="5">已付款</option>
                                    <option value="6">取消</option>
                                    <option value="8">退货</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control required date-picker minView: 0"
                                       name="start_time" id="start_time" title="开始时间" placeholder="请选择开始时间段"
                                       {{--data-fv-date="true" required=""--}}
                                       data-fv-date-message="请选择开始时间段"
                                       data-bv-notempty-message="选择开始时间段"
                                       data-fv-date-format="YYYY-MM-DD HH:II"/>
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control required date-picker" name="end_time"
                                       id="end_time" title="结束时间" placeholder="请选择结束时间段"
                                       {{--data-fv-date="true" required=""--}}
                                       data-fv-date-message="请选择结束时间段"
                                       data-bv-notempty-message="选择结束时间段"
                                       data-fv-date-format="YYYY-MM-DD HH:II"/>
                            </div>
                            <div class="col-xs-2">
                                <button type="submit" class="btn btn-info"><i
                                            class="glyphicon glyphicon-search"></i> 搜索
                                </button>
                            </div>
                            <div class="col-xs-2">
                                @if($url=='manage.order.index')
                                    <button type="submit" formaction="{{ URL::to('/manage/order/export') }}" class="btn btn-warning exp"><i
                                                class="glyphicon glyphicon-floppy-save"></i> 导出未发货订单
                                    </button>
                                @else
                                    <button type="submit" formaction="{{ URL::to('/manage/order/exportorder') }}" class="btn btn-danger exporder"><i
                                                class="glyphicon glyphicon-floppy-save"></i> 导出已支付订单
                                    </button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <table class="table  table-bordered table-striped">
            <thead>
            <th>订单号</th>
            <th>下单手机号</th>
            <th>支付方式</th>
            <th>下单时间</th>
            <th>收货人</th>
            <th>总金额</th>
            <th>付款金额</th>
            <th>酒币金额</th>
            <th>团购状态</th>
            <th>订单状态</th>
            <th>操作</th>
            </thead>
            <tbody>
            @foreach ($info as $list)
                <tr @if( $list->pay_name =='woke'&&$list->integral_money<=0) class="warning" @endif>
                    <td>
                        &nbsp;&nbsp;&nbsp;{{ $list->order_sn }}
                    </td>
                    <td><a href="{{URL::to('manage/user')}}?keyword={{$list->user_id}}&type=all"
                           target="_blank">@if(isset($list->user)){{$list->user->mobile_phone}}@endif</a></td>
                    <td> @if( $list->pay_name =='alipay'|| $list->pay_name =='alipay_wap')
                            支付宝 @elseif( $list->pay_name =='wx') 微信APP @elseif( $list->pay_name =='wx_pub')
                            微信公众 @elseif( $list->pay_name =='woke')  酒币支付 @endif</td>
                    <td>{{date('Y-m-d H:i:s',$list->add_time)  }}</td>
                    <td><span style=" color:red">{{ $list->consignee }}</span>---{{ $list->province }}-{{ $list->city }}
                        -{{ $list->district }}-{{ $list->address }}</td>
                    <td>{{ $list->order_amount }}</td>
                    <td>{{ $list->order_amount-$list->integral_money }}</td>
                    <td>{{ $list->integral_money }}</td>
                    <td>@if($list->extension_code=='group_buy') <span
                                style="color:red">未成团</span> @elseif($list->extension_code=='group_success') <span
                                style="color:green">已成团</span>   @elseif($list->extension_code=='refund') <span
                                style="color:red">团购-退款</span> @else @endif</td>
                    <td>
                        @if( $list->order_status == 0) 未确认 @elseif( $list->order_status == 1)
                            已确认 @elseif( $list->order_status == 2) <span
                                style=" color:red">取消</span> @elseif( $list->order_status == 3) <span
                                style=" color:red">无效</span> @elseif( $list->order_status == 4)  退货 @else  已分单 @endif，
                        @if( $list->pay_status == 0)<span style=" color:red">未付款</span> @elseif( $list->pay_status == 1)
                            <span style=" color:red">付款中</span> @else  <span style=" color:green">已付款 </span>  @endif，
                        @if( $list->shipping_status == 0) 未发货 @elseif( $list->shipping_status == 1)<span
                                style=" color:green"> 已发货</span> @elseif( $list->shipping_status == 2) 收货确认 @else
                            已收货  @endif
                    </td>
                    <td>
                        <a href="{{URL::to('manage/order')}}/{{ $list->order_id }}" class="btn btn-success btn-xs">
                            <i class="glyphicon glyphicon-search"></i>查看
                        </a>
                        <button @if( $list->order_status == 0 || $list->order_status == 1 || $list->order_status == 4 || $list->order_status == 5) style="display:none"
                                @endif class="btn btn-danger  btn-xs "
                                type="button"><i class="glyphicon glyphicon-trash"></i> 移除
                        </button>
                        <div>@if($list->order_status == 4)<span style=" color:red">退货</span> @endif</div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="panel-footer">
            <div class="row text-left">
                <div class="col-xs-5">
                </div>
                <div class="col-xs-7 text-right">
                    {!! $info->appends(['keyword' =>$keyword , 'start_time'=>$start_time, 'end_time'=>$end_time, 'status' =>$status])->render() !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.date-picker').datetimepicker({minView: "0", format: 'yyyy-mm-dd hh:ii'});
            $('.exp').click(function(){
                var start_time = $('#start_time').val();
                var end_time = $('#end_time').val();
                $.ajax({
                    url:'/manage/order/export',
                    data:{start_time:start_time, end_time:end_time},
                    type:'get',

                });
            });
            $('.exporder').click(function(){
                var start_time = $('#start_time').val();
                var end_time = $('#end_time').val();
                $.ajax({
                    url:'/manage/order/exportorder',
                    data:{start_time:start_time, end_time:end_time},
                    type:'get',
                });
            });
    </script>
@stop







