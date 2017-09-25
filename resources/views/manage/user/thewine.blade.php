@extends('_layouts.master')
@section('content')
    <link rel="stylesheet" href="/assets/js/editable/bootstrap-editable.css">
    <link rel="stylesheet" href="/assets/js/editable/1.85.css">
    <script src="/assets/js/editable/bootstrap-editable.min.js"></script>
    <div class="panel  panel-info">
        <div class="panel-heading">
            酒币明细
        </div>
        <div class="panel-body">
            <form action="" method="get">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-3" >
                            <input type="text" class="form-control" id="increase" name="increase" placeholder="+ 输入要增加的酒币金额" value="">
                        </div>
                        <div class="col-xs-3">
                            <button type="button" class="btn btn-info increase"> <i class="glyphicon glyphicon-search"></i> 确认</button>
                        </div>
                        <div class="col-xs-3">
                            <input type="text" class="form-control"  id="reduce" name="reduce" placeholder="- 输入要减少的酒币金额" value="">
                        </div>
                        <div class="col-xs-3">
                            <button type="button" class="btn btn-info reduce"> <i class="glyphicon glyphicon-search"></i> 确认</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="tabbable">
            @if($account_info)
            <ul class="nav nav-tabs" id="myTab">
                <li class="active" class="tab-red">
                    <a href="#">
                        全部明细
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                    <div id="all" class="tab-pane in active">
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th>用户ID</th>
                            <th>酒币金额</th>
                            <th>change_type</th>
                            <th>详细</th>
                            <th>时间</th>
                            </thead>
                            <tbody>
                            @foreach($account_info as $key=>$value)
                                <input type="hidden" id="id" name="id" value="{{ $value->user_id }}">
                                    <td scope="row">{{ $value->user_id }}</td>
                                    <td>{{ $value->user_money }}</td>
                                    <td>{{ $value->change_type }}</td>
                                    <td>{{ $value->change_desc }}</td>
                                    <td>{{ date('Y-m-d H:i:s', $value->change_time) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div style="padding-left: 88%;"><h5><strong>酒币总计：{{ $user_money  }}</strong></h5></div>
                    </div>
            </div>
            @else
                <div class="tab-content">
                    <div id="all" class="tab-pane in active" style="padding-left: 45%;font-size: 15px;">
                            <tr>
                                <td scope="row"></td>
                                <td><strong>该用户暂无酒币明细</strong></td>
                            </tr>
                    </div>
                </div>
            @endif
        </div>

    </div>
    <div class="row text-left" >
        <div class="col-xs-5">
        </div>
        <div class="col-xs-7 text-right">
            {!! $account_info->render() !!}
        </div>
    </div>
    <script>
        $('.increase').click(function(){
            var increase = $("#increase").val();
            var type = 1;
            if(!increase){
                alert("请输入酒币数量");
                return false;
            }
            var reason = prompt("请输入 + 备注！");
            if(reason == null ){
                return false;
            }
            if(reason == ""){
                prompt("修改失败，备注不能为空！");
                return false;
            }
            $.ajax({
                url:"/manage/user/thewine/{{ Session::get('user_id') }}",
                data:{increase:increase, type:type, reason:reason},
                type:"get",
                success:function(){
                    location.href="/manage/user/thewine/{{ Session::get('user_id') }}";
                }
            });
        });
        $('.reduce').click(function(){
            var reduce = $("#reduce").val();
            var type = 2;
            if(!reduce){
                alert("请输入酒币数量");
                return false;
            }
            var reason = prompt("请输入 - 备注！");
            if(reason == null ){
                return false;
            }
            if(reason == ""){
                prompt("修改失败，备注不能为空！");
                return false;
            }
            $.ajax({
                url:"/manage/user/thewine/{{ Session::get('user_id') }}",
                data:{reduce:reduce, type:type, reason:reason},
                type:"get",
                success:function(){
                    location.href="/manage/user/thewine/{{ Session::get('user_id') }}";
                }
            });
        });
    </script>
@stop




