@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li>管理员列表</li>
                <li class="active">{{ $main->name }}角色下的成员</li>
            </ol>
        </div>
        <div class="panel-body">
            <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal"
                    data-target="#myModals">
                <i class="glyphicon glyphicon-plus"></i>添加{{ $main->name }}</button>
        </div>
        <table class="table  table-bordered table-striped">
            <thead>
            <th>#</th>
            <th>管理员名称</th>
            <th>EMAIL</th>
            <th>添加时间</th>
            <th>操作</th>
            </thead>
            <tbody>
            @foreach ($admin as $admin)
                <tr>
                    <td>{{ $admin->user_id }}</td>
                    <td>{{ $admin->user_name }}</td>
                    <td>{{ $admin->email }}</td>
                    <td>{{ $admin->add_time }}</td>
                    <td class="center ">
                        @if($admin->user_id == 1 )
                            <button class="btn btn-danger  btn-xs " type="button" disabled><i
                                        class="glyphicon glyphicon-trash"></i> 删除
                            </button>@else
                            <button class="btn btn-danger  btn-xs " type="button" onclick="if (confirm('确定删除吗?')) {
                                    $(this).parent().hide().next().show();
                                    var obj = $(this);
                                    $.get('{{URL::to('manage/rbac/admindel?'.'user_id='.$admin->user_id.'&'.'id='.$main->id)}}', function(response){
                                    obj.parents('tr:first').remove();
                                    });
                                    }"><i class="glyphicon glyphicon-trash"></i> 删除
                            </button>@endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="panel-footer">
            <div class="row text-right">
            </div>
        </div>

    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加超级管理员</h4>
                </div>
                <form method="post" id="addForm" action="{{URL::to('manage/rbac/addadmin')}}"
                      class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空"
                      data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
                      data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
                      data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="uuid"
                                   class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">用户名称:</label>
                            <div class="col-sm-9 col-xs-9 col-md-9">
                                <input type="text" class="form-control required safe-input" name="user_name"
                                       id="user_name" title="输入用户名称" placeholder="输入用户名称" required=""
                                       data-bv-notempty-message="用户名称">
                                <input type="hidden" name="role_id" value="{{ $main->id }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid"
                                   class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">用户邮箱:</label>
                            <div class="col-sm-9 col-xs-9 col-md-9">
                                <input type="text" class="form-control required safe-input" name="user_email"
                                       id="user_email" title="输入邮箱" placeholder="输入邮箱" required=""
                                       data-bv-notempty-message="用户邮箱" onblur="email()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid"
                                   class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">用户密码:</label>
                            <div class="col-sm-9 col-xs-9 col-md-9">
                                <input type="text" class="form-control required safe-input" name="user_password"
                                       id="user_password" title="输入密码" placeholder="输入密码" required=""
                                       data-bv-notempty-message="用户密码">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> 确定添加</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        function email() {
            var email = $("#user_email").val();
            var z_email = /^\w+@\w+(\.)\w+$/;
            if (z_email.test(email)) {
                $.ajax({
                    url: "/manage/rbac",
                    data: {"email": email},
                    type: "post",
                    success: function (e) {
                        if (e == 0) {
                            $("#user_email").val("");
                            alert("已经被注册，重新输入");
                        } else {
                            alert("OK");
                        }
                    }
                });
            } else {
                $("#user_email").val("");
                alert("字母@字母或数字.com");
                return false;
            }
        }
    </script>
@stop






