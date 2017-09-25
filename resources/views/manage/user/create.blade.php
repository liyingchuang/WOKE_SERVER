@extends('_layouts.master')
@section('content')
<div class="panel  panel-info">
    <div class="panel-heading">
        <h2>用户管理</h2>
    </div>
    <div class="panel-body">
        
        <button type="button" class="btn btn-success btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModal"> 
            <i class="glyphicon glyphicon-plus"></i>添加生成</button> 
        
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>#编号</th>
        <th>用户名</th>
        <th>手机号</th>
        <th>密码</th>
        <th>email</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($list as $k=>$u)
            <tr>
                <td scope="row">{{$k+1}}</td>
                <td>{{$u->user_name}}</td>
                <td>{{$u->mobile_phone}}</td>
                <td>{{$u->password}}</td>
                <td>{{$u->email}}</td>
                <td class="center ">
                    <a class="btn btn-default  btn-xs" href="">日志</a>
                    <a class="btn btn-danger btn-xs " href="">删除</a>
                    <a class="btn btn-warning btn-xs" href="">编辑</a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
    <div class="panel-footer"> 
           <div class="row text-right" >
                                     {!! $list->render() !!}
           </div>
    </div>
</div>
@stop