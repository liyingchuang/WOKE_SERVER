@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <script src="/assets/js/jquery-file-upload/vendor/jquery.ui.widget.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-file-upload/jquery.iframe-transport.js" type="text/javascript"></script>
    <script src="/assets/js/jquery-file-upload/jquery.fileupload.js" type="text/javascript"></script>
    <script src="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.js')}}"></script>
    <link href="{{URL::asset('assets/js/datetime/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{URL::asset('/assets/js/dist/js/wangEditor.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/assets/js/dist/css/wangEditor.min.css')}}">
    <style type="text/css">
        .img {
            height: 150px;
            width: 150px;
        }

        .img-contianer {
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .remove_img {
            position: absolute;
            display: block;
            right: 0;
            top: 0;
            font-size: 30px;
            color: #ff4a00;
        }

        .img-contianer {
            float: left;
            margin-left: 15px;
            position: relative;
        }
    </style>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li class="active">添加商品</li>
            </ol>
        </div>
        <form method="post"
              action="@if(isset($id)){{ route('manage.goods.save') }}@else{{ route('manage.goods.store') }}@endif"
              class="form-horizontal" id="html5Form"
              data-bv-message="数据不能为空" data-bv-feedbackicons-valid="glyphicon glyphicon-ok"
              data-bv-feedbackicons-invalid="glyphicon glyphicon-remove"
              data-bv-feedbackicons-validating="glyphicon glyphicon-refresh">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        1.基本信息
                                        <span class="panel-desc"> </span>
                                    </h3>
                                    <div class="panel-actions">
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12 col-md-12">
                                            <div class="input-group ">
                                                <span class="input-group-addon">商品名称</span>
                                                <input type="text" class="form-control" id="goods_name"
                                                       name="goods_name" placeholder="商品名称"   required=""
                                                       value="@if(isset($goods->goods_name)){{ $goods->goods_name }}@endif"
                                                       data-bv-notempty-message="商品名称不能为空">
                                                @if(isset($id))
                                                    <input type="hidden" name="id" value="{{$id}}">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 10px 0">
                                        <div class="col-md-6 col-md-6">
                                            <div class="input-group">
                                                              <span class="input-group-addon">
                                                                副标题
                                                              </span>
                                                <input type="text" class="form-control" id="goods_name_style"
                                                       name="goods_name_style" placeholder="副标题"
                                                       value="@if(isset($goods->goods_name_style)){{ $goods->goods_name_style }}@endif">
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-group">
                                                             <span class="input-group-addon">
                                                              商品货号
                                                          </span>
                                                <input type="text" class="form-control" name="goods_sn"
                                                       placeholder="请输入商品货号"
                                                       value="@if(isset($goods->goods_sn)){{ $goods->goods_sn }}@else{{'1'}}@endif">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="margin: 10px 0">
                                        <div class="col-md-6 col-md-6">
                                            <div class="input-group">
                                                          <span class="input-group-addon">
                                                            市场价格
                                                          </span>
                                                <input type="text" class="form-control" id="market_price"
                                                       name="market_price"
                                                       placeholder="市场价格"
                                                       value="@if(isset($goods->market_price)){{ $goods->market_price }}@endif"
                                                       required="" min="0"
                                                       data-bv-greaterthan-inclusive="false"
                                                       data-bv-greaterthan-message="请输入大于1"
                                                       data-bv-lessthan-inclusive="true" data-bv-message="市场售价不为空">
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-group">
                                                          <span class="input-group-addon">
                                                           销售价格
                                                          </span>
                                                <input type="text" class="form-control" id="shop_price"
                                                       name="shop_price"
                                                       placeholder="销售价格"
                                                       value="@if(isset($goods->shop_price)){{ $goods->shop_price }}@endif"
                                                       required="" min="0"
                                                       data-bv-greaterthan-inclusive="false"
                                                       data-bv-greaterthan-message="请输入大于1"
                                                       data-bv-lessthan-inclusive="true" data-bv-message="市场售价不为空">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" style="margin: 10px 0">
                                        <div class="col-md-6 col-md-6">
                                            <div class="input-group"><span class="input-group-addon">商品库存</span>
                                                <input type="text" class="form-control" id="goods_number"
                                                       name="goods_number"
                                                       placeholder="商品库存"
                                                       value="@if(isset($goods->goods_number)){{ $goods->goods_number }}@endif"
                                                       required="" min="0"
                                                       data-bv-greaterthan-inclusive="false"
                                                       data-bv-greaterthan-message="请输入大于1"
                                                       data-bv-lessthan-inclusive="true" data-bv-message="不为空">
                                            </div><!-- /input-group -->
                                        </div><!-- /.col-lg-6 -->
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-group">
                                                             <span class="input-group-addon">
                                                              商品分类
                                                          </span>
                                                <select class="form-control " name="cat_id">
                                                    <option value="0"  >-选择分类- </option>
                                                    @foreach ($cat as $c)
                                                        <option value="{{ $c->cat_id }}" @if(isset($goods->cat_id)&&$goods->cat_id==$c->cat_id) selected @endif>{{ $c->cat_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row" @if(session('manage_role') != 'manage') style="display: none;" @else style="margin: 10px 0" @endif>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-group">
                                              <span class="input-group-addon">
                                                  参与分成
                                             </span>
                                                <select class="form-control " name="is_real">
                                                    <option value="0" @if(isset($goods->is_real)&&$goods->is_real==0) selected @endif>不参与</option>
                                                   <option value="1" @if(isset($goods->is_real)&&$goods->is_real==1) selected @endif>参与</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="input-group">
                                              <span class="input-group-addon">
                                                  返酒币
                                             </span>
                                                <input type="text" class="form-control" id="integral"
                                                       name="integral"
                                                       placeholder="返酒币"
                                                       value="@if(isset($goods->integral)){{ $goods->integral }}@else{{0}}@endif">
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="row" style="margin: 10px 0">--}}
                                        {{--<div class="col-lg-6 col-md-6">--}}
                                            {{--<div class="input-group">--}}
                                              {{--<span class="input-group-addon">--}}
                                                  {{--选择店铺--}}
                                             {{--</span>--}}
                                                {{--<select class="form-control " name="supplier_id">--}}
                                                   {{--<option value="{{ $supplier->supplier_id }}" @if(isset($supplier->supplier_id)&&$supplier->supplier_id== $supplier->supplier_id ) selected @endif>{{ $supplier->supplier_name }}</option>--}}
                                                {{--</select>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        2.商品相册
                                        <span class="panel-desc"> </span>
                                    </h3>
                                    <div class="panel-actions">
                                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                           aria-hidden="true"></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="img-content-container clearfix">
                                        <div class="module-content">
                                            @if(!empty($goods->gallery))
                                                @foreach($goods->gallery as $v)
                                                    <div class='thumbnail img-contianer'>
                                                        <img src='{{$v->img_url}}?imageView2/1/w/150/h/150' class='img img-rounded'  width='150' height='150' />
                                                        <span  class='remove_img' onclick='mydelete(this)' ><i class='fa fa-fw fa-times'></i></span>
                                                     <input  name='desc[]' type='hidden'   value='{{$v->img_desc}}'>
                                                    </div>
                                                @endforeach
                                            @endif
                                            <div class="thumbnail img-contianer " id="example_img">
                                                <img src="{{url('add.png')}}" width="150" height="150"
                                                     class="img-rounded uploadBtnDesc ">
                                            </div>
                                        </div>
                                    </div>
                                    <input class="hide" id="fileuploadDesc" type="file" name="files">
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">
                                        3.商品介绍
                                        <span class="panel-desc"> </span>
                                    </h3>
                                    <div class="panel-actions">
                                        <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                           aria-hidden="true"></a>
                                    </div>
                                </div>
                                <div class="panel-body">
                                            <textarea id="editor" name="goods_desc" placeholder="商品介绍"  style="height:400px;max-height:500px;"
                                                      >@if(isset($goods->goods_desc)){{$goods->goods_desc}}@endif</textarea>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6">
                        <!-- ### TITLE ### -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <span class="panel-desc">4.属性名称</span>
                                </h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-hover table-bordered"
                                       id="sample_editable_1">
                                    <thead>
                                    <tr>
                                        <th width="200"> 规格值</th>
                                        <th> 描述</th>
                                        <th width="80"> 排序</th>
                                        <th width="100"> 操作</th>
                                    </tr>
                                    </thead>
                                    <tbody id="param-items">
                                    @if(isset($goods->item))
                                        @foreach($goods->item as $v)
                                        <tr>
                                            <td><input type="text" name="keyname[]" value="{{$v->key}}" class="form-control" placeholder="属性名"></td>
                                            <td><input type="text" name="keyvalue[]" value="{{$v->value}}"  class="form-control" placeholder="属性数据"></td>
                                            <td><input type="number" name="stor[]" value="{{$v->stor}}"  class="form-control" placeholder="排序"></td>
                                            <td>
                                                <button class="btn btn-danger" data-id="8"
                                                        onclick='deleteParam(this)'><i class="fa fa-remove"></i>删除
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                   @else
                                        <tr>
                                            <td><input type="text" name="keyname[]" class="form-control" placeholder="属性名"></td>
                                            <td><input type="text" name="keyvalue[]" class="form-control" placeholder="属性数据"></td>
                                            <td><input type="number" name="stor[]" class="form-control" placeholder="排序"></td>
                                            <td>
                                                <button class="btn btn-danger" data-id="8"
                                                        onclick='deleteParam(this)'><i class="fa fa-remove"></i>删除
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="keyname[]" class="form-control" placeholder="属性名"></td>
                                            <td><input type="text" name="keyvalue[]" class="form-control" placeholder="属性数据"></td>
                                            <td><input type="number" name="stor[]" class="form-control" placeholder="排序"></td>
                                            <td>
                                                <button class="btn btn-danger" data-id="8"
                                                        onclick='deleteParam(this)'><i class="fa fa-remove"></i>删除
                                                </button>
                                            </td>
                                        </tr>
                                   @endif
                                    </tbody>
                                </table>
                                <br>
                                <button class="btn  btn-sm btn-info " id='add-param' onclick="addParam()"> 新增
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- ### TITLE ### -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">
                                    <i class="voyager-character"></i>
                                    <span class="panel-desc">5.商品规格</span>
                                </h3>
                                <div class="panel-actions">
                                    <a class="panel-action voyager-angle-down" data-toggle="panel-collapse"
                                       aria-hidden="true"></a>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table class="table table-striped table-hover table-bordered"
                                       id="sample_editable_1">
                                    <thead>
                                    <tr>
                                        <th> 名称A</th>
                                        <th> 名称B</th>
                                        <th width="100"> 库存</th>
                                        <th width="100"> 原价</th>
                                        <th width="100"> 现价</th>
                                        <th width="100"> 操作</th>
                                    </tr>
                                    </thead>
                                    <tbody id="param-format">
                                    @if(isset($goods->attr))
                                        @foreach($goods->attr as $k=>$v)
                                        <tr class="tr_{{$k}}">
                                            <td rowspan="{{count($v)}}">
                                                <input type="text" class="form-control" name="attr_name[tr_{{$k}}]"  placeholder="例如:原味" value="{{$k}}">
                                            </td>
                                            <td><input type="text" name="attr_value[tr_{{$k}}][]" class="form-control" placeholder="例如:1盒装" value="{{$v[0]['attr_value']}}"></td>
                                            <td><input type="text" name="attr_number[tr_{{$k}}][]"  class="form-control" placeholder="库存" value="{{$v[0]['goods_number']}}"></td>
                                            <td><input type="text"   name="attr_market[tr_{{$k}}][]"  class="form-control" placeholder="原价" value="{{$v[0]['market_price']}}"></td>
                                            <td><input type="text" name="attr_price[tr_{{$k}}][]" class="form-control" placeholder="现价" value="{{$v[0]['shop_price']}}"></td>
                                            <td>
                                                <button class="btn  btn-sm btn-info " type="button" onclick="addFormatRow(this)">
                                                    新增<i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        @foreach($v as $kk=>$vv)
                                            @if($kk<>0)
                                        <tr>
                                            <td><input type="text"  name="attr_value[tr_{{$k}}][]"  class="form-control" placeholder="例如:1盒装" value="{{$vv['attr_value']}}"></td>
                                            <td><input type="text" name="attr_number[tr_{{$k}}][]"  class="form-control" placeholder="库存" value="{{$vv['goods_number']}}"></td>
                                            <td><input type="text"  name="attr_market[tr_{{$k}}][]"    class="form-control" placeholder="原价" value="{{$vv['market_price']}}"></td>
                                            <td><input type="text"  name="attr_price[tr_{{$k}}][]" class="form-control" placeholder="现价" value="{{$vv['shop_price']}}"></td>
                                            <td>
                                                <button class="btn  btn-sm btn-danger "  type="button"
                                                        onclick="removeFormatRow(this,'tr_{{$k}}')"> 删除<i
                                                            class="fa fa-remove"></i></button>
                                            </td>
                                        </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                 @else
                                        <tr class="tr_1">
                                            <td rowspan="2">
                                                <input type="text" class="form-control" name="attr_name[tr_1]"  placeholder="例如:原味">
                                            </td>
                                            <td><input type="text" name="attr_value[tr_1][]" class="form-control" placeholder="例如:1盒装"></td>
                                            <td><input type="text" name="attr_number[tr_1][]"  class="form-control" placeholder="库存"></td>
                                            <td><input type="text"  name="attr_market[tr_1][]" class="form-control" placeholder="原价"></td>
                                            <td><input type="text" name="attr_price[tr_1][]"  class="form-control" placeholder="现价"></td>
                                            <td>
                                                <button class="btn  btn-sm btn-info "  type="button"  onclick="addFormatRow(this)">
                                                    新增<i class="fa fa-plus"></i></button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="attr_value[tr_1][]" class="form-control" placeholder="例如:2盒装"></td>
                                            <td><input type="text" name="attr_number[tr_1][]"  class="form-control" placeholder="库存"></td>
                                            <td><input type="text"  name="attr_market[tr_1][]"  class="form-control" placeholder="原价"></td>
                                            <td><input type="text" name="attr_price[tr_1][]" class="form-control" placeholder="现价"></td>
                                            <td>
                                                <button class="btn  btn-sm btn-danger "  type="button"
                                                        onclick="removeFormatRow(this,'tr_1')"> 删除<i
                                                            class="fa fa-remove"></i></button>
                                            </td>
                                        </tr>
                                  @endif
                                    </tbody>
                                </table>
                                <br>
                                <button class="btn  btn-sm btn-info "  type="button"  id='add-format' onclick="addFormat()"> 新增
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                <div class="row text-right" style="padding-right: 10px">
                    <button type="submit" class="btn btn-info btn-lg"><i class="fa fa-save"></i>
                        确定添加
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script>
        //编辑器
        var _edit_menu= [
            'source',
            '|',
            'bold',
            'underline',
            'italic',
            'strikethrough',
            'eraser',
            'forecolor',
            'bgcolor',
            '|',
            'quote',
            'fontfamily',
            'fontsize',
            'head',
            'unorderlist',
            'orderlist',
            'alignleft',
            'aligncenter',
            'alignright',
            'img',
            '|',
            'fullscreen'
        ];
        var desc_text = $('#editor');
        var desc_text_editor = new wangEditor(desc_text);
        desc_text_editor.config.menus=_edit_menu;
        desc_text_editor.config.uploadImgFileName='files';
        desc_text_editor.config.uploadImgUrl = '{{URL::to('manage/upload')}}?type=simditor';
        desc_text_editor.create();
        $(".uploadBtnDesc").on("click", function () {
            $("#fileuploadDesc").trigger("click");
        });
        $('#fileuploadDesc').fileupload({
            url: "{{URL::to('manage/upload')}}?type=LeanCloud",
            dataType: 'json',
            autoUpload: true
        }).on('fileuploaddone', function (e, data) {
            var file = data.result;
            if (file.code == 0) {
                var imgObj = "<div class='thumbnail img-contianer'><img src='" + file.data.url + "?imageView2/1/w/150/h/150' class='img img-rounded'  width='150' height='150' /> <span  class='remove_img' onclick='mydelete(this)' ><i class='fa fa-fw fa-times'></i></span>";
                var input = " <input  name='desc[]' type='hidden'   value='" + file.data.fileName + "'></div>";
                $("#example_img").before(imgObj + input);
            } else if (file.code == 1) {
                alert(file.info);
            }
        }).on('fileuploadfail', function (e, data) {
            alert('上传出错!请检查图片大小');
        })
        $('#html5Form').bootstrapValidator();
        function mydelete(obj) {
            var html = $(obj).prev().attr('src');
            $(obj).parent().remove();
        }
        function addParam() {
            var data = "<tr> <td><input type='text' name='keyname[]' class='form-control'  placeholder='属性名' ></td> <td><input type='text' name='keyvalue[]' class='form-control'  placeholder='属性数据' ></td> <td><input name='stor[]' class='form-control' type='number' placeholder='排序'>  </td><td> <button class=\"btn btn-danger\"  onclick='deleteParam(this)' data-id=\"8\"  type='button' ><i class='fa fa-remove'></i>删除</button> </td></tr>"
            $('#param-items').append(data);
        }
        function deleteParam(o) {
            $(o).parent().parent().remove();
        }
        var i = 10000;
        function addFormat() {
            i++;
            var data = "<tr class='tr_" + i + "'><td rowspan='1'><input type='text' class='form-control' name='attr_name[tr_" + i + "]' placeholder='例如:辣味' ></td> <td><input type='text' name='attr_value[tr_" + i + "][]' class='form-control'  placeholder='例如:2盒装' ></td> <td><input type='text' name='attr_number[tr_" + i + "][]' class='form-control'  placeholder='库存' ></td><td><input type='text'   name='attr_market[tr_" + i + "][]' class='form-control'  placeholder='原价' ></td><td><input type='text' name='attr_price[tr_" + i + "][]' class='form-control'  placeholder='现价' ></td><td> <button class=\"btn btn-info btn-sm \"  onclick='addFormatRow(this)' type='button'  >新增<i class='fa fa-plus'></i></button> </td></tr>"

            $('#param-format').append(data);

        }
        function removeFormatRow(o,id) {
            console.log(o);
          // console.log($('.' + id).html());
            var x = $(o).parent().parent();
            var rowspan = $('.' + id).find('td:first').attr("rowspan");
            console.log("====" + rowspan);
             rowspan = parseInt(rowspan) - 1;
             $('.' + id).find('td:first').attr("rowspan", rowspan);
            $(o).parent().parent().remove();
        }
        function addFormatRow(o) {
            var x = $(o).parent().parent();
            var rowspan = x.find('td:first').attr("rowspan");
            console.log("====" + rowspan);
            rowspan = parseInt(rowspan) + 1;
            console.log("====" + rowspan);
            x.find('td:first').attr("rowspan", rowspan);
            var id=x.attr('class');
            console.log(id);
            var data = "<tr><td><input type='text' class='form-control' name='attr_value[" + id + "][]'   placeholder='例如:" + rowspan + "盒装' ></td> <td><input type='text' name='attr_number[" + id + "][]' class='form-control'  placeholder='库存' ></td><td><input type='text'  name='attr_market[" + id + "][]'  class='form-control'  placeholder='原价' ></td><td><input type='text' name='attr_price[" + id + "][]' class='form-control'  placeholder='现价' ></td><td> <button class=\"btn btn-danger btn-sm \"  onclick=\"removeFormatRow(this,'"+id+"')\"  type='button' >删除<i class='fa fa-remove'></i></button> </td></tr>"
            $('.' + x.attr('class')).after(data);

        }
    </script>
@stop