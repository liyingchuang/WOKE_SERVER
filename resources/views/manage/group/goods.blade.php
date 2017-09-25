@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
    <script src="/assets/js/editable/bootstrap-editable.min.js"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            已开团商品列表
        </div>
        <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{ URL::to('manage/group/goods') }}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" class="form-control"  name="goods_name" placeholder="输入 商品名 进行查询">
                            </div>
                            <div class="col-xs-6">
                                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @if(!$group_id)
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="">开团商品</a></li>
            </ul>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <th>团购ID</th>
                    <th>团长编号</th>
                    <th>团长姓名</th>
                    <th>商品ID</th>
                    <th>商品名</th>
                    <th>已参团人数</th>
                    <th>开团时间</th>
                    <th>状态</th>
                    <th>操作</th>
                    </thead>
                    <tbody>
                    @foreach( $info as $value)
                    <tr>
                        <td>{{ $value->group_id }}</td>
                        <td>{{ $value->user_id }}</td>
                        <td>{{ $value->user_name }}</td>
                        <td>{{ $value->goods_id }}</td>
                        <td>{{ $value->goods_name }}</td>
                        <td>{{ $value->have }}</td>
                        <td>{{ date('Y-m-d H:i:s', $value->start_time) }}</td>
                        <td>@if($value->group_status == 0) <span style="color: red;">未成团 @elseif($value->group_status == 1) <span style="color: green;">已成团 @elseif($value->group_status == 3) <span style="color: red;">已退款 @endif</td>
                        <td><a class="btn btn-info btn-xs" href="{{ URL::to('manage/group/goods') }}/{{ $value->group_id }}"><span class="glyphicon glyphicon-eye-open"></span>查看</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            @else
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="">参团商品</a></li>
                </ul>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <th>团ID</th>
                        <th>参团人编号</th>
                        <th>参团人姓名</th>
                        <th>订单号</th>
                        <th>商品名</th>
                        <th>收货人姓名</th>
                        <th>收货地址</th>
                        <th>收货人电话</th>
                        <th>购买数量</th>
                        <th>支付方式</th>
                        <th>支付时间</th>
                        <th>总金额</th>
                        </thead>
                        <tbody>
                        @foreach( $info as $value )
                            <tr>
                                <td>{{ $value->group_id }}</td>
                                <td>{{ $value->user_id }}</td>
                                <td>{{ $value->user_name }}</td>
                                <td>{{ $value->order_sn }}</td>
                                <td>{{ $value->goods_name }}</td>
                                <td>{{ $value->user_name }}</td>
                                <td>{{ $value->address }}</td>
                                <td>{{ $value->tel }}</td>
                                <td>{{ $value->buy_number }}</td>
                                <td>{{ $value->pay_name }}</td>
                                <td>{{ date('Y-m-d H:i:s', $value->pay_time) }}</td>
                                <td>{{ $value->order_amount }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row text-left" >
                    <div class="col-xs-5">
                    </div>
                    <div class="col-xs-7 text-right">
                        <a href="#" class="btn btn-info" onClick="javascript :history.back(-1);">返回上一页</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="panel-footer">
        <div class="row text-left" >
            <div class="col-xs-5">
            </div>
            <div class="col-xs-7 text-right">
                {!! $info->appends(['goods_name' => $goods_name])->render() !!}
            </div>
        </div>
    </div>
@stop