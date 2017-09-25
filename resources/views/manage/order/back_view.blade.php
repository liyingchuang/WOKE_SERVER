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
						<table class="table  table-bordered table-striped float">
							<tbody>
							<tr>
								<td colspan="4" align="center"> 原订单基本信息</td>
							</tr>
							<tr>
								<td align="right">订单号：</td>
								<td>{{ $info->order_sn }}</td>
								<td align="right">下单时间：</td>
								<td>{{ $info->add_time }}</td>
							</tr>
							<tr>
								<td align="right">服务类型：</td>
								<td>@if( $info_back->back_pay == 1) 退款至账户余额 @else 原支付方式返回 @endif</td>
								<td align="right">退款方式：</td>
								<td>@if( $info_back->back_type == 1) 退货  @elseif( $info_back->back_type == 2) 换货 @elseif( $info_back->back_type == 3) 维修 @else 退款（无需退货） @endif</td>
							</tr>
							<tr>
								<td align="right">购货人：</td>
								<td>{{ $info->consignee }}</td>
								<td align="right">配送方式：</td>
								<td>{{ $info->shipping_name }}</td>
							</tr>
							<tr>
								<td align="right">发货单号：</td>
								<td>{{ $info->invoice_no }}</td>
								<td align="right">发货时间：</td>
								<td>{{ $info->shipping_time }}</td>
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
								<td colspan="4" align="center"> 退款/退货/返修信息</td>
							</tr>
							<tr>
								<td align="right">申请退货/维修时间：</td>
								<td>{{ $info_back->add_time }}</td>
								<td align="right">申请人用户名：</td>
								<td>{{ $info_back->consignee }}</td>
							</tr>
							<tr>
								<td align="right">换回商品收件人：</td>
								<td>{{ $info_back->consignee }}</td>
								<td align="right">联系电话：</td>
								<td>{{ $info_back->mobile }}</td>
							</tr>
							<tr>
								<td align="right">换回商品收货人地址：</td>
								<td>[{{ $info_back->country }} {{ $info_back->province }} {{ $info_back->city }} {{ $info_back->district }}] {{ $info_back->address }}</td>
								<td align="right">用户退回商品所用快递：</td>
								<td>{{ $info_back->invoice_no }}</td>
							</tr>
							<tr>
								<td align="right">退货原因：</td>
								<td colspan="3"><font color="red">{{ $info_back->back_reason }}</font></td>
							</tr>
							<tr>
								<td align="right">买家退货邮寄地址：</td>
								<td colspan="3"><font color="red">{{ $info_back->address }}</font></td>
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
								<td align="center"> 退货/返修-商品信息</td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="table-responsive" style="margin-top:0px;">
						<table class="table  table-bordered table-striped">
							<thead>
							<th>商品名称 [ 品牌 ]</th>
							<th>商品编号</th>
							<th>货号</th>
							<th>业务</th>
							<th>应退金额</th>
							<th>数量</th>
							</thead>
							<tbody>
							@foreach ($info_shop as $info_shop)
								<tr>
									<td>{{ $info_shop->goods_name }}</td>
									<td>{{ $info_shop->goods_id }}</td>
									<td>{{ $info_shop->goods_sn }}</td>
									<td>@if( $info_shop->back_type == 0) 退货-退回  @else 退货-退款 @endif</td>
									<td>{{ $info_shop->back_goods_price }}</td>
									<td>{{ $info_shop->back_goods_number }}</td>
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

										<input type="hidden"  data-backdrop="static" data-toggle="modal" data-target="#myModals" id="model" >
										<button type="button" class="btn btn-info"  onclick="if (confirm('注意，选择直接退款后，将跳过其他审核步骤直接进行退款，请确认是否直接退款!')) {
											main = $('#remarks').val();
											if(main == ''){
												alert('操作备注不能为空！');
												return false;
											}
												remarks_view = $('#remarks').val();
												$('#remarks_view').val(remarks_view);
												$('#model').click();
											}">直接退款</button>

										<button type="button" class="btn btn-info"  onclick="if (confirm('注意，选择直接退款后，将跳过其他审核步骤直接进行退款，请确认是否直接退款!')) {
												$.get('{{URL::to('manage/order/del_back')}}/{{ $info_back->back_id }}', function(response){
													location.href='/manage/order'
												});
									}">取消退款</button>
									@if($info_back->status_back !=4 && $info_back->status_refund == 0)
									@endif
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
							<th>退换货状态</th>
							<th>退款状态</th>
							<th>备注</th>
							</thead>
							<tbody>
							@foreach ($info_action as $info_action)
								<tr>
									<td>{{ $info_action->action_user }}</td>
									<td>{{ $info_action->log_time }}</td>
									<td>@if( $info_action->status_back == 6) 此单已被管理员拒绝 @elseif( $info_action->status_back == 7) 此单已被系统取消 @elseif( $info_action->status_back == 8) 此单已被用户自行取消 @else 申请审核中  @endif</td>
									<td>@if( $info_action->status_refund == 0) 未退款 @else 退款成功 @endif</td>
									<td>{{ $info_action->action_note }}</td>
								</tr>
							@endforeach
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

	<div class="modal fade" id="myModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">退货单操作：</h4>
				</div>
				<form method="post"  id="addForm" action="{{URL::to('manage/order/back_money')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
					<div class="modal-body">
						<div class="table-responsive">
							<table class="table  table-bordered table-striped float">
								<tbody>
								<tr>
									<td rowspan="4" width="20%" align="right"> 操作流程: </td>
								<tr>
								<tr>
									<td colspan="6"><p><font color='red'>1.请先到 <a href="https://e.alipay.com/index.htm" target="_blank">{{$info_back_goods->pay_name}}</a> 完成退款</font></p> </td>
								</tr>
								<tr>
									<td colspan="6"><p><font color='red'>2.点击本页面确定按钮</font></p></td>
								</tr>
								</tbody>
							</table>

							<table class="table  table-bordered table-striped float" style="margin-top:20px;">
								<tbody>
								<tr>
									<td width="20%" align="right">商家订单号：</td>
									<td>{{ $info->order_sn }}</td>
								<tr>
								<tr>
									<td align="right"> 退款金额：</td>
									<td>
										商品价格：<input type="text" id="goods_amount" name="goods_amount" size="3" value="{{$info_back_goods->goods_amount}}" />
										运费：<input type="text" id="shipping_fee" name="shipping_fee" size="3" value="0.00" />
										<input type="hidden" id="shipping_fee_hide" value="{{$info_back_goods->shipping_fee}}">
										<input type="hidden" id="back_id" name="back_id" value="{{$info_back->back_id}}">
										是否退运费：
										<label style="padding-top:5px;">
											<input class="checkbox-slider colored-blue yesno" title="{{$info_back_goods->order_id}}" type="checkbox" @if(!$info_back_goods->order_id) checked @endif>
											<span class="text"></span>
										</label>
									</td>
								</tr>
								<tr>
									<td align="right"> 操作备注：</td>
									<td><input type="text" id="remarks_view" name="remarks_view" placeholder="操作备注" class="form-control" value="" /></td>
								</tr>
								</tbody>
							</table>
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
				express_type = $("#express_type").val();
				if(type == 'express' ){
					if(!invoice_no){
						alert("请填写单号！");
						return false;
					}
					if(!express_type){
						alert("请选择快递类型！");
						return false;
					}
				}
				$.ajax({
					url:"/manage/order/delivery_operation",
					data:{"type":type,"delivery_id":delivery_id,"invoice_no":invoice_no,"express_type":express_type},
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

