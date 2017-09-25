@extends('_layouts.master')
@section('content')
    <link rel="stylesheet" href="/assets/js/editable/1.85.css">
    <div class="panel  panel-info">
        <div class="panel-heading">
            <ol class="breadcrumb">
                <li>提现管理</li>
                <li class="active">提现信息</li>
            </ol>
        </div>
        <div class="panel-body">
        </div>
        <form method="post"  class="form-horizontal form-bordered" >
            <div class="form-group">
                <div class="col-sm-2 col-xs-2 col-md-2"></div>
                <div class="col-sm-8 col-xs-8 col-md-8">
                    <div class="table-responsive">
                        <input type="hidden" name="order_id" id="order_id" value="{{ $ali->id }}">
                        <table class="table  table-bordered table-striped float">
                            <tbody>
                            <tr>
                                <td colspan="4" align="center"> 提现信息</td>
                            </tr>
                            <tr>
                                <td align="right">订单号：</td>
                                <td>{{ $ali->flowing }}</td>
                                <td align="right">订单状态：</td>
                                <td>
                                    @if( $ali->type == 4) 已驳回 @elseif( $ali->type == 3) 已转账 @elseif( $ali->type == 2) 待处理 @endif</td>
                            </tr>
                            <tr>
                                <td align="right">提现人：</td>
                                <td>{{ $ali->user_name }}</td>
                                <td align="right">支付宝账号：</td>
                                <td>{{$ali->alipay_id}}</td>
                            </tr>
                            <tr>
                                <td align="right">提现方式：</td>
                                <td>@if( $ali->mode =='1')支付宝提现@endif @if( $ali->mode =='2')微信提现@endif</td>
                                <td align="right">提现时间：</td>
                                <td>{{ date('Y-m-d', $ali->alipay_time) }}</td>
                            </tr>
                            <tr>
                                <td align="right">提现金额：</td>
                                <td>{{ $ali->alipay_money }}</td>
                                <td align="right">用户手机号：</td>
                                <td>{{ $ali->mobile_phone }}</td>
                            </tr>
                            <tr>
                                <td align="right">操作人：</td>
                                <td>{{ $ali->operator }}</td>
                                <td align="right">操作时间：</td>
                                <td>@if(!empty($ali->oper_time)){{ date('Y-m-d', $ali->oper_time) }} @else @endif</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-2 col-md-2"></div>
            </div>
            <div class="form-group">
                <div class="col-sm-2 col-xs-2 col-md-2"></div>
                <div class="col-sm-8 col-xs-8 col-md-8">
                    <div class="table-responsive">
                        <table class="table  table-bordered table-striped float">
                            <tbody>
                            <tr>
                                <td align="center"> 用户信息</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive" style="margin-top:0px;">
                        <table class="table  table-bordered table-striped">
                            <thead>
                            <th>用户ID</th>
                            <th>用户名</th>
                            <th>手机号</th>
                            <th>酒币余额</th>
                            <th>支付宝账号</th>
                            <th>酒币明细</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $user->user_id }}</td>
                                    <td>{{ $user->user_name }}</td>
                                    <td>{{ $user->mobile_phone }}</td>
                                    <td>{{ $user->user_money }}</td>
                                    <td>{{ $user->alipay_id }}</td>
                                    <td><a href="{{ URL::to('manage/user/thewine') }}/{{ $user->user_id }}" >查看</a></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                    </div>
                </div>
                <div class="col-sm-2 col-xs-2 col-md-2"></div>
            </div>
        </form>
    </div>
        <div style="padding-left:77.5%;">
            <div id="main">
                <div class="demo">
                    <a href="#" class="btn btn-info" onClick="javascript :history.back(-1);">返回上一页</a>

                </div>
            </div>
        </div>
@stop

