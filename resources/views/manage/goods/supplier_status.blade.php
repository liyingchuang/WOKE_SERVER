@extends('_layouts.master')
@section('content')
    <link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
    <script src="/assets/js/editable/bootstrap-editable.min.js"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li class="active">数据审核</li>
            </ol>
        </div>
        <div class="panel-body">
            <div class="col-xs-10 text-left">
                <form action="{{URL::to('manage/goods/supplier_status')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="text" class="form-control" name="keyword" placeholder="请输入手机号或者编号或者用户名搜索">
                                <input type="hidden" name="type" value="{{$type}}">
                            </div>
                            <div class="col-xs-3">
                                <button type="submit" class="btn btn-info"><i class="glyphicon glyphicon-search"></i> 搜索
                                </button>
                            </div>
                            <div class="col-xs-3"></div>
                        </div>
                        <div class="col-xs-2"></div>
                    </div>
                </div>
            </form>
        </div>
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li @if($type=='yes') class="active" @else class="tab-red" @endif >
                    <a href="{{URL::to('manage/goods/supplier_status')}}?type=yes">
                        审核中商品
                    </a>
                </li>
                <li @if($type=='no') class="active" @else class="tab-red" @endif >
                    <a href="{{URL::to('manage/goods/supplier_status')}}?type=no">
                        未通过商品
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                @if($type=='yes')
                    <div id="yes" class="tab-pane in active">
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th><label><input type="checkbox" onclick="checkall(this)" class="multi_checked"><span
                                            class="text"></span></label>&nbsp;&nbsp;&nbsp;编号
                            </th>
                            <th>商品名称</th>
                            <th>货号</th>
                            <th>价格</th>
                            <th>上架</th>
                            <th>推荐排序</th>
                            <th>库存</th>
                            <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach ($list as $goods)
                                <tr>
                                    <td><label><input type="checkbox" name="checkboxes[]" value="{{ $goods->goods_id }}"
                                                      class="multi_checked"><span
                                                    class="text"></span>&nbsp;&nbsp;&nbsp;{{ $goods->goods_id }}</label>
                                    </td>
                                    <td>
                                        {{ $goods->goods_name }}
                                    </td>
                                    <td>
                                        {{ $goods->goods_sn }}
                                    </td>
                                    <td>
                                        <input type="text" onkeydown="this.onkeyup();"
                                               onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4"
                                               id="market_price{{ $goods->goods_id }}"
                                               value="{{ $goods->market_price }}" style="display:none"
                                               onblur="update_goods({{ $goods->goods_id }}, 3)">
                                        <span id="market_prices{{ $goods->goods_id }}"
                                              onclick="show_goods({{ $goods->goods_id }}, 3)">{{ $goods->market_price }}</span>
                                    </td>
                                    <td>
                                        <a onclick="update_prices({{ $goods->goods_id }},{{ $goods->is_on_sale }}, 5)"
                                           href="javascript:;">
                                            <i id="sale{{ $goods->goods_id }}"
                                               class="menu-icon glyphicon @if( $goods->is_on_sale == 1) glyphicon-ok @else glyphicon-remove @endif"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <input type="text" onkeydown="this.onkeyup();"
                                               onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4"
                                               id="sort_order{{ $goods->goods_id }}" value="{{ $goods->sort_order }}"
                                               style="display:none" onblur="update_goods({{ $goods->goods_id }}, 4)"/>
                                        <span id="sort_orders{{ $goods->goods_id }}"
                                              onclick="show_goods({{ $goods->goods_id }}, 4)">{{ $goods->sort_order }}</span>
                                    </td>
                                    <td>
                                        <input type="text" onkeydown="this.onkeyup();"
                                               onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4"
                                               id="goods_number{{ $goods->goods_id }}"
                                               value="{{ $goods->goods_number }}" style="display:none"
                                               onblur="update_goods({{ $goods->goods_id }}, 5)"/>
                                        <span id="goods_numbers{{ $goods->goods_id }}"
                                              onclick="show_goods({{ $goods->goods_id }}, 5)">{{ $goods->goods_number }}</span>
                                    </td>
                                    <td class="center ">
                                        <a class="btn btn-info  btn-xs"
                                           href="{{URL::to('manage/goods/goods_update')}}/{{ $goods->goods_id }}"><i
                                                    class="glyphicon glyphicon-edit"></i> 编辑</a>
                                        <button class="btn btn-danger  btn-xs " type="button"
                                                onclick="if (confirm('确定删除吗?')) {
                                                        $(this).parent().hide().next().show();
                                                        var obj = $(this);
                                                        $.get('{{ url('manage/goods') }}/{{ $goods->goods_id }}/edit', function(response){
                                                        obj.parents('tr:first').remove();
                                                        });
                                                        }"><i class="glyphicon glyphicon-trash"></i> 回收站
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if($type=='no')
                    <div id="no" class="tab-pane in active">
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th><label><input type="checkbox" onclick="checkall(this)" class="multi_checked"><span
                                            class="text"></span></label>&nbsp;&nbsp;&nbsp;编号
                            </th>
                            <th>商品名称</th>
                            <th>货号</th>
                            <th>价格</th>
                            <th>上架</th>
                            <th>推荐排序</th>
                            <th>库存</th>
                            <th>操作</th>
                            </thead>
                            <tbody>
                            @foreach ($list as $goods)
                                <tr>
                                    <td><label><input type="checkbox" name="checkboxes[]" value="{{ $goods->goods_id }}"
                                                      class="multi_checked"><span
                                                    class="text"></span>&nbsp;&nbsp;&nbsp;{{ $goods->goods_id }}</label>
                                    </td>
                                    <td>
                                        {{ $goods->goods_name }}
                                    </td>
                                    <td>
                                        {{ $goods->goods_sn }}
                                    </td>
                                    <td>
                                        <input type="text" onkeydown="this.onkeyup();"
                                               onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4"
                                               id="market_price{{ $goods->goods_id }}"
                                               value="{{ $goods->market_price }}" style="display:none"
                                               onblur="update_goods({{ $goods->goods_id }}, 3)">
                                        <span id="market_prices{{ $goods->goods_id }}"
                                              onclick="show_goods({{ $goods->goods_id }}, 3)">{{ $goods->market_price }}</span>
                                    </td>
                                    <td>
                                        <a onclick="update_prices({{ $goods->goods_id }},{{ $goods->is_on_sale }}, 5)"
                                           href="javascript:;">
                                            <i id="sale{{ $goods->goods_id }}"
                                               class="menu-icon glyphicon @if( $goods->is_on_sale == 1) glyphicon-ok @else glyphicon-remove @endif"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <input type="text" onkeydown="this.onkeyup();"
                                               onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4"
                                               id="sort_order{{ $goods->goods_id }}" value="{{ $goods->sort_order }}"
                                               style="display:none" onblur="update_goods({{ $goods->goods_id }}, 4)"/>
                                        <span id="sort_orders{{ $goods->goods_id }}"
                                              onclick="show_goods({{ $goods->goods_id }}, 4)">{{ $goods->sort_order }}</span>
                                    </td>
                                    <td>
                                        <input type="text" onkeydown="this.onkeyup();"
                                               onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4"
                                               id="goods_number{{ $goods->goods_id }}"
                                               value="{{ $goods->goods_number }}" style="display:none"
                                               onblur="update_goods({{ $goods->goods_id }}, 5)"/>
                                        <span id="goods_numbers{{ $goods->goods_id }}"
                                              onclick="show_goods({{ $goods->goods_id }}, 5)">{{ $goods->goods_number }}</span>
                                    </td>
                                    <td class="center ">
                                        <a class="btn btn-info  btn-xs"
                                           href="{{URL::to('manage/goods/goods_update')}}/{{ $goods->goods_id }}"><i
                                                    class="glyphicon glyphicon-edit"></i> 编辑</a>
                                        <button class="btn btn-danger  btn-xs " type="button"
                                                onclick="if (confirm('确定删除吗?')) {
                                                        $(this).parent().hide().next().show();
                                                        var obj = $(this);
                                                        $.get('{{ url('manage/goods') }}/{{ $goods->goods_id }}/edit', function(response){
                                                        obj.parents('tr:first').remove();
                                                        });
                                                        }"><i class="glyphicon glyphicon-trash"></i> 回收站
                                        </button>
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
            <div class="row text-right" style="padding-right: 10px">
                {!! $list->appends(['keyword' =>$keyword,'type'=>$type])->render() !!}
            </div>
        </div>
    </div>
    <script src='{{ asset("/assets/js/only.js")}}'></script>
    <script>
        function update_prices(goods_id, now, my) {
            $.ajax({
                url: "/manage/goods/update_price",
                data: {"goods_id": goods_id, "now": now, "my": my},
                type: "get",
                success: function (e) {
                    if (e == 1) {
                        if ($("#sale" + goods_id).attr("class") == "menu-icon glyphicon glyphicon-ok") {
                            $("#sale" + goods_id).attr("class", "menu-icon glyphicon glyphicon-remove");
                        } else {
                            $("#sale" + goods_id).attr("class", "menu-icon glyphicon glyphicon-ok");
                        }
                    } else {
                        alert("对不起，商品未审核通过！");
                    }
                }
            })
        }
    </script>
@stop