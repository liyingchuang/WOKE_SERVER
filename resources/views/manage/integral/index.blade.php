@extends('_layouts.master')
@section('content')
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li class="active">积分项目</li>
            </ol>
        </div>
        <div class="panel-body">

        </div>
        <table class="table  table-bordered table-striped">
            <thead>
            <th>ID</th>
            <th>项目名称</th>
            <th>任务类型</th>
            <th>每 / 积分</th>
            <th>排序</th>
            <th>创建时间</th>
            <th>是否显示</th>
            <th>操作</th>
            </thead>
            <tbody>
            @foreach ($integral as $k=>$v)
                <tr>
                    <td scope="row">{{ $k+1 }}</td>
                    <td>{{$v->name}}</td>
                    <td>@if($v->type == 0) 每日 @else 综合 @endif</td>
                    <td>{{$v->min}} / {{$v->integral}}</td>
                    <td>{{$v->sort_order}}</td>
                    <td>{{$v->create_at}}</td>
                    <td>@if($v->is_show == 1) 显示 @else 不显示 @endif</td>
                    <td class="center ">
                        <button  data-backdrop="static" data-toggle="modal" data-target="#myModals"
                                 class="btn btn-success  btn-xs edit" style="margin-left: 5px"
                                 data-id="{{$v->id}}"
                                 data-name="{{$v->name}}"
                                 data-type="{{$v->type}}"
                                 data-is_show="{{$v->is_show}}"
                                 data-min="{{$v->min}}"
                                 data-sort_order="{{$v->sort_order}}"
                                 data-integral="{{$v->integral}}">
                            <i class="glyphicon glyphicon-edit"></i> 编辑</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="panel-footer">
            <div class="row text-right" style="padding-right: 10px" >

            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">编辑项目选项</h4>
                </div>
                <form method="post"  action="{{URL::to('manage/integral/store')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">项目名称:</label>
                            <div class="col-sm-10 col-xs-10 col-md-10">
                                <input type="text" class="form-control required safe-input" name="name" id="name" title="项目名称" placeholder="输入项目名称" required="" data-bv-notempty-message="分类名称不能为空">
                                <input type="hidden" name="id" id="id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">任务类型:</label>
                            <div class="col-sm-10 col-xs-10 col-md-10">
                                <select name="type" id="type" onchange=""   class="form-control" >
                                    <option value="1">综合</option>
                                    <option value="0">每日</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">是否显示:</label>
                            <div class="col-sm-10 col-xs-10 col-md-10">
                                <select name="is_show" id="is_show" onchange=""   class="form-control" >
                                    <option value="1">显示</option>
                                    <option value="0">不显示</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">要求:</label>
                            <div class="col-sm-10 col-xs-10 col-md-10">
                                <input type="text" class="form-control required safe-input" name="min" id="min" title="要求" placeholder="输入达标要求" required="" data-bv-notempty-message="分类名称不能为空">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">排序:</label>
                            <div class="col-sm-10 col-xs-10 col-md-10">
                                <input type="text" class="form-control required safe-input" name="sort_order" id="sort_order" title="排序" placeholder="输入排序" required="" data-bv-notempty-message="分类名称不能为空">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">积分:</label>
                            <div class="col-sm-10 col-xs-10 col-md-10">
                                <input type="text" class="form-control required safe-input" name="integral" id="integral" title="积分" placeholder="输入积分" required="" data-bv-notempty-message="分类名称不能为空">
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
$(".edit").on("click",function(){
    var id=$(this).attr('data-id');
    var name=$(this).attr('data-name');
    var type=$(this).attr('data-type');
    var is_show=$(this).attr('data-is_show');
    var min=$(this).attr('data-min');
    var sort_order=$(this).attr('data-sort_order');
    var integral=$(this).attr('data-integral');
    $("#id").val(id);
    $("#name").val(name);
    $("#type").val(type);
    $("#is_show").val(is_show);
    $("#min").val(min);
    $("#sort_order").val(sort_order);
    $("#integral").val(integral);
});
</script>
@stop