@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
<script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
<script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
<link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
<script src="/assets/js/editable/bootstrap-editable.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        入驻商列表
    </div>
    <div class="panel-body">
        <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals"> 
            <i class="glyphicon glyphicon-plus"></i>申请开店铺</button>
    </div>
    <table class="table  table-bordered table-striped" id="all">
        <thead>
        <th>#</th>
        <th>店铺名</th>
        <th>联系人</th>
        <th>电话</th>
        <th>状态</th>
        <th>店铺街开关</th>
        <th>会员ID</th>
        <th>排序</th>
        <th>申请日期</th>
        <th>操作</th>
        </thead>
        <tbody>
            @foreach ($list as $k=>$u)
            <tr>
                <td scope="row">{{$k+1}}</td>
                <td>{{$u->supplier_name}}</td>
                <td>{{$u->contacts_name}}</td>
                <td>{{$u->contacts_phone}}</td>
                <td>
                <label>
                    <input class="checkbox-slider slider-icon colored-darkorange offon" type="checkbox"  title="{{$u->supplier_id}}" @if($u->status) checked @endif>
                    <span class="text"></span>
                </label>
                </td>
                <td>
                    <label>
                        <input class="checkbox-slider colored-blue yesno" title="{{$u->supplier_id}}" type="checkbox" @if($u->enabled) checked @endif>
                               <span class="text"></span>
                    </label>  
                </td>
                <td>{{$u->user_id}}</td>
                <td><span  class="supple_order" data-type="text" data-pk="{{$u->supplier_id}}" data-url="{{URL::to('manage/store/addstore')}}" data-title="输入排序">{{$u->supple_sort_order}}</span></td>
                <td><?php echo date('Y-m-d H:i', $u->add_time + 28800); ?></td>
                <td class="center ">
                    <a href="{{URL::to('manage/store/show')}}?supplier_id={{$u->supplier_id}}" class="btn btn-success  btn-xs edit">
                        <i class="glyphicon glyphicon-edit"></i>  编辑店铺</a>
                        <!--
                    <button class="btn btn-info  btn-xs edit" style="margin-left: 5px"><i class="glyphicon glyphicon-edit"></i> 编辑公司</button>-->
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
                <h4 class="modal-title" id="myModalLabel">申请开店铺</h4>
            </div>
            <form method="post"  id="addForm" action="{{URL::to('manage/store/addstore')}}" class="form-horizontal form-bordered" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
            <div class="modal-body">
                 <h5><b>店铺信息</b></h5>
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">申请人登录手机号:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="mobil" id="mobil" title="输入申请开店人App登录手机号／Email" placeholder="输入申请开店人App登录手机号／Email" required="" data-bv-notempty-message="手机号不能为空">
                    </div>
                </div>
              
                 <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">店铺名称:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="company_name" id="company_name" title="输入店铺名称" placeholder="输入店铺名称" required="" data-bv-notempty-message="店铺名称空">
                    </div>
                </div>
              <!--   <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">地址:</label>
                    <div class="col-sm-3 col-xs-3 col-md-3">
                         <select  class="form-control e1" name="province" id="province" data-bv-notempty="true"
                                                data-bv-notempty-message="不能为空" >
                         <option value="">请选择</option>
                        @foreach ($province as $k=>$u)
                          <option value="{{$u->region_id}}" >{{$u->region_name}}</option>
                        @endforeach
                         </select>
                    </div>
                    <div class="col-sm-3 col-xs-3 col-md-3">
                        <select  class="form-control e1"  name="city" id="city" data-bv-notempty="true"
                                                data-bv-notempty-message="不能为空" >
                            <option value="">请选择</option>
                        </select>
                    </div>
                    <div class="col-sm-3 col-xs-3 col-md-3">
                        <select  class="form-control e1"  name="district" id="district" data-bv-notempty="true"
                                                data-bv-notempty-message="不能为空" >
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>-->
                <div class="form-group">
                      <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">详细地址:</label>
                      <div class="col-sm-9 col-xs-9 col-md-9">
                            <input type="text" class="form-control required safe-input" name="address" id="address" title="输入详细地址" placeholder="输入详细地址" required="" data-bv-notempty-message="输入详细地址">
                      </div>
                </div>
                 
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">客服电话:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="tel" id="tel" title="输入客服电话" placeholder="输入客服电话" required="" data-bv-notempty-message="输入客服电话">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">电子邮件:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="email" id="email" title="输入电子邮件" placeholder="输入电子邮件" required="" data-bv-notempty-message="输入电子邮件">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">店铺描述:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <textarea class="form-control required safe-input" name="content" id="content" title="输入店铺描述" placeholder="输入店铺描述" required="" data-bv-notempty-message="输入店铺描述"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label">店铺照片:</label>
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
                                <img src="/assets/images/nophoto.png" width="50%" class="img-rounded " id="supplier_img">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 col-xs-3 col-md-3">
                        <button type="button" class="btn btn-default uploadBtn" alt="supplier" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                        <input  name="supplier" id="supplier" type="hidden" value="">
                    </div>
                </div>
                <hr class="wide">
                <h5><b>商户个人信息</b></h5>
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">开户行银行帐号:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="bank_account_number" id="bank_account_number" title="输入开户行银行帐号" placeholder="输入开户行银行帐号" required="" data-bv-notempty-message="输入开户行银行帐号">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">开户行支行名称:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input"  name="bank_name" id="bank_name"  title="输入开户行支行名称" placeholder="输入开户行支行名称" required="" data-bv-notempty-message="输入联系人姓名">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">开户人姓名:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="bank_account_name" id="bank_account_name" title="输入开户人姓名" placeholder="输入开户人姓名" required="" data-bv-notempty-message="输入开户人姓名">
                    </div>
                </div>
             <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">联系电话:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="contacts_phone" id="contacts_phone" title="输入开户人联系电话" placeholder="输入开户人联系电话" required="" data-bv-notempty-message="输入开户人联系电话">
                    </div>
             </div>
             <div class="form-group">
                    <label for="uuid" class="col-sm-3 col-xs-3 col-md-3 control-label no-padding-right">开户人身份证号:</label>
                    <div class="col-sm-9 col-xs-9 col-md-9">
                        <input type="text" class="form-control required safe-input" name="id_card_no" id="id_card_no" title="输入开户人身份证号" placeholder="输入开户人身份证号" required="" data-bv-notempty-message="输入开户人身份证号">
                    </div>
             </div>  
                <div class="form-group">
                    <label for="uuid" class="col-sm-4 col-xs-4 col-md-4 control-label no-padding-right">开户人身份证照片正面:</label>
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
                            <input  name="idcard_front" id="idcard_front" type="hidden" value="">
                    </div>
             </div>  
             <div class="form-group">
                    <label for="uuid" class="col-sm-4 col-xs-4 col-md-4 control-label no-padding-right">开户人身份证照片反面:</label>
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
                                    <img src="/assets/images/nophoto.png" width="50%" class="img-rounded " id="idcard_reverse_img">
                                </div>
                            </div>
                    </div>
                    <div class="col-sm-3 col-xs-3 col-md-3">
                         <button type="button" class="btn btn-default uploadBtn" alt="idcard_reverse" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                         <input  name="idcard_reverse" id="idcard_reverse" type="hidden" value="">
                    </div>
             </div>
             <div class="form-group">
                    <label for="uuid" class="col-sm-4 col-xs-4 col-md-4 control-label no-padding-right">开户人手持身份证照片:</label>
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
                                    <img src="/assets/images/nophoto.png" width="50%" class="img-rounded " id="handheld_idcard_img">
                                </div>
                            </div>
                    </div>
                    <div class="col-sm-3 col-xs-3 col-md-3">
                         <button type="button" class="btn btn-default uploadBtn"  alt="handheld_idcard"><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                         <input  name="handheld_idcard" id="handheld_idcard" type="hidden" value="">
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
$(".yesno").bind("change", function() {
        var id = $(this).attr('title');
        if ($(this).is(':checked')) {
            $.get("{{URL::to('manage/store')}}/edit?id=" + id+'&on=yesno', function(result) {

            });
            $(this).attr("checked", true);
        } else {
            $.get("{{URL::to('manage/store')}}/edit?id=" + id+'&on=yesno', function(result) {

            });
            $(this).attr("checked", false);
        }
});
$(".offon").bind("change", function() {
        var id = $(this).attr('title');
        if ($(this).is(':checked')) {
            $.get("{{URL::to('manage/store')}}/edit?id=" + id+'&on=offon', function(result) {

            });
            $(this).attr("checked", true);
        } else {
            $.get("{{URL::to('manage/store')}}/edit?id=" + id+'&on=offon', function(result) {

            });
            $(this).attr("checked", false);
        }
}); 
$("#addForm").bootstrapValidator({
    feedbackIcons: {
         valid: 'glyphicon glyphicon-ok',
         invalid: 'glyphicon glyphicon-remove',
         validating: 'glyphicon glyphicon-refresh'
     },
     fields: {
         mobil: {
             validators: {
                 notEmpty: {
                     message: '开店人App登录手机号不能为空'
                 },
                 remote: {
                     url: "{{URL::to('manage/user/create')}}",
                     type: 'get',
                     data: {
                            tel: function(validator)
                            {
                               return $('#mobil').val();
                            }
                      },
                     message: '开店人App登录手机号不正确！'  
                 }
             }
         }
     }
 }).on('change', '[id="province"]', function(e) {
   var select_val = $(this).children('option:selected').val(); 
      $.ajax({type:'GET',
             url:"{{url('manage/store/city')}}?region_id="+select_val,
             cache:false,
             success:function(msg){ 
                $('#city').html(msg);
         }
   });
}).on('change', '[id="city"]', function(e) {
    var option= $(this).children('option:selected').val();
    console.log(option);
    $.ajax({type:'GET',
           url:"{{url('manage/store/city')}}?region_id="+option,
           cache:false,
             success:function(msg){ 
             $('#district').html(msg);
          }
    });
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
       // var node=$("#" + u_option+"_img");
        if (file.code == 0) {
           // node.find('src').remove(); 
           // node.append();
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