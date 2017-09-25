@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        商品积分兑换
    </div>
    <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{URL::to('manage/integral/show')}}" method="get">
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
        <th>商品名称</th>
        <th>所需积分</th>
        <th>所有数量</th>
        <th>库存量</th>
        <th>排序</th>
        <th>添加时间</th>
        <th>是否上架</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($goods_list as $key=>$goods)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $goods->name }}</td>
                <td>{{ $goods->integral }}</td>
                <td>{{ $goods->goods_all_number}}</td>
                <td>{{ $goods->goods_number }}</td>
                <td>{{ $goods->sort_order }}</td>
                <td>{{ $goods->created_at }}</td>
                <td>
                    <a onclick="update_price({{ $goods->id }},{{ $goods->is_show }})" href="javascript:;">
                        <i id="show{{ $goods->id }}" class="menu-icon glyphicon @if( $goods->is_show == 0) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
                <td class="center ">
                    <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/integral/updateshow')}}/{{ $goods->id }}"><i class="glyphicon glyphicon-edit"></i> 编辑</a>
                    <button class="btn btn-danger  btn-xs "  type="button"  onclick="if (confirm('确定删除吗?')) {
                                $(this).parent().hide().next().show();
                                        var obj = $(this);
                                        $.get('{{ url('manage/integral/delete') }}/{{ $goods->id }}', function(response){
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
                    {!! $goods_list->appends(['keyword' =>$keyword])->render() !!}
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







