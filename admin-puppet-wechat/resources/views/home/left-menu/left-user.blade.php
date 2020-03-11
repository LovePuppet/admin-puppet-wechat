@if(App\Components\MenuTools::isShowMenu(['/admin/user/list','/admin/tag/list','/admin/channel/list','/admin/email/list'])) 

@if((isset($admin_user_tree_menu) && $admin_user_tree_menu) || (isset($admin_tag_tree_menu) && $admin_tag_tree_menu)
|| (isset($admin_channel_tree_menu) && $admin_channel_tree_menu) || (isset($admin_email_tree_menu) && $admin_email_tree_menu))
<li class="active treeview">
    @else
<li class="treeview">
    @endif
    <a href="#"><i class="fa fa-user"></i> <span><?php echo \App\Components\Tools::sLang('User management', '用户管理');?></span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(App\Components\MenuTools::isShowMenu(['/admin/user/list']))
        <li @if((isset($admin_user_tree_menu) && $admin_user_tree_menu))class="active" @endif><a href="/admin/user/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('User list', '用户列表');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/tag/list']))
        <li @if((isset($admin_tag_tree_menu) && $admin_tag_tree_menu))class="active" @endif><a href="/admin/tag/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Tag list', '标签列表');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/channel/list']))
        <li @if((isset($admin_channel_tree_menu) && $admin_channel_tree_menu))class="active" @endif><a href="/admin/channel/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Channel list', '渠道列表');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/email/list']))
        <li @if((isset($admin_email_tree_menu) && $admin_email_tree_menu))class="active" @endif><a href="/admin/email/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Email list', '邮箱列表');?></a></li>
        @endif
    </ul>
</li>
@endif
