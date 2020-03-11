@if((isset($admin_test1_tree_menu) && $admin_test1_tree_menu) || (isset($admin_test2_tree_menu) && $admin_test2_tree_menu))
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
    <li @if((isset($admin_test1_tree_menu) && $admin_test1_tree_menu))class="active" @endif><a href="/test1"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Statistics Demo1', '统计demo1');?></a></li>
    <li @if((isset($admin_test2_tree_menu) && $admin_test2_tree_menu))class="active" @endif><a href="/test2"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Statistics Demo2', '统计demo2');?></a></li>
  </ul>
</li>