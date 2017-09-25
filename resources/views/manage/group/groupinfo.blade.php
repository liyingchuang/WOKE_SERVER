@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
<script src="/assets/js/editable/bootstrap-editable.min.js"></script>
@if(!$goods_id)
<div class="panel  panel-info">
    <div class="panel-heading">
        参团人信息
    </div>
    <div class="panel-body">
        <div class="col-xs-10">
            <form action="" method="get">
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
    <div class="tabbable">
        <ul class="nav nav-tabs">
            <li role="presentation" class="active"><a href="">参团商品</a></li>
        </ul>
        <div class="panel-body">
            <table class="table table-bordered table-striped">
                <thead>
                <th>团购ID</th>
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
                <th>团购状态</th>
                <th>操作</th>
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
                    <td>@if($value->group_status==0) <span style="color:red">未成团</span> @else <span style="color:green">已成团</span> @endif</td>
                    <td><a href="{{ URL::to('manage/group/groupinfo') }}/{{ $value->goods_id }}" class="btn btn-info btn-xs"><span class="glyphicon glyphicon-eye-open"></span>查看</a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
        @else
        <div class="panel panel-info">
            <div class="panel-heading">
                商品详情
            </div>
            <div class="panel-body">
                <div class="col-xs-10">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <span style="padding-left:48%;font-weight: 700 ">商品信息</span><p>
                            @foreach($info as $value)
                                <tr>
                                    <td>商品ID：{{ $value->goods_id }}</td>
                                    <td>商品名：{{ $value->goods_name }}</td>
                                </tr>
                                <tr>
                                    <td>商品类别：{{ $value->cat_id }}</td>
                                    <td>商品编号：{{ $value->goods_sn }}</td>
                                </tr>
                                <tr>
                                    <td>商品库存：{{ $value->goods_number }}</td>
                                    <td>商品原价：{{ $value->shop_price }}</td>
                                </tr>
                        @endforeach
                        </thead>
                    </table>
                </div>
            </div>
            <div class="panel-body">
                <div class="col-xs-10">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <span style="padding-left:48%;font-weight: 700 ">团购信息</span><p>
                            @foreach($info as $value)
                                <tr>
                                    <td>需参团人数：{{ $value->ex_number }}</td>
                                    <td>已参团人数：{{ $value->ex_have }}</td>
                                </tr>
                                <tr>
                                    <td>团购价：{{ $value->group_price }}</td>
                                    <td>团购时间：{{ date('Y-m-d H:i:s', $value->start_time) }} - {{ date('Y-m-d H:i:s', $value->end_time) }}</td>
                                </tr>
                        @endforeach
                        </thead>
                    </table>
                </div>
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