@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li class="active">店内分类</li>
        </ol>
    </div>
    <div class="panel-body">
			<div class="col-xs-10"></div>
            <div class="col-xs-2">
              <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals">
                    <i class="glyphicon glyphicon-plus"></i>添加分类</button>
            </div>
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>分类名称</th>
        <th>商品数量</th>
		<th>商品排序</th>
        <th>是否显示</th>
        <th>操作</th>
        </thead>
        <tbody>
           @foreach ($user_cat as $user_cat)
            <tr>
				<td>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="supplier_cat{{ $user_cat->cat_id }}" value="{{ $user_cat->cat_name }}" style="display:none" onblur="update_cat({{ $user_cat->cat_id }}, 1)">
                    <span id="supplier_cats{{ $user_cat->cat_id }}" onclick="show_cat({{ $user_cat->cat_id }}, 1)">{{ $user_cat->cat_name }}</span>
                </td>
                <td>
                    {{ $user_cat->goods_cat_num }}
                </td>
				<td>
                    <input type="text" onkeydown="this.onkeyup();" onkeyup="this.size=(this.value.length>4?this.value.length:4);" size="4" id="supplier_order{{ $user_cat->cat_id }}" value="{{ $user_cat->sort_order }}" style="display:none" onblur="update_cat({{ $user_cat->cat_id }}, 2)">
                    <span id="supplier_orders{{ $user_cat->cat_id }}" onclick="show_cat({{ $user_cat->cat_id }}, 2)">{{ $user_cat->sort_order }}</span>
                </td>
				 <td>
                    <a onclick="update_price({{  $user_cat->cat_id }},{{  $user_cat->is_show }}, 1)" href="javascript:;">
                        <i id="is_show{{  $user_cat->cat_id }}" class="menu-icon glyphicon @if(  $user_cat->is_show == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
				<td class="center" width="300px;">
                    <button class="btn btn-info  btn-xs"  type="button" data-target="#shopModals" data-backdrop="static" data-toggle="modal"> <i class="glyphicon glyphicon-resize-horizontal"></i> 转移</button>
                    <button class="btn btn-danger  btn-xs "  type="button"  onclick="if (confirm('确定删除吗?')) {
                                        var obj = $(this);
                                        $.get('{{ url('manage/goods/supplier_category_del') }}/{{ $user_cat->cat_id }}', function(response){
											if(response == 1){
												alert('此分类下有商品，无法删除！');
												exit;
											}else{
												$(this).parent().hide().next().show();
												 obj.parents('tr:first').remove();
											}                       
                                                            });
                                                    }"> <i class="glyphicon glyphicon-trash"></i> 移除</button>
                </td>
            </tr>
		@endforeach
        </tbody>
    </table>
    <div class="panel-footer">
		<div class="row text-right" ></div>
    </div>
 </div>
<!--添加分类 Modal -->
<div class="modal fade" id="myModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加店内分类</h4>
            </div>
            <form method="get"  id="addForm" action="{{URL::to('manage/goods/supplier_category_add')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">分类名称:</label>
                        <div class="col-sm-9 col-xs-9 col-md-9">
                            <input type="text" class="form-control required safe-input" name="cat_name" id="cat_name" title="分类名称" placeholder="分类名称" required="" data-bv-notempty-message="分类名称">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> 确定添加</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!--转移分类 Modal -->
<div class="modal fade" id="shopModals" tabindex="-1" role="dialog" aria-labelledby="shopModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="shopModalLabel">转移分类</h4>
            </div>
            <form method="get"  id="addForm" action="{{URL::to('manage/goods/supplier_category_transfer')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">从此分类</label>
                        <div class="col-sm-3 col-xs-3 col-md-3">
                            <select name="used_cat_id" id="used_cat_id" onchange="">
                                @foreach ($used_cat_id as $used_cat_id)
                                <option value="{{ $used_cat_id->cat_id }}">{{ $used_cat_id->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">---转移到---</label>
                        <div class="col-sm-3 col-xs-3 col-md-3">
                            <select name="new_cat_id" id="new_cat_id" onchange="">
                                @foreach ($new_cat_id as $new_cat_id)
                                    <option value="{{ $new_cat_id->cat_id }}">{{ $new_cat_id->cat_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> 确定转移</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function show_cat(id,my){
	if(my == 1){
		$("#supplier_cat"+id).show();
		$("#supplier_cat"+id).focus(); //获取光标
		$("#supplier_cats"+id).hide();
	}else{
		$("#supplier_order"+id).show();
		$("#supplier_order"+id).focus(); //获取光标
		$("#supplier_orders"+id).hide();
	}
}
function update_cat(cat_id,my){
	if(my == 1){
		var father=$("#supplier_cat"+cat_id).val();
		var son=$("#supplier_cats"+cat_id).html();
		if(father==son){
			$("#supplier_cat"+cat_id).hide();
			$("#supplier_cats"+cat_id).show();
			return false;
		}
	}else{
		var father=$("#supplier_order"+cat_id).val();
		var son=$("#supplier_orders"+cat_id).html();
		if(father==son){
			$("#supplier_order"+cat_id).hide();
			$("#supplier_orders"+cat_id).show();
			return false;
		}
	}
	$.ajax({
		url:"/manage/goods/update_supplier_cat",
		data:{"cat_id":cat_id,"my":my,"father":father},
		type:"post",
		success:function(e){
			if(e==1){
				$("#supplier_cats"+cat_id).html(father);
				$("#supplier_cats"+cat_id).show();
				$("#supplier_cat"+cat_id).hide();
			}else if(e==2){
				$("#supplier_orders"+cat_id).html(father);
				$("#supplier_orders"+cat_id).show();
				$("#supplier_order"+cat_id).hide();
			}else{
				alert("出现问题，请联系NB管理员！！！");
			}
		}
	});
}
function update_price(cat_id,now,my){
	if(my == 1){
		if($("#is_show"+cat_id).attr("class")=="menu-icon glyphicon glyphicon-ok"){
			$("#is_show"+cat_id).attr("class","menu-icon glyphicon glyphicon-remove");
		}else{
			$("#is_show"+cat_id).attr("class","menu-icon glyphicon glyphicon-ok");
		}
	}
   $.ajax({
		url:"/manage/goods/update_supplier_price",
		data:{"cat_id":cat_id,"now":now,"my":my},
		type:"get"
	})
}
</script>
@stop







