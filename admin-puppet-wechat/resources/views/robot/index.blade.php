@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Robot List', 'Robot列表');
?>
@section('title',$title)
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/dataTables.bootstrap.css') }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
              <?php echo \App\Components\Tools::sLang('Robot List', 'Robot列表');?>
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
              <li class="active"><?php echo \App\Components\Tools::sLang('Robot List', 'Robot列表');?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            @if($fid != 0)
                            <a href="{{URL('admin/robot/list').'?fid='.$prev_id }}" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo \App\Components\Tools::sLang('Return', '返回');?></a>
                            @endif
                            <a href="{{ URL('admin/robot/create').'?fid='.$fid }}" class="btn btn-info"><?php echo \App\Components\Tools::sLang('Add rule', '新增规则');?></a>
                            <h3 class="box-title"></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width:40px;"><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                    <th style="width:120px;"><?php echo \App\Components\Tools::sLang('Key word', '关键词');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Reply content', '回复内容');?></th>
                                    <th style="width:120px;"><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                    <th style="width:40px;"><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                    <th style="width:70px;"><?php echo \App\Components\Tools::sLang('Next set', '下一步设置');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="width:40px;"><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                    <th style="width:120px;"><?php echo \App\Components\Tools::sLang('Key word', '关键词');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Reply content', '回复内容');?></th>
                                    <th style="width:120px;"><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                    <th style="width:40px;"><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                    <th style="width:70px;"><?php echo \App\Components\Tools::sLang('Next set', '下一步设置');?></th>
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
                "targets": [1,2,3,4,5], //列的样式名
                "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
                targets: 2,
                data: "content",
                title: puppet_lang == 2 ? "回复内容" : "Reply content",
                render: function (data, type, row, meta) {
                  return "<div style='word-wrap:break-word;overflow:hidden;word-break:break-all;width:100%;'>"+data+"</div>";
                }
            },
            {
                targets: 3,
                data: "create_time",
                title: puppet_lang == 2 ? "创建时间" : "Create time",
                render: function (data, type, row, meta) {
                    return puppet.formatDateTime(data * 1000);
                }
            },
            {
                targets: 4,
                title: puppet_lang == 2 ? "操作" : "Action",
                render: function (data, type, row, meta) {
                    return  "<a href='{{ URL('admin/robot/edit') }}/"+row.robot_id+"?fid="+row.fid+"' title='修改' aria-label='Update' data-pjax='0'><span class='glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                            "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+row.robot_id+",-1)'><span class='glyphicon glyphicon-trash'></span></a>";
                }
            },
            {
                targets: 5,
                title: puppet_lang == 2 ? "下一步设置" : "Next set",
                render: function (data, type, row, meta) {
                    return  "<a href='{{ URL('admin/robot/list') }}/?fid="+row.robot_id+"' title='下一步设置' aria-label='Update' data-pjax='0'>"+(puppet_lang == 2 ? "下一步设置" : "Next set")+"</a>";
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
            param.fid = {{ $fid }};
            $.ajax({
                type: "POST",
                url: "{{ URL('admin/robot/list/ajax') }}",
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
            {"data": "robot_id"},
            {"data": "keyword"},
            {"data": "content"},
            {"data": "create_time"},
            {"data":null},
        ]
    }).api();
});
function actionList(id,status){
  var content = (puppet_lang == 2 ? "是否确认删除该规则，流程后的子规则都将被删除" : "If you confirm that the rule is deleted, the sub rules after the process will be deleted.");
  var result = confirm(content);
  if(result){
    var url = "{{URL('admin/robot/delete')}}";
    var data = {id:id,status:status};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == 1){
      alert(result.msg);
      return false;
    }else{
      list_ajax.ajax.reload(null,false);
    }
  }
  return false;
}
</script>
@endsection