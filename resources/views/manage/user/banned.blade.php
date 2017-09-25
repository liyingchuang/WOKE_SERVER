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
            <th>用户名</th>
            <th>手机号</th>
            <th>原因</th>
            <th>禁言开始时间</th>
            <th>禁言结束时间</th>
            <th>禁言时间</th>
            <th>操作</th>
            </thead>
            <tbody>
                @foreach ($list as $k=>$u)
                <tr @if($u->end_time< time()) class="danger" @endif>
                    <td>{{$u->user->user_id}}</td>
                    <td>{{$u->user->user_name}}</td>
                    <td>{{$u->user->mobile_phone}}</td>
                    <td>{{$u->desc}}</td>
                    <td>{{date('Y-m-d H:i:s',$u->start_time) }}</td>
                    <td>{{date('Y-m-d H:i:s',$u->end_time) }}</td>
                    <td>{{$u->created_at}}</td>
                    <td><button class="btn btn-danger  btn-xs remove "  type="button" alt="{{$u->id}}"><i class="glyphicon glyphicon-trash"></i> 取消禁言</button></td>
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
            url: "{{URL::to('manage/user')}}/"+id,
            type: 'DELETE',
            success: function(result) {
                
            }
        });
        $(this).parents('tr:first').remove();
});

</script>
@stop