@extends('_layouts.master')
@section('content')
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<style type="text/css">
  .img{
    height: auto; width: 150px; 
  }
  .img-contianer{
    margin-right: 10px;
    margin-bottom: 10px;
  }
  .remove_img{
    position: absolute;
    display: block;
    right: 0;
    top: 0;
    font-size: 30px;
    color: #ff4a00;
  }
  .img-contianer{
    float: left;
    margin-left: 15px;
    position: relative;
  }
  #editor {overflow:scroll; max-height:200px}
</style>

<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li><a href="{{URL::to('manage/user')}}">用户管理</a></li>
            <li class="active">编辑用户</li>
        </ol>
    </div>
    <div class="panel-body">

    </div>
    <form method="post"  action="{{URL::to('manage/user')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
        <input type="hidden" name="user_id" value="{{$info->user_id}}">
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">用户信息:</label>  
            <div class="col-sm-1 col-xs-1 col-md-1">
                <img id="avatar" src="{{$info->headimg}}?imageView2/2/w/100" alt="" class="img-thumbnail" width="100">
               
            </div>
            <div class="col-sm-9 col-xs-9 col-md-9">
                 <p>用户名:{{$info->user_name}}<br>手机号:{{$info->mobile_phone}}</p>
                 <p>匠人:@if($info->is_v) <i class="glyphicon glyphicon-ok" style="color: #255625"></i>@else <i class="glyphicon glyphicon-remove" style="color: red"></i> @endif</p> 
            </div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">匠人简介:</label>  
            <div class="col-sm-5 col-xs-5 col-md-5">
                <textarea name="about" class="form-control" rows="10" size="200">{{$info->about}}</textarea>
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5">200个字以内</div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">匠人图片:</label>  
            <div class="col-sm-7 col-xs-7 col-md-7">
                  <div class="img-content-container clearfix">
                            <div class="module-uploading">
                                <div class="upload-progress-container">
                                    <div id="progress" class="progress hide">
                                        <div class="progress-bar progress-bar-primary"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="module-content">
                               @if($info->image)
                                    <div class="thumbnail img-contianer selected_img">
                                        <img src="{{$info->image}}"   class='img img-rounded'  >
                                        <input  name='file_name' type='hidden'   value='{{$info->image}}'>
                                    </div>
                               
                                @else
                                    <div class="img-contianer selected_img" id="example_img">
                                        <img src="/assets/images/nophoto.png" width="40%" class="img-rounded ">
                                    </div>
                                @endif
                            </div>
                </div>
                
            </div>
             <div class="col-sm-3 col-xs-3 col-md-3 upload-images">
              <button  type="button" class="btn btn-danger uploadBtn"  id="uploadBtn" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
              <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
            
            </div>
        </div>
        <div class="form-group">
             <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">匠人作品:</label>  
            <div class="col-sm-5 col-xs-5 col-md-5">
                  @if(count($info->photos)>0)
                      @foreach($info->photos  as $k=>$v)
                      <img src="{{$v->file_name}}"   class='img img-rounded'  >
                      @endforeach
                  @else
                   无
                  @endif
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
    <div class="panel-footer"> 
        <div class="row " >
            <div class="col-sm-offset-10 col-sm-2  col-xs-10 col-md-10 col-md-2 col-xs-2">
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> 确定</button>
            </div>
        </div>
    </div>
    </form>
</div>
<script>
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
          var input=" <input  name='file_name' type='hidden'   value='"+file.data.fileName+"'></div>";
           node.find(".module-content").html(imgObj+input);
          //node.find(".module-content").html(imgObj);
       
        } else if (file.code==1) {
          $('#store_image').val('');
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