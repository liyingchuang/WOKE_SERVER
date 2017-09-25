@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>奖励池物品</li>
        </ol>
    </div>
    <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{URL::to('manage/prize/index')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="text" class="form-control"  name="keyword" placeholder="请输入兑换商品名称进行搜索">
                            </div>
                            <div class="col-xs-3">
                                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                            </div>
                            <div class="col-xs-3"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-xs-2 text-left">
                <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals" onclick="add_goods()">
                    <i class="glyphicon glyphicon-plus"></i>添加商品</button>
            </div>
        </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>编号</th>
        <th>奖品名称</th>
        <th>商品数量</th>
        <th>所属种类</th>
        <th>是否显示</th>
        <th>兑换所需碎片</th>
        <th>最小积分</th>
        <th>最大积分</th>
        <th>添加时间</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($prize as $key=>$val)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $val->name }}</td>
                <td>{{ $val->num}}</td>
                <td>@if( $val->type == 1 ) 实物 @elseif($val->type == 2 ) 碎片 @elseif($val->type == 3 ) 红包 @else 积分 @endif</td>
                <td>@if( $val->is_show == 1 ) 显示 @else 不显示 @endif</td>
                <td>@if( $val->type == 2 ) {{ $val->fragment }} @else -- @endif</td>
                <td>@if( $val->type == 5 ) {{ $val->min }} @else -- @endif</td>
                <td>@if( $val->type == 5 ) {{ $val->max }} @else -- @endif</td>
                <td>{{ $val->created_time }}</td>
                <td class="center ">
                    <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/prize/updateshow')}}/{{ $val->id }}"><i class="glyphicon glyphicon-edit"></i> 编辑</a>
                    <button class="btn btn-danger  btn-xs "  type="button"  onclick="if (confirm('确定删除吗?')) {
                                $(this).parent().hide().next().show();
                                        var obj = $(this);
                                        $.get('{{ url('manage/prize/delete') }}/{{ $val->id }}', function(response){
                                                                    obj.parents('tr:first').remove();
                                                            });
                                                    }"> <i class="glyphicon glyphicon-trash"></i> 回收站</button>
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
                    {!! $prize->appends(['keyword' =>$keyword])->render() !!}
                </div>
        </div>
    </div>
</div>
<script>
function add_goods(){
	location.href="/manage/prize/addshow";
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







