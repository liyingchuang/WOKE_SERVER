@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
<script src="/assets/js/editable/bootstrap-editable.min.js"></script>
<style>
     .btn-default{
                background-color: #F5F5F5;
                border-color: #B9A644;
                color: #B9A644;
                border-radius:20px;
               
            }
</style>
<div class="panel  panel-info">
    <div class="panel-heading">
        晒晒盘玩榜
    </div>
    <div class="panel-body">
       <form action="{{URL::to('manage/show/baskinstore')}}" method="get">
        <div class="form-group">
            <div class="row">
            <div class="col-xs-9">
                <input type="text" class="form-control"  name="keyword" placeholder="请输入标签内容搜索">
            </div>
            <div class="col-xs-3">
                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
            </div>
          </div> 
        </div>
       </form>
    </div>
   <form action="{{URL::to('manage/show')}}" method="post">
    <table class="table  table-bordered table-striped">
        <thead>
        <th>标签排序</th>
		<th>晒晒内容</th>
        <th>标签内容</th>
        <th>被盘完次数</th>
		<th>用户关注次数</th>
        <th>搜索排序</th>
		<th>上传晒晒内容</th>
        </thead>
        <tbody>   
    @foreach ($list as $k=>$u)
    <tr>
        <td>{{$k+1}}</td>
		<td>
		<a href="javascript:;" onclick='baskin_thumb("{{$u->tag_name}}")' data-target="#myModals" data-backdrop="static" data-toggle="modal"><img src="{{$u->thumb}}?imageView2/2/w/50" alt="..." class="img-thumbnail"></a></td>
         <td>{{$u->tag_name}}</td>   
        <td>{{$u->size}}</td>
		<td>{{$u->user_tags}}</td>
        <td><a class="search_sort_order" data-type="text" data-pk="{{$u->tag_name}}" data-url="{{URL::to('manage/show/search_sort_order')}}" data-title="输入排序">{{$u->search_sort_order}}</a></td>
		<td>
			<a class="btn btn-info  btn-xs" href="javascript:;" onclick='baskin_thumb("{{$u->tag_name}}")' data-target="#myModals" data-backdrop="static" data-toggle="modal"><i class="glyphicon glyphicon-camera"></i>上传图片</a>
		</td>
    </tr>
    @endforeach   
        </tbody>
    </table>
    <div class="panel-footer"> 
        <div class="row" >
		<div class="col-sm-5 col-xs-5 col-md-5 " >
            </div>
            <div class="col-sm-7 col-xs-7 col-md-7 text-right" > 
              {!! $list->appends(['keyword' =>$keyword])->render() !!}
            </div>
        </div>
    </div>
   </form>
</div>
<!-- Modal -->
<div class="modal fade" id="myModals" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">盘玩榜标签</h4>
            </div>
            <form method="get"  id="addForm" action="{{URL::to('manage/show/baskin_image')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
            <div class="modal-body">
                 <h5><b>晒晒信息</b></h5>
                <div class="form-group">
                    <label for="uuid" class="col-sm-4 col-xs-4 col-md-4 control-label no-padding-right">修改晒晒内容:</label>
                    <div class="img-content-container col-sm-5 col-xs-5 col-md-5 clearfix">
                            <div class="module-uploading">
                                <div class="upload-progress-container">
                                    <div id="progress" class="progress hide">
                                        <div class="progress-bar progress-bar-primary"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="module-content">
                                <div class="img-contianer selected_img" >
                                    <img src="/assets/images/nophoto.png" width="50%" class="img-rounded " id="idcard_front_img">
                                </div>
                            </div>
                    </div>
                    <div class="col-sm-3 col-xs-3 col-md-3"> 
                          <button type="button" class="btn btn-default uploadBtn" alt="idcard_front" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                          <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                            <input  name="thumb" id="thumb" type="hidden" value="">
							<input  name="baskin_name" id="baskin_name" type="hidden" value="">
                    </div>
             </div>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-info" id="myModalis"><i class="fa fa-save"></i> 确定添加</button>
            </div>
          </form>
        </div>
    </div>
</div>
<script type="text/javascript">
function baskin_thumb(a){
		$('#baskin_name').val(a);
}
/*
* $('.myModals').map(function() {$(this).modal('hide');});
* */

$("#myModalis").on("click", function() {
    $('#addForm').submit();
    $('#myModals').modal('hide');

});
var u_option = '';
$(".uploadBtn").on("click", function() {
        var filename = $(this).attr('alt');
        console.log(filename);
        u_option = filename;
        $("#fileupload").trigger("click");
		
    });
$('#fileupload').fileupload({
        url: "{{URL::to('manage/upload')}}?type=LeanCloud",
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        disableImageResize: /Android(?!.*Chrome)|Opera/
                .test(window.navigator.userAgent),
        previewMaxWidth: 600,
        previewCrop: false,
        thumbnail: false
}).on('fileuploaddone', function(e, data) {
        var file = data.result;
        if (file.code == 0) {
           // node.find('src').remove(); 
           // node.append();
            $("#" + u_option+"_img").attr('src',file.data.url);
            $("#thumb").val(file.data.fileName);
        } else {
            alert('上传出错!');
        }
}).on('fileuploadfail', function(e, data) {
        alert('上传出错!');
});
$(function(){
    $('#all .editable').editable('toggleDisabled');
    $.fn.editable.defaults.mode = 'popup';
    $('.search_sort_order').editable({
        name: 'search_sort_order',
        success: function(response, newValue) {
            if(response.status == 'error') return response.msg; //msg will be shown in editable form
        }
    });

});
</script>
@stop