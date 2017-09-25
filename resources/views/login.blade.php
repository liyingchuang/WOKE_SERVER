<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="renderer" content="webkit">
    <title>蜗客商城系统管理</title>
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/login.min.css">
</head>
<body class="login">
    <div class="form-signin">
      <div class="text-center">
          <img src="/assets/images/logo.png"  width="100">
      </div>
      <hr>
      <div class="tab-content">
        <div id="login" class="tab-pane active">
            <form action="{{URL::to('manage/login')}}" method="post">
            <p class="text-muted text-center">
                @if(Session::has('message'))
            <div style="color:red;text-align: center">{{Session::get('message')}}</div>
                @else
                请输入帐号密码
                @endif
            </p>
            <input type="text" name="email" placeholder="请输入Email/用户名" class="form-control top">
            <input type="password" name="password" placeholder="请输入密码" class="form-control bottom">
            <div class="checkbox">
              <label>
                  <input type="checkbox" name="remember">记住我
              </label>
            </div>
            <button class="btn btn-lg btn-primary btn-block" type="submit">登录</button>
          </form>
        </div>
 
      </div>
      <hr>
      <div class="text-center">
        <ul class="list-inline">
          
        </ul>
      </div>
    </div>
</body>
</html>