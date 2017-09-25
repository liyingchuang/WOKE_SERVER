@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
       订单管理
    </div>
	 <div class="panel-body">
         <div class="row " >
             <div class="col-xs-10">
                 <form action="{{URL::to('manage/order/back_list')}}" method="get">
                     <div class="form-group">
                         <div class="row">
                             <div class="col-sm-3">
                                <input type="text" class="form-control"  name="keyword" placeholder="原来订单号 或 收货人 查询">
                             </div>
                             <div class="col-sm-2">
                                 <select name="status" class="form-control">
                                     <option value="" selected>订单状态请选择......</option>
                                     <option value="1">退款已完成</option>
                                     <option value="2">退款未完成</option>
                                 </select>
                             </div>
                             <div class="col-xs-1">
                                 <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                             </div>
							 <div class="col-xs-6"></div>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="col-xs-2"></div>
         </div>
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th><label><input type="checkbox" onclick="checkall(this)" class="multi_checked"><span class="text"></span></label>&nbsp;&nbsp;&nbsp;序号</th>
        <th>原订单号</th>
        <th>退货/返修商品</th>
        <th>申请时间</th>
        <th>应退金额</th>
        <th>实退金额</th>
        <th>收货人</th>
        <th>退换状态</th>
        <th>申请人</th>
		<th>操作</th>
        </thead>
        <tbody>
            @foreach ($info as $list)
            <tr>
                <td>
                    <label><input type="checkbox" name="checkboxes[]" value="{{ $list->back_id }}" class="multi_checked"><span class="text"></span></label>
                    &nbsp;&nbsp;&nbsp;{{ $list->back_id }}
                </td>
				<td>{{ $list->order_sn }}</td>
                <td><font color="red">{{ $list->goods_id }}</font>{{ $list->goods_name }}</td>
                <td>{{ $list->add_time }}</td>
                <td>{{ $list->refund_money_1 }}</td>
                <td>{{ $list->refund_money_2 }}</td>
                <td><font color="red">{{ $list->consignee }}</font>{{ $list->address }}</td>
                <td>
					@if( $list->status_back == 0) 审核通过 @elseif( $list->status_back == 1) 收到寄回商品 @elseif( $list->status_back == 2) 换回商品已寄出 @elseif( $list->status_back == 3) 完成退货/返修 @elseif( $list->status_back == 4) 退款(无需退货) @elseif( $list->status_back == 5) 审核中 @elseif( $list->status_back == 6) 申请被拒绝 @elseif( $list->status_back == 7) 管理员取消 @else 用户自己取消  @endif
					@if( $list->status_refund == 0) 未退款 @else 已退款  @endif
				</td>
                <td>{{ $list->consignee }}</td>
                <td>
					<a class="btn btn-success btn-xs"  href="{{URL::to('manage/order/back_list_view')}}/{{ $list->back_id }}"><i class="glyphicon glyphicon-search"></i>查看</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <div class="row text-left" >
            <div class="col-xs-1">
                <button onclick="button('order_status',1)" class="btn btn-info"> <i class="glyphicon glyphicon-trash"></i> 移除</button>
            </div>
            <div class="col-xs-11 text-right">
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
    function button(){
        var back_id=new Array();
        $('input[name="checkboxes[]"]:checked').each(function(){
            back_id.push($(this).val());
        });
        if(back_id == ''){
            alert('请你选择操作对象！');
            return false;
        }
      $.ajax({
            url:"/manage/order/del_all_back",
            data:{"back_id":back_id},
            type:"get",
            success:function(e){
                alert(e);
                setTimeout(window.location.href='/manage/order/back_list',100);
            }
        });
    }
</script>
@stop







