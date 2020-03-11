@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Tag list', '标签列表');
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
              <?php echo \App\Components\Tools::sLang('Tag list', '标签列表');?>
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
              <li class="active"><?php echo \App\Components\Tools::sLang('Tag list', '标签列表');?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info"><?php echo \App\Components\Tools::sLang('Add tag', '新增标签');?></button>
                            <h3 class="box-title"></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Tag name', '标签名称');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('User Set', '用户设置');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Tag name', '标签名称');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('User Set', '用户设置');?></th>
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
<div class="modal modal-info fade" id="modal-info">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title"><?php echo \App\Components\Tools::sLang('Input tag name', '输入标签名称');?></h4>
          </div>
          <div class="modal-body">
              <div class="form-group">
                  <label for="menu_name"><span style="color: red">*</span><?php echo \App\Components\Tools::sLang('Tag name', '标签名称');?></label>
                  <input type="hidden" class="form-control" id="tag_id" name="tag_id" value="0">
                  <input type="text" class="form-control" id="name" name="name">
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"><?php echo \App\Components\Tools::sLang('Close', '关闭');?></button>
              <button type="button" class="btn btn-outline" onclick="saveTag()"><?php echo \App\Components\Tools::sLang('Save', '保存');?></button>
          </div>
      </div>
      <!-- /.modal-content -->
  </div>
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
                "targets": [1,2,3,4], //列的样式名
                "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
                targets: 2,
                data: "create_time",
                title: puppet_lang == 2 ? "创建时间" : "Create time",
                render: function (data, type, row, meta) {
                    return puppet.formatDateTime(data * 1000);
                }
            },
            {
                targets: 3,
                title: puppet_lang == 2 ? "操作" : "Action",
                render: function (data, type, row, meta) {
                    return  "<a href='javascript:;' title='修改' aria-label='Update' data-pjax='0' onclick='giveModalData("+row.tag_id+",&#39"+row.name+"&#39)'><span class='glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                            "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+row.tag_id+")'><span class='glyphicon glyphicon-trash'></span></a>";
                }
            },
            {
                targets: 4,
                title: puppet_lang == 2 ? "用户设置" : "User set",
                render: function (data, type, row, meta) {
                  return  "<a href='{{ URL('admin/user/relation/tag') }}/"+row.tag_id+"' title='用户设置' aria-label='Update' data-pjax='0'>"+(puppet_lang == 2 ? "用户设置" : "User set")+"</a>";
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
                url: "{{ URL('admin/tag/list/ajax') }}",
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
            {"data": "tag_id"},
            {"data": "name"},
            {"data": "create_time"},
            {"data": null},
            {"data":null},
        ]
    }).api();
});
function actionList(id,status){
  var content = (puppet_lang == 2 ? "是否确认删除该标签？" : "Are you sure you want to delete this tag?");
  var result = confirm(content);
  if(result){
    var url = "{{URL('admin/tag/delete')}}";
    var data = {id:id};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == 1){
      puppet.mesFailure(result.msg)
      return false;
    }else{
      list_ajax.ajax.reload(null,false);
      $('#name').val('');
      $('#tag_id').val(0);
      $('.close').click();
    }
  }
  return false;
}
function giveModalData(id = 0,name = ''){
  $('#tag_id').val(id);
  $('#name').val(name);
  $('#modal-info').modal();
}
/*保存菜单*/
function saveTag(){
  var tag_id = $.trim($('#tag_id').val());
  var name = $.trim($('#name').val());
  if(name == ''){
    puppet.mesWarn('请输入标签名称');
    return false;
  }
  if(name.length > 16){
    puppet.mesWarn('标签名称过长');
    return false;
  }
  var url = "{{ URL('admin/tag/valid') }}";
  var data = {id:tag_id,name:name};
  var result = puppet.myajax('post',url,data,false);
  if(result.data == true){
    puppet.mesFailure('标签名已经存在');
    return false;
  }else{
    var url_ = "{{ URL('admin/tag/save') }}";
    var data_ = {id:tag_id,name:name};
    var result_ = puppet.myajax('post',url_,data_,false);
    if(result_.code == 1){
      puppet.mesFailure(result_.msg);
      return false;
    }else{
      list_ajax.ajax.reload(null,false);
      $('#name').val('');
      $('#tag_id').val(0);
      $('.close').click();
    }
  }
  return false;
}
</script>
@endsection