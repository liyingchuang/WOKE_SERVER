@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<script src="{{URL::asset('assets/js/select2/select2.js')}}"></script>
<script src="{{URL::asset('assets/js/toastr/toastr.js')}}"></script>
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
        编辑店铺信息
    </div>
    <div class="panel-body">

    </div>
    <form method="post"  action="{{URL::to('manage/store/store')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
        <h5><b> 店铺信息</b></h5>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">商店名称:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">
                {{ csrf_field() }}
                <input type="hidden" name="supplier_id" value="{{$store->supplier_id}}">
                <input type="text" class="form-control required safe-input" name="supplier_name"   value="{{$store->supplier_name}}"  title="商店名称" required="" data-bv-notempty-message="商店名称不能为空">
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">商店LOGO:</label>
            <div class="col-sm-4 col-xs-4 col-md-4">
                <input type="text" class="form-control  safe-input" id="file_logo" name="logo_file" @if(!empty($store_info))  value="{{$store_info->logo_file}}" @endif>
            </div>
            <div class="col-sm-1 col-xs-1 col-md-1">尺寸:120x80</div>
            <div class="col-sm-2 col-xs-2 col-md-2">
                <button  type="button" class="btn btn-danger tooltip-danger uploadBtn"  alt="file_logo"  data-toggle="tooltip" data-placement="top" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
            </div>
            <div class="col-sm-3 col-xs-3 col-md-3">
            </div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">店招图片:</label>
            <div class="col-sm-4 col-xs-4 col-md-4">
                <input type="text" class="form-control  safe-input" id="banner_file" name="banner_file" @if(!empty($store_info))  value="{{$store_info->banner_file}}" @endif >
            </div>
            <div class="col-sm-1 col-xs-1 col-md-1">尺寸:750x250</div>
            <div class="col-sm-2 col-xs-2 col-md-2">
                <button  type="button" class="btn btn-danger tooltip-danger uploadBtn"  alt="banner_file"  data-toggle="tooltip" data-placement="top"><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
            </div>
            <div class="col-sm-3 col-xs-3 col-md-3">
            </div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">客服电话:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <input type="text" class="form-control required safe-input" name="tel"  value="{{$store->tel}}" placeholder="客服电话" required="" data-bv-notempty-message="店铺名不能为空">
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">详细地址:</label>
            <div class="col-sm-6 col-xs-6 col-md-6">
                <input type="text" class="form-control required safe-input" name="address" value="{{$store->address}}" placeholder="详细地址" required="" data-bv-notempty-message="详细地址不能为空">
            </div>
            <div  class="col-sm-3 col-xs-3 col-md-3">
             </div>
            <div class="col-sm-2 col-xs-2 col-md-2"></div>
        </div>
        <div class="form-group" id="store_key">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">商店关键字标签<a href="javascript:;" onclick="addKey(this)">[ + ]</a>:</label>
			@if(!empty($store_info->keyword))
			@foreach ($store_info->keyword as $k)
            <div class="col-sm-2 col-xs-2 col-md-2">
                <input type="text" class="form-control" name="keyword[]" size="4" maxlength="4"   value="{{$k}}"  placeholder="商店关键字标签">
            </div>
			@endforeach
			@else
				<div class="col-sm-2 col-xs-2 col-md-2">
                    <input type="text" class="form-control" name="keyword[]" size="4" maxlength="4"   value=""  placeholder="商店关键字标签">
            </div>
			@endif
            <div class="col-sm-4 col-xs-4 col-md-4">每个不可超过4个字，用于搜索和商店街展示</div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">商店介绍:</label>
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
                        @if(!empty($store_info))
                        @foreach($store_info->supplier_desc  as $k=>$v)
                        <div class="thumbnail img-contianer selected_img">
                            <img src="{{$_ENV['QINIU_HOST']}}/{{$v}}"   class='img img-rounded'  ><span  class='remove_img' onclick='mydelete(this)' ><i class='fa fa-fw fa-times'></i></span>
                            <input  name='desc[]' type='hidden'   value='{{$v}}'>
                        </div>
                        @endforeach
                        @else
                        <div class="img-contianer selected_img" id="example_img">
                            <img src="/assets/images/nophoto.png" width="40%" class="img-rounded ">
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-3 col-md-3 upload-images">
                <button  type="button" class="btn btn-danger uploadBtnDesc"  ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                <input class="hide" id="fileuploadDesc" type="file" name="files" accept="image/gif,image/jpg,image/png">
                <p></p>
                <div class="alert alert-info">
                    商店介绍图片尺寸为:750X500
                </div>
            </div>
        </div>
		<div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">上传二维码:</label>
            <div class=" col-sm-5 col-xs-5 col-md-5 clearfix">
                <div class="module-uploading">
                    <div class="upload-progress-container">
                        <div id="progress" class="progress hide">
                            <div class="progress-bar progress-bar-primary"></div>
                        </div>
                    </div>
                </div>
                <div class="module-content">
                    <div class="img-contianer selected_img"  >
                        <img id="qr_code_img" @if(!empty($store_info->qr_code)) src="{{$_ENV['QINIU_HOST']}}/{{$store_info->qr_code}}" @else src="/assets/images/nophoto.png"  @endif width="40%" class="img-rounded ">
                    </div>
                </div>
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <button type="button" class="btn btn-danger uploadBtn" alt="qr_code" ><i class="glyphicon glyphicon-upload"></i> 上传二维码</button>
                <input  name="qr_code" id="qr_code" type="hidden" value="">
            </div>
        </div>   
        @if($manage_role!='supplier')
        <hr class="wide">
        <h5><b> 商户个人信息</b></h5>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开户行银行帐号:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <input type="text" class="form-control required safe-input" value="{{$store->bank_account_number}}" name="bank_account_number" id="bank_account_number" title="输入开户行银行帐号" placeholder="输入开户行银行帐号" required="" data-bv-notempty-message="输入开户行银行帐号">
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开户行支行名称:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <input type="text" class="form-control required safe-input"  value="{{$store->bank_name}}" name="bank_name" id="bank_name"  title="输入开户行支行名称" placeholder="输入开户行支行名称" required="" data-bv-notempty-message="输入联系人姓名">
            </div>
             <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开户人姓名:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <input type="text" class="form-control required safe-input" name="bank_account_name" value="{{$store->bank_account_name}}" id="bank_account_name" title="输入开户人姓名" placeholder="输入开户人姓名" required="" data-bv-notempty-message="输入开户人姓名">
            </div>
              <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">联系电话:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <input type="text" class="form-control required safe-input" name="contacts_phone"  value="{{$store->contacts_phone}}" id="contacts_phone" title="输入开户人联系电话" placeholder="输入开户人联系电话" required="" data-bv-notempty-message="输入开户人联系电话">
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开户人身份证号:</label>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <input type="text" class="form-control required safe-input" name="id_card_no" value="{{$store->id_card_no}}" id="id_card_no" title="输入开户人身份证号" placeholder="输入开户人身份证号" required="" data-bv-notempty-message="输入开户人身份证号">
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5"></div>
        </div>  
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开户人身份证照片正面:</label>
            <div class=" col-sm-5 col-xs-5 col-md-5 clearfix">
                <div class="module-uploading">
                    <div class="upload-progress-container">
                        <div id="progress" class="progress hide">
                            <div class="progress-bar progress-bar-primary"></div>
                        </div>
                    </div>
                </div>
                <div class="module-content">
                    <div class="img-contianer selected_img" >
                        <img  id="idcard_front_img"  @if(!empty($store->idcard_front)) src="{{$_ENV['QINIU_HOST']}}/{{$store->idcard_front}}" @else src="/assets/images/nophoto.png"  @endif width="50%" class="img-rounded " >
                    </div>
                </div>
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5"> 
                <button type="button" class="btn btn-default codeuploadBtn" alt="idcard_front" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                <input class="hide" id="codefileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
              <input  name="idcard_front" id="idcard_front" type="hidden" value="{{$store->idcard_front}}">
            </div>
        </div>  
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开户人身份证照片反面:</label>
            <div class=" col-sm-5 col-xs-5 col-md-5 clearfix">
                <div class="module-uploading">
                    <div class="upload-progress-container">
                        <div id="progress" class="progress hide">
                            <div class="progress-bar progress-bar-primary"></div>
                        </div>
                    </div>
                </div>
                <div class="module-content">
                    <div class="img-contianer selected_img"  >
                        <img id="idcard_reverse_img"  @if(!empty($store->idcard_reverse)) src="{{$_ENV['QINIU_HOST']}}/{{$store->idcard_reverse}}" @else src="/assets/images/nophoto.png"  @endif  width="50%" class="img-rounded ">
                    </div>
                </div>
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <button type="button" class="btn btn-default codeuploadBtn" alt="idcard_reverse" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                <input  name="idcard_reverse" id="idcard_reverse" type="hidden" value="{{$store->idcard_reverse}}">
            </div>
        </div>   
        <div class="form-group">
            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开户人手持身份证照片:</label>
            <div class=" col-sm-5 col-xs-5 col-md-5 clearfix">
                <div class="module-uploading">
                    <div class="upload-progress-container">
                        <div id="progress" class="progress hide">
                            <div class="progress-bar progress-bar-primary"></div>
                        </div>
                    </div>
                </div>
                <div class="module-content">
                    <div class="img-contianer selected_img"  >
                        <img id="handheld_idcard_img" @if(!empty($store->handheld_idcard)) src="{{$_ENV['QINIU_HOST']}}/{{$store->handheld_idcard}}" @else src="/assets/images/nophoto.png"  @endif  width="50%" class="img-rounded ">
                    </div>
                </div>
            </div>
            <div class="col-sm-5 col-xs-5 col-md-5">
                <button type="button" class="btn btn-default codeuploadBtn"  alt="handheld_idcard" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                <input  name="handheld_idcard" id="handheld_idcard" type="hidden" value="{{$store->handheld_idcard}}">
            </div>
        </div> 
        @endif
        <div class="panel-footer"> 
            <div class="row " >
                <div class="col-sm-offset-10 col-sm-2  col-xs-10 col-md-10 col-md-2 col-xs-2">
                    <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> 确定编辑</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
//标签管理
function addKey(e){
	if($("#store_key").find("div").length < 4){
		$(e).parent().after("<div class='col-sm-2 col-xs-2 col-md-2'><input type='text' class='form-control' name='keyword[]' size='4' maxlength='4' placeholder='商店关键字标签'></div>");
	}else{
            alert("最多添加3个关键字");
	}
}
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
        //Notify('无法添加订单 因为销售都不在线！', 'bottom-right', '5000', 'danger', 'fa-desktop', true);  
});
//身份证上🚢
@if($manage_role!='supplier')
var code_u_option = '';
$(".codeuploadBtn").on("click", function() {
        var filename = $(this).attr('alt');
        console.log(filename);
        code_u_option = filename;
        $("#codefileupload").trigger("click");
    });
$('#codefileupload').fileupload({
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
       // var node=$("#" + u_option+"_img");
        if (file.code == 0) {
           // node.find('src').remove(); 
           // node.append();
            $("#" + code_u_option+"_img").attr('src',file.data.url);
            $("#" + code_u_option).val(file.data.fileName);
        } else {
            alert('上传出错!');
        }
}).on('fileuploadfail', function(e, data) {
        alert('上传出错!');
});  
@endif
//表单验证
$('#html5Form').bootstrapValidator().on('change', '[id="province"]', function(e) {
        var option = $(this).children('option:selected').val();
        console.log(option);
        $.ajax({type: 'GET',
            url: "{{url('manage/store/city')}}?region_id=" + option,
            cache: false,
            success: function(msg) {
                console.log(msg);
                $('#city').html(msg);
            }
        });
}).on('change', '[id="city"]', function(e) {
        var option = $(this).children('option:selected').val();
        console.log(option);
        $.ajax({type: 'GET',
            url: "{{url('manage/store/city')}}?region_id=" + option,
            cache: false,
            success: function(msg) {
                $('#district').html(msg);
            }
        });
});
//文件上传相关
var u_option = 'file_logo';
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
            $("#" + u_option).val(file.data.fileName);
			$("#" + u_option+"_img").attr('src',file.data.url);
        } else {
            alert('上传出错!');
        }
}).on('fileuploadfail', function(e, data) {
        alert('上传出错!');
});
//删除图片
function mydelete(obj) {
        var html = $(obj).prev().attr('src');
        $(obj).parent().remove();
}
//多文件上传
$(".uploadBtnDesc").on("click", function() {
        $("#fileuploadDesc").trigger("click");
});
$('#fileuploadDesc').fileupload({
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
        var node = $(".img-content-container");
        node.find(".module-uploading").addClass("hide");
        if (file.code == 0) {
            node.find('#example_img').remove();
            var imgObj = "<div class='thumbnail img-contianer'><img src='" + file.data.url + "' class='img img-rounded'  /> <span  class='remove_img' onclick='mydelete(this)' ><i class='fa fa-fw fa-times'></i></span>";
            var input = " <input  name='desc[]' type='hidden'   value='" + file.data.fileName + "'></div>";
            node.find(".module-content").append(imgObj + input);
        } else if (file.code == 1) {
            alert(file.info);
        }
}).on('fileuploadfail', function(e, data) {
        alert('上传出错!请检查图片大小');
}).on('fileuploadprogressall', function(e, data) {

        var progress = parseInt(data.loaded / data.total * 100, 10);
        $('#progress .progress-bar').css(
                'width',
                progress + '%'
                );
}).on('fileuploadadd', function(e, data) {
        $.each(data.files, function(index, file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var image = new Image();
                image.onload = function() {
                    console.log(this.width, this.height);

                };
                image.src = e.target.result;
                console.log(image);
                if (image.width != 750 && image.height != 500) {
                    alert('你上传的图片不是750X500像素的图片！');
                    return false;
                }
            };
            reader.readAsDataURL(file);
            console.log('ok');
    });
});

</script>
@stop