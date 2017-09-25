@extends('_layouts.master')
@section('content')
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
<link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{URL::asset('assets/js/simditor/styles/simditor.css')}}" />
<script type="text/javascript" src="{{URL::asset('assets/js/simditor/scripts/module.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/simditor/scripts/hotkeys.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/simditor/scripts/uploader.js')}}"></script>
<script type="text/javascript" src="{{URL::asset('assets/js/simditor/scripts/simditor.js')}}"></script>
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
</style>
<div class="panel  panel-info">
    <div class="panel-heading">
        商品积分兑换  /  商品编辑
    </div>
    <div class="panel-body">
        <div class="tabbable">
            <div class="tab-content">
                <form method="post"  action="{{URL::to('manage/integral/save')}}" class="form-horizontal" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 control-label">商品名称：</label>
                        <div class="col-sm-5">
                            <input name="id" id="id" value="{{  $goods_list -> id }}" type="hidden">
                            <input class="form-control" id="name" name="name" value="{{  $goods_list -> name }}" type="text" placeholder="商品名称" required="" data-bv-notempty-message="商品名称不能为空"/>
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 control-label">所需积分：</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control required safe-input" value="{{  $goods_list -> integral }}" id="integral" name="integral" placeholder="所需积分" min="-1" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="不能输入比0少的" data-bv-lessthan-inclusive="true" data-bv-message="限购数量不为空">
                        </div>
                        <label for="market_price"  class="col-sm-1 control-label">排序：</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control required safe-input" value="{{  $goods_list -> sort_order }}" id="sort_order" name="sort_order" placeholder="排序" required="" min="0" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="请输入大于1" data-bv-lessthan-inclusive="true" data-bv-message="排序不为空">
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 control-label">商品数量：</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control required safe-input" value="{{  $goods_list -> goods_number }}" id="goods_all_number" name="goods_all_number" placeholder="所需积分" required="" min="0" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="请输入大于1" data-bv-lessthan-inclusive="true" data-bv-message="所需积分不为空"  >
                        </div>
                        <label for="market_price"  class="col-sm-1 control-label">是否显示：</label>
                        <div class="col-sm-2">
                            <select name="is_show" id="is_show"  class="form-control">
                                <option value="0" @if ($goods_list->is_show == 0) selected @endif >显示</option>
                                <option value="1" @if ($goods_list->is_show == 1) selected @endif >不显示</option>
                            </select>
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 control-label">商品照片：</label>
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
                                    @if(!$goods_list->goods_thumb)
                                        <img src="/assets/images/nophoto.png" width="50%" class="img-rounded " id="idcard_front_img">
                                    @else
                                        <img src="{{$_ENV['QINIU_HOST']}}/{{ $goods_list->goods_thumb }}" class="img-rounded " id="idcard_front_img">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-3 col-md-3">
                            <button type="button" class="btn btn-default uploadBtn" alt="idcard_front" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                            <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                            <input  name="idcard_front" id="idcard_front" type="hidden" value="{{$goods_list->goods_thumb}}">
                            <div><font color="red">必须是正方形 大小500X500</font></div>
                        </div>
                        <div class="col-sm-2 col-xs-2 col-md-2"></div>
                    </div>
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 col-xs-2 col-md-2 control-label">商品介绍：</label>
                        <div class="col-sm-9 col-xs-9 col-md-9">
                            <textarea id="editor" name="editorValue" placeholder="商品介绍" autofocus>{{  $goods_list -> goods_desc }}</textarea>
                        </div>
                        <div class="col-sm-1 col-xs-1 col-md-1">
                        </div>
                    </div>
                    <div class="form-group">
                         <div class="row " >
                            <div class="col-sm-offset-10 col-sm-2  col-xs-10 col-md-10 col-md-2 col-xs-2">
                                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> 编辑保存</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="row text-right" style="padding-right: 10px" >
        </div>
    </div>
</div>
<script>
//编辑器
(function() {
  $(function() {
    var $preview, editor, mobileToolbar, toolbar;
    toolbar = ['title', 'bold', 'italic', 'underline', 'strikethrough', 'fontScale', 'color', '|', 'ol', 'ul', 'blockquote', 'table', '|',  'image', 'hr', '|', 'indent', 'outdent', 'alignment'];
    editor = new Simditor({
      textarea: $('#editor'),
      placeholder: '商品描述...',
      toolbar: toolbar,
      pasteImage: true,
      defaultImage: "{{URL::asset('assets/js/simditor/images/image.png')}}",
      upload:{
        url: "{{URL::to('manage/upload')}}?type=simditor",
        fileKey:'files',
        leaveConfirm: '正在上传文件'  
      }
    });
  });
}).call(this);
//编辑器结束
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
        $("#" + u_option+"_img").attr('src',file.data.url);
        $("#" + u_option).val(file.data.fileName);
    } else {
        alert('上传出错!');
    }
}).on('fileuploadfail', function(e, data) {
    alert('上传出错!');
});
$(function(){
    $('#all .editable').editable('toggleDisabled');
    $.fn.editable.defaults.mode = 'popup';
    $('.supple_order').editable({
        name: 'supple_sort_order',
        success: function(response, newValue) {
            if(response.status == 'error') return response.msg; //msg will be shown in editable form
        }
    });
});
</script>
@stop