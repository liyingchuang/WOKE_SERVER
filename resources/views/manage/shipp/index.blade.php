@extends('_layouts.master')
@section('content')
    <div class="panel  panel-info">
        <div class="panel-heading">
            运费模板
        </div>
        <div class="panel-body">
            <div class="col-xs-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-offset-12">
                            <a href="{{ URL::to('manage/shipp/province') }}" class="btn btn-info"> <i
                                        class="glyphicon glyphicon-plus"></i> 添加运费模板</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="table">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="">模板管理</a></li>
            </ul>
            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    @foreach($shipp as $pp)
                        <thead>
                        <tr class="danger">
                            <td>
                                <h4>{{ $pp->shipp_fee_name }}　@if($pp->is_default==0)
                                        <a href="{{URL::to('manage/shipp/edit')}}?ship_id={{ $pp->shipp_fee_id }}&type=onship" class="btn btn-info onship">使用此模板</a> @else <i
                                                class="glyphicon glyphicon-ok"></i> @endif</h4>

                            </td>
                            <td></td>
                            <td></td>
                            <td><a href="{{URL::to('manage/shipp/edit')}}?ship_id={{ $pp->shipp_fee_id }}&type=del" class="">删除</a></td>
                        </tr>
                        </thead>
                        <thead>
                        <th>配送地区　　{{ $pp->shipp_name }}</th>
                        <th>重量(kg)</th>
                        <th>价格(元)</th>
                        <th>操作</th>
                        </thead>
                        @foreach($pp->extends as $v)
                            <tbody>
                            <tr>
                                <td>@if($v->is_default == 0) @foreach($v->ine as $s)  {{ $s->name }}&nbsp @endforeach @else
                                        <h5>默认</h5> @endif</td>
                                <td>{{ $v->number }}</td>
                                <td>{{ $v->price }}</td>
                                <td>@if($v->is_default == 0)
                                        <a href="{{URL::to('manage/shipp/edit')}}?ship_id={{ $v->id }}&type=delcity" type="button" class="btn btn-danger btn-xs"><i
                                                    class="glyphicon glyphicon-remove"></i></a>@else @endif</td>
                            </tr>

                            </tbody>
                        @endforeach
                    @endforeach
                </table>
            </div>
        </div>
        <div class="panel-footer"></div>
    </div>
@stop