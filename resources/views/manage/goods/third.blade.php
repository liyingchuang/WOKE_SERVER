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
        <div class="row " >
            <div class="col-xs-10">
                <form action="{{URL::to('manage/goods/third_party')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-5">
                                <input type="text" class="form-control"  name="keyword" placeholder="请输入商品名称或者商品编号进行搜索">
                            </div>
                            <div class="col-xs-3">
                                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th><label><input type="checkbox" onclick="checkall(this)" class="multi_checked"><span class="text"></span></label>&nbsp;&nbsp;&nbsp;编号</th>
        <th>供货商</th>
		<th>商品名称</th>
        <th>货号</th>
        <th>价格</th>
        <th @if($shop_brand != 'manage') style="display:none;"  @endif>精品</th>
        <th @if($shop_brand != 'manage') style="display:none;"  @endif>新品</th>
        <th @if($shop_brand != 'manage') style="display:none;"  @endif>热销</th>
        <th>上架</th>
		<th>审核状态</th>
        <th>推荐排序</th>
        <th>库存</th>
        </thead>
        <tbody>
            @foreach ($goods_list as $goods)
            <tr>
                <td><label><input type="checkbox" name="checkboxes[]" value="{{ $goods->goods_id }}" class="multi_checked"><span class="text"></span>&nbsp;&nbsp;&nbsp;{{ $goods->goods_id }}</label>
                </td>
                <td>
                    {{ $goods->supplier_name }}
                </td>
				<td>
                    {{ $goods->goods_name }}
                </td>
                <td>
                    {{ $goods->goods_sn }}
                </td>
                <td>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="market_price{{ $goods->goods_id }}" value="{{ $goods->market_price }}" style="display:none" onblur="update_goods({{ $goods->goods_id }}, 3)">
                    <span id="market_prices{{ $goods->goods_id }}" onclick="show_goods({{ $goods->goods_id }}, 3)">{{ $goods->market_price }}</span>
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
                    <a onclick="update_price({{ $goods->goods_id }},{{ $goods->is_on_sale }}, 4)" href="javascript:;">
                        <i id="sale{{ $goods->goods_id }}" class="menu-icon glyphicon @if( $goods->is_on_sale == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
				 <td>
                    <a onclick="update_price({{ $goods->goods_id }},{{ $goods->supplier_status }}, 6)" href="javascript:;">
                        <i id="examine{{ $goods->goods_id }}" class="menu-icon glyphicon @if( $goods->supplier_status == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
                <td>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="sort_order{{ $goods->goods_id }}" value="{{ $goods->sort_order }}" style="display:none" onblur="update_goods({{ $goods->goods_id }}, 4)"/>
                    <span id="sort_orders{{ $goods->goods_id }}" onclick="show_goods({{ $goods->goods_id }}, 4)">{{ $goods->sort_order }}</span>
              </td>
                <td>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="goods_number{{ $goods->goods_id }}" value="{{ $goods->goods_number }}" style="display:none" onblur="update_goods({{ $goods->goods_id }}, 5)"/>
                    <span id="goods_numbers{{ $goods->goods_id }}" onclick="show_goods({{ $goods->goods_id }}, 5)">{{ $goods->goods_number }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer"> 
            <div class="row " >
                <div class="col-xs-2 text-right"> 
                    <select id="select" class="form-control" style="margin-left: 10px;" >
                        <option value="null">请选择...</option>
						<option value="examine1">审核通过</option>
						<option value="examine0">未审核</option>
						<option value="examine-1">审核未通过</option>
                        <option value="delete">回收站</option>
                        <option value="shelve">精品</option>
                        <option value="off-shelve">取消精品</option>
                        <option value="fine">热销</option>
                        <option value="off-fine">取消热销</option>
                        <option value="sale">上架</option>
                        <option value="off-sale">下架</option>
                    </select>
                </div>
                <div class="col-xs-1">
                    <button onclick="button()" class="btn btn-info"> <i class="glyphicon glyphicon-hand-right"></i> 确定</button>
                </div>
                <div class="col-xs-9 text-right">
                    {!! $goods_list->appends(['keyword' =>$keyword])->render() !!}
                </div>
        </div>
    </div>
</div>
<script src='{{ asset("/assets/js/only.js")}}'></script>
<script>
function button(){
    var goods_id=new Array();
    $('input[name="checkboxes[]"]:checked').each(function(){
		goods_id.push($(this).val());
    });
    if(goods_id == ""){
        alert('请你选择操作对象！');
        return false;
    }
	$main = $("#select").val();
	if($main == 'null'){
		alert("请选择你需要的操作！！！");
		exit;
	}
	if($main == 'delete'){
		if(confirm('确定删除吗?')){	
			$.ajax({
			url:"/manage/goods/create",
			data:{"goods_id":goods_id,"main":$main},
			type:"get",
			success:function(e){
					alert(e);
					setTimeout(window.location.href='/manage/goods',100); 
				}
			});	
		}else{
			alert('no');
		}
	}else{
		$.ajax({
			url:"/manage/goods/create",
			data:{"goods_id":goods_id,"main":$main},
			type:"get",
			success:function(e){
					alert(e);
					setTimeout(window.location.href='/manage/goods/third_party',100); 
				}
			});	
	}
}
</script>
@stop







