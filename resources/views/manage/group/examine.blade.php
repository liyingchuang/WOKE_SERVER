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
            团购商品审核
        </div>
        <div class="panel-body">
            <div class="col-xs-10">
                <form action="{{ URL::to('manage/group/goods') }}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" class="form-control"  name="goods_name" placeholder="输入 商品名 进行查询">
                            </div>
                            <div class="col-xs-6">
                                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="tabbable">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="">商品审核</a></li>
            </ul>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead>
                    <th>店铺ID</th>
                    <th>店铺名</th>
                    <th>商品ID</th>
                    <th>商品名</th>
                    <th>需要参团人数（个）</th>
                    <th>团购价格（元）</th>
                    <th>开始时间</th>
                    <th>结束时间</th>
                    <th>商品状态</th>
                    <th>操作</th>
                    </thead>
                    <tbody>
                    @foreach( $info as $value)
                        <tr>
                            <td>{{ $value->supplier_id }}</td>
                            <td>{{ $value->supplier_name }}</td>
                            <td>{{ $value->goods_id }}</td>
                            <td>{{ $value->goods_name }}</td>
                            <td>{{ $value->ex_number }}</td>
                            <td>{{ $value->group_price }}</td>
                            <td>{{ date('Y-m-d H:i:s', $value->start_time) }}</td>
                            <td>{{ date('Y-m-d H:i:s', $value->end_time) }}</td>
                            <td>@if($value->examine_status == 1) <span style="color:#FF0000;">待审核 @elseif($value->examine_status == 2) <span class="glyphicon glyphicon-ok" title="已上架"> @elseif($value->examine_status == 3) <span class="glyphicon glyphicon-remove" title="未上架">  @elseif($value->examine_status == 4) <span style="color:#9ACD32;">团购中 @endif</td>
                            <td>@if($value->examine_status == 1)<a class="btn btn-info btn-xs" href="{{ URL::to('manage/group/examine') }}/{{ $value->goods_id }}"><i class="glyphicon glyphicon-edit"></i>确定</a>
                                <a class="btn btn-info btn-xs" href="{{ URL::to('manage/group/examine') }}/{{ $value->goods_id }}/type=1"><i class="glyphicon glyphicon-edit"></i>驳回</a>@endif
                                @if($value->examine_status == 3)<a class="btn btn-info btn-xs" href="{{ URL::to('manage/group/examine') }}/{{ $value->goods_id }}"><i class="glyphicon glyphicon-edit"></i>重审</a>@endif
                                <a class="btn btn-info btn-xs" href="{{ URL::to('manage/group/lookinfo') }}/{{ $value->goods_id }}"><span class="glyphicon glyphicon-eye-open"></span>查看</a>
                                {{--@if($value->examine_status == 2)--}}
                                    {{--@if(session('supplier_id') == 0)--}}
                                        {{--@if($value->recommend == 1)--}}
                                            {{--<button type="button" class="btn btn-info btn-xs down" title="{{ $value->goods_id }}"><span class="glyphicon glyphicon-thumbs-down"></span>取消</button>--}}
                                        {{--@else--}}
                                            {{--<button type="button" class="btn btn-info btn-xs up" title="{{ $value->goods_id }}"><span class="glyphicon glyphicon-thumbs-up"></span>推荐</button>--}}
                                        {{--@endif--}}
                                    {{--@endif--}}
                                {{--@endif--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
            {{--<div class="panel-footer">--}}
                {{--<div class="row text-left" >--}}
                    {{--<div class="col-xs-5">--}}
                    {{--</div>--}}
                    {{--<div class="col-xs-7 text-right">--}}
                        {{--<a href="#" class="btn btn-info" onClick="javascript :history.back(-1);">返回上一页</a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
    </div>
    <div class="panel-footer">
        <div class="row text-left" >
            <div class="col-xs-5">
            </div>
            <div class="col-xs-7 text-right">
                {!! $info->appends(['goods_id'=>$goods_id])->render() !!}
            </div>
        </div>
    </div>
    <script>
        $('.down').click(function(){
            var goods_id = $(this).attr("title");
            var type = 1;
            $.ajax({
                url:"/manage/group/index",
                data:{type:type, goods_id:goods_id},
                type:'get',
                success:function(){
                    location.href="/manage/group/examine/"+goods_id;
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
                    location.href="/manage/group/examine/"+goods_id;
                }
            });
        });
    </script>
@stop