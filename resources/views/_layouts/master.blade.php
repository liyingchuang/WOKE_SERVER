<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="renderer" content="webkit">
    <meta name='_token' id="maxv_token" content="{{ csrf_token() }}" />
    <title>@if($manage_role=='manage') 系统管理 @else 店铺管理 @endif</title>
    <link rel="stylesheet" href="/assets/css/main.min.css"/>
    <link rel="stylesheet" href="/assets/css/xiaoku.min.css"/>
    <script type="text/javascript" src="/assets/js/main.min.js"></script>
    <style>
        .dropdown-menu{z-index:1100}
    </style>
</head>
<body>
 @include('_layouts.navbar')
    <div class="main-container container-fluid">
        <div class="page-container">
            @include('_layouts.sidebar')
            <div class="page-content">
            @yield('content')
            </div>
        </div>
    </div>
<script type="text/javascript" src="/assets/js/xiaoku.min.js"></script>
<script type="text/javascript">
$('#fullscreen-toggler').on('click', function (e) {
    var element = document.documentElement;
    if (!$('body')
        .hasClass("full-screen")) {
        $('body').addClass("full-screen");
        $('#fullscreen-toggler')
            .addClass("active");
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }

    } else {

        $('body')
            .removeClass("full-screen");
        $('#fullscreen-toggler')
            .removeClass("active");

        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }

    }
});
</script>
</body>
</html>