@extends('_layouts.master')
@section('content')
    <div class="panel  panel-info">
        <div class="panel-heading">
            禁言管理
        </div>
        <div class="panel-body"></div>
        <div class="tabbable">
            <table class="table  table-bordered table-striped">
                <thead>
                <th>编号</th>
                <th>被投诉人</th>
                <th>投诉人</th>
                <th>原因</th>
                <th>投诉时间</th>
                <th>操作</th>
                </thead>
                <tbody>
                @foreach ($list as $k=>$u)
                    <tr >
                        <td>{{$k+1}}</td>
                        <td>{{$u->user->user_name}}</td>
                        <td>{{$u->from->user_name}}</td>
                        <td>{{$u->desc}}</td>
                        <td>{{$u->created_at}}</td>
                        <td><button class="btn btn-danger  btn-xs remove "  type="button" alt="{{$u->id}}"><i class="glyphicon glyphicon-trash"></i> </button></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            <div class="row text-right" style="padding-right: 10px" >
                {!! $list->render() !!}
            </div>
        </div>
    </div>
    <script>
        $('.remove').click(function() {
            var id = $(this).attr('alt');
            $.ajax({
                type: 'DELETE',
                success: function(result) {

                }
            });
            $(this).parents('tr:first').remove();
        });

    </script>
@stop