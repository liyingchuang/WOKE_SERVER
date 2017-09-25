@extends('_layouts.master')
@section('content')

<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>用户管理><a href ='/manage/user/excel'>导出</a></li>
            <li class="active">用户：<font color="red">{{ $user->user_name }}</font>积分明细 总计:{{ $user->integral }}</li>
        </ol>
    </div>
	<div class="panel-body">
        <form action="{{ url('/manage/user/excel') }}" method="post">
            <input type="hidden" name="user_id" value="{{ $user->user_id }}">
                <button type="submit" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal">
                    <i class="glyphicon glyphicon-floppy-saved"></i>导出明细
                </button>
        </form>
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>编号</th>
        <th>积分状况</th>
        <th>积分</th>
        <th>介绍</th>
        <th>创建时间</th>
        </thead>
        <tbody>
            @foreach ($info_integral as $key => $val)
            <tr>
                <td>{{ $key +1 }}</td>
                <td>@if( $val->type == 1) 增加 @else <font color =='red'>减少</font> @endif</td>
                <td>{{ $val->size }}</td>
                <td>{{ $val->desc }}</td>
                <td>{{ $val->create_time }}</td>
            </tr>
           @endforeach
        </tbody>
    </table>
    <div class="panel-footer">
        <div class="row text-right" style="padding-right: 10px" >
            {!! $info_integral->render() !!}
        </div>
    </div>
</div>
@stop




