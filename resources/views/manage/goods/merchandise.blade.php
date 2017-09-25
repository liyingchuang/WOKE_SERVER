@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li class="active">123</li>
            </ol>
        </div>
        <div class="panel-body">
            <div class="tabbable">
                <div class="tab-content">
                    <form class="form-horizontal" action="{{URL::to('manage/goods/addgoods')}}" method="post">
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">商品名称：</label>
                            <div class="col-sm-5"><input class="form-control" id="goods_name" name="goods_name" type="text" placeholder="商品名称"/></div>
                            <div class="col-sm-5"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label"><i class="glyphicon glyphicon-hand-right"></i>&nbsp;商品货号：</label>
                            <div class="col-sm-5">
                                <input class="form-control" id="goods_sn" name="goods_sn" type="text" placeholder="商品货号"/>
                                如果您不输入商品货号，系统自动生成一个唯一的货号。
                            </div>
                            <div class="col-sm-5"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">商品分类：</label>
                            <div class="col-sm-5"><input class="form-control" id="goods_name_style" name="goods_name_style" type="text" placeholder="商品分类"/></div>
                            <div class="col-sm-5"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">店内分类：</label>
                            <div class="col-sm-3" style="width:200px; height:100px; margin-left:15px; overflow-y:scroll; border: 1px solid grey;">
                                <table style="float:left; margin-left:20px;">
                                    <tr >
                                        <td><label><input type="checkbox" name="chk_brand[]" value="1" class="multi_checked"><span class="text"></span></label></td>
                                        <td>松石</td>
                                    </tr>
                                    <tr >
                                        <td><label><input type="checkbox" name="chk_brand[]" value="1" class="multi_checked"><span class="text"></span></label></td>
                                        <td>橄榄核</td>
                                    </tr>
                                    <tr >
                                        <td><label><input type="checkbox" name="chk_brand[]" value="1" class="multi_checked"><span class="text"></span></label></td>
                                        <td>南红玛瑙</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">本店售价：</label>
                            <div class="col-sm-1"><input class="form-control" id="shop_price" name="shop_price" type="text" placeholder="0"/></div>
                            <label for="market_price"  class="col-sm-1 control-label">市场售价：</label>
                            <div class="col-sm-1"> <input class="form-control" id="market_price" name="market_price" type="text" placeholder="0"/></div>
                            <div class="col-sm-7"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">限购日起：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="开始时间" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" id="promote_end_date" name="promote_end_date" placeholder="结束时间" class="form-control">
                            </div>
                            <div class="col-sm-6"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">上传图片：</label>
                            <div class="col-lg-3">
                                <button type="button" class="btn btn-default" id="uploadBtn"><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                                <input class="hide" id="fileupload" name="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                                <input id="store_image" name="images" type="hidden" value="">
								<div class="col-sm-5"><p style="color: red">图片尺寸:230X150</p></div>
                            </div>
                            <!--<label for="disabledSelect"  class="col-sm-1 control-label">上传缩略图：</label>
                            <div class="col-lg-3">
                                <button type="button" class="btn btn-default" id="uploadBtn"><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                                <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                                <input id="store_image" name="images" type="hidden" value="">
                                <br><p style="color: red">图片尺寸:230X150</p>
                            </div>-->
                            <div class="col-sm-2"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">商品品牌：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="商品品牌" class="form-control">
                            </div>
                            <label for="disabledSelect"  class="col-sm-1 control-label">商品作者：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="商品作者" class="form-control">
                            </div>
                            <div class="col-sm-5"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">商品产地：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="商品产地" class="form-control">
                            </div>
                            <label for="disabledSelect"  class="col-sm-1 control-label">商品重量：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="商品重量" class="form-control">
                            </div>
                            <div class="col-sm-5"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">商品材质：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="N/A" class="form-control">
                            </div>
                            <label for="disabledSelect"  class="col-sm-1 control-label">商品规格：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="N/A" class="form-control">
                            </div>
                            <div class="col-sm-5"></div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">商品库存量：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="N/A" class="form-control">
                            </div>
                            <label for="disabledSelect"  class="col-sm-1 control-label">限购数量：</label>
                            <div class="col-sm-2">
                                <input type="text" id="promote_start_date" name="promote_start_date" placeholder="N/A" class="form-control">
                            </div>
                            <div class="col-sm-5">在购买期限，每个用户最多能买多少件。0：不限购</div>
                        </div>
                        <div class="form-group">
                            <label for="disabledSelect"  class="col-sm-2 control-label">是否为免运费商品：</label>
                            <div class="col-sm-3">
                                <select class="input-xlarge" name="se">
                                    <option value="1">不免运费</option>
                                    <option value="2">免运费</option>
                                </select>
                                选择免运费方案
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8"></div>
                            <div class="col-sm-3">
                                <button class="btn btn-success">添加商品</button>
                            </div>
                            <div class="col-sm-1"></div>
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
    <script src='{{ asset("/assets/js/jq.js")}}'></script>
    <link rel="stylesheet" href='{{ asset("/assets/js/datetime/bootstrapDatepickr-1.0.0.css")}}'>
    <script src='{{ asset("/assets/js/datetime/bootstrapDatepickr-1.0.0.js")}}'></script>
    <script>
        $(document).ready(function() {
            $("#promote_start_date").bootstrapDatepickr({date_format: "d-m-Y"});
            $("#promote_end_date").bootstrapDatepickr({date_format: "d-m-Y"});
        });
        $("#uploadBtn").on("click", function() {
            $("#fileupload").trigger("click");
        });
        $('#fileupload').fileupload({
            url: "{{URL::to('manage/upload')}}?type=LeanCloud",
            dataType: 'json',
            autoUpload: true,
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
            maxFileSize: 5000000, 
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









