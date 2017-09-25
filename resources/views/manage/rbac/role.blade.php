@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>角色分配权限</li>
            <li class="active">{{ $role_resource->role_name }}分配</li>
        </ol>
    </div>
    <div class="panel-body">
    </div>
    <form action="{{ url('manage/rbac/create') }}" method="get">
        <table class="table  table-bordered table-striped"  id="tbodyID">
            <tbody>
                @foreach ($data as $key=> $data)

                <tr id="{{$key}}" >
                    <td width="10%"><label><input type="checkbox" onclick="checkall(this)" class="multi_checked"><span class="text"></span>&nbsp;&nbsp;&nbsp;{{$key}}</label></td>	
                    <td>
                        @foreach ($data as $son)

                        <label><input type="checkbox" name="chk_role[]" value="{{ $son->resource_id }}" class="multi_checked"
                                      @if( in_array($son->resource_id, $role_array)) checked @endif><span class="text"></span>&nbsp;&nbsp;&nbsp;{{ $son->resource_name }}</label>
                        @endforeach	
                    </td>
                </tr>	
                @endforeach
            </tbody>
        </table>
        <div class="panel-footer">
            <div class="row text-right" >
                <div class="col-sm-offset-10 col-sm-2  col-xs-10 col-md-10 col-md-2 col-xs-2">
                <input type="hidden" name="role_id" value="{{ $role_resource->role_id }}">
                <button type="submit" class="btn btn-info btn-ms  pull-right"  >
                    <i class="fa fa-save"></i>保存权限
                </button>
                </div>
            </div>
        </div>
    </form>
</div>
<script style="text/javascript">
        function checkall(a) {
            if (a.checked == true) {
                reg = $(a).parent().parent().next().children().children().prop("checked", true);
            } else {
                reg = $(a).parent().parent().next().children().children().prop("checked", false);
            }
        }
</script>
@stop









