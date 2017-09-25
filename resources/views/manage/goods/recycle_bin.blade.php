@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li class="active">回收站列表</li>
            </ol>
        </div>
        <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{URL::to('manage/goods/recycle_bin')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="text" class="form-control" name="keyword" placeholder="请输入商品名称或者商品编号进行搜索">
                            </div>
                            <div class="col-xs-3">
                                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> 搜索
                                </button>
                            </div>
                            <div class="col-xs-3"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xs-2">
                <a class="btn btn-info btn-ms  pull-right" href="{{URL::to('manage/goods')}}">
                    <i class="glyphicon glyphicon-th"></i>商品列表</a>
            </div>
        </div>
        <table class="table  table-bordered table-striped">
            <thead>
            <th>编号</th>
            <th>商品名称</th>
            <th>货号</th>
            <th>价格</th>
            <th>排序</th>
            <th>库存</th>
            <th>操作</th>
            </thead>
            <tbody>
            @foreach ($goods_list as $goods)
                <tr>
                    <td>{{ $goods->goods_id }}</td>
                    <td>
                        {{ $goods->goods_name }}
                    </td>
                    <td>
                        {{ $goods->goods_sn }}
                    </td>
                    <td>
                        {{ $goods->market_price }}
                    </td>
                    <td>
                        {{ $goods->sort_order }}
                    </td>
                    <td>
                        {{ $goods->goods_number }}
                    </td>
                    <td class="center ">
                        <button class="btn btn-danger  btn-xs remove_img" alt="{{ $goods->goods_id }}" type="button" ><i class="glyphicon glyphicon-trash"></i> 还原
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="panel-footer">
            <div class="row ">
                <div class="col-xs-2 text-right">
                </div>
                <div class="col-xs-1">

                </div>
                <div class="col-xs-9 text-right">
                    {!! $goods_list->appends(['keyword' =>$keyword])->render() !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.remove_img').click(function() {
            var id = $(this).attr('alt');
            $.get("{{URL::to('manage/goods/updateStatus')}}/" ,{"goods_id":id,"now":1,"my":5}, function(result) {
            });
            $(this).parents('tr:first').remove();

        });

    </script>
@stop







