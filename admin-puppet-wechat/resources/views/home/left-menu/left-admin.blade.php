@if(App\Components\MenuTools::isShowMenu(['/admin/list','/admin/role/list','/admin/limit/list']))
@if((isset($admin_tree_menu) && $admin_tree_menu) || (isset($admin_role_tree_menu) && $admin_role_tree_menu) || (isset($admin_limit_tree_menu) && $admin_limit_tree_menu))
<li class="active treeview">
    @else
<li class="treeview">
    @endif
    <a href="#"><i class="fa fa-laptop"></i> <span><?php echo \App\Components\Tools::sLang('Authorization', '权限管理');?></span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(App\Components\MenuTools::isShowMenu(['/admin/list']))
        <li @if((isset($admin_tree_menu) && $admin_tree_menu))class="active" @endif><a href="/admin/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Administrator list', '管理员列表');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/role/list','/admin/limit/list']))
        <li @if((isset($admin_role_tree_menu) && $admin_role_tree_menu) || (isset($admin_limit_tree_menu) && $admin_limit_tree_menu))class="active"@endif>
             <a href="#"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Administrator type', '管理员类型');?>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            @if((isset($admin_role_tree_menu) && $admin_role_tree_menu) || (isset($admin_limit_tree_menu) && $admin_limit_tree_menu))
            <ul class="treeview-menu menu-open" style="display:block;">
                @else
                <ul class="treeview-menu" style="display:none;">
                    @endif
                    @if(App\Components\MenuTools::isShowMenu(['/admin/role/list']))
                    <li @if((isset($admin_role_tree_menu) && $admin_role_tree_menu))class="active" @endif>
                         <a href="/admin/role/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Set administrator role', '角色配置');?></a>
                    </li>
                    @endif
                    @if(App\Components\MenuTools::isShowMenu(['/admin/limit/list']))
                    <li @if((isset($admin_limit_tree_menu) && $admin_limit_tree_menu))class="active" @endif>
                         <a href="/admin/limit/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Set administrator limit', '权限配置');?></a>
                    </li>
                    @endif
                </ul>
        </li>
        @endif
    </ul>
</li>
@endif
