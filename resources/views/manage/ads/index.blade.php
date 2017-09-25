@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li class="active">广告分类</li>
        </ol>
    </div>
    <div class="panel-body">
      <!--  <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals"> 
            <i class="glyphicon glyphicon-plus"></i>添加广告分类</button> -->
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>ID</th>
        <th>分类名称</th>
        <th>广告宽度</th>
        <th>广告高度</th>
        <th>分类描述</th>
        <th>创建日期</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($list as $k=>$u)
            <tr>
                <td scope="row">{{$u->id}}</td>
                <td>{{$u->category_name}}</td>
                <td>{{$u->ad_height}}</td>
                <td>{{$u->ad_width}}</td>
                <td>{{$u->category_desc}}</td>
                <td>{{$u->created_at}}</td>
                <td class="center ">
                    <a href="{{URL::to("manage/ads")}}?id={{$u->id}}" class="btn btn-info  btn-xs delete"    ><i class="glyphicon glyphicon-eye-open"></i> 查看广告</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="panel-footer"> 
        <div class="row text-right" style="padding-right: 10px" >
            {!! $list->render() !!}
        </div>
    </div>
</div>
<!-- Modal -->

<div class="modal fade" id="myModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加广告分类</h4>
            </div>
            <form method="post"  action="{{URL::to('manage/adCategory')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                <div class="modal-body">
                  
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">分类名称:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input" name="category_name" title="分类名称" placeholder="输入分类名称" required="" data-bv-notempty-message="分类名称不能为空">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">广告宽度:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input" name="ad_width" title="广告宽度" placeholder="输入广告宽度" required="" min="0" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="请输入1到10000" max="10000" data-bv-lessthan-inclusive="true" data-bv-lessthan-message="最大数为10000" data-bv-notempty-message="广告宽度不能为空">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">广告高度:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input" name="ad_height" title="广告高度" placeholder="输入广告高度" required=""min="0" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="请输入1到10000" max="10000" data-bv-lessthan-inclusive="true" data-bv-lessthan-message="最大数为10000" data-bv-notempty-message="广告高度不能为空">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">描述:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input" name="category_desc" title="描述" placeholder="输入描述" >
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
$('#html5Form').bootstrapValidator();
</script>
@stop