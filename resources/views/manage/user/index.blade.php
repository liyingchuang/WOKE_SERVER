@extends('_layouts.master')
@section('content')
<link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
<script src="/assets/js/editable/bootstrap-editable.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        用户管理
    </div>
    <div class="panel-body">
        <form action="{{URL::to('manage/user')}}" method="get">
            <div class="form-group">
                <div class="row">
                    <div class="col-xs-9">
                        <input type="text" class="form-control"  name="keyword" placeholder="请输入手机号或者编号或者用户名搜索">
                        <input type="hidden" name="type" value="{{$type}}" >
                    </div>
                    <div class="col-xs-3">
                        <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                    </div>
                </div> 
            </div>
        </form>
    </div>
    <div class="tabbable">
        <ul class="nav nav-tabs" id="myTab">
            <li @if($type=='all') class="active" @else class="tab-red" @endif >
                 <a href="{{URL::to('manage/user')}}?type=all">
                    全部用户
                </a>
            </li>
        </ul>
        <div class="tab-content">
            @if($type=='all') 
            <div id="all" class="tab-pane in active">
                <table class="table  table-bordered table-striped">
                    <thead>
                    <th>编号</th>
                    <th>用户名</th>
                    <th>手机号</th>
                    <th>上级用户</th>
                    <th>可用酒币</th>
                    <th>已用酒币</th>
                    <th>级别</th>
                    <th>注册日期</th>
                    <th>操作</th>
                    </thead>
                    <tbody>
                        @foreach ($list as $k=>$u)
                        <tr>
                            <td scope="row">{{$u->user_id}}</td>
                            <td><a class="user_name" data-type="text" data-pk="{{$u->user_id}}" data-url="{{URL::to('manage/user')}}" data-title="输入用户名">{{$u->user_name}}</a></td>
                            <td>{{$u->mobile_phone}}</td>
                            <td><a href="{{URL::to('manage/user')}}?keyword={{$u->parent_id}}&type=all">{{$u->parent_id}}</a></td>
                            <td>{{$u->user_money}} </td>
                            <td>{{$u->frozen_money}}</td>
                            <td>
                                @if($u->user_rank==0) 普通用户 @endif
                                    @if($u->user_rank==1) 专员 @endif
                                    @if($u->user_rank==2) 高级专员 @endif
                                    @if($u->user_rank==3) 经理 @endif
                                    @if($u->user_rank==4) 三级总监 @endif
                                    @if($u->user_rank==5) 二级总监 @endif
                                    @if($u->user_rank==6) 一级总监 @endif

                            </td>
                            <td>{{$u->reg_time}}</td>
                            <td class="center ">
                                {{--<button class="btn btn-info btn-xs banned"   data-whatever="{{$u->user_id}}"><i class="glyphicon glyphicon-user"></i> 下级用户</button>--}}
                                <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/user/subor')}}/{{ $u->user_id }}"><i class="glyphicon glyphicon-eye-open"></i> 下级用户</a>
                                <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/user/thewine')}}/{{ $u->user_id }}"><i class="glyphicon glyphicon-eye-open"></i> 酒币明细</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif



        </div>

    </div>
    <div class="panel-footer"> 
        <div class="row text-right" style="padding-right: 10px" >
            {!! $list->appends(['keyword' =>$keyword,'type'=>$type])->render() !!}
        </div>
    </div>
</div>
<script>
</script>
@stop