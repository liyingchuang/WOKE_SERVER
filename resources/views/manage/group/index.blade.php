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
            团购商品列表
        </div>
        <div class="panel-body">
                <div class="col-xs-10">
                    <form action="" method="get">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="text" class="form-control"  name="goods_name" placeholder="输入 商品名 进行查询">
                                </div>
                                <div class="col-xs-6">
                                    <button type="submit" formaction="{{ URL::to('manage/group/index') }}" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                                </div>
                                    <a type="button" href="{{ URL::to('manage/group/uploade') }}" class="btn btn-info btn-ms  pull-right"> <i class="glyphicon glyphicon-plus"></i> 添加团购</a>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="">团购商品</a></li>
                </ul>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <th>商品ID</th>
                            <th>商品名</th>
                            <th>商品类别</th>
                            <th>需要参团人数</th>
                            <th>已参团人数</th>
                            <th>团购价格</th>
                            <th>开始时间</th>
                            <th>结束时间</th>
                            <th>团购开关</th>
                            <th>状态</th>
                            <th>操作</th>
                        </thead>
                        @foreach($info as $value)
                        <tbody>
                        <tr>
                            <td>{{ $value->goods_id }}</td>
                            <td>{{ $value->goods['goods_name'] }}</td>
                            <td>{{ $value->ify['ify_name'] }}</td>
                            <td>{{ $value->ex_number }}</td>
                            <td>{{ $value->ex_have }}</td>
                            <td>{{ $value->group_price }}</td>
                            <td>{{ date('Y-m-d H:i', $value->start_time) }}</td>
                            <td>{{ date('Y-m-d H:i', $value->end_time) }}</td>
                            <td><label>
                                    <input class="checkbox-slider slider-icon colored-darkorange offon" type="checkbox" value="{{ $value->examine_status }}" title="{{ $value->goods_id }}" @if($value->examine_status == 4) checked @endif>
                                    <span class="text"></span>
                                </label></td>
                            <td>@if($value->examine_status == 1) <span class="glyphicon glyphicon-repeat" title="待审核"> @elseif($value->examine_status == 2) <span class="glyphicon glyphicon-ok" title="已上架"> @elseif($value->examine_status == 3) <span class="glyphicon glyphicon-remove" title="未上架">  @elseif($value->examine_status == 4) <span style="color:green;">团购中 @endif</td>
                            <td><a type="button" href="{{ URL::to('manage/group/uploade') }}/{{ $value->goods_id }}" class="btn btn-info  btn-xs"> <i class="glyphicon glyphicon-edit"></i>修改</a>
                                @if(session('supplier_id') == 0)
                                    @if($value->recommend == 1)
                                        <button type="button" class="btn btn-danger btn-xs down" title="{{ $value->goods_id }}"><span class="glyphicon glyphicon-thumbs-down"></span>取消</button>
                                    @else
                                        <button type="button" class="btn btn-info btn-xs up" title="{{ $value->goods_id }}"><span class="glyphicon glyphicon-thumbs-up"></span>推荐</button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
    </div>
    <div class="panel-footer">
        <div class="row text-left" >
            <div class="col-xs-5">
            </div>
            <div class="col-xs-7 text-right">
            {!! $info->appends(['goods_name' => $goods_name])->render() !!}
            </div>
        </div>
    </div>
    <script>
        $(".offon").bind("change", function() {
            var id = $(this).attr('title');
            var sta = $(this).attr('value');
            if(sta == 1 || sta == 3){
                alert('审核尚未通过，暂无法开启团购');
                $(this).attr("checked", false);
                return false;
            }
            if ($(this).is(':checked')) {
                $.get("{{URL::to('manage/group')}}/switch?goods_id=" + id+'&type=on', function(result) {

                });
                $(this).attr("checked", true);
            } else {
                $.get("{{URL::to('manage/group')}}/switch?goods_id=" + id+'&type=off', function(result) {

                });
                $(this).attr("checked", false);
            }
        });

        $('.down').click(function(){
            var goods_id = $(this).attr("title");
            var type = 1;
            $.ajax({
                url:"/manage/group/index",
                data:{type:type, goods_id:goods_id},
                type:'get',
                success:function(){
                    location.href="/manage/group/index/"+goods_id;
                }
            });
        });

        $('.up').click(function(){
            var goods_id = $(this).attr("title");
            var type = 2;
            $.ajax({
                url:"/manage/group/index",
                data:{type:type, goods_id:goods_id},
                type:'get',
                success:function(){
                    location.href="/manage/group/index/"+goods_id;
                }
            });
        });
    </script>
@stop