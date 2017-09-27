@extends('_layouts.master')
@section('content')
    <script src="http://cdn.bootcss.com/bootstrap-validator/0.5.3/js/bootstrapValidator.min.js"></script>
    <link rel="stylesheet" href="/assets/shipp/admin.css">
    <script src="/assets/js/editable/bootbox.js"></script>
    <script src="/assets/shipp/checkbox.js"></script>
    <style>
        input[type="checkbox"] {
            top: 3px;
            height: 20px;
            margin: 0;
            padding: 0;
            opacity: 1;
            left: 20px;
        }

        .hidden {
            display: inline !important;
        }
    </style>
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li class="active">添加包邮模板</li>
            </ol>
        </div>
        <form action="{{URL::to('manage/shipp/shipp')}}" method="post">
        <div class="panel-body">
            <span style="color:red;">* </span>选择快递：
            <select id="express" name="express">
                <option value="顺丰快递">顺丰快递</option>
                <option value="中通快递">中通快递</option>
                <option value="圆通快递">圆通快递</option>
                <option value="韵达快递">韵达快递</option>
                <option value="EMS">EMS</option>
            </select>
            模板名称：<input type="text" style="height:30px;" id="shipp_name" name="shipp_name" required="" data-bv-notempty-message=""/>
            @foreach($errors->all() as $error)
                <h5><span class="danger">　　　　 　{{ $error }}</span></h5>
            @endforeach
        </div>
        <table class="table  table-bordered table-striped" id="table">
            <thead>
            <th>运送地址</th>
            <th>重量(kg)</th>
            <th>价格(元)</th>
            <th>操作</th>
            </thead>
            <tbody id="table_tr">
            <tr>
                <td class='col-sm-7'><h5>全国</h5></td>
                <td><input type="text" id="number" name="number" style="height: 30px;" required="" data-bv-notempty-message=""></td>
                <td><input type="text" id="price" name="price" style="height: 30px;" required="" data-bv-notempty-message=""></td>
                <td><button type='button' class='btn btn-danger btn-xs' disabled><i class='glyphicon glyphicon-remove'></i></button></td>
            </tr>
            </tbody>
        </table>
        <div class="panel-footer" style="height: 50px;">
            <div class="col-xs-6" >
                <button type="button" id="address"  class="btn btn-info"  ><i
                            class="glyphicon glyphicon-plus"></i>添加地区设置运费
                </button>
            </div>
            <div class="col-xs-6 text-right" >
                <button type="submit" class="btn btn-info "><i class="glyphicon glyphicon-ok"></i>确定
                </button>　　
                <a href="#" class="btn btn-info" onClick="javascript :history.back(-1);"><i class="glyphicon glyphicon-chevron-left"></i>返回</a>
            </div>
        </div>
        </form>
    </div>
    <div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <h4 class="modal-title" id="myModalLabel">
                        选择地区
                    </h4>
                </div>
                <div class="panel-footer">
                    <div class="col-xs-offset">
                        <table class="table table-bordered">
                            <tbody>
                            @foreach($area as $are)
                                <tr>
                                    <th style="width:80px;color: #787878">{{ $are->area_name }}</th>
                                    @foreach($are->area_extends as $val)
                                        <td>
                                            <div class="">
                                                <a href="javascript:;" class="action ic" style="color: black;">
                                                    <label class="checkbox-inline">
                                                        <input class="province" type="checkbox" id="province" name="province" date="{{ $val->name }}" value="{{ $val->id }}"/> {{ $val->name }}
                                                    </label>
                                                </a>
                                                {{--<div class="menu_select c{{ $val->id }}" id="c{{ $val->id }}" style="top: 0px; left: 0px;width:0px;padding: 0px;">--}}
                                                    {{--@foreach($val->city as $v)--}}
                                                        {{--<label class="checkbox-inline">--}}
                                                            {{--<span class="areas"><input type="checkbox" id="city" name="city" value="{{ $v->id }}" disabled>{{ $v->name }}</span>--}}
                                                        {{--</label>--}}
                                                    {{--@endforeach--}}
                                                {{--</div>--}}
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭
                            </button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal -->
            </div>
        </div>
    </div>
    <script>
        var node;
        var p;
        var trindex=100;
        $('#address').click(function(){
            $('#table_tr').append("<tr tle='"+ trindex +"'><td class='col-sm-7'><span></span>　" + "<button type='button' class='btn btn-info selectcity' tile='"+trindex+"' >选择城市</button></td>" + "<td><input type='text' name='cnumber["+trindex+"]' style='height: 30px;' required='' data-bv-notempty-message=''/></td>" + "<td><input type='text' name='cprice["+trindex+"]' style='height: 30px;' required='' data-bv-notempty-message=''/></td>" + "<td><button type='button'  onClick='getDel(this)' class='btn btn-danger btn-xs'>" + "<i class='glyphicon glyphicon-remove'></i></button></td></tr>");
            trindex++;
        });
        function getDel(del){
            $(del).parent().parent().remove();
            $(".province"+trindex).removeAttr("disabled");
        }
        $(".province").click(function(){
            p = $(this).val();
            if($(this).is(':checked')) {
               var trvalue=$('#myModal').attr("tile");
                $('.c'+p).find("input").prop("checked", true);
                var province_name = $(this).attr("date");
                node.find('span:first').append('<span class="d'+ p +'">'+province_name+' </span>' + '<input class="d'+ p +'" type="hidden" value="'+p+'" name="province['+ trvalue +']['+ p+']">' );
                var city=$('.c'+p).find("input");
                var c='';
                for(var a=0; a<city.length; a++){
                    if(city[a].checked) c+=city[a].value+',';
                }
                node.find('span:first').append('<input class="d'+ p +'" type="hidden" value="'+c+'" name="city['+ p+']">' );
            }else{
                $('.c'+p).find("input").removeAttr("checked");
                node.find(".d"+p).remove();
            }
        });
        $( "#table").delegate( ".selectcity", "click", function(){
            node = $(this).parent().parent();
            var trvalue=$(this).attr('tile');
            $('#myModal').modal('show');
            $('#myModal').attr("tile",trvalue);
            $("input:checked").each(function () {
                $(this).attr("disabled", "disabled");
            });
        });
    </script>
@stop