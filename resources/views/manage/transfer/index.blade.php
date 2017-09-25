@extends('_layouts.master')
@section('content')
    <div class="panel  panel-info">
        <div class="panel-heading">
            提现管理
        </div>
        <div class="panel-body">
            <div class="row " >
                <div class="col-xs-10">
                <form action="{{URL::to('manage/transfer')}}" method="get">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" class="form-control"  name="phone" placeholder="输入 手机号、编号 查询">
                            </div>
                            <div class="col-sm-2">
                                <select name="type" class="form-control">
                                    <option value="" selected>请选择转账状态</option>
                                    <option value="1">待转账</option>
                                    <option value="2">已转账</option>
                                    <option value="3">已驳回</option>
                                </select>
                            </div>
                            <div class="col-xs-6">
                                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
                            </div>
                        </div>
                    </div>
            </div>
          </div>
        </div>
        <div class="panel-body">
            <table class="table  table-bordered table-striped">
                <thead>
                <th>编号</th>
                <th>提现人</th>
                <th>手机号</th>
                <th>提现额</th>
                <th>提现方式</th>
                <th>申请时间</th>
                <th>状态</th>
                <th>订单号/驳回原因</th>
                <th>操作</th>
                </thead>
                <tbody>
                @foreach($info as $infos)
                    <tr>
                        <td>{{ $infos->id }}</td>
                        <td>{{ $infos->user_name }}</td>
                        <td>{{ $infos->mobile_phone }}</td>
                        <td>{{ $infos->alipay_money }}</td>
                        <td>支付宝：{{ $infos->alipay_id }}</td>
                        <td>{{ date('Y-m-d H:i:s',$infos->alipay_time) }}</td>
                        <td>@if($infos->type == 2)<div style="color:red" title="待处理">？</div>@elseif($infos->type == 4) <div style="color:red" title="已驳回">✘</div>@else <div style="color:green" title="已转账">✔@endif</div></td>
                        <td width="18%">@if($infos->type == 2) <input type="text" class="form-control" id ="alipay_id{{ $infos->id }}" placeholder=" 请输入转账单号">@elseif( $infos->type == 3) {{ $infos->flowing }} @elseif( $infos->type == 4 ){{ $infos->reason }}  @endif</td>
                        <td>
                            @if( $infos->type == 2 )<button type="button"  class="btn btn-info alid"  date_id = "{{ $infos->id }}">确认</button>
                            <button type="button"  class="btn btn-info update"  date_id = "{{ $infos->id }}">驳回</button>@endif
                                <a href="{{ URL::to('manage/transfer') }}/{{ $infos->id }}" class="btn btn-info" >查看</a>
                                @if( $infos->type == 3 )<a class="btn btn-info" disabled>通过</a>@endif
                                @if( $infos->type == 4 )<a class="btn btn-info" disabled style="color:red">失败</a>@endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="row text-left" >
                <div class="col-xs-5">
                </div>
                <div class="col-xs-7 text-right">
                    {!! $info->appends(['phone' =>$phone , 'type' =>$type])->render() !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.alid').click(function() {
            var id = $(this).attr('date_id');
            var alipay_id = $("#alipay_id"+id).val();
            var action = 1;
            if(!alipay_id){
                alert('请输入订单号！');
                return false;
            }
            $.ajax({
                url:"/manage/transfer",
                data:{"alipay_id":alipay_id, "id":id, "action":action},
                type:"get",
                success:function() {
                location.href="/manage/transfer";
                }
            });
        });

        $('.update').click(function () {
            var id = $(this).attr('date_id');
            var alipay_id = $("#alipay_id"+id).val();
            var action = 2;
            var reason = prompt("请输入驳回原因！");
            if(reason == null ){
                return false;
            }
            $.ajax({
                url:"/manage/transfer",
                data:{alipay_id:alipay_id, id:id, action:action, reason:reason},
                type:"get",
                success:function() {
                   location.href="/manage/transfer";
                }
            });
        });
    </script>
@stop