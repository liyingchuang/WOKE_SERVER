@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>订单管理</li>
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
				<input type="hidden" name="order_amount" id="order_amount" value="{{ $info_basic->order_amount }}">
                  <table class="table  table-bordered table-striped float">
                    <tbody>
                        <tr>
                         <td colspan="4" align="center"> 基本信息</td>
                        </tr>
						<tr>
							 <td align="right">订单号：</td>
							 <td>{{ $info_basic->order_sn }}</td>
							 <td align="right">订单状态：</td>
							 <td>
								 @if( $info_basic->order_status == 0) 未确认 @elseif( $info_basic->order_status == 1) 已确认 @elseif( $info_basic->order_status == 2) <font color="red">取消</font> @elseif( $info_basic->order_status == 3) <span color='red'>无效</span> @elseif( $info_basic->order_status == 4) 退货 @else  已分单 @endif，
								@if( $info_basic->pay_status == 0) 未付款 @elseif( $info_basic->pay_status == 1) 付款中  @else  已付款 @endif，
								@if( $info_basic->shipping_status == 0) 未发货 @elseif( $info_basic->shipping_status == 1) 已发货 @elseif( $info_basic->shipping_status == 2) 收货确认 @else 备货中  @endif
							 </td>
                         </tr>
						 <tr>
							 <td align="right">购货人：</td>
							 <td>{{ $info_basic->consignee }}</td>
							 <td align="right">下单时间：</td>
							 <td>{{$info_basic->add_time}}</td>
                         </tr>
						 <tr>
							 <td align="right">支付方式：</td>
							 <td>@if( $info_basic->pay_name =='wx')微信支付@endif @if( $info_basic->pay_name =='alipay')支付宝@endif</td>
							 <td align="right">付款时间：</td>
							 <td>@if( $info_basic->pay_status == 0 || $info_basic->pay_status == 1) 未付款 @else {{$info_basic->pay_time}}@endif</td>
                        </tr>
						 <tr>
							 <td align="right">配送方式：</td>
							 <td>{{ $info_basic->shipping_name }}</td>
							 <td align="right">发货时间：</td>
							 <td>@if( $info_basic->shipping_status == 0) 未发货 @else {{ $info_basic->shipping_time }}@endif</td>
                        </tr>
						<tr>
							 <td align="right">发货单号：</td>
							 <td>{{ $info_basic->shipping_express }} {{ $info_basic->invoice_no }} </td>
							 <td align="right">备注：</td>
							 <td>{{ $info_basic->postscript }} 发票抬头:{{$info_basic->vat_inv_company_name}} 税号/身份证：{{$info_basic->vat_inv_taxpayer_id}} </td>
                        </tr>
						<tr>
							 <td align="right">收货人信息：</td>
							 <td colspan="3">
								[{{ $info_basic->province }}-{{ $info_basic->city }}-{{ $info_basic->district }}-{{ $info_basic->address }}] {{ $info_basic->mobile}}
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
                    <th>数量</th>
					<th>库存</th>
					<th>小计</th>
                    </thead>
                    <tbody>
						@foreach ($info_shop as $info_shop)
                        <tr>
							 <td>{{ $info_shop->goods_name }}{{ $info_shop->goods_attr }}</td>
							 <td>{{ $info_shop->goods_sn }}</td>
							 <td>{{ $info_shop->goods_price }}</td>
							 <td>{{ $info_shop->goods_number }}</td>
							 <td>{{ $info_shop->surplus }}</td>
							 <td>{{ $info_shop->subtotal }}</td>
                        </tr>
						@endforeach
						<tr>
							<td colspan="7" align="right">合计：{{ $info_basic->goods_amount }}</td>
						</tr>
						<tr>
							 <td colspan="7" align="right">商品总金额：{{ $info_basic->goods_amount }}  </td>
                        </tr>
                        <tr>
                            <td colspan="7" align="right">订单总金额： 商品总金额({{ $info_basic->goods_amount }})+ 快递费({{ $info_basic->shipping_fee  }}) ={{ $info_basic->order_amount }}</td>
                        </tr>
						<tr>
							 <td colspan="7" align="right"> 酒币使用额：{{ $info_basic->integral_money  }} 付款金额：<font color="red">{{ $info_basic->order_amount-$info_basic->integral_money }}</font></td>
                        </tr>
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
								<button type="button"   class="btn btn-info delete" @if($info_basic->order_status == 0 || $info_basic->order_status == 1 || $info_basic->order_status == 5 || $info_basic->order_status == 4 && $info_basic->shipping_status == 3) style="display:none;" @endif>移除</button>
								<button type="button"   class="btn btn-info yes" @if($info_basic->order_status == 2 || $info_basic->order_status == 3 ||$info_basic->pay_status == 1 || $info_basic->pay_status == 2 ) style="display:none;" @endif>付款</button>
								<button type="button"   class="btn btn-info no" @if($info_basic->order_status == 2 || $info_basic->order_status == 3 ||$info_basic->pay_status == 1 || $info_basic->pay_status == 2 ) style="display:none;" @endif>取消</button>
								<div @if( $info_basic->pay_status == 0 || $info_basic->pay_status == 1 || $info_basic->shipping_status == 1 || $info_basic->shipping_status == 2 || $info_basic->shipping_status == 3 || $info_basic->order_status == 2 || $info_basic->order_status == 3) style="display:none;" @endif>
									<div class="col-sm-3">
										<input type="text" class="form-control"  name="invoice_no" id="invoice_no" placeholder="请输入快递单号"  @if( $info_basic->invoice_no) value="{{$info_basic->invoice_no}}" @endif>
									</div>
									<div class="col-sm-2">
										<select name="shipping_express" id="shipping_express" class="form-control">
											<option value="tongcheng" @if( $info_basic->shipping_express == 'tongcheng' ) selected @endif>同城配送</option>
											<option value="zhongtong" @if( $info_basic->shipping_express == 'zhongtong' ) selected @endif>中通速递</option>
											<option value="guotongkuaidi" @if( $info_basic->shipping_express == 'guotongkuaidi' ) selected @endif>国通快递</option>
											<option value="shunfeng" @if( $info_basic->shipping_express == 'shunfeng' ) selected @endif>顺丰速运</option>
											<option value="youzhengguonei" @if( $info_basic->shipping_express == 'youzhengguonei' ) selected @endif>邮政小包</option>
											<option value="other" @if( $info_basic->shipping_express == 'other' ) selected @endif>其他</option>
										</select>
									</div>
									<div class="col-xs-6">
										<button type="button"  class="btn btn-info express">一键发货</button>
									</div>
								</div>
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
						@foreach ($info_order as $info_order)
                        <tr>
							 <td>{{ $info_order->action_user }}</td>
							 <td>{{ $info_order->log_time }}</td>
							<td>@if( $info_order->order_status == 0) 未确认 @elseif( $info_order->order_status == 1) 已确认 @elseif( $info_order->order_status == 2) <span style=" color:red">取消</span> @elseif( $info_order->order_status == 3) <span style=" color:red">无效</span> @elseif( $info_order->order_status == 4)  退货  @elseif( $info_order->order_status == 4)  退货 @endif</td>
							 <td>@if( $info_order->pay_status == 0) 未付款 @elseif( $info_order->pay_status == 1) 付款中 @else 已付款 @endif</td>
							 <td>@if( $info_order->shipping_status == 0) 未发货 @elseif( $info_order->shipping_status == 1) 已发货 @elseif( $info_order->shipping_status == 2) 已收货 @else 备货中  @endif</td>
							 <td>{{ $info_order->action_note }}</td>
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
<script>
$('.yes').click(function() {
    var    remarks = $("#remarks").val();
    var    order_id = $("#order_id").val();
    var    invoice_no = $("#invoice_no").val();
    var    shipping_express = $("#shipping_express").val();
    var    order_amount = $("#order_amount").val();
    if(!remarks){
        alert("请填写操作备注！");
        return false;
    }

});
$('.no').click(function() {
    var    remarks = $("#remarks").val();
    var    order_id = $("#order_id").val();
    var    invoice_no = $("#invoice_no").val();
    var    shipping_express = $("#shipping_express").val();
    var    order_amount = $("#order_amount").val();
    if(!remarks){
        alert("请填写操作备注！");
        return false;
    }
    $.ajax({
        url:"/manage/order",
        data:{"type":'cancel',"remarks":remarks,"order_id":order_id,"invoice_no":invoice_no,"shipping_express":shipping_express,"order_amount":order_amount},
        type:"post",
        success:function(e){
            alert("操作成功！");
            location.href="/manage/order/"+order_id;
        }
    });
});

$('.delete').click(function() {
    var    remarks = $("#remarks").val();
    var    order_id = $("#order_id").val();
    var    invoice_no = $("#invoice_no").val();
    var    shipping_express = $("#shipping_express").val();
    var    order_amount = $("#order_amount").val();
    if(!remarks){
        alert("请填写操作备注！");
        return false;
    }


});
$('.express').click(function() {
    var    remarks = $("#remarks").val();
    var    order_id = $("#order_id").val();
    var    invoice_no = $("#invoice_no").val();
    var    shipping_express = $("#shipping_express").val();
    var    order_amount = $("#order_amount").val();

    if(!invoice_no){
        alert("请填写单号！");
        return false;
    }
    if(!shipping_express){
        alert("请填写快递类型！");
        return false;
    }
    $.ajax({
        url:"/manage/order",
        data:{"type":'express',"remarks":remarks,"order_id":order_id,"invoice_no":invoice_no,"shipping_express":shipping_express,"order_amount":order_amount},
        type:"post",
        success:function(e){
            alert("操作成功！");
            location.href="/manage/order/"+order_id;
        }
    });
});
</script>
@stop

