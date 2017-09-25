@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>订单列表</li>
            <li class="active">订单信息</li>
        </ol>
    </div>
    <div class="panel-body">
    </div>
    <form method="post"  class="form-horizontal form-bordered" >
     <div class="form-group">
			<div class="col-sm-2 col-xs-2 col-md-2"></div>
            <div class="col-sm-8 col-xs-8 col-md-8">
                <div class="table-responsive">
				<input type="hidden" name="order_id" id="order_id" value="{{ $info_basic->order_id }}">
				<input type="hidden" name="delivery_id" id="delivery_id" value="{{ $info_delivery->delivery_id }}">
                  <table class="table  table-bordered table-striped float">
                    <tbody>
                        <tr>
                         <td colspan="4" align="center"> 基本信息</td>
                        </tr>
						<tr>
							 <td align="right">发货单流水号：</td>
							 <td>{{ $info_delivery->delivery_sn }}</td>
							 <td align="right">发货时间：</td>
							 <td>{{ $info_delivery->add_time }}</td>
                         </tr>
						 <tr>
							 <td align="right">订单号：</td>
							 <td>{{ $info_delivery->order_sn }}</td>
							 <td align="right">下单时间：</td>
							 <td>{{ $info_delivery->update_time }}</td>
                         </tr>
						 <tr>
							 <td align="right">购货人：</td>
							 <td>{{ $info_delivery->action_user }}</td>
							 <td align="right">配送费用：</td>
							 <td>{{ $info_delivery->shipping_fee }}</td>
                        </tr>
						<tr>
							 <td align="right">收货人信息：</td>
							 <td colspan="3">
								{{ $info_delivery->consignee }} - [{{ $info_delivery->country }} {{ $info_delivery->province }} {{ $info_delivery->city }} {{ $info_delivery->district }}] {{ $info_delivery->address }}-{{ $info_delivery->mobile}}
							</td> 
                        </tr>
						<tr>
							 <td align="right">发货单号：</td>
							 <td colspan="3">
								<div class="col-sm-2">
										<select name="shipping_express" id="shipping_express" class="form-control">
											<option value="tongcheng" @if( $info_delivery->shipping_express == 'tongcheng' ) selected @endif>同城配送</option>
											<option value="zhongtong" @if( $info_delivery->shipping_express == 'zhongtong' ) selected @endif>中通速递</option>
											<option value="guotongkuaidi" @if( $info_delivery->shipping_express == 'guotongkuaidi' ) selected @endif>国通快递</option>
											<option value="shunfeng" @if( $info_delivery->shipping_express == 'shunfeng' ) selected @endif>顺丰速运</option>
											<option value="youzhengguonei" @if( $info_delivery->shipping_express == 'youzhengguonei' ) selected @endif>邮政小包</option>
											<option value="other" @if( $info_delivery->shipping_express == 'other' ) selected @endif>其他</option>
										</select>
									</div>
									<div class="col-xs-2">
										<input type="text" id="invoice_no" name="invoice_no" placeholder="快递单号" class="form-control" value="{{ $info_delivery->invoice_no }}" />
									</div>
									<div class="col-xs-4">
										<button type="button" onclick='operation("express")' class="btn btn-info">修改快递单号</button>
									</div>
							 </td> 
                        </tr>
                    </tbody>
                  </table>
                </div>
            </div>
            <div class="col-sm-2 col-xs-2 col-md-2"></div>
        </div> 
		<div class="form-group">
             <div class="col-sm-2 col-xs-2 col-md-2"></div>
            <div class="col-sm-8 col-xs-8 col-md-8">
				<div class="table-responsive">
                  <table class="table  table-bordered table-striped float">
                    <tbody>
                        <tr>
                         <td align="center"> 商品信息</td>
                        </tr>
                    </tbody>
                  </table>
                </div>
                <div class="table-responsive" style="margin-top:0px;">
                  <table class="table  table-bordered table-striped">
                    <thead>
                    <th>商品名称 [ 品牌 ]</th>
                    <th>货号</th>
                    <th>价格</th>
                    <th>发货数量</th> 
                    </thead>
                    <tbody>
						@foreach ($info_shop as $info_shop)
                        <tr>
							 <td>{{ $info_shop->goods_name }}</td>
							 <td>{{ $info_shop->goods_sn }}</td>
							 <td>{{ $info_shop->goods_price }}</td>
							 <td>{{ $info_shop->goods_number }}</td>
                        </tr>
						@endforeach
                    </tbody>
                  </table>
                    <br>
                </div>
            </div>
            <div class="col-sm-2 col-xs-2 col-md-2"></div>
        </div> 
		<div class="form-group">
             <div class="col-sm-2 col-xs-2 col-md-2"></div>
            <div class="col-sm-8 col-xs-8 col-md-8">
				 <div class="table-responsive">
                  <table class="table  table-bordered table-striped float">
                    <tbody>
                        <tr>
                         <td align="center" colspan="2"> 操作信息</td>
                        </tr>
						<tr>
							<td align="right" width="15%"> 操作备注</td>
							<td><input type="text" id="remarks" name="remarks" placeholder="操作备注" class="form-control" value="" /></td> 
                        </tr>
						<tr>
							<td align="right"> 当前可执行操作</td>
							<td>
								<button type="button" onclick='operation("yes")' class="btn btn-info" @if(!$info_basic->shipping_status == 0) style="display:none;" @endif>发货</button>
								<button type="button" onclick='operation("no")' class="btn btn-info" @if(!$info_basic->shipping_status == 1) style="display:none;" @endif>取消发货</button>
							</td> 
                        </tr>
                    </tbody>
                  </table>
                </div>
                <div class="table-responsive" style="margin-top:10px;">
                  <table class="table  table-bordered table-striped">
                    <thead>
                    <th>操作者</th>
                    <th>操作时间</th>
                    <th>订单状态</th>
                    <th>付款状态</th>
                    <th>发货状态</th> 
					<th>备注</th> 
                    </thead>
                    <tbody>		
                        <tr>
							 <td>{{ $info_order->action_user }}</td>
							 <td>{{ $info_order->log_time }}</td>
							 <td>@if( $info_order->order_status == 0) 未确认 @elseif( $info_order->order_status == 1) 已确认 @elseif( $info_order->order_status == 2) <font color="red">取消</font> @elseif( $info_order->order_status == 3) <font color='red'>无效</font> @else 退货 @endif</td>
							 <td>@if( $info_order->pay_status == 0) 未付款 @elseif( $info_order->pay_status == 1) 已付款 @elseif( $info_order->pay_status == 2) 已收货 @else 备货中 @endif</td>
							 <td>@if( $info_order->shipping_status == 0) 未发货 @elseif( $info_order->shipping_status == 1) 已发货 @elseif( $info_order->shipping_status == 2) 已收货 @else 备货中  @endif</td>
							 <td>{{ $info_order->action_note }}</td>
                        </tr>
                    </tbody>
                  </table>
                    <br>
                </div>
            </div>
            <div class="col-sm-2 col-xs-2 col-md-2"></div>
        </div>  
    </form>
    <div class="panel-footer"> 
        <div class="row " >
            <div class="col-sm-offset-10 col-sm-2  col-xs-10 col-md-10 col-md-2 col-xs-2">
            </div>
        </div>
    </div>
</div>
<script>
function operation(type){
	remarks = $("#remarks").val();
	order_id = $("#order_id").val();
	delivery_id = $("#delivery_id").val();
	if(type == 'yes' || type == 'no'){
		if(!remarks){
			alert("请填写操作备注！");
			return false;
		}
		$.ajax({
			url:"/manage/order/delivery_operation",
			data:{"type":type,"remarks":remarks,"order_id":order_id,"delivery_id":delivery_id},
			type:"post",
			success:function(e){
				if(e == 1){
					alert("成功发货！");
					location.href="/manage/order/delivery_list_view/"+delivery_id;
				}
				else if(e == 2){
					alert("取消发货成功！");
					location.href="/manage/order/delivery_list_view/"+delivery_id;
				}
			}
		});		
	}else{
		invoice_no = $("#invoice_no").val();
		shipping_express = $("#shipping_express").val();
		if(type == 'express' ){
				if(!invoice_no){
				alert("请填写单号！");
				return false;
			}
			if(!shipping_express){
				alert("请选择快递类型！");
				return false;
			}
		}
		$.ajax({
			url:"/manage/order/delivery_operation",
			data:{"type":type,"delivery_id":delivery_id,"invoice_no":invoice_no,"shipping_express":shipping_express},
			type:"post",
			success:function(e){
				 if(e == 3){
					alert("修改成功！");
					"/manage/order/delivery_list_view/"+delivery_id;
				}
			}
		});		
	}
}
$('.remove_img').click(function() {
	var id = $(this).attr('alt');
	$.get("{{URL::to('manage/show')}}/del/" + id, function(result) {
	});
	$(this).parents('tr:first').remove();
});
$('.remove').click(function() {
	var id = $(this).attr('alt');
	$.get("{{URL::to('manage/show')}}/del/" + id+'?type=report', function(result) {
	});
	$(this).parents('tr:first').remove();
});
</script>
@stop

