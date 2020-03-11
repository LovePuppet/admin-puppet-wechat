@if(App\Components\MenuTools::isShowMenu(['/admin/material/text/list','/admin/material/image/list','/admin/material/news/list','/admin/material/file/list','/admin/material/video/list'])) 

@if((isset($material_text_tree_menu) && $material_text_tree_menu) || (isset($material_image_tree_menu) && $material_image_tree_menu)
    || (isset($material_news_tree_menu) && $material_news_tree_menu) || (isset($material_file_tree_menu) && $material_file_tree_menu)
    || (isset($material_video_tree_menu) && $material_video_tree_menu))
<li class="active treeview">
    @else
<li class="treeview">
    @endif
    <a href="#"><i class="fa fa-inbox"></i><span><?php echo \App\Components\Tools::sLang('Message Setting', '素材管理');?></span>
        <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
        </span>
    </a>
    <ul class="treeview-menu">
        @if(App\Components\MenuTools::isShowMenu(['/admin/material/text/list']))
        <li @if((isset($material_text_tree_menu) && $material_text_tree_menu))class="active" @endif><a href="/admin/material/text/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Text ', '文本素材');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/material/image/list']))
        <li @if((isset($material_image_tree_menu) && $material_image_tree_menu))class="active" @endif><a href="/admin/material/image/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Image', '图片素材');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/material/news/list']))
        <li @if((isset($material_news_tree_menu) && $material_news_tree_menu))class="active" @endif><a href="/admin/material/news/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('News', '图文素材');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/material/video/list']))
        <li @if((isset($material_video_tree_menu) && $material_video_tree_menu))class="active" @endif><a href="/admin/material/video/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Video', '视频列表');?></a></li>
        @endif
        @if(App\Components\MenuTools::isShowMenu(['/admin/material/file/list']))
        <li @if((isset($material_file_tree_menu) && $material_file_tree_menu))class="active" @endif><a href="/admin/material/file/list"><i class="fa fa-circle-o"></i><?php echo \App\Components\Tools::sLang('Files', '文件列表');?></a></li>
        @endif
    </ul>
</li>
@endif