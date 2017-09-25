@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/flot/0.8.3/jquery.flot.min.js"></script>
    <script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
    <link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
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
    </style>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li>奖励池物品</li>
                <li class="active">添加物品</li>
            </ol>
        </div>
        <div class="panel-body">
            <div class="tabbable">
                <div class="tab-content">
                    <form method="post"  action="{{URL::to('manage/prize/saveactivity')}}" class="form-horizontal" id="html5Form" data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok" data-bv-feedbackicons-invalid="glyphicon glyphicon-remove" data-bv-feedbackicons-validating="glyphicon glyphicon-refresh" >
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">活动名称:</label>
                            <div class="col-sm-3 col-xs-3 col-md-3">
                                <input type="hidden" name="id" id="id" value="{{$prize->id}}">
                                <input type="text" class="form-control required safe-input" name="name" id="name" value="{{$prize->name}}" title="输入活动名称" placeholder="输入活动名称" required="" data-bv-notempty-message="活动名称空">
                            </div>
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">活动所需积分:</label>
                            <div class="col-sm-3 col-xs-3 col-md-3">
                                <input type="text" class="form-control required safe-input" name="size" id="size" value="{{$prize->size}}" title="输入活动所需积分" placeholder="输入活动所需积分" required="" data-bv-notempty-message="输入活动所需积分">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开始时间:</label>
                            <div class="col-sm-3 col-xs-3 col-md-3">
                                <input type="text" class="form-control required date-picker minView: 2" name="start_time" id="start_time" value="{{$prize->start_time}}"  title="开始时间" placeholder="活动开始时间"
                                       data-fv-date="true" required=""
                                       data-fv-date-message="请选择开始时间段"
                                       data-bv-notempty-message="选择开始时间段"
                                       data-fv-date-format="YYYY-MM-DD" />
                            </div>
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">结束时间:</label>
                            <div class="col-sm-3 col-xs-3 col-md-3">
                                <input type="text" class="form-control required date-picker" name="end_time" id="end_time" value="{{$prize->end_time}}" title="结束时间" placeholder="活动结束时间"
                                       data-fv-date="true" required=""
                                       data-fv-date-message="请选择结束时间段"
                                       data-bv-notempty-message="选择结束时间段"
                                       data-fv-date-format="YYYY-MM-DD" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">画布:</label>
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
                                        @if(!$prize->image)
                                            <img src="{{ $prize->image }}" width="50%" class="img-rounded " id="idcard_front_img">
                                        @else
                                            <img src="{{ $prize->image }}" class="img-rounded " id="idcard_front_img">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 col-xs-3 col-md-3">
                                <button type="button" class="btn btn-default uploadBtn" alt="idcard_front" ><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                                <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                                <input  name="idcard_front" id="idcard_front" type="hidden" value="{{ $prize->image }}">
                            </div>
                            <div class="col-sm-2 col-xs-2 col-md-2"></div>
                        </div>
                        <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">奖励池物品:</label>
                            <div class="col-sm-8 col-xs-8 col-md-8">
                                <table class="table  table-bordered table-striped">
                                    <thead>
                                    <th></th>
                                    <th>编号</th>
                                    <th>奖励名称</th>
                                    <th>奖励图片</th>
                                    <th>商品数量</th>
                                    <th>用户兑换所需碎片数量</th>
                                    <th>所属种类</th>
                                    <th>概率</th>
                                    </thead>
                                    <tbody>
                                    <!--                   'id','name' ,'num','fragment','type'         ---->
                                    @foreach ($prize_goods as $key => $val)
                                        <tr>
                                            <td scope="row"><label><input type="checkbox" name="chk_role[]" value="{{ $val->id }}" class="multi_checked" @if( in_array($val->id, $prize_array)) checked @endif><span class="text"></span></label></td>
                                            <td>{{$key+1}}</td>
                                            <td>{{$val->name}}</td>
                                            <td>
                                                @if(!empty($val->image))
                                                    <a href="{{ $val->image }}" target="_break" >
                                                        <img src="{{ $val->image }}?imageView2/2/w/50" alt="..." class="img-thumbnail">
                                                    </a>
                                                @else
                                                    <a href="1465286688.3069.png" target="_break" >
                                                        <img src="1465286688.3069.png?imageView2/2/w/50" alt="..." class="img-thumbnail">
                                                    </a>
                                                @endif
                                            </td>
                                            <td>{{$val->num}}</td>
                                            <td>{{$val->fragment}}</td>
                                            <td>@if( $val->type == 1 ) 实物 @elseif($val->type == 2 ) 碎片 @elseif($val->type == 2 ) 红包 @else 积分 @endif</td>
                                            <td><input type="text" name="probability[{{ $val->id }}]" class="multi_checked" style="width:35px;" placeholder="概率" @if( in_array($val->id, $prize_array)) value = "{{ $probability[$val->id] }}" @endif>%</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
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
        $('.date-picker').datetimepicker({ minView: "month",format: 'yyyy-mm-dd'});
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