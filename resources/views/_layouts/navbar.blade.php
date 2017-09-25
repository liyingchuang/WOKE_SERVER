<!-- Navbar -->
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="navbar-container">
            <!-- Navbar Barnd -->
            <div class="navbar-header pull-left">
                <a href="#" class="navbar-brand" style="line-height: 40px;">
                    <i class="glyphicon glyphicon-home"></i> @if($manage_role=='manage') 系统管理 @else 店铺管理 @endif
                </a>
            </div>
            <!-- /Navbar Barnd -->
            <!-- Sidebar Collapse -->
            <div class="sidebar-collapse" id="sidebar-collapse">
                <i class="collapse-icon fa fa-bars"></i>
            </div>
            <!-- /Sidebar Collapse -->
            <!-- Account Area and Settings -->
            <div class="navbar-header pull-right">
                <div class="navbar-account">
                    <ul class="account-area" style="right:5px;">
                        <li>
                            <a class="login-area dropdown-toggle" data-toggle="dropdown">
                                <div class="avatar text-right"  style="color:#fff;font-size: 20px;border-left:0px">
                                    <i class="glyphicon glyphicon-cog"> </i>
                                </div>
                                <section>
                                    <h2>
                                        <span class="profile"><span>{{$manage_user_email}}</span></span>
                                    </h2>
                                </section>
                            </a> 
                            <!--Login Area Dropdown-->
                            <ul
                                class="pull-right dropdown-menu dropdown-arrow dropdown-login-area">
                                <li class="username"><a> </a></li>
                                <li class="email"><a> </a></li>
                                <!--Avatar Area-->
                                <li>
                                    <div class="avatar-area">
                                        <img src="/assets/images/logo.png" class="avatar"> <span
                                            class="caption"></span>
                                    </div>
                                </li>
                                <!--Avatar Area-->

                                <!--Theme Selector Area-->
                                <li class="theme-area"></li>
                                <!--/Theme Selector Area-->
                                <li class="dropdown-footer"><a href="{{URL::to('manage/logout')}}"> 退出 </a>
                                </li>
                            </ul> <!--/Login Area Dropdown-->
                        </li>
                        <!-- /Account Area -->

                        <!-- Settings -->
                    </ul>

                </div>
            </div>
            <!-- /Account Area and Settings -->
        </div>
    </div>
</div>
<!-- /Navbar -->