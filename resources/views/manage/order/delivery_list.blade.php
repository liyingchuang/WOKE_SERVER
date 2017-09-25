@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
       发货单列表
    </div>
	 <div class="panel-body">
         <div class="row " >
             <div class="col-xs-10">
                 <form action="{{URL::to('manage/order/show')}}" method="get">
                     <div class="form-group">
                         <div class="row">
                             <div class="col-sm-3">
                                <input type="text" class="form-control"  name="keyword" placeholder="发货单流水号 或 订单号 或 收货人 查询">
                             </div>
                             <div class="col-xs-6">
                                 <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                             </div>
							  <div class="col-sm-2"></div>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="col-xs-2"></div>
         </div>
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th><label><input type="checkbox" onclick="checkall(this)" class="multi_checked"><span class="text"></span></label>&nbsp;&nbsp;&nbsp;发货单流水号</th>
        <th>订单号</th>
        <th>下单时间</th>
        <th>收货人</th>
        <th>发货时间</th>
		<th>快递类型</th>
        <th>发货单状态</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($info as $list)
            <tr>
                <td>
					<label><input type="checkbox" name="checkboxes[]" value="{{ $list->delivery_id }}" class="multi_checked"><span class="text"></span>&nbsp;&nbsp;&nbsp;{{ $list->delivery_sn }}</label>
                </td>
                <td>{{ $list->order_sn }}</td>
                <td>{{ $list->add_time }}</td>
                <td>{{ $list->consignee }}</td>
                <td>{{ $list->update_time }}</td>
				 <td>{{ $list->shipping_name }}</td>
                <td>
                    @if($list->status == 0) 已发货 @elseif($list->status == 1) 退货 @else 正常 @endif
                </td>
                <td>
                    <a href="{{URL::to('manage/order/delivery_list_view')}}/{{ $list->delivery_id }}" class="btn btn-success btn-xs">
                        <i class="glyphicon glyphicon-search"></i>查看
                    </a>
                    <button class="btn btn-danger  btn-xs "  type="button"  onclick="if (confirm('确定删除吗?')) {
                            $(this).parent().hide().next().show();
                            var obj = $(this);
                            $.get('{{URL::to('manage/order/del_delivery')}}/{{ $list->delivery_id }}', function(response){
                            obj.parents('tr:first').remove();
                            });
                            }"> <i class="glyphicon glyphicon-trash"></i> 移除</button>
					<div></div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <div class="row text-left" >
            <div class="col-xs-1">
                 <button onclick="button()" class="btn btn-info"> <i class="glyphicon glyphicon-trash"></i> 移除</button>
            </div>
            <div class="col-xs-11 text-right">
                {!! $info->appends(['keyword' =>$keyword])->render() !!}
            </div>
        </div>
    </div>
</div>
<script>
    function checkall(a){
        if($(a).prop("checked")){
            $("input[name='checkboxes[]']").prop("checked",true);
        }else{
            $("input[name='checkboxes[]']").prop("checked",false);
        }
    }
    function button(){
        var delivery_id=new Array();
        $('input[name="checkboxes[]"]:checked').each(function(){
            delivery_id.push($(this).val());
        });
        if(delivery_id == ''){
            alert('请你选择操作对象！');
            return false;
        }
      $.ajax({
            url:"/manage/order/del_all_delivery",
            data:{"delivery_id":delivery_id},
            type:"get",
            success:function(e){
                alert(e);
                setTimeout(window.location.href='/manage/order/show',100);
            }
        });
    }
</script>
@stop







