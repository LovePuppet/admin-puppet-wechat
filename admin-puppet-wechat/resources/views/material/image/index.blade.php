@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Image', '图片素材');
?>
@section('title',$title)
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/dataTables.bootstrap.css') }}">
<style type="text/css">
    .icon_img_css{
        max-width:60px;
        max-height:60px;
    }
</style>
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
              <?php echo \App\Components\Tools::sLang('Image', '图片素材');?>
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
              <li class="active"><?php echo \App\Components\Tools::sLang('Image', '图片素材');?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"></h3>
                            <button type="button" class="btn btn-success" style="margin-left:80%;" onclick="imageSync()"><?php echo \App\Components\Tools::sLang('Synchronization', '同步');?></button>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Image', '图片');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Name', '名称');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Image', '图片');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Name', '名称');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                        <!-- /.box-body -->
                      </div>
                      <!-- /.box -->
                </div>
            </div>
        </section>
    </div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('/js/fastclick.js') }}"></script>
<script src="{{ asset('/js/tools.js').'?v='.env('VERSION') }}"></script>
<script>
var image_domain = '{{ env('IMAGE_DOMIAN')}}';
var list_ajax;
$(function () {
//提示信息
    var lang = puppet.langShow(puppet_lang);
    //初始化表格
    list_ajax = $("#data_list").dataTable({
        language: lang, //提示信息
        autoWidth: false, //禁用自动调整列宽
        stripeClasses: ["odd", "even"], //为奇偶行加上样式，兼容不支持CSS伪类的场合
        processing: true, //隐藏加载提示,自行处理;
        serverSide: true, //启用服务器端分页
        searching: false, //禁用原生搜索
        orderMulti: false, //启用多列排序
        order: [], //取消默认排序查询,否则复选框一列会出现小箭头
        renderer: "bootstrap", //渲染样式：Bootstrap和jquery-ui
        pagingType: "simple_numbers", //分页样式：simple,simple_numbers,full,full_numbers
        columnDefs: [
            {
                "targets": [0,1,2,3,4], //列的样式名
                "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
                targets: 1,
                title: puppet_lang == 2 ? "图片" : "Image",
                render: function (data, type, row, meta) {
                    return "<img src="+image_domain+data+" class='icon_img_css'/>";
                }
            },
            {
                targets: 3,
                title: puppet_lang == 2 ? "创建时间" : "Create time",
                render: function (data, type, row, meta) {
                    return puppet.formatDateTime(data*1000);
                }
            },
            {
                targets: 4,
                title: puppet_lang == 2 ? "操作" : "Action",
                render: function (data, type, row, meta) {
                    return "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+row.image_id+",-1)'><span class='glyphicon glyphicon-trash'></span></a>";
                }
            },
        ],
        ajax: function (data, callback, settings) {
            //封装请求参数
            var param = {};
            param.limit = data.length; //页面显示记录条数，在页面显示每页显示多少项的时候
            param.start = data.start; //开始的记录序号
            param.page = (data.start / data.length) + 1; //当前页码
            param.order = data.order;
            param.search = data.search;
            $.ajax({
                type: "POST",
                url: "{{ URL('admin/material/image/list/ajax') }}",
                cache: false, //禁用缓存
                data: param, //传入组装的参数
                dataType: "json",
                success: function (result) {
                    var returnData = {};
                    returnData.draw = data.draw; //这里直接自行返回了draw计数器,应该由后台返回
                    returnData.recordsTotal = result.total; //返回数据全部记录
                    returnData.recordsFiltered = result.total; //后台不实现过滤功能，每次查询均视作全部结果
                    returnData.data = result.data; //返回的数据列表
                    callback(returnData);
                }
            });
        },
        //列表表头字段
        columns: [
            {"data": "image_id"},
            {"data": "url"},
            {"data": "name"},
            {"data": "create_time"},
            {"data":null},
        ]
    }
    ).api();
}
);
function actionList(id,status){
  var content = (puppet_lang == 2 ? "删除该图片将会连同微信素材中一起删除，是否确认删除？" : "Deleting the picture will be deleted together with the WeChat material. Do you want to delete it?");
  var result = confirm(content);
  if(result){
    var url = "{{URL('admin/material/image/delete')}}";
    var data = {id:id,status:status};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == true){
      alert(result.msg);
      return false;
    }else{
      list_ajax.ajax.reload(null,false);
    }
  }
  return false;
}
function imageSync(){
  var url = "{{URL('admin/material/image/sync')}}";
  var data = {};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == true){
    alert(result.msg);
    return false;
  }else{
    list_ajax.ajax.reload(null,false);
  }
}
</script>
@endsection