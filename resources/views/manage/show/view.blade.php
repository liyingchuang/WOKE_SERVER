@extends('_layouts.master')
@section('content')
<style>
    .btn-default{
        background-color: #F5F5F5;
        border-color: #B9A644;
        color: #B9A644;
        border-radius:20px;
    }
    .btn-default.active.focus, .btn-default.active:focus, .btn-default.active:hover, .btn-default:active.focus, .btn-default:active:focus, .btn-default:active:hover, .open>.dropdown-toggle.btn-default.focus, .open>.dropdown-toggle.btn-default:focus, .open>.dropdown-toggle.btn-default:hover{
        color: #333;
        background-color: #f5ede0;
        border-color: #8c8c8c;
    }
    .btn-default.active.focus, .btn-default.active:focus, .btn-default.active:hover, .btn-default:active.focus, .btn-default:active:focus, .btn-default:active:hover, .open>.dropdown-toggle.btn-default.focus, .open>.dropdown-toggle.btn-default:focus, .open>.dropdown-toggle.btn-default:hover{
        color: #333;
        background-color: #f5ede0;
        border-color: #8c8c8c;
    }

</style>
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>晒晒管理</li>
            <li class="active">晒晒详情</li>
        </ol>
    </div>
    <div class="panel-body">

    </div>
    <form method="post"  class="form-horizontal form-bordered" >
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">晒晒信息:</label>
            <div class="col-sm-3 col-xs-3 col-md-3">   <img src="{{$info->file_name}}?imageView2/2/w/200" alt="..." class="img-thumbnail"></div>
            <div class="col-sm-7 col-xs-7 col-md-7">
                晒晒投诉<br>
                <table class="table  table-bordered table-striped">
                    <thead>
                    <th>#</th>
                    <th>用户</th>
                    <th>投诉内容</th>
                    <th>投诉时间</th> 
                    <th>操作</th>
                    </thead>
                    <tbody>
                        @foreach ($info->report as $k=>$u)
                        <tr>
                            <td>{{$k+1}}</td>
                            <td>@if(!empty($u->user))
                                <img id="avatar" src="{{$u->user->headimg}}?imageView2/2/w/20" alt="" class="img-circle" width="40">
                                {{$u->user->user_name}}@if($u->user->is_v)<span style="font-weight:bold;font-style:italic;color:red"> V</span> @endif 
                                @endif
                            </td>
                            <td>{{$u->desc}}</td>
                            <td>{{$u->created_at}}</td>
                            <td>  <button class="btn btn-danger  btn-xs remove "  type="button" alt="{{$u->id}}"><i class="glyphicon glyphicon-trash"></i> 取消举报</button></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">发布用户:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">@if(!empty($info->user)) <img id="avatar" src="{{$info->user->headimg}}?imageView2/2/w/50" alt="" class="img-circle" width="40">
                {{$info->user->user_name}}  @if($info->user->is_v)<span style="font-weight:bold;font-style:italic;color:red"> V</span> @endif @endif</div>
            <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">晒晒标签:</label>
            <div class="col-sm-8 col-xs-8 col-md-8">
                <div class="table-responsive">
                  <table class="table  table-bordered table-striped">
                    <thead>
                    <th>#</th>
                    <th>用户</th>
                    <th>标签</th>
                    <th>创建日期</th>
                    <th>操作</th> 
                    </thead>
                    <tbody>
                        @foreach ($info->tags as $k=>$u)
                        <tr>
                         <td>{{$k+1}}</td>
                          <td>@if(!empty($u->user))
                                <img id="avatar" src="{{$u->user->headimg}}?imageView2/2/w/20" alt="" class="img-circle" width="40">
                                {{$u->user->user_name}}@if($u->user->is_v)<span style="font-weight:bold;font-style:italic;color:red"> V</span> @endif
                                @endif</td>
                          <td><a class='btn btn-default'  href="{{URL::to("manage/show/tag")}}/{{$u->id}}">{{$u->tag_name}}|{{$u->size}} </a></td> 
                          <td>{{$u->created_at}}</td> 
                          <td>
                             <button class="btn btn-danger  btn-xs remove_img "  type="button" alt="{{$u->id}}"><i class="glyphicon glyphicon-trash"></i> 删除</button>
                            <a class="btn btn-info  btn-xs"  href="{{URL::to("manage/show/tag")}}/{{$u->id}}"><i class="glyphicon glyphicon-eye-open"></i> 查看评论</a>
                          </td> 
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
    $('.remove_img').click(function() {
        var id = $(this).attr('alt');
        $.get("{{URL::to('manage/show')}}/del/" + id, function(result) {
        });
        $(this).parents('tr:first').remove();
       // $("#tag" + id).remove();
        //$(this).remove();
    });
    $('.remove').click(function() {
        var id = $(this).attr('alt');
        $.get("{{URL::to('manage/show')}}/del/" + id+'?type=report', function(result) {
        });
        $(this).parents('tr:first').remove();
       // $("#tag" + id).remove();
        //$(this).remove();
    });
    
    
</script>
@stop

