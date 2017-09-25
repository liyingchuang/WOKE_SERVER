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
            上传商品
        </div>
    </div>
    <div class="panel  panel-info">
        <div class="panel-body">
            <form action="" class="form-horizontal form-bordered">
                @if( $goods )
                    @foreach( $goods as $good)
                        <div class="form-group">
                            <div class="col-sm-5 col-xs-5 col-md-5">
                                <input type="hidden" class="form-control required safe-input" id="goods_id" name="goods_id"   value="{{ $good->goods_id }}" >
                            </div>
                            <div class="col-sm-5 col-xs-5 col-md-5"></div>
                        </div>
                    @endforeach
                @else
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">搜索商品:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5" id="cim">
                            <input type="text" id="goods_name" name="goods_name" class="form-control" oninput="sel()">
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">选择商品:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5">
                            <select id="new_id" name="new_id" class="form-control">
                            </select>
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                    </div>
                @endif
                @foreach( $goods as $value )
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">商品名:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5">
                            <input type="text" class="form-control required safe-input" id="" name=""   value="{{ $value->goods_name }}" required="" data-bv-notempty-message="" disabled>
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">需参团人数:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5">
                            <input type="text" class="form-control required safe-input" id="" name=""   value="{{ $value->ex_number }}" required="" data-bv-notempty-message="" disabled>
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                    </div>
                    <div class="form-group">
                            <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">团长免单:</label>
                            <div class="col-sm-5 col-xs-5 col-md-5">
                                <select    class="form-control e1" >
                                    <option  value="0" @if($value->head_free==0) selected @endif>不参与团长免单</option>
                                    <option  value="1"  @if($value->head_free) selected @endif>参与团长免单</option>
                                </select>
                                <input type="text" class="form-control required safe-input" id="" name=""   value="{{ $value->ex_number }}" required="" data-bv-notempty-message="" disabled>
                            </div>
                            <div class="col-sm-5 col-xs-5 col-md-5"></div>
                     </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">团购价格:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5">
                            <input type="text" class="form-control required safe-input" id="" name=""   value="{{ $value->group_price }}" required="" data-bv-notempty-message="" disabled>
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">开始时间:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5">
                            <input type="text" class="form-control required safe-input" id="" name=""   value="{{ date('Y-m-d H:i:s', $value->start_time) }}" required="" data-bv-notempty-message="" disabled>
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                    </div>
                    <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">结束时间:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5">
                            <input type="text" class="form-control required safe-input" id="" name=""   value="{{ date('Y-m-d H:i:s', $value->end_time) }}" required="" data-bv-notempty-message="" disabled>
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                    </div>
                @endforeach
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">选择分类:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <select id="ify_id"  class="form-control e1">
                            <option  value="">选择分类</option>
                            @foreach($class as $clas)
                                <option  value="{{ $clas->ify_id }}">{{ $clas->ify_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">选择店铺:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <select id="supplier_id"  class="form-control e1" >
                            @foreach($supplier as $sup)
                                <option  value="{{ $sup->supplier_id }}">{{ $sup->supplier_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">参团人数:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <input type="text" class="form-control required safe-input" id="ex_number" name="ex_number"   value="" required="" data-bv-notempty-message="">
                    </div>
                    <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
                 <div class="form-group">
                        <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">团长免单:</label>
                        <div class="col-sm-5 col-xs-5 col-md-5">
                            <select id="head_free" name="head_free"   class="form-control e1" >
                                <option  value="0">不参与团长免单</option>
                                <option  value="1">参与团长免单</option>
                            </select>
                        </div>
                        <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">团购价格:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <input type="text" class="form-control required safe-input" id="group_price" name="group_price"   value="" required="" data-bv-notempty-message="">
                    </div>
                    <div class="col-sm-5 col-xs-5 col-md-5"></div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">添加描述:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <textarea class="form-control" id="describe" name="describe"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right"><b class="danger">*</b>&nbsp;选择图片:</label>
                    <div class="col-sm-5 col-xs-5 col-md-5">
                        <button type="button" class="btn btn-default" id="uploadBtn"><i class="glyphicon glyphicon-upload"></i> 上传图片</button>
                        <input class="hide" id="fileupload" type="file" name="files" accept="image/gif,image/jpg,image/png">
                        <input id="store_image" name="group_file" type="hidden" value="">
                    </div>
                </div>
                <div class="form-group">
                    <label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">选择时间:</label>
                    <div  style="padding-left: 280px">
                        <div class="col-sm-3">
                            <input type="text" class="form-control required date-picker minView: 0" name="start_time" id="start_time"  title="开始时间" placeholder="请选择开始时间段"
                                   {{--data-fv-date="true" required=""--}}
                                   data-fv-date-message="请选择开始时间段"
                                   data-bv-notempty-message="选择开始时间段"
                                   data-fv-date-format="YYYY-MM-DD HH:II" />
                        </div>

                        <div class="col-sm-3">
                            <input type="text" class="form-control required date-picker" name="end_time" id="end_time"  title="结束时间" placeholder="请选择结束时间段"
                                   {{--data-fv-date="true" required=""--}}
                                   data-fv-date-message="请选择结束时间段"
                                   data-bv-notempty-message="选择结束时间段"
                                   data-fv-date-format="YYYY-MM-DD HH:II" />
                        </div>
                    </div>
                </div>
                @if($attr)
                    <div class="form-group">
                        <table class="table table-bordered">
                            <span style="color:red">*注：分类价格需单独修改（不修改可留空）</span>
                            <thead>
                            <th>商品类别</th>
                            <th>商品类别</th>
                            <th>原价</th>
                            <th>市场价</th>
                            <th>库存</th>
                            <th>团购价</th>
                            <th>操作</th>
                            </thead>
                            @foreach($attr as $value)
                                <input type="hidden" value="{{ $value->id }}">
                                <tbody>
                                <tr>
                                    <td>{{ $value->attr_name }}</td>
                                    <td>{{ $value->attr_value }}</td>
                                    <td>{{ $value->market_price }}</td>
                                    <td>{{ $value->shop_price }}</td>
                                    <td>{{ $value->goods_number }}</td>
                                    <td>现售价：<input type="text" value="{{ $value->group_price }}" style="width:60px;" disabled>　修改价：<input type="text" id="rice{{ $value->id }}" style="width:60px;"></td>
                                    <td><button type="button" data_id="{{ $value->id }}" class="btn btn-info btn-xs group">确定</button></td>
                                </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>
                @endif
                <div class="form-group" id="sku">
                </div>
            </form>
        </div>
    </div>
    <div style="padding-left:88%">
        <button type="button" class="btn btn-info yes"><span class="glyphicon glyphicon-pencil"> 确认</span></button>
        <a href="#" class="btn btn-info" onClick="javascript :history.back(-1);"><span class=" glyphicon glyphicon-chevron-left">返回</span></a>
    </div>
    <script>
        $('.date-picker').datetimepicker({ minView: "0", format: 'yyyy-mm-dd hh:ii'});

        function sel(){
            var goods_name = $("#goods_name").val();
            $.post('/manage/group/js',{goods_name:goods_name}, function(date){
                var html = '';
                $.each(date, function(){
                    html += '<option value="'+ this.goods_id +'">';
                    html += "" +this.goods_name+ "";
            });
                html += '</option>';
                $("#new_id").html(html);
                $('#new_id').click(function(){
                    var new_id = $("#new_id option:selected").text();
                    var cim = "<input type='text' id='text' class='col-xs-3 form-control' oninput='sel()' value='"+ new_id +"'/>";
                    $('#cim').html(cim);
                });
            });
        }

        $('.yes').click(function() {
            var store_image = $('#store_image').val();
            var supplier_id = $("#supplier_id").val();
            var goods_id = $("#goods_id").val();
            var new_id = $("#new_id").val();
            var ify_id = $("#ify_id").val();
            var ex_number = $("#ex_number").val();
            var group_price = $("#group_price").val();
            var describe = $("#describe").val();
            var start_time = $("#start_time").val();
            var end_time = $("#end_time").val();
            var head_free = $("#head_free").val();

//        var date = new Date().getTime();
//        var start = new Date(Date.parse(start_time.replace(/-/g, "/")));
//        var start_t = start.getTime();
            if(!new_id && !goods_id){
                alert('请选择商品');
                return false;
            }
            if(!ify_id){
                alert('请选择商品类别');
                return false;
            }
            if(!ex_number){
                alert('请输入参团人数');
                return false;
            }
            if(!group_price){
                alert('请输入团购价格');
                return false;
            }
            if(!store_image){
                alert('请选择图片');
                return false;
            }
            if(!start_time || !end_time){
                alert('请选择时间');
                return false;
            }
            $.ajax({
                url:"/manage/group/load",
                data:{head_free:head_free,supplier_id:supplier_id, store_image:store_image, goods_id:goods_id, new_id:new_id, ify_id:ify_id, ex_number:ex_number, group_price:group_price, describe:describe, start_time:start_time, end_time:end_time},
                type:"get",
                success:function() {
                    location.href="/manage/group/index";
                },
                error:function(){
                    alert('该商品已在拼团中');
                    return false;
                }
            });
        });

        $('.goods').click(function(){
            var goods_id = $(this).attr('value');
            var type = 1;
            $.ajax({
                url:"/manage/group/uploade",
                data:{ goods_id:goods_id, type:type},
                type:"get",
                success:function(data){
                    var sku = '<table class="table table-bordered"> <span style="color:red">*注：分类价格需单独修改（不修改可留空）</span>'+' <thead> <th>商品类别</th> <th>商品类别</th> <th>原价</th> <th>市场价</th> <th>库存</th> <th>团购价</th> <th>操作</th> </thead>';
//                var value = '<label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">商品类别:</label>'+'<select id="attr_value" style="border-radius: 4px; margin-left:1.8%;">';
//                var price = '<label for="uuid" class="col-sm-2 col-xs-2 col-md-2 control-label no-padding-right">分类价格:</label>'+'　<input type="text" id="attr_price" style="height:32px; width:10%;">';
                    var data = eval("("+data+")");
                    $.each(data, function(){
                        sku += "<tbody><tr><input type='hidden' value='"+ this.id +"'>";
                        sku += "<td>"+ this.attr_name +"</td>";
                        sku += "<td>"+ this.attr_value +"</td>";
                        sku += "<td>"+ this.market_price +"</td>";
                        sku += "<td>"+ this.shop_price +"</td>";
                        sku += "<td>"+ this.goods_number +"</td>";
                        sku += "<td>现售价：<input type='text' value='"+ this.group_price +"' style='width:60px;' disabled>" + "　修改价：<input type='text' id='rice"+ this.id +"' style='width:60px;'></td>";
                        sku += "<td><button type='button' onclick='yes("+ this.id +")' class='btn btn-info btn-xs'>确定</button></td>";
                    });
                    sku += '</tr></tbody></table>';
                    if( data != "" ){
                        $('#sku').html(sku);
                    }else{
                        $('#sku').html("");
                    }
//                $.each(data, function(){
//                    value += "<option value="+$(this)[0].attr_value+">" + this.attr_value + "</option>";
//                });
//                value += '</select>';
//                $('#value').html(value);
//                $('#price').html(price);
                }
            });
        });

        $('.group').click(function(){
            var goods_id = $("#goods_id").val();
            var attr_id = $(this).attr('data_id');
            var attr_price = $('#rice'+attr_id).val();
            var type= 2;
            if(!attr_price){
                alert('请输入价格!');
                return false;
            }
            if( attr_price < 1){
                if(confirm("价格过低,请确认是否正确！")){

                }else{
                    return false;
                }
            }
            $.ajax({
                url:"/manage/group/uploade",
                data:{ attr_id:attr_id, attr_price:attr_price, type:type },
                type:"get",
                success:function(){
                    alert('修改成功');
                }
            });
        });

        function yes(id){
            var rice = $('#rice'+id).val();
            var type= 2;
            if(!rice){
                alert('请输入价格!');
                return false;
            }
            if(rice < 1){
                if(confirm('价格过低,请确认是否正确！')){

                }else{
                    return false;
                }
            }
            $.ajax({
                url:"/manage/group/uploade",
                data:{ attr_id:id,  attr_price:rice, type:type },
                type:"get",
                success:function(){
                    alert('修改成功');
                }
            });
        }

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
        }).on('fileuploaddone', function (e, data) {
            var file = data.result;
//
//        console.log('ok->'+file.data);
//        console.log('ok->'+file.data);
            if (file.code==0) {
                $('#store_image').val(file.data.fileName);

            } else if (file.code==1) {
                $('#store_image').val();
                alert('图片上传失败！');
            }
        }).on('fileuploadfail', function (e, data) {
            alert('图片上传失败！');
        });
    </script>
@stop