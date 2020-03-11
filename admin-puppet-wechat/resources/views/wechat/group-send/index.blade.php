@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Group record', '群发记录');
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
              <?php echo \App\Components\Tools::sLang('Group record', '群发记录');?>
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
              <li class="active"><?php echo \App\Components\Tools::sLang('Group record', '群发记录');?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <a href="{{ URL('admin/group/send/create') }}" class="btn btn-info"><?php echo \App\Components\Tools::sLang('Add group', '新建群发');?></a>
                            <h3 class="box-title"></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Group tag', '群发标签');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Msg type', '消息类型');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Msg content', '消息内容');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Send time', '发送时间');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Group tag', '群发标签');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Msg type', '消息类型');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Msg content', '消息内容');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Send time', '发送时间');?></th>
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
        order: [[0, 'desc']], //取消默认排序查询,否则复选框一列会出现小箭头
        renderer: "bootstrap", //渲染样式：Bootstrap和jquery-ui
        pagingType: "simple_numbers", //分页样式：simple,simple_numbers,full,full_numbers
        columnDefs: [
            {
              "targets": [0,1,2,3,4], //列的样式名
              "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
              targets: 3,
              title: puppet_lang == 2 ? "消息内容" : "Msg content",
              render: function (data, type, row, meta) {
                if(row.material_type == 1){
                  return data;
                }else{
                  return "<img src="+image_domain+data+" class='icon_img_css'/>";
                }
              }
            },
            {
              targets: 4,
              title: puppet_lang == 2 ? "发送时间" : "Send time",
              render: function (data, type, row, meta) {
                return puppet.formatDateTime(data*1000);
              }
            }
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
                url: "{{ URL('admin/group/send/list/ajax') }}",
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
            {"data": "group_send_record_id"},
            {"data": "tag_names"},
            {"data": "material_type_name"},
            {"data": "content"},
            {"data":"create_time"},
        ]
    }).api();
});
</script>
@endsection