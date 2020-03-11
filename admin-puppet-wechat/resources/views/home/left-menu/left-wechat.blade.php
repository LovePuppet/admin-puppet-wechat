@if(App\Components\MenuTools::isShowMenu(['/admin/wechat/menu/list','/admin/wechat/keyword/list','/admin/wechat/follow/list','/admin/group/send/list'])) 

@if((isset($admin_wechat_menu_tree_menu) && $admin_wechat_menu_tree_menu) || (isset($admin_wechat_keyword_tree_menu) && $admin_wechat_keyword_tree_menu)
 || (isset($admin_wechat_follow_tree_menu) && $admin_wechat_follow_tree_menu) || (isset($admin_group_send_tree_menu) && $admin_group_send_tree_menu))
<li class="active treeview">
    @else
<li class="treeview">
    @endif
    <a href="#"><i class="fa fa-wechat"></i> <span><?php echo \App\Components\Tools::sLang('WeChat setting', '微信管理');?></span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(App\Components\MenuTools::isShowMenu(['/admin/wechat/menu/list']))
        <li @if((isset($admin_wechat_menu_tree_menu) && $admin_wechat_menu_tree_menu))class="active" @endif><a href="/admin/wechat/menu/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Custom menu', '自定义菜单');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/wechat/follow/list']))
        <li @if((isset($admin_wechat_follow_tree_menu) && $admin_wechat_follow_tree_menu))class="active" @endif><a href="/admin/wechat/follow/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Follow/reply message', '关注回复');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/group/send/list']))
        <li @if((isset($admin_group_send_tree_menu) && $admin_group_send_tree_menu))class="active" @endif><a href="/admin/group/send/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Chat log', '群发记录');?></a></li>
        @endif
        <!--
        @if(App\Components\MenuTools::isShowMenu(['/admin/wechat/keyword/list']))
        <li @if((isset($admin_wechat_keyword_tree_menu) && $admin_wechat_keyword_tree_menu))class="active" @endif><a href="/admin/wechat/keyword/list"><i class="fa fa-circle-o"></i>关键词回复</a></li>
        @endif
        -->
    </ul>
</li>
@endif