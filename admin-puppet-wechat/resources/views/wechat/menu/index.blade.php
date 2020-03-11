@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Wechat menu', '微信菜单');
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
              <?php echo \App\Components\Tools::sLang('Wechat menu', '微信菜单');?>
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
              <li class="active"><?php echo \App\Components\Tools::sLang('Wechat menu', '微信菜单');?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-info"><?php echo \App\Components\Tools::sLang('Add menu', '新增菜单');?></button>
                            <h3 class="box-title"></h3>
                            <button type="button" class="btn btn-success" style="margin-left:80%;" onclick="publishMenu()"><?php echo \App\Components\Tools::sLang('Publish', '发布');?></button>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Menu name', '菜单名称');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Sort', '排序');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Click type', '点击类型');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Reply content', '回复内容');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Menu name', '菜单名称');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Sort', '排序');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Click type', '点击类型');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Reply content', '回复内容');?></th>
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
    <div class="modal fade in" id="modal-info">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo \App\Components\Tools::sLang('Input menu name', '输入菜单名称');?></h4>
          </div>
          <div class="modal-body">
            <p id="modal_msg"><?php echo \App\Components\Tools::sLang('The first class menu does not exceed 16 bytes or 8 Chinese characters.', '一级菜单不超过16个字节或者8个汉字');?></p>
            <div class="form-group">
              <label for="menu_name"><span style="color: red">*</span><?php echo \App\Components\Tools::sLang('Menu name', '菜单名称');?></label>
              <input type="hidden" class="form-control" id="menu_id" name="menu_id" value="0">
              <input type="hidden" class="form-control" id="fid" name="fid" value="0">
              <input type="text" class="form-control" id="menu_name" name="menu_name">
            </div>
            <div class="form-group">
              <label for="menu_sort"><?php echo \App\Components\Tools::sLang('Sort', '排序');?></label>
              <input type="text" class="form-control" id="menu_sort" name="menu_sort">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo \App\Components\Tools::sLang('Close', '关闭');?></button>
            <button type="button" class="btn btn-primary" onclick="saveMenu()"><?php echo \App\Components\Tools::sLang('Save', '保存');?></button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <input type="hidden" id="material_menu_id"/>
    <input type="hidden" id="material_type" value="click"/>
    <input type="hidden" id="material_content"/>
    <input type="hidden" id="material_media_id"/>
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
      serverSide: false, //启用服务器端分页
      searching: false, //禁用原生搜索
      orderMulti: false, //启用多列排序
      bLengthChange:false,//禁用每页选择条数
      paging:false,       //禁止翻页
      order: [], //取消默认排序查询,否则复选框一列会出现小箭头
      renderer: "bootstrap", //渲染样式：Bootstrap和jquery-ui
      pagingType: "simple_numbers", //分页样式：simple,simple_numbers,full,full_numbers
      columnDefs: [
          {
              "targets": [0,1,2,3,4,5,6], //列的样式名
              "orderable": false    //包含上样式名‘nosort’的禁止排序
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
                  var res = '';
                  if(row.fid == 0){
                      res = "<a href='javascript:;' title='查看' aria-label='View' data-pjax='0'onclick='giveModalData(0,"+row.menu_id+")'><span class='glyphicon glyphicon-plus'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                          "<a href='javascript:;' title='修改' aria-label='Update' data-pjax='0' onclick='giveModalData("+row.menu_id+","+row.fid+",&#39"+row.name+"&#39,"+row.sort+")'><span class='glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                          "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+row.menu_id+",-1)'><span class='glyphicon glyphicon-trash'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                          "<a href='javascript:void(0);' title='设置' aria-label='Delete' data-pjax='0' onclick='menuSetModal("+row.menu_id+")'>"+(puppet_lang == 2 ? "设置" : "Set")+"</a>";
                  }else{
                      res = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' title='修改' aria-label='Update' data-pjax='0' onclick='giveModalData("+row.menu_id+","+row.fid+",&#39"+row.name+"&#39,"+row.sort+")'><span class='glyphicon glyphicon-pencil'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                          "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+row.menu_id+",-1)'><span class='glyphicon glyphicon-trash'></span></a>&nbsp;&nbsp;&nbsp;&nbsp;"+
                          "<a href='javascript:void(0);' title='设置' aria-label='Delete' data-pjax='0' onclick='menuSetModal("+row.menu_id+")'>"+(puppet_lang == 2 ? "设置" : "Set")+"</a>";
                  }
                  return  res;
              }
          },
          {
            targets: 6,
            title: puppet_lang == 2 ? "回复内容" : "Reply content",
            render: function (data, type, row, meta) {
              var result = "<div style='word-wrap:break-word;overflow:hidden;word-break:break-all;width:220px;'>";
              if(row.type == 'view'){
                result += row.url+"</div>";
              }else if(row.type == 'click'){
                result += row.content+"</div>";
              }else if(row.type == 'media_id'){
                result += row.media_id+"</div>";
              }
              return result;
            }
          },
        ],
        ajax: function (data, callback, settings) {
            //封装请求参数
            var param = {};
            param.limit = 20; //页面显示记录条数，在页面显示每页显示多少项的时候
            param.start = 1; //开始的记录序号
            param.page = 1; //当前页码
            param.order = data.order;
            param.search = data.search;
            $.ajax({
                type: "POST",
                url: "{{ URL('admin/wechat/menu/list/ajax') }}",
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
            {"data": "menu_id"},
            {"data": "name"},
            {"data": "sort"},
            {"data": "create_time"},
            {"data":null},
            {"data":"type"},
            {"data":null},
        ]
    }
    ).api();
}
);
function actionList(id,status){
    var result;
    switch(status){
      case 0:
        var content = (puppet_lang == 2 ? "是否确认关闭该菜单" : "Are you sure you want to close the menu?");
        result = confirm(content);
        break;
      case 1:
        var content = (puppet_lang == 2 ? "是否确认启用该菜单" : "Are you sure that the menu is enabled?");
        result = confirm(content);
        break;
      case -1:
        var content = (puppet_lang == 2 ? "是否确认删除该菜单" : "Are you sure you want to delete this menu?");
        result = confirm(content);
        break;
      default:
        var content = (puppet_lang == 2 ? "是否确认删除该菜单" : "Are you sure you want to delete this menu?");
        result = confirm(content);
        break;
    }
    if(result){
        var url = "{{URL('admin/wechat/menu/delete')}}";
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
function saveMenu(){
    var menu_id = $.trim($('#menu_id').val());
    var fid = $.trim($('#fid').val());
    var name = $.trim($('#menu_name').val());
    var sort = $.trim($('#menu_sort').val());
    if(name == '' || name.length > 16){
        return false;
    }
    var url = "{{URL('admin/wechat/menu/save')}}";
    var data = {id:menu_id,fid:fid,name:name,sort:sort};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == true){
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
function giveModalData(id = 0,fid = 0,name = '',sort = 0){
    if(fid != 0){
        $('#modal_msg').html('二级菜单不超过60个字节或者30个汉字');
    }
    $('#menu_id').val(id);
    $('#fid').val(fid);
    $('#menu_name').val(name);
    $('#menu_sort').val(sort);
    $('#modal-info').modal();
}
function menuSetModal(id){
  var url = "{{URL('admin/wechat/menu/check')}}";
  var data = {id:id};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    alert(result.msg);
    return false;
  }else{
    $('#material_menu_id').val(id);
    $('.v-modal').show();
    $('#menue-set').show();
  }
}
</script>
@include('wechat.select-material-head')
@include('wechat.select-material-body')
<script>
function saveMaterialData(){
  var menu_id = $.trim($('#material_menu_id').val());
  var type = $.trim($('#material_type').val());
  var content = $.trim($('#material_content').val());
  var media_id = $.trim($('#material_media_id').val());
  var appid = $.trim($('#miniprogram_appid').val());
  var pagepath = $.trim($('#miniprogram_pagepath').val());
  if(type == 'miniprogram'){
    var wxurl = $.trim($('#miniprogram_url').val());
    if(appid == '' && wxurl == '' && pagepath == ''){
      alert('请填写完整小程序内容信息');
      return false;
    }
  }else{
    var wxurl = $.trim($('#material_url').val());
    if(content == '' && media_id == '' && wxurl == ''){
      alert('请选择设置内容');
      return false;
    }
  }
  var url = "{{URL('admin/wechat/material/save')}}";
  var data = {menu_id:menu_id,type:type,content:content,media_id:media_id,url:wxurl,appid:appid,pagepath:pagepath};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    alert(result.msg);
    return false;
  }else{
    $('.v-modal').hide();
    $('#menue-set').hide();
    list_ajax.ajax.reload(null,false);
  }
}
function publishMenu(){
  var url = "{{URL('admin/wechat/menu/publish')}}";
  var result = puppet.myajax('post',url,false,false);
  alert(result.msg);
}
</script>
@endsection