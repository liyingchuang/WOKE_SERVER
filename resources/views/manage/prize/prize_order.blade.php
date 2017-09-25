@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>奖励订单列表</li>
        </ol>
    </div>
    <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{URL::to('manage/prize/ordershow')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="text" class="form-control"  name="keyword" placeholder="请输入兑换奖励池名称进行搜索">
                            </div>
                            <div class="col-xs-3">
                                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                            </div>
                            <div class="col-xs-3"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xs-2 text-left"></div>
        </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>编号</th>
        <th>收货人</th>
        <th>收货地址</th>
        <th>收货手机号</th>
        <th>下单时间</th>
        <th>订单状态</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($prize_orders as $key=>$val)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $val->username }}</td>
                <td>{{ $val->address }}</td>
                <td>{{ $val->mobile}}</td>
                <td>{{ $val->created_at}}</td>
                <td>
                    @if( $val->shipping_status == 0) 未发货 @elseif( $val->shipping_status == 1) 已发货 @elseif( $val->shipping_status == 2) 收货确认 @else 备货中  @endif
                </td>
                <td>
                    <a href="{{URL::to('manage/prize/prizeordershow')}}/{{ $val->id }}" class="btn btn-success btn-xs">
                        <i class="glyphicon glyphicon-search"></i>查看
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer"> 
            <div class="row " >
                <div class="col-xs-2 text-right"></div>
                <div class="col-xs-1"></div>
                <div class="col-xs-9 text-right">
                    {!! $prize_orders->appends(['keyword' =>$keyword])->render() !!}
                </div>
        </div>
    </div>
</div>
<script>
function add_goods(){
	location.href="/manage/integral/addshow";
}
function update_price(id,now){
    if($("#show"+id).attr("class")=="menu-icon glyphicon glyphicon-ok")
        $("#show"+id).attr("class","menu-icon glyphicon glyphicon-remove");
    else
        $("#show"+id).attr("class","menu-icon glyphicon glyphicon-ok");

    $.ajax({
        url:"/manage/integral/edit",
        data:{"id":id,"now":now},
        type:"get"
    })
}
</script>
@stop







