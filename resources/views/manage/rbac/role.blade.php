@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li>角色分配权限</li>
                <li class="active">{{ $role->display_name }}分配</li>
            </ol>
        </div>
        <div class="panel-body">
        </div>
        <form action="{{ url('manage/rbac/create') }}" method="get">
            <table class="table  table-bordered table-striped" id="tbodyID">
                <tbody>
                <tr id="">
                    <td width="10%"><label><input type="checkbox" onclick="checkall(this)"
                                                  class="multi_checked"><span
                                    class="text"></span>&nbsp;&nbsp;&nbsp;</label></td>
                    <td>
                        @foreach ($permission as $key => $son)
                            <label style="width: 172px;"><input type="checkbox" name="chk_role[]" value="{{ $son->id }}"
                                          class="multi_checked"
                                          @if( in_array($son->id, $role_array)) checked @endif><span
                                        class="text"></span>&nbsp;&nbsp;&nbsp;{{ $son->description }}</label>
                        @endforeach
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="panel-footer">
                <div class="row text-right">
                    <div class="col-sm-offset-10 col-sm-2  col-xs-10 col-md-10 col-md-2 col-xs-2">
                        <input type="hidden" name="role_id" value="{{ $role->id }}">
                        <button type="submit" class="btn btn-info btn-ms  pull-right">
                            <i class="fa fa-save"></i>保存权限
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script>
        function checkall(a) {
            if (a.checked == true) {
                reg = $(a).parent().parent().next().children().children().prop("checked", true);
            } else {
                reg = $(a).parent().parent().next().children().children().prop("checked", false);
            }
        }
    </script>
@stop









