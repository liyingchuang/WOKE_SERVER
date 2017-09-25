@extends('_layouts.master')
@section('content')
    <link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
    <link rel="stylesheet" href="/assets/js/editable/1.85.css">
    <script src="/assets/js/editable/bootstrap-editable.min.js"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            下级用户
        </div>
        <div class="panel-body">
            <form action="{{URL::to('manage/subor')}}" method="get">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-9">
                            <input type="text" class="form-control"  name="keyword" placeholder="请输入手机号或者编号或者用户名搜索" disabled>
                        </div>
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-info" disabled> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active" class="tab-red">
                    <a href="">
                        全部下级
                    </a>
                </li>
            </ul>
                <div class="tab-content">
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
                            </thead>

                            <tbody>
                            @foreach ($parent as $key=>$value)
                                <tr>
                                    <td scope="row">{{$value->user_id}}</td>
                                    <td><a class="user_name" data-type="text" data-pk="{{$value->user_id}}" data-url="{{URL::to('manage/user')}}" data-title="输入用户名">{{$value->user_name}}</a></td>
                                    <td>{{$value->mobile_phone}}</td>
                                    <td><a href="{{URL::to('manage/user')}}?keyword={{$value->parent_id}}&type=all">{{$value->parent_id}}</a></td>
                                    <td>{{$value->user_money}} </td>
                                    <td>{{$value->frozen_money}}</td>
                                    <td>
                                        @if($value->user_rank==0) 普通用户 @endif
                                        @if($value->user_rank==1) 专员 @endif
                                        @if($value->user_rank==2) 高级专员 @endif
                                        @if($value->user_rank==3) 经理 @endif
                                        @if($value->user_rank==4) 三级总监 @endif
                                        @if($value->user_rank==5) 二级总监 @endif
                                        @if($value->user_rank==6) 一级总监 @endif

                                    </td>
                                    <td>{{$value->reg_time}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>

    </div>
    <div style="padding-left:94%;">
        <div id="main">
            <div class="demo">
                <a href="#" class="btn btn-info" onClick="javascript :history.back(-1);">返回上一页</a>

            </div>
        </div>
    </div>
@stop




