@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Text', '文本素材');
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
              <?php echo \App\Components\Tools::sLang('Text', '文本素材');?>
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
              <li class="active"><?php echo \App\Components\Tools::sLang('Text', '文本素材');?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <button type="button" class="btn btn-info" onclick="newModal()"><?php echo \App\Components\Tools::sLang('Add text', '新增');?></button>
                            <h3 class="box-title"></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th style="width:800px;"><?php echo \App\Components\Tools::sLang('Text content', '文本内容');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th style="width:800px;"><?php echo \App\Components\Tools::sLang('Text content', '文本内容');?></th>
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
    <div class="modal modal-info fade" id="modal-info">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><?php echo \App\Components\Tools::sLang('Input text content', '输入文本内容');?></h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="text_id" name="text_id"/>
                    <textarea rows="10" style="width: 570px;color:#000" id="content"></textarea>
                    <div class="_3y42Z m-b-10"><?php echo \App\Components\Tools::sLang('Note: the form of adding hyperlinks is', '注:添加超链接的形式为');?>:&lt;a href="http://www.baidu.com"&gt;<?php echo \App\Components\Tools::sLang('Baidu', '百度');?>&lt;/a&gt;</div><div>
                    <button type="button" class="btn btn-success" onclick="addUserName()"><span><?php echo \App\Components\Tools::sLang('User nick', '用户昵称');?></span></button></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"><?php echo \App\Components\Tools::sLang('Close', '关闭');?></button>
                    <button type="button" class="btn btn-outline" onclick="saveData()"><?php echo \App\Components\Tools::sLang('Save', '保存');?></button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
          <!-- /.modal-dialog -->
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
        searching: true, //禁用原生搜索
        orderMulti: false, //启用多列排序
        order: [], //取消默认排序查询,否则复选框一列会出现小箭头
        renderer: "bootstrap", //渲染样式：Bootstrap和jquery-ui
        pagingType: "simple_numbers", //分页样式：simple,simple_numbers,full,full_numbers
        columnDefs: [
            {
                "targets": [0,1,2,3], //列的样式名
                "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
                targets: 2,
                title: puppet_lang == 2 ? "创建时间" : "Create time",
                render: function (data, type, row, meta) {
                    return puppet.formatDateTime(data*1000);
                }
            },
            {
                targets: 3,
                title: puppet_lang == 2 ? "操作" : "Action",
                render: function (data, type, row, meta) {
                    return  "<a href='javascript:;' title='修改' aria-label='Update' data-pjax='0' onclick='giveModalData("+row.text_id+")'><span class='glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                            "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+row.text_id+",-1)'><span class='glyphicon glyphicon-trash'></span></a>";
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
                url: "{{ URL('admin/material/text/list/ajax') }}",
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
            {"data": "text_id"},
            {"data": "content"},
            {"data": "create_time"},
            {"data":null},
        ]
    }
    ).api();
}
);
function actionList(id,status){
  var content = (puppet_lang == 2 ? "是否确认删除" : "Do you confirm deletion?");
  var result = confirm(content);
  if(result){
    var url = "{{URL('admin/material/text/delete')}}";
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
/*保存菜单*/
function saveData(){
  var text_id = $.trim($('#text_id').val());
  var content = $.trim($('#content').val());
  if(content == ''){
    return false;
  }
  var url = "{{URL('admin/material/text/save')}}";
  var data = {id:text_id,content:content};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    alert(result.msg);
    return false;
  }else{
    list_ajax.ajax.reload(null,false);
    $('#menu_name').val('');
    $('#menu_sort').val('');
    $('.close').click();
  }
  return false;
}
function newModal(){
  $('#text_id').val('');
  $('#content').val('');
  $('#modal-info').modal();
}
function giveModalData(id){
  var url = "{{URL('admin/material/text/get')}}";
  var data = {id:id};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    alert(result.msg);
    return false;
  }else{
    $('#text_id').val(id);
    $('#content').val(result.data.content);
    $('#modal-info').modal();
  }
}
function addUserName(){
  var content_htm = $('#content').val();
  var user_name = '${userName}';
  $('#content').val(content_htm + user_name);
}
</script>
@endsection