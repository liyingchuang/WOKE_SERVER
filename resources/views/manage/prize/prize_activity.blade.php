@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>活动列表</li>
        </ol>
    </div>
    <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{URL::to('manage/prize/show')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-xs-4">
                                <input type="text" class="form-control"  name="keyword" placeholder="请输入活动名称进行搜索">
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
                <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals" onclick="add_activity()">
                    <i class="glyphicon glyphicon-plus"></i>添加活动</button>
            </div>
        </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>编号</th>
        <th>活动名称</th>
        <th>抽奖所需积分</th>
        <th>开始时间</th>
        <th>结束时间</th>
        <th>是否发布活动</th>
        <th>添加时间</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($activity as $key=>$val)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $val->name }}</td>
                <td>{{ $val->size }}</td>
                <td>{{ $val->start_time}}</td>
                <td>{{ $val->end_time }}</td>
                <td>
                    <a onclick="update_show({{ $val->id }},{{ $val->is_show }})" href="javascript:;">
                        <i id="is_show{{ $val->id }}" class="menu-icon glyphicon @if( $val->is_show == 1) glyphicon-ok @else glyphicon-remove @endif" ></i>
                    </a>
                </td>
                <td>{{ $val->created_time }}</td>
                <td class="center ">
                    <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/prize/updateshowactivity')}}/{{ $val->id }}"><i class="glyphicon glyphicon-edit"></i> 编辑</a>
                    <button class="btn btn-danger  btn-xs "  type="button"  onclick="if (confirm('确定删除吗?')) {
                                $(this).parent().hide().next().show();
                                        var obj = $(this);
                                        $.get('{{ url('manage/prize/acdelete') }}/{{ $val->id }}', function(response){
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
                    {!! $activity->appends(['keyword' =>$keyword])->render() !!}
                </div>
        </div>
    </div>
</div>
<script>
function add_activity(){
    location.href="/manage/prize/showactivity";
}

function update_show(id,now){
    $.ajax({
        url:"/manage/prize/price",
        data:{"id":id,"now":now},
        type:"post",
        success: function (e) {
            if(e == 1){
                alert("每次只能发布一个活动！");
                return false;
            }else if(e == 0){
                alert("本次活动未添加商品！");
                return false;
            }else{
                if($("#is_show"+id).attr("class")=="menu-icon glyphicon glyphicon-ok"){
                    $("#is_show"+id).attr("class","menu-icon glyphicon glyphicon-remove");
                }else{
                    $("#is_show"+id).attr("class","menu-icon glyphicon glyphicon-ok");
                }
            }
        }
    })
}
</script>
@stop







