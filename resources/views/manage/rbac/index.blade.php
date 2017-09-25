@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        角色列表
    </div>
	 <div class="panel-body">
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>#</th>
        <th>角色名称</th>
        <th>角色介绍</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($role as $role)
            <tr>
                <td>{{ $role->role_id }}</td>
                <td>{{ $role->role_name }}</td>
                <td>{{ $role->role_describe }}</td>
                <td class="center ">
                    <a href="{{URL::to('manage/rbac/show')}}?role_id={{ $role->role_id }}" class="btn btn-success  btn-xs
				    @if( $role->role_id == 1) disabled @else edit @endif"><i class="glyphicon glyphicon-edit"></i>编辑用户</a>
				   <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/rbac/users')}}/{{ $role->role_id }}"><i class="glyphicon glyphicon-eye-open"></i> 查看</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <div class="row text-right" >
        </div>
    </div>
</div>
@stop







