@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        首页店铺管理
    </div>
    <div class="panel-body">
        <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals"> 
            <i class="glyphicon glyphicon-plus"></i>添加店铺</button> 
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>#</th>
        <th>店铺名</th>
        <th>URL</th>
        <th>图片</th>
        <th>排序</th>
        <th>创建日期</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($list as $k=>$u)
            <tr>
                <td scope="row">{{$k+1}}</td>
                <td>{{$u->title}}</td>
                <td>{{$u->url}}</td>
                <td><a href="{{$u->files}}" target="_black" style="font-size: 20px"><i class="glyphicon glyphicon-picture"> </i></a></td>
                <td>{{$u->sort_order}}</td>
                <td>{{$u->created_at}}</td>
                <td class="center ">
                    <button class="btn btn-danger  btn-xs delete"  data-target="#modal-danger" data-toggle="modal" data-whatever="{{$u->id}}"><i class="glyphicon glyphicon-trash"></i> 删除</a>
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
                <h4 class="modal-title" id="myModalLabel">首页店铺添加</h4>
            </div>
            <form method="post"  action="{{URL::to('manage/apps/store')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                <div class="modal-body">
                    <div class="alert alert-danger" role="alert">
                        跳转地址格式:<br>
                        例如店铺:http://www.377123.org/supplier.php?suppId=16 跳转地址:store/16<br>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">店铺名:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input" name="title" title="店铺名" placeholder="输入店铺名" required="" data-bv-notempty-message="店铺名不能为空">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">URL:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input"  name="url" title="用户名"  placeholder="输如URL,格式为:store/16" required="" data-bv-notempty-message="店铺名不能为空">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">排序:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                              {{ csrf_field() }}
                            <input type="text" class="form-control required safe-input" name="sort_order" required="" min="0" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="请输入1到1000" max="1000" data-bv-lessthan-inclusive="true" data-bv-lessthan-message="最大数为1000" data-bv-message="排序不能为空"  >
                        </div>
                    </div>
                    <div class="form-group relative" style="clear:both">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">图片:</label>
                         <div class="img-content-container col-lg-7 clearfix">
                            <div class="module-uploading">
                                <div class="upload-progress-container">
                                    <div id="progress" class="progress hide">
                                        <div class="progress-bar progress-bar-primary"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="module-content">
                                <div class="img-contianer selected_img" id="example_img">
                                    <img src="/assets/images/nophoto.png" width="50%" class="img-rounded ">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <button type="button" class="btn btn-default" id="uploadBtn"><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                            <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                            <input id="store_image" name="images" type="hidden" value="">
                            <br><p style="color: red">图片尺寸:230X150</p>
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
<!--Danger Modal Templates-->
<div id="modal-danger" class="modal modal-message modal-danger fade" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{URL::to('manage/apps/delete')}}" method="get">
                <div class="modal-header">
                    <h4 class="modal-title">确定删除吗</h4>
                </div>
                <div class="modal-title">
                    <input type="hidden" name="id" value="">
                </div>
                <div class="modal-footer">
                      {{ csrf_field() }}
                    <button type="button" class="btn btn-info" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-danger" ><i class="fa fa-save"></i> 确定</button>
                </div>
               </form>
            </div> <!-- / .modal-content -->
        </div> <!-- / .modal-dialog -->
</div>
<script>
$('#html5Form').bootstrapValidator();
$('#modal-danger').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var recipient = button.data('whatever'); // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  console.log('ok'+recipient);
  var modal = $(this);
  modal.find('.modal-title input').val(recipient);
});


$("#uploadBtn").on("click", function() {
        $("#fileupload").trigger("click");
});
$('#fileupload').fileupload({
        url: "{{URL::to('manage/upload')}}?type=LeanCloud",
        dataType: 'json',
        autoUpload: true,
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        maxFileSize: 5000000, // 5 MB
        // Enable image resizing, except for Android and Opera,
        // which actually support image resizing, but fail to
        // send Blob objects via XHR requests:
        disableImageResize: /Android(?!.*Chrome)|Opera/
            .test(window.navigator.userAgent),
        previewMaxWidth: 600,
        previewCrop: false,
        thumbnail: false
}).on('fileuploadadd', function (e, data) {
        var node = $(".img-content-container");
        var preview_node = $("#img-contianer");
        node.find(".progress").removeClass("hide");
}).on('fileuploadprocessalways', function (e, data) {
     var index = data.index,
      file = data.files[index],
      preview_node = $("#img-contianer");
      if (file.preview) {
          preview_node.append(file.preview);
      }
      node.find(".progress").removeClass("hide");
      if (file.code==1) {
          node.append('<br>').append($('<span class="text-danger"/>').text(file.info));
      }
}).on('fileuploadprogressall', function (e, data) {
      var progress = parseInt(data.loaded / data.total * 100, 10);
      $('#progress .progress-bar').css(
          'width',
          progress + '%'
      );
}).on('fileuploaddone', function (e, data) {
     var file = data.result;
      
     console.log('ok->'+file.data);
      console.log('ok->'+file.data); 
      var node = $(".img-content-container");
      node.find(".module-uploading").addClass("hide");
        if (file.code==0) {
          node.find('#example_img').remove();
          var imgObj = "<div class='thumbnail img-contianer'><img src='"+file.data.url+"'  /></div>";
          node.find(".module-content").html(imgObj);
          $('#store_image').val(file.data.fileName);
        } else if (file.code==1) {
          $('#store_image').val('');
          alert('图片上传失败！');
            var error = $('<span class="text-danger"/>').text(file.info);
            $(data.context.children()[index]).append('<br>').append(error);
        }
}).on('fileuploadfail', function (e, data) {
    $('#store_image').val('');
    $.each(data.data, function (index, file) {
      console.log(e);
    });
}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
</script>
@stop