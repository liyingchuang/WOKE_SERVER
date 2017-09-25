@extends('_layouts.master')
@section('content')

<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>晒晒管理</li>
            <li>晒晒详情</li>
            <li class="active">标签评论</li>
        </ol>
    </div>
    <div class="panel-body">
        <div class="alert alert-info" role="alert">
            <div class="text">
                @if(!empty($tag->user))<img  src="{{$tag->user->headimg}}?imageView2/2/w/50" alt="" class="img-circle" width="40"></a>@endif 
                <span class="glyphicon glyphicon-user"></span> {{$tag->user->user_name}}  <span class="glyphicon glyphicon-tag"></span> {{$tag->tag_name}}  <span class="glyphicon glyphicon-thumbs-up"></span> {{$tag->size}}
            </div>
        </div>
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>编号</th>
        <th>用户</th>
        <th>评论内容</th>
        <th>发布日期</th> 
        <th>操作</th> 
        </thead>
        <tbody>
            @foreach ($list as $k=>$u)
            <tr>
                <td>{{$k+1}}</td>
                <td>@if(!empty($u->user))<img id="avatar" src="{{$u->user->headimg}}?imageView2/2/w/50" alt="" class="img-circle" width="40">{{$u->user->user_name}} </a>@endif</td>
                <td>{{$u->desc}}</td>
                <td>{{$u->created_at}}</td>
                <td><button class="btn btn-danger  btn-xs remove_img"  type="button" alt="{{$u->id}}"><i class="glyphicon glyphicon-trash"></i> 删除</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer"> 
        <div class="row " >
            <div class="col-sm-5 col-xs-5 col-md-5 " ></div>
            <div class="col-sm-7 col-xs-7 col-md-7 text-right" > 
                       {!! $list->appends(['id' =>$id])->render() !!}
            </div>
        </div>
    </div>
</div>
<script>
    $('.remove_img').click(function() {
        var id = $(this).attr('alt');
        $.get("{{URL::to('manage/show/comment')}}/" + id, function(result) {
        });
        $(this).parents('tr:first').remove();
       // $("#tag" + id).remove();
        //$(this).remove();
    });
</script>
@stop