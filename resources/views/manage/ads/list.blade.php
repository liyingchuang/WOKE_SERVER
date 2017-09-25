@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
<link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
<div class="panel  panel-info">
    <div class="panel-heading">
        <ol class="breadcrumb">
            <li>广告分类</li>
            <li class="active">{{$info->category_name}}广告管理</li>
        </ol>
    </div>
    <div class="panel-body">
        <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals"> 
            <i class="glyphicon glyphicon-plus"></i>添加{{$info->category_name}}广告</button> 
    </div>
    <table class="table  table-bordered table-striped">
        <thead>
        <th>#</th>
        <th>广告名称</th>
        <th>广告链接</th>
        <th>广告图片</th>
        <th>广告显示</th>
        <th>开始时间</th>
        <th>结束时间</th>
        <th>编辑日期</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($list as $k=>$u)
            <tr @if($u->end_time<time()&&$u->enabled) class="danger" @endif >
                <td scope="row">{{$k+1}}</td>
                <td>{{$u->ad_name}}</td>
                <td>{{$u->ad_link}}</td>
                <td><a href="{{URL($u->ad_file)}}" target="_black" style="font-size: 20px"><i class="glyphicon glyphicon-picture"> </i></a></td>
                <td>
                   
                       <label>
                           <input class="checkbox-slider colored-blue yesno" title="{{$u->id}}" type="checkbox" @if($u->enabled) checked @endif>
                      <span class="text"></span>
                     </label>
                </td>
                <td>{{date('Y-m-d H:i:s',$u->start_time)}}</td>
                <td >{{date('Y-m-d H:i:s',$u->end_time)}} </td>
                <td>{{$u->updated_at}}</td>
                <td class="center ">
                    <button class="btn btn-danger  btn-xs delete"  data-target="#modal-danger" data-toggle="modal" data-whatever="{{$u->id}}"><i class="glyphicon glyphicon-trash"></i>删除</a>
                    <button class="btn btn-success  btn-xs edit" style="margin-left: 5px" data-ad_name="{{$u->ad_name}}" data-whatever="{{$u->id}}" data-ad_link="{{$u->ad_link}}" data-ad_file="{{URL($u->ad_file)}}" data-start_time="{{date('Y-m-d H:i',$u->start_time)}}" data-end_time="{{date('Y-m-d H:i',$u->end_time)}}" ><i class="glyphicon glyphicon-edit"></i> 编辑</button>
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
                <h4 class="modal-title" id="myModalLabel">添加{{$info->category_name}}广告</h4>
            </div>
            <form method="post"  action="{{URL::to('manage/ads')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                <div class="modal-body">
                       <div class="alert alert-danger" role="alert">
                        跳转地址格式:<br>
                        例如商品跳转地址:goods/16<br>
                        例如分类跳转地址:category/250<br>
                        例如URL跳转地址:http://www.baidu.com<br>

                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">广告名称:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input" name="ad_name" id="ad_name" title="广告名称" placeholder="输入广告名称" required="" data-bv-notempty-message="广告名称不能为空">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">广告链接:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required safe-input" name="ad_link" id="ad_link" title="广告链接" placeholder="输入广告链接" required="" data-bv-notempty-message="广告链接不能为空">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开始时间:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required date-picker" name="start_time" id="start_time"  title="开始时间" placeholder="请选择开始时间"
                                  data-fv-date="true"
                                                                data-fv-date-message="请选择开始时间"
                                                                data-fv-date-format="YYYY-MM-DD hh:ii"  >
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">结束时间:</label>
                        <div class="col-sm-10 col-xs-10 col-md-10">
                            <input type="text" class="form-control required date-picker" name="end_time" id="end_time"  title="结束时间" placeholder="请选择结束时间"  
                                                       data-fv-date="true"
                                                                data-fv-date-message="请选择结束时间"
                                                                data-fv-date-format="YYYY-MM-DD hh:ii" >
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
                            <input id="store_image" name="file_name" type="hidden" value="">
                            <input  id="ad_id" name="id" type="hidden" value="">
                            <input  name="advertisement_category_id" type="hidden" value="{{$info->id}}">
                            <br><p style="color: red">尺寸:{{$info->ad_width}}X{{$info->ad_height}}</p>
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
                <form action="{{URL::to('manage/ads/create')}}" method="get">
                <div class="modal-header">
                    <h4 class="modal-title">确定删除吗</h4>
                </div>
                <div class="modal-title">
                    <input type="hidden"  id="ads" name="id" value="">
                    <input type="hidden" name="category_id" value="{{$info->id}}">
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
$(".yesno").bind("change", function () {
   var id=$(this).attr('title');
   if($(this).is(':checked')) {
       $.get("{{URL::to('manage/ads')}}/"+id+"/edit", function(result){
            
       });
       $(this).attr("checked", true); 
   }else{
      $.get("{{URL::to('manage/ads')}}/"+id+"/edit", function(result){
            
       });
       $(this).attr("checked", false);
   }
});
$('#modal-danger').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget); // Button that triggered the modal
  var recipient = button.data('whatever'); // Extract info from data-* attributes
  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
  console.log('ok'+recipient);
  var modal = $(this);
  $('#ads').val(recipient);
 // modal.find('.modal-title input').val(recipient);
});
$(".edit").on("click",function(){
   var id=$(this).attr('data-whatever');
   var ad_name=$(this).attr('data-ad_name');
   var ad_link=$(this).attr('data-ad_link');
   var ad_file=$(this).attr('data-ad_file');
   var start_time=$(this).attr('data-start_time');
   var end_time=$(this).attr('data-end_time');
   $("#ad_name").val(ad_name);
   $("#ad_link").val(ad_link);
   $("#store_image").val(ad_file);
   $("#start_time").val(start_time);
   $("#end_time").val(end_time);
   $('#ad_id').val(id);
   $('#example_img img').attr('src',ad_file);
   $('#myModals').modal('show');
});

$('.date-picker').datetimepicker({ format: 'yyyy-mm-dd hh:ii'});
$('#html5Form').bootstrapValidator();
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
          //var input=" <input  name='file_name' type='hidden'   value='"++"'></div>";
           node.find(".module-content").html(imgObj);
          //node.find(".module-content").html(imgObj);
          $('#store_image').val(file.data.fileName);
          
        } else if (file.code==1) {
            alert('图片上传失败！');
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