@extends('_layouts.master')
@section('content')
<style>
     .btn-default{
                background-color: #F5F5F5;
                border-color: #B9A644;
                color: #B9A644;
                border-radius:20px;
               
            }
</style>
<div class="panel  panel-info">
    <div class="panel-heading">
        晒晒推荐
    </div>
    <div class="panel-body">
       <form action="{{URL::to('manage/show/recommend')}}" method="get">
        <div class="form-group">
            <div class="row">
            <div class="col-xs-9">
                <input type="text" class="form-control"  name="keyword" placeholder="请输入手机号或者编号或者用户名搜索">
            </div>
            <div class="col-xs-3">
                <button type="submit" class="btn btn-info"> <i class="glyphicon glyphicon-search"></i> 搜索</button>
            </div>
          </div> 
        </div>
       </form>
    </div>
   <form action="{{URL::to('manage/show/recommend')}}" method="post">
    <table class="table  table-bordered table-striped">
        <thead>
        <!--<th><label> <input type="checkbox" class="multi_checked"><span class="text"></span>-->
	</label></th>
        <th>编号</th>
        <th>晒晒内容</th>
        <th>用户</th>
        <th>显示否</th>
        <th>是否推荐</th>
        <th>发布日期</th> 
        <th>操作</th> 
        </thead>
        <tbody>   
    @foreach ($list as $k=>$u)
    <tr  @if(count($u->report)>0)  class="danger" @endif >
          <!--<td scope="row"><label> <input type="checkbox" class="t" name="ids[]" value="{{$u->id}}"><span class="text"></span></label></td>-->
        <td>{{$k+1}}</td>
         <td>
             <a href="{{$u->file_name}}" target="_break" ><img src="{{$u->file_name}}?imageView2/2/w/50" alt="..." class="img-thumbnail"></a>
         </td>
         <td>@if(!empty($u->user))<a href="{{URL::to('manage/show/recommend')}}?id={{$u->user->user_id}}"   > <img id="avatar" src="{{$u->user->headimg}}?imageView2/2/w/50" alt="" class="img-circle" width="40"></a>
         {{$u->user->user_name}}  @if($u->user->is_v)<span style="font-weight:bold;font-style:italic;color:red"> V</span> @endif @endif
         </td>
         <td>
           <label>
                <input class="checkbox-slider colored-blue yesno" title="{{$u->id}}" type="checkbox" @if($u->is_show) checked @endif>
                <span class="text"></span>
            </label>  
         </td>
        <td>
            <label>
                <input class="checkbox-slider slider-icon colored-darkorange offon" type="checkbox" id="is_recommend{{$u->id}}"  title="{{$u->id}}" @if($u->is_recommend == 2) checked @endif>
                <span class="text"></span>
            </label>
        </td>
        <td>{{$u->created_at}}</td>
         <td class="center ">
             <a class="btn btn-info  btn-xs"  href="{{URL::to('manage/show')}}/{{$u->id}}"><i class="glyphicon glyphicon-eye-open"></i> 查看</a>
        </td>
    </tr>
    @endforeach
            
        </tbody>
    </table>
    <div class="panel-footer"> 
        <div class="row" >
            <div class="col-sm-5 col-xs-5 col-md-5 " >
            </div>
            <div class="col-sm-7 col-xs-7 col-md-7 text-right" > 
 
                {!! $list->appends(['id' =>$id,'keyword'=>$keyword])->render() !!}
            </div>
      
        </div>
    </div>
   </form>
</div>
<script type="text/javascript">
$('.multi_checked').change(function () {
    $checkeds=$('.multi_checked').is(':checked');
    if($checkeds){
        $('.t').prop('checked', true);
    }else{
      $('.t').prop('checked', false);
    }
});
$(".yesno").bind("change", function () {
   var id=$(this).attr('title');
   if($(this).is(':checked')) {
       $.ajax({
            url: "{{URL::to('manage/show')}}/"+id,
            type: 'DELETE',
            success: function(result) {
                
            }
       });
       $(this).attr("checked", true); 
   }else{
      $.ajax({
            url: "{{URL::to('manage/show')}}/"+id,
            type: 'DELETE',
            success: function(result) {
                
            }
       });
       $(this).attr("checked", false);
   }
});

$(".offon").bind("change", function () {
    var id=$(this).attr('title');
    if($(this).is(':checked')) {
        $.ajax({
            url:"/manage/show/offon",
            data:{"id":id},
            type:"get"
        });
        $(this).attr("checked", true);
    }else{
        $.ajax({
            url:"/manage/show/offon",
            data:{"id":id},
            type:"get",
			success: function(result) {
                $("#is_recommend"+id).parents('tr:first').remove();
            }
        });
        $(this).attr("checked", false);
    }
});
</script>
@stop