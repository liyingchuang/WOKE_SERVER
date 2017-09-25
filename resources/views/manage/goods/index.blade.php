@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li class="active">商品列表</li>
        </ol>
    </div>
    <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{URL::to('manage/goods')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="text" class="form-control"  name="keyword" placeholder="请输入商品名称或者商品编号进行搜索">
                            </div>
                            <div class="col-xs-2">
                                <select class="form-control" name="is_on_sale">
                                <option value="">请选择</option>
                                <option value="1">已上架</option>
                                <option value="2">未上架</option>
                                </select>
                            </div>
                            <div class="col-xs-3">
                                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                            </div>
                            <div class="col-xs-3"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xs-2 text-left">
                <a   class="btn btn-info btn-ms  pull-right" href="{{URL::to('manage/goods/create')}}" >
                    <i class="glyphicon glyphicon-plus"></i>添加商品</a>
            </div>
        </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>编号</th>
        <th>商品名称</th>
        <th>货号</th>
        <th>价格</th>
        <th @if($shop_brand != 'manage') style="display:none;"  @endif>APP首页</th>
        <th @if($shop_brand != 'manage') style="display:none;"  @endif>新品</th>
        <th @if($shop_brand != 'manage') style="display:none;"  @endif>热销</th>
        <th>上架</th>
        <th @if($shop_brand != 'manage') style="display:none;"  @endif>推荐排序</th>
        <th>库存</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($goods_list as $goods)
            <tr>
                <td>
                    {{ $goods->goods_id }}
                </td>
                <td>
                    {{ $goods->goods_name }}
                </td>
                <td>
                    {{ $goods->goods_sn }}
                </td>
                <td>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="market_price{{ $goods->goods_id }}" value="{{ $goods->market_price }}" style="display:none" onblur="update_goods({{ $goods->goods_id }}, 3)">
                    <span id="market_prices{{ $goods->goods_id }}" onclick="show_goods({{ $goods->goods_id }}, 3)">{{ $goods->shop_price }}</span>
                </td>
                <td  @if($shop_brand != 'manage') style="display:none;"  @endif>
                    <a onclick="update_price({{ $goods->goods_id }},{{ $goods->is_best }}, 1)" href="javascript:;">
                        <i id="best{{ $goods->goods_id }}" class="menu-icon glyphicon @if( $goods->is_best == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
                <td @if($shop_brand != 'manage') style="display:none;"  @endif>
                    <a onclick="update_price({{ $goods->goods_id }},{{ $goods->is_new }}, 2)" href="javascript:;">
                        <i id="new{{ $goods->goods_id }}" class="menu-icon glyphicon @if( $goods->is_new == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
                <td @if($shop_brand != 'manage') style="display:none;"  @endif>
                    <a onclick="update_price({{ $goods->goods_id }},{{ $goods->is_hot }}, 3)" href="javascript:;">
                        <i id="hot{{ $goods->goods_id }}" class="menu-icon glyphicon @if( $goods->is_hot == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
                <td>
                    <a @if(Session('manage_role') == 'manage') onclick="update_price({{ $goods->goods_id }},{{ $goods->is_on_sale }}, 4, 0)" @else onclick="update_price({{ $goods->goods_id }},{{ $goods->is_on_sale }}, 4, 1,'{{ $goods->rec_content }}')" @endif href="javascript:;">
                        @if($goods->is_on_sale == 3 ) <i class="glyphicon glyphicon-question-sign danger" title="{{ $goods->rec_content }}"></i> @else <i id="sale{{ $goods->goods_id }}" class="menu-icon glyphicon @if( $goods->is_on_sale == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>@endif
                    </a>
                </td>
                <td @if($shop_brand != 'manage') style="display:none;"  @endif>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="sort_order{{ $goods->goods_id }}" value="{{ $goods->sort_order }}" style="display:none" onblur="update_goods({{ $goods->goods_id }}, 4)"/>
                    <span id="sort_orders{{ $goods->goods_id }}" onclick="show_goods({{ $goods->goods_id }}, 4)">{{ $goods->sort_order }}</span>
              </td>
                <td>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="goods_number{{ $goods->goods_id }}" value="{{ $goods->goods_number }}" style="display:none" onblur="update_goods({{ $goods->goods_id }}, 5)"/>
                    <span id="goods_numbers{{ $goods->goods_id }}" onclick="show_goods({{ $goods->goods_id }}, 5)">{{ $goods->goods_number }}</span>
                </td>
                <td class="center ">
                 <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/goods')}}/{{ $goods->goods_id }}/edit"><i class="glyphicon glyphicon-edit"></i> 编辑</a>
                    <button class="btn btn-danger  btn-xs  remove_img "  type="button"    alt="{{ $goods->goods_id }}"> <i class="glyphicon glyphicon-trash"></i> 回收站</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer"> 
            <div class="row " >
                <div class="col-xs-2 text-right"> 

                </div>
                <div class="col-xs-1">

                </div>
                <div class="col-xs-9 text-right">
                    {!! $goods_list->appends(['keyword' =>$keyword,'is_on_sale'=>$is_on_sale])->render() !!}
                </div>
        </div>
    </div>
</div>
<script src='{{ asset("/assets/js/only.js")}}?time={{time()}}'></script>
    <script>
        $('.remove_img').click(function() {
            var id = $(this).attr('alt');
            $.get("{{URL::to('manage/goods/updateStatus')}}/" ,{"goods_id":id,"now":0,"my":5}, function(result) {
            });
            //console.log();
            $(this).parents('tr:first').remove();
            // $("#tag" + id).remove();
            //$(this).remove();
        });


    </script>
@stop







