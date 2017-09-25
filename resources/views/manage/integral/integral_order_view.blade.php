@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>积分订单表</li>
            <li class="active">积分订单信息</li>
        </ol>
    </div>
    <div class="panel-body">
    </div>
    <form method="post"  class="form-horizontal form-bordered" >
     <div class="form-group">
			<div class="col-sm-2 col-xs-2 col-md-2"></div>
            <div class="col-sm-8 col-xs-8 col-md-8">
                <div class="table-responsive">
				<input type="hidden" name="id" id="id" value="{{ $info_integral->id }}">
                  <table class="table  table-bordered table-striped float">
                    <tbody>
                        <tr>
                         <td colspan="4" align="center"> 基本信息</td>
                        </tr>
						<tr>
							 <td align="right">订单号：</td>
							 <td>{{ $info_integral->order_sn }}</td>
							 <td align="right">订单状态：</td>
							 <td>
								@if( $info_integral->shipping_status == 0) 未发货 @elseif( $info_integral->shipping_status == 1) 已发货 @elseif( $info_integral->shipping_status == 2) 收货确认 @else 备货中  @endif
							 </td>
                         </tr>
						 <tr>
							 <td align="right">购货人：</td>
							 <td>{{ $info_integral->username }}</td>
							 <td align="right">下单时间：</td>
							 <td>{{ $info_integral->created_at }}</td>
                         </tr>
						 <tr>
							 <td align="right">发货单号：</td>
							 <td>{{ $info_integral->shipping_number }}</td>
							 <td align="right">发货时间：</td>
							 <td>@if( $info_integral->integral == 0) 未发货 @else {{ $info_integral->shipping_time }}  @endif</td>
                        </tr>
						<tr>
							<td align="right">配送方式：</td>
							<td>{{ $info_integral->shipping_name }}</td>
							 <td align="right">收货人信息：</td>
							 <td >
								{{ $info_integral->address }}
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
					@if($prize == 0)
					  <table class="table  table-bordered table-striped">
						<thead>
						<th>商品名称</th>
						<th>积分</th>
						<th>数量</th>
						<th>库存</th>
						</thead>
						<tbody>
							@foreach ($info_integral_goods as $key=>$val)
							<tr>
								 <td>{{ $val->name }}</td>
								 <td>{{ $val->integral }}</td>
								 <td>{{ $val->goods_all_number }}</td>
								 <td>{{ $val->goods_number }}</td>
							</tr>
							@endforeach
						</tbody>
					  </table>
					@else
						<table class="table  table-bordered table-striped">
							<thead>
							<th>奖品名称</th>
							<th>奖品图片</th>
							<th>兑换奖品所需碎片</th>
							<th>奖品添加时间</th>
							</thead>
							<tbody>
							@foreach ($info_integral_goods as $key=>$val)
								<tr>
									<td>{{ $val->name }}</td>
									<td>
										@if(!empty($val->image))
											<a href="{{ $val->image }}" target="_break" >
												<img src="{{ $val->image }}?imageView2/2/w/50" alt="..." class="img-thumbnail">
											</a>
										@else
											<a href="{{$_ENV['QINIU_HOST']}}/1465286688.3069.png" target="_break" >
												<img src="{{$_ENV['QINIU_HOST']}}/1465286688.3069.png?imageView2/2/w/50" alt="..." class="img-thumbnail">
											</a>
										@endif
									</td>
									<td>{{ $val->fragment }}</td>
									<td>{{ $val->created_time }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					@endif
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
							<td align="right" align="right" width="15%"> 当前可执行操作</td>
							<td>
								@if( $info_integral->shipping_status == 0)
								<div>
									<div class="col-sm-3">
										<input type="text" class="form-control"  name="shipping_num" id="shipping_num" placeholder="请输入快递单号">
									</div>
									<div class="col-sm-2">
										<select name="shipping_express" id="shipping_express" class="form-control">
											<option value=""  @if( $info_integral->username != '顺丰速运' ) selected @endif>快递选择......</option>
											<option value="圆通速递">圆通速递</option>
											<option value="申通快递">申通快递</option>
											<option value="中通速递">中通速递</option>
											<option value="韵达快递">韵达快递</option>
											<option value="天天快递">天天快递</option>
											<option value="顺丰速运" @if( $info_integral->username == '顺丰速运' ) selected @endif>顺丰速运</option>
											<option value="中国邮政">中国邮政</option>
											<option value="宅急送">宅急送</option>
											<option value="百世汇通">百世汇通</option>
											<option value="全峰快递">全峰快递</option>
											<option value="EMS">EMS</option>
										</select>
									</div>
									<div class="col-xs-6">
										<button type="button" onclick='operation()' class="btn btn-info">一键发货</button>
									</div>
								</div>
								@endif
							</td> 
                        </tr>
                    </tbody>
                  </table>
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
function operation(){
	id = $("#id").val();
	shipping_number = $("#shipping_num").val();
	shipping_express = $("#shipping_express").val();
	if(!shipping_number){
		alert("请填写单号！");
		return false;
	}
	if(!shipping_express){
		alert("请填写快递类型！");
		return false;
	}
	$.ajax({
		url:"/manage/integral/operation",
		data:{"shipping_express":shipping_express,"shipping_number":shipping_number,"id":id},
		type:"post",
		success:function(e){
			alert("发货成功成功！");
			location.href = "/manage/integral/ordershow";
		}
	});
}
</script>
@stop

