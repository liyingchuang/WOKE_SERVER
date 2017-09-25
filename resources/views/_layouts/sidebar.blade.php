<div class="page-sidebar" id="sidebar">
	<!-- Page Sidebar Header
	<div class="sidebar-header-wrapper">
		<input type="text" class="searchinput" /> <i
			class="searchicon fa fa-search"></i>
		<div class="searchhelper">Search Reports, Charts, Emails or
			Notifications</div>
	</div>-->
	<!-- /Page Sidebar Header -->
	<!-- Sidebar Menu -->
	<ul class="nav sidebar-menu">
		<!--Dashboard-->
		<li><a href="{{URL::to('manage/home')}}"> <i
				class="menu-icon glyphicon glyphicon-home"></i> <span
				class="menu-text"> 平台首页 </span>
		</a></li>

		<!--Databoxes-->
               @foreach ($menus as $k=>$v)
		<li  @if(count($v['child_menus']))
                    <?php
                    $array = array_fetch($v['child_menus'], 'action');
                    $is_open=0;
                    foreach ($v['child_menus'] as $kk=>$vv){
                        $is_open=isset($vv['opened_actions'])&&in_array($url, $vv['opened_actions']);
                        break;

                    }


                    if(in_array($url, $array)||$is_open)
                    echo "class='active open'";
                    ?>@else
                    <?php
                      if($url==$v['action']||(!empty($v['opened_actions'])&&in_array($url, $v['opened_actions'])))
                         echo "class='active'";

                    ?>
                    @endif>
                <a href="{{URL::route($v['action'])}}"  @if(count($v['child_menus'])) class="menu-dropdown" @endif> <i  class="menu-icon fa  {{$v['icon']}}"></i>

		<span class="menu-text"> {{$v['alias']}} </span>@if(count($v['child_menus']))<i class="menu-expand"></i>@endif</a>
                 @if(count($v['child_menus']))
		  <ul class="submenu">
                      @foreach($v['child_menus'] as $value)
		     <li @if($url==$value['action']||(!empty($value['opened_actions'])&&in_array($url, $value['opened_actions']))) class='active' @endif><a href="{{URL::route($value['action'])}}">
				<span class="menu-text">{{$value['alias']}} </span></a>
                    </li>
			@endforeach
		  </ul>
                 @endif
               </li>
               @endforeach
	</ul>
	<!-- /Sidebar Menu -->
</div>