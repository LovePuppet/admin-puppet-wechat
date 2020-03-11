@if(App\Components\MenuTools::isShowMenu(['/admin/robot/list'])) 

@if((isset($admin_robot_tree_menu) && $admin_robot_tree_menu))
<li class="active treeview">
    @else
<li class="treeview">
    @endif
    <a href="#"><i class="fa fa-slideshare"></i> <span><?php echo \App\Components\Tools::sLang('ROBOT management', 'ROBOT管理');?></span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(App\Components\MenuTools::isShowMenu(['/admin/robot/list']))
        <li @if((isset($admin_robot_tree_menu) && $admin_robot_tree_menu))class="active" @endif><a href="/admin/robot/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('ROBOT list', 'Robot列表');?></a></li>
        @endif
    </ul>
</li>
@endif