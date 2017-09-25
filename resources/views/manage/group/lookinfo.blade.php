@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
    <link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
    <script src="/assets/js/editable/bootstrap-editable.min.js"></script>
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
                    <td>商品编号：{{ $value->goods_sn }}</td>
                    <td>商品库存：{{ $value->goods_number }}</td>
                </tr>
                <tr>
                    <td>商品原价：{{ $value->shop_price }}</td>
                    <td>商品状态：@if( $value->is_on_sale==1 ) 上架中 @else 已下架 @endif</td>
                </tr>
                @endforeach
                </thead>
            </table>
        </div>
    </div>
    </div>
        <div class="panel panel-info">
        <div class="panel-body">
            <div class="col-xs-10">
                <table class="table table-bordered table-striped">
                    <thead>
                    <span style="padding-left:48%;font-weight: 700 ">店铺信息</span><p>
                        @foreach($info as $value)
                            <tr>
                                <td>店铺ID：{{ $value->supplier_id }}</td>
                                <td>用户ID：{{ $value->user_id }}</td>
                            </tr>
                            <tr>
                                <td>店铺名：{{ $value->supplier_name }}</td>
                                <td>店长Tel：{{ $value->mobile_phone }}</td>
                            </tr>
                            <tr>
                                <td>详细地址：{{ $value->address }}</td>
                                <td>客服Tel：{{ $value->tel }}</td>
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
@stop