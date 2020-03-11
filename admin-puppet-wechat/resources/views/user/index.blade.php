@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('User list', '用户列表');
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
            <?php echo \App\Components\Tools::sLang('User list', '用户列表');?>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
            <li class="active"><?php echo \App\Components\Tools::sLang('User list', '用户列表');?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box"> 
                    <div class="box-header">
                        <div class="form-group col-md-4">
                            <div class="input-group date">
                              <select class="form-control" id="search_channel_id" style="width:120px;">
                                <option value="0"><?php echo \App\Components\Tools::sLang('Channel', '渠道');?></option>
                                <?php if(!empty($channels)){ foreach ($channels as $channel){ ?>
                                <option value="<?php echo $channel['channel_id'];?>"><?php echo $channel['channel_name'];?></option>
                                <?php }}?>
                              </select>
                            </div>
                            <div class="input-group date" style="margin:-34px 0 0 140px;">
                              <input type="text" class="form-control pull-right" id="search_nick_name" style="width:160px;" placeholder="<?php echo \App\Components\Tools::sLang('Nick name', '昵称');?>">
                            </div>
                            <div class="input-group date" style="margin:-34px 0 0 320px;">
                              <input type="text" class="form-control pull-right" id="search_real_name" style="width:160px;" placeholder="<?php echo \App\Components\Tools::sLang('Real name', '真实姓名');?>">
                            </div>
                            <div class="input-group date" style="margin:-34px 0 0 500px;">
                              <input type="text" class="form-control pull-right" id="search_email" style="width:160px;" placeholder="<?php echo \App\Components\Tools::sLang('Email', '邮箱');?>">
                            </div>
                            <div class="input-group date" style="margin:20px 0 0 0;">
                              <input type="text" class="form-control pull-right" id="search_mobile" style="width:160px;" placeholder="<?php echo \App\Components\Tools::sLang('Mobile', '手机号码');?>">
                            </div>
                            <div class="input-group date" style="margin:-34px 0 0 180px;">
                              <select class="form-control" id="search_lang" style="width:160px;">
                                <option value="0"><?php echo \App\Components\Tools::sLang('Language', '语言');?></option>
                                <option value="1">English</option>
                                <option value="2">Chinese</option>
                              </select>
                            </div>
                            <div class="input-group date" style="margin:-34px 0 0 360px;">
                              <select class="form-control" id="search_customer_type" style="width:160px;">
                                <option value="0"><?php echo \App\Components\Tools::sLang('Customer type', '客户类型');?></option>
                                <option value="1">Agency</option>
                                <option value="2">Brand</option>
                              </select>
                            </div>
                        </div>
                        <a href="javascript:void(0)" class="btn btn-info" onclick="searchData()" style="margin:56px 0 0 350px;"><?php echo \App\Components\Tools::sLang('Search', '搜索');?></a>
                        <a href="javascript:void(0)" class="btn btn-success" onclick="userSync()" style="margin:-56px 0 0 550px;"><?php echo \App\Components\Tools::sLang('Synchronization', '同步');?></a>
                        <h3 class="box-title"></h3>
                    </div>
                    <div class="box-body">
                        <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Head image', '头像');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Nick name', '微信昵称');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Language', '语言');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Customer type', '客户类型');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Real name', '真实姓名');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Mobile', '手机号码');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Email', '邮箱');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Follow time', '关注时间');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Head image', '头像');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Nick name', '微信昵称');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Language', '语言');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Customer type', '客户类型');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Real name', '真实姓名');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Mobile', '手机号码');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Email', '邮箱');?></th>
                                    <th><?php echo \App\Components\Tools::sLang('Follow time', '关注时间');?></th>
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
<div class="modal fade in" id="modal-info">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?php echo \App\Components\Tools::sLang('User detail', '用户详情');?></h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Unique identifier', '唯一标识');?></th>
                            <td id="view_token"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Head image', '头像');?></th>
                            <td><img src="" id="view_head_img"/></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Nick name', '昵称');?></th>
                            <td id="view_nick_name"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Channel', '渠道');?></th>
                            <td id="view_channel_id"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Language', '语言');?></th>
                            <td id="view_lang"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Customer type', '客户类型');?></th>
                            <td id="view_customer_type"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Real name', '真实姓名');?></th>
                            <td id="view_real_name"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Mobile', '手机号');?></th>
                            <td id="view_mobile"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Email', '邮箱');?></th>
                            <td id="view_email"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Address', '地址');?></th>
                            <td id="view_address"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Remark', '备注信息');?></th>
                            <td id="view_remark"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Company', '公司');?></th>
                            <td id="view_company"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Job', '职位');?></th>
                            <td id="view_profession"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Sex', '性别');?></th>
                            <td id="view_sex"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Country', '国家');?></th>
                            <td id="view_country"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Province', '省');?>省</th>
                            <td id="view_province"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('City', '城市');?></th>
                            <td id="view_city"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                            <td id="view_create_time"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Follow time', '关注时间');?></th>
                            <td id="view_subscribe_time"></td>
                        </tr>
                        <tr>
                            <th><?php echo \App\Components\Tools::sLang('Focus on channel sources', '关注渠道来源');?></th>
                            <td id="view_subscribe_scene"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/jquery.dataTables.js') }}"></script>
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
        language:lang,  //提示信息
        autoWidth: false,  //禁用自动调整列宽
        stripeClasses: ["odd", "even"],  //为奇偶行加上样式，兼容不支持CSS伪类的场合
        processing: true,  //隐藏加载提示,自行处理
        serverSide: true,  //启用服务器端分页
        searching: false,  //禁用原生搜索
        orderMulti: false,  //启用多列排序
        order: [[0,'desc']],  //取消默认排序查询,否则复选框一列会出现小箭头
        renderer: "bootstrap",  //渲染样式：Bootstrap和jquery-ui
        pagingType: "simple_numbers",  //分页样式：simple,simple_numbers,full,full_numbers
        columnDefs: [
            {
            "targets": [1,2,3,4,5,6,7,8,9],  //列的样式名
            "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
                targets: 1,
                data: "img",
                title: puppet_lang == 2 ? "头像" : "Head image",
                render: function (data, type, row, meta) {
                    return "<img src='"+data+"' class='icon_img_css' />";
                }
            },
            {
                targets: 3,
                data: "lang",
                title: puppet_lang == 2 ? "语言" : "Language",
                render: function (data, type, row, meta) {
                    var result = '';
                    switch(data){
                        case 1:
                            result = 'English';
                            break;
                        case 2:
                            result = 'Chinese';
                            break;
                    }
                    return result;
                }
            },
            {
                targets: 4,
                data: "customer_type",
                title: puppet_lang == 2 ? "客户类型" : "Customer type",
                render: function (data, type, row, meta) {
                    var result = '';
                    switch(data){
                        case 1:
                            result = 'agency';
                            break;
                        case 2:
                            result = 'brand';
                            break;
                    }
                    return result;
                }
            },
            {
                targets: 8,
                data: "subscribe_time",
                title: puppet_lang == 2 ? "关注时间" : "Follow time",
                render: function (data, type, row, meta) {
                    return (data > 0) ? puppet.formatDateTime(data*1000) : '';
                }
            },
            {
                targets: 9,
                title: puppet_lang == 2 ? "操作" : "Action",
                render:function(data,type,row,meta){
                    return  "&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' title='查看' aria-label='View' data-pjax='0' onclick='showModal("+row.user_id+")'><span class='glyphicon glyphicon-eye-open'></span></a>";
                }
            },
        ],
        ajax: function (data, callback, settings) {
          //封装请求参数
          var param = {};
          param.limit = data.length;//页面显示记录条数，在页面显示每页显示多少项的时候
          param.start = data.start;//开始的记录序号
          param.page = (data.start / data.length)+1;//当前页码
          param.order = data.order;
          param.search = data.search;
          param.channel_id = $.trim($('#search_channel_id').val());
          param.email = $.trim($('#search_email').val());
          param.nick_name = $.trim($('#search_nick_name').val());
          param.real_name = $.trim($('#search_real_name').val());
          param.mobile = $.trim($('#search_mobile').val());
          param.lang = $('#search_lang').val();
          param.customer_type = $('#search_customer_type').val();
          $.ajax({
            type: "POST",
            url: "{{ URL('admin/user/list/ajax') }}",
            cache: false,  //禁用缓存
            data: param,   //传入组装的参数
            dataType: "json",
            success: function (result) {
              var returnData = {};
              returnData.draw = data.draw;//这里直接自行返回了draw计数器,应该由后台返回
              returnData.recordsTotal = result.total;//返回数据全部记录
              returnData.recordsFiltered = result.total;//后台不实现过滤功能，每次查询均视作全部结果
              returnData.data = result.data;//返回的数据列表
              callback(returnData);
            }
          });
        },
        //列表表头字段
        columns: [
            {"data":"user_id"},
            {"data":"head_img"},
            {"data":"nick_name"},
            {"data":"lang"},
            {"data":"customer_type"},
            {"data":"real_name"},
            {"data":"mobile"},
            {"data":"email"},
            {"data":"subscribe_time"},
            {"data":null},
        ],
        "fnDrawCallback":function(){
            $("#data_list_paginate").children('ul').append("<li style='margin-left:5px;'>&nbsp;&nbsp;<label style='margin-top:-5px;'>"+(puppet_lang == 2 ? '到第' : 'To')+"</lable>&nbsp;<input style='width:40px;hidden:none' class='margin text-center' id='changePage' type='text'>&nbsp;&nbsp;<label>"+(puppet_lang == 2 ? '页' : 'Page')+"</lable>&nbsp;</li> <li style='margin-left:5px;float:right;' class='paginate_button'><a style='margin-bottom:5px' href='javascript:void(0);'id='dataTable-btn'>"+(puppet_lang == 2 ? '确认' : 'Confirm')+"</a></li>");  
                var oTable = $("#data_list").dataTable();
                $('#dataTable-btn').click(function(e){
                    if($("#changePage").val() && $("#changePage").val() > 0) {
                        var redirectpage = $("#changePage").val() - 1;
                    }else{
                        var redirectpage = 0;
                    }
                    oTable.fnPageChange(redirectpage);
                });
        },
    }).api();
});
function searchData(){
  $("#data_list").dataTable().fnDraw(false);
}
function showModal(id){
    var url = "{{ URL('admin/user/info') }}";
    var data = {id:id};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == 1){
        alert(result.msg);
        return false;
    }else{
        $('#view_token').html(result.data.token);
        $('#view_head_img').attr("src",result.data.head_img);
        $('#view_nick_name').html(result.data.nick_name);
        $('#view_real_name').html(result.data.real_name);
        var sex = '';
        switch(result.data.sex){
            case 1:
                sex = 'Male';
                break;
            case 2:
                sex = 'Female';
                break;
            case 0:
                sex = 'Unknown';
                break;
        }
        $('#view_sex').html(sex);
        $('#view_mobile').html(result.data.mobile);
        $('#view_email').html(result.data.email);
        $('#view_company').html(result.data.company);
        $('#view_profession').html(result.data.profession);
        $('#view_create_time').html(result.data.create_time);
        $('#view_subscribe_time').html(result.data.subscribe_time);
        $('#view_subscribe_scene').html(result.data.subscribe_scene);
        $('#view_country').html(result.data.country);
        $('#view_province').html(result.data.province);
        $('#view_city').html(result.data.city);
        var lang = '';
        switch(result.data.lang){
            case 1:
                lang = 'English';
                break;
            case 2:
                lang = 'Chinese';
                break;
        }
        $('#view_lang').html(lang);
        var customer_type = '';
        switch(result.data.customer_type){
            case 1:
                customer_type = 'agency';
                break;
            case 2:
                customer_type = 'brand';
                break;
        }
        $('#view_customer_type').html(customer_type);
        $('#view_address').html(result.data.address);
        $('#view_remark').html(result.data.remark);
        $('#view_channel_id').html(result.data.channel_name);
        $('#modal-info').modal();
    }
}
function userSync(){
  var url = "{{ URL('admin/user/sync') }}";
  var result = puppet.myajax('post',url,'',false);
  if(result.code == 1){
    puppet.mesFailure(result.msg);
    return false;
  }else{
    list_ajax.ajax.reload(null,false);
  }
}
</script>
@endsection