<header class="main-header">
    <a href="javascript:void(0);" class="logo">
        <span class="logo-mini"><b>{{ env('SYSTEM_NAME','PUPPET') }}</b></span>
        <span class="logo-lg"><b>{{ env('SYSTEM_NAME','PUPPET') }}<?php echo \App\Components\Tools::sLang('Background Management', '');?></b></span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php 
                  use Illuminate\Support\Facades\Cookie;
                  $lang = Cookie::get('puppet_lang');
                ?>
                @if($lang == 2)
                <li class="dropdown user user-menu">
                    <a href="{{ URL('admin/lang/set/1')}}">
                    <img src="{{ asset('/images/en.jpg').'?v='.env('VERSION') }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">EN</span>
                  </a>
                </li>
                @else
                <li class="dropdown user user-menu">
                  <a href="{{ URL('admin/lang/set/2')}}">
                    <img src="{{ asset('/images/cn.jpg').'?v='.env('VERSION') }}" class="user-image" alt="User Image">
                    <span class="hidden-xs">CN</span>
                  </a>
                </li>
                @endif
                <li class="dropdown user user-menu">
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                      <img src="{{ asset('/images/head.jpg').'?v='.env('VERSION') }}" class="user-image" alt="User Image">
                      <span class="hidden-xs"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{ asset('/images/head.jpg').'?v='.env('VERSION') }}" class="img-circle" alt="User Image">
                            <p>
                                {{ isset(session('user')[0]['admin_name']) ? session('user')[0]['admin_name'] : '' }} {{ isset(session('user')[0]['real_name']) ? ('- '.session('user')[0]['real_name']) : '' }}
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{URL('admin/update/password')}}" class="btn btn-default btn-flat"><?php echo \App\Components\Tools::sLang('Change Password', '修改密码');?></a>
                            </div>
                            <div class="pull-right">
                                <a href="{{URL('admin/logoff')}}" class="btn btn-default btn-flat"><?php echo \App\Components\Tools::sLang('Logout', '注销');?></a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!--
                <li>
                  <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
                -->
            </ul>
        </div>
    </nav>
</header>