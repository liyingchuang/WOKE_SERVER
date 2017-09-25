@extends('_layouts.master')
@section('content')
<script src="http://cdn.bootcss.com/flot/0.8.3/jquery.flot.min.js"></script>
<script type="text/javascript" src="/assets/js/editable/jquery-1.12.2.min.js"></script>
<div class="panel  panel-info">
    <div class="panel-heading">
        系统首页
    </div>
    <div class="panel-body">
        <!--  <button type="button" class="btn btn-info btn-ms  pull-right" data-backdrop="static" data-toggle="modal" data-target="#myModals">
             <i class="glyphicon glyphicon-plus"></i></button>
        <center><h3>最多再能创建个用户</h3></center>-->

        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only"></span>
           <h5>欢迎使用 @if($manage_role=='manage') 蜗客后台 @else 蜗客商家后台 @endif 管理系统</h5>
            <p></p>
        </div>
        @if(!empty($sale))
        <div class="alert alert-warning" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only"></span>
            <h5>&nbsp;有新的待审核商品.<a href="{{ url('manage/goods/istrator') }}" class="default col-xs-1 pull-right">去审核</a></h5>
        </div>
        @endif
    </div>
    <div class="panel-footer">
        <div class="row text-right" style="padding-right: 10px" >
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function() {
        x = 5;
        y = 15;
        $("p").hover(function(e) {
            otitle = this.title;
            this.title = "";
            var ndiv = "<div id='leo'>" + otitle + "</div>";
            $("body").append(ndiv);
            $("#leo").css({
                "top" : (e.pageY + y) + "px",
                "left" : (e.pageX + x) + "px"
            }).show(2000);
            $(this).mousemove(function(e) {
                $("#leo").css({
                    "top" : (e.pageY + y) + "px",
                    "left" : (e.pageX + x) + "px"
                }).show(1000);
            });
        }, function() {
            this.title = otitle;
            $("#leo").remove();
        });
    });
</script>
<style type="text/css">
    #leo {
        position: absolute;
        border: 1px solid grey;
        opacity: 0.8;
        background: grey;
    }
</style>
@stop