@if(App\Components\MenuTools::isShowMenu(['/statistics/user/cumulate'])) 

@if((isset($admin_user_cumulate_tree_menu) && $admin_user_cumulate_tree_menu))
<li class="active treeview">
    @else
<li class="treeview">
    @endif
    <a href="#"><i class="fa fa-pie-chart"></i> <span><?php echo \App\Components\Tools::sLang('Data Statistics', '数据统计');?></span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(App\Components\MenuTools::isShowMenu(['/statistics/user/cumulate']))
        <li @if((isset($admin_user_cumulate_tree_menu) && $admin_user_cumulate_tree_menu))class="active" @endif><a href="/statistics/user/cumulate"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('User Analysis', '用户分析');?></a></li>
        @endif
    </ul>
</li>
@endif