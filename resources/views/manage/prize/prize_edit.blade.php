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
        <ol class="breadcrumb">
            <li>奖励池物品</li>
            <li class="active">商品编辑</li>
        </ol>
    </div>
    <div class="panel-body">
        <div class="tabbable">
            <div class="tab-content">
                <form method="post"  action="{{URL::to('manage/prize/save')}}" class="form-horizontal" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 control-label">商品名称：</label>
                        <div class="col-sm-5">
                            <input name="id" id="id" value="{{  $prize_goods -> id }}" type="hidden">
                            <input class="form-control" id="name" name="name" value="{{  $prize_goods -> name }}" type="text" placeholder="商品名称" required="" data-bv-notempty-message="商品名称不能为空"/>
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="market_price"  class="col-sm-2 control-label">商品库存：</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control required safe-input" value="{{  $prize_goods -> num }}" id="num" name="num" placeholder="商品数量" required="" min="0" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="请输入大于1" data-bv-lessthan-inclusive="true" data-bv-message="商品数量不为空">
                        </div>
                        <label for="disabledSelect"  class="col-sm-1 control-label">是否显示：</label>
                        <div class="col-sm-2">
                            <select name="is_show" id="is_show"  class="form-control">
                                <option value="1" @if ($prize_goods->is_show == 1) selected @endif >显示</option>
                                <option value="0" @if ($prize_goods->is_show == 0) selected @endif >不显示</option>
                            </select>
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 control-label">商品状态：</label>
                        <div class="col-sm-2">
                            <select name="type" id="type"  class="form-control" onchange="fragment_goods()">
                                <option value="1" @if ($prize_goods->type == 1) selected @endif >实物</option>
                                <option value="2" @if ($prize_goods->type == 2) selected @endif >碎片</option>
                                <option value="3" @if ($prize_goods->type == 3) selected @endif >红包</option>
                                <option value="5" @if ($prize_goods->type == 5) selected @endif >积分</option>
                            </select>
                        </div>
                        <div class="col-sm-8"></div>
                    </div>

                    <div class="form-group" @if ($prize_goods->type != 2) style="display:none;" @endif id="trade">
                        <label for="market_price"  class="col-sm-2 control-label">兑换所需：</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control required safe-input" value="{{  $prize_goods -> fragment }}" id="fragment" name="fragment" placeholder="所需碎片数量" min="-1" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="不能输入比0少的" data-bv-lessthan-inclusive="true" data-bv-message="所需碎片数量不为空">
                        </div>
                        <label for="disabledSelect"  class="col-sm-1 control-label">兑换物品：</label>
                        <div class="col-sm-2">
                            <select name="trade_goods" id="trade_goods"  class="form-control" >
                                <option value="0">请选择</option>
                                @foreach ($prize as $key=>$val)
                                    <option value="{{$val->id}}" @if($prize_goods->trade_goods==$val->id) selected @endif>{{$val->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                    <div class="form-group"  @if ($prize_goods->type != 5) style="display:none;" @endif id="integral">
                        <label for="disabledSelect"  class="col-sm-2 control-label">最小积分：</label>
                        <div class="col-sm-2">
                            <input type="text" value="{{  $prize_goods -> min  }}" class="form-control required safe-input" id="min" name="min" required="" data-bv-notempty-message="最小积分" placeholder="最小积分" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="不能输入比0少的" data-bv-lessthan-inclusive="true" data-bv-message="最小积分不为空">
                        </div>
                        <label for="disabledSelect"  class="col-sm-1 control-label">最大积分：</label>
                        <div class="col-sm-2">
                            <input type="text" value="{{  $prize_goods -> max }}" class="form-control required safe-input" id="max" name="max" required="" data-bv-notempty-message="最大积分" placeholder="最大积分" data-bv-greaterthan-inclusive="false" data-bv-greaterthan-message="不能输入比0少的" data-bv-lessthan-inclusive="true" data-bv-message="最大积分不为空">
                        </div>
                        <div class="col-sm-5"></div>
                    </div>
                    <div class="form-group"></div>
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
                                    @if(!$prize_goods->image)
                                        <img src="/assets/images/nophoto.png" width="50%" class="img-rounded " id="idcard_front_img">
                                    @else
                                        <img src="{{ $prize_goods->image }}" class="img-rounded " id="idcard_front_img">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3 col-xs-3 col-md-3">
                            <button type="button" class="btn btn-default uploadBtn" alt="idcard_front" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                            <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                            <input  name="idcard_front" id="idcard_front" type="hidden" value="{{$prize_goods->image}}">
                            <div><font color="red">必须是正方形 大小500X500</font></div>
                        </div>
                        <div class="col-sm-2 col-xs-2 col-md-2"></div>
                    </div>
                    <div class="form-group">
                        <label for="disabledSelect"  class="col-sm-2 col-xs-2 col-md-2 control-label">商品介绍：</label>
                        <div class="col-sm-9 col-xs-9 col-md-9">
                            <textarea id="editor" name="editorValue" placeholder="商品介绍" autofocus>{{  $prize_goods->desc }}</textarea>
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
//碎片添加物品
function fragment_goods() {
    type = $('#type').val();
    name = $('#name').val();
    if(type == 2){
        $('#fragment').val("");
        $('#trade').show();
        $.ajax({
            url:"/manage/prize/fragment",
            data:{"name":name},
            type:"get",
            cache:false,
            success:function(msg){
                $('#trade_goods').html(msg);
            }
        });
        $('#max').val(0);
        $('#min').val(0);
        $('#integral').hide();
    }else if(type == 5){
        $('#max').val("");
        $('#min').val("");
        $('#integral').show();
        $("#trade_goods").prepend("<option value='0' selected>请选择</option>"); //在前面插入一项option
        $('#fragment').val(1);
        $('#trade').hide();
    }else{
        $("#trade_goods").prepend("<option value='0' selected>请选择</option>"); //在前面插入一项option
        $('#fragment').val(1);
        $('#trade').hide();

        $('#max').val(0);
        $('#min').val(0);
        $('#integral').hide();
    }
}

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