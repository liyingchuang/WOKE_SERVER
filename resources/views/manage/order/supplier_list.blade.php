@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
       订单管理
    </div>
	 <div class="panel-body">
         <div class="row " >
             <div class="col-xs-10">
                 <form action="{{URL::to('manage/order/supplier_list_view')}}" method="get">
                     <div class="form-group">
                         <div class="row">
                             <div class="col-sm-3">
                                 <input type="text" class="form-control"  name="keyword" placeholder="订单号 或 收货人 查询">
                             </div>
                             <div class="col-sm-2">
                                 <select name="status" class="form-control">
                                     <option value="" selected>订单状态请选择......</option>
                                     <option value="1">待确认</option>
                                     <option value="2">待付款</option>
                                     <option value="3">待发货</option>
                                     <option value="4">已完成</option>
                                     <option value="5">已付款</option>
                                     <option value="6">取消</option>
                                     <option value="7">无效</option>
                                     <option value="8">退货</option>
                                 </select>
                             </div>
                             <div class="col-xs-6">
                                 <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                             </div>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="col-xs-2"></div>
         </div>
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th><label><input type="checkbox" onclick="checkall(this)" class="multi_checked"><span class="text"></span></label>&nbsp;&nbsp;&nbsp;订单号</th>
		<th>供货商家</th>
        <th>下单时间</th>
        <th>收货人</th>
        <th>总金额</th>
        <th>应付金额</th>
        <th>订单来源</th>
        <th>订单状态</th>
        </thead>
        <tbody>
            @foreach ($info as $list)
            <tr>
                <td>
                    <label><input type="checkbox" name="checkboxes[]" value="{{ $list->order_id }}" class="multi_checked"><span class="text"></span></label>
                    &nbsp;&nbsp;&nbsp;{{ $list->order_sn }}
                </td>
				<td>{{ $list->referer }}</td>
                <td>{{ $list->add_time }}</td>
                <td><font color='red'>{{ $list->consignee }}</font>{{ $list->address }}</td>
                <td>{{ $list->inv_money }}</td>
                <td>{{ $list->order_amount }}</td>
                <td>{{ $list->froms }}</td>
                <td>
                    @if( $list->order_status == 0) 未确认 @elseif( $list->order_status == 1) 已确认 @elseif( $list->order_status == 2) <font color="red">取消</font> @elseif( $list->order_status == 3) <font color='red'>无效</font> @elseif( $list->order_status == 4)  退货 @else  已分单 @endif，
                    @if( $list->pay_status == 0) 未付款 @elseif( $list->pay_status == 1) 付款中 @else 已付款   @endif，
                    @if( $list->shipping_status == 0) 未发货 @elseif( $list->shipping_status == 1) 已发货 @elseif( $list->shipping_status == 2) 收货确认 @else 已收货  @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <div class="row text-left" >
            <div class="col-xs-5">
                <button onclick="button('order_status',1)" class="btn btn-info"> <i class="glyphicon glyphicon-ok"></i> 确认</button>
                <button onclick="button('order_status',2)" class="btn btn-info"> <i class="glyphicon glyphicon-remove"></i> 取消</button>
				<button onclick="button('order_status',3)" class="btn btn-info"> <i class="glyphicon glyphicon-move"></i> 无效</button>
            </div>
            <div class="col-xs-7 text-right">
                {!! $info->appends(['keyword' =>$keyword , 'status' =>$status])->render() !!}
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
    function button(operation,operations){
        var order_id=new Array();
        $('input[name="checkboxes[]"]:checked').each(function(){
            order_id.push($(this).val());
        });
        if(order_id == ""){
            alert('请你选择操作对象！');
            return false;
        }
        $.ajax({
            url:"/manage/order/create",
            data:{"operation":operation,"operations":operations,"order_id":order_id},
            type:"get",
            success:function(e){
				if(e == 1){
					alert("部分订单订单无法确认！");
				}else if(e == 2){
					alert("部分订单订单无法取消！");
				}else if(e == 3){
					alert("部分订单订单无法设置无效！");
				}else{
					alert("操作成功，稍后刷新！");
					setTimeout(window.location.href='/manage/order/supplier_list_view',100);
				} 
            }
        });
    }
</script>
@stop







