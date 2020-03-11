@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Set user tag', '用户标签设置');
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
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <a href="{{URL('admin/tag/list')}}" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo \App\Components\Tools::sLang('Return', '返回');?></a>
                <p class="lead"></p>
                <div class="box">
                    <!-- /.box-header -->
                    <div class="box-body">
                      <table id="data_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('ID', '序号');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Tag name', '标签名称');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Head image', '用户头像');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Nick name', '用户昵称');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('ID', '序号');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Tag name', '标签名称');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Head image', '用户头像');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Nick name', '用户昵称');?></th>
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
                        <a href="javascript:void(0)" class="btn btn-success" onclick="searchData()" style="margin:56px 0 0 350px;"><?php echo \App\Components\Tools::sLang('Search', '搜索');?></a>
                        <h3 class="box-title"></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                      <table id="user_data_list" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th></th>
                                <th><?php echo \App\Components\Tools::sLang('Head image', '用户头像');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Nick name', '用户昵称');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Language', '语言');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Customer type', '客户类型');?></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <th><?php echo \App\Components\Tools::sLang('Head image', '用户头像');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Nick name', '用户昵称');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Language', '语言');?></th>
                                <th><?php echo \App\Components\Tools::sLang('Customer type', '客户类型');?></th>
                            </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.box-body -->
                  </div>
                  <!-- /.box -->
            </div>
        </div>
        <a href="javascript:;" class="btn btn-success" onclick="saveUserRelationData()"><?php echo \App\Components\Tools::sLang('Save settings', '保存数据');?></a>
    </section>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('/js/fastclick.js') }}"></script>
<script src="{{ asset('/js/tools.js').'?v='.env('VERSION') }}"></script>
<script type="text/javascript">
var list_ajax;
var user_list_ajax;
var user_ids = '<?php echo $ids;?>';
var user_ids_arr = [];
$(function () {
    if(user_ids != ''){
      user_ids_arr = user_ids.split(',');
    }
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
        order: [],  //取消默认排序查询,否则复选框一列会出现小箭头
        renderer: "bootstrap",  //渲染样式：Bootstrap和jquery-ui
        pagingType: "simple_numbers",  //分页样式：simple,simple_numbers,full,full_numbers
        columnDefs: [
            {
            "targets": [0,1,2,3,4,5],  //列的样式名
            "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
              targets: 2,
              title: puppet_lang == 2 ? "用户头像" : "Head image",
              render: function (data, type, row, meta) {
                return "<img src="+data+" class='icon_img_css'/>";
              }
            },
            {
                targets: 4,
                title: puppet_lang == 2 ? "创建时间" : "Create time",
                render: function (data, type, row, meta) {
                    return puppet.formatDateTime(data*1000);
                }
            },
            {
                targets: 5,
                title: puppet_lang == 2 ? "操作" : "Action",
                render:function(data,type,row,meta){
                  return  "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionData("+row.user_tag_relation_id+","+row.user_id+")'><span class='glyphicon glyphicon-trash'></span></a>";
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
            param.tag_id = '<?php echo $id;?>';
            $.ajax({
                type: "POST",
                url: "{{ URL('admin/user/relation/tag/list/ajax') }}",
                cache: false,  //禁用缓存
                data: param,  //传入组装的参数
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
            {"data":"user_tag_relation_id"},
            {"data":"name"},
            {"data":"head_img"},
            {"data":"nick_name"},
            {"data":"create_time"},
            {"data":null},
        ]
    }).api();
    
    user_list_ajax = $("#user_data_list").dataTable({
        language:lang,  //提示信息
        autoWidth: false,  //禁用自动调整列宽
        stripeClasses: ["odd", "even"],  //为奇偶行加上样式，兼容不支持CSS伪类的场合
        processing: true,  //隐藏加载提示,自行处理
        serverSide: true,  //启用服务器端分页
        searching: false,  //禁用原生搜索
        orderMulti: false,  //启用多列排序
        order: [],  //取消默认排序查询,否则复选框一列会出现小箭头
        renderer: "bootstrap",  //渲染样式：Bootstrap和jquery-ui
        pagingType: "simple_numbers",  //分页样式：simple,simple_numbers,full,full_numbers
        columnDefs: [
            {
            "targets": [0,1,2,3,4],  //列的样式名
            "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
              targets: 0,
              data: "user_id",
              title: '<input type="checkbox" style="cursor: pointer;" onclick="selectAll(this)"/> '+(puppet_lang == 2 ? "全选" : "All"),
              render: function (data, type, row, meta) {
                var i = $.inArray(data.toString(),user_ids_arr);
                var checkbox_html = '';
                if(i >= 0){
                  checkbox_html = '<td><input type="checkbox" name="class_checkbox" class_id='+data+' style="cursor: pointer;" checked="checked" onclick="selectClass(this)"></td>';
                }else{
                  checkbox_html = '<td><input type="checkbox" name="class_checkbox" class_id='+data+' style="cursor: pointer;" onclick="selectClass(this)"></td>';
                }
                return checkbox_html;
              }
            },
            {
              targets: 1,
              title: puppet_lang == 2 ? "用户头像" : "Head image",
              render: function (data, type, row, meta) {
                return "<img src="+data+" class='icon_img_css'/>";
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
            data: param,  //传入组装的参数
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
        ]
    }).api();
});
function searchData(){
  $("#user_data_list").dataTable().fnDraw(false);
}
function actionData(id,user_id){
  var content = (puppet_lang == 2 ? "是否确认删除？" : "Do you confirm deletion?");
  var result = confirm(content);
  if(result){
    var url = "{{ URL('admin/user/tag/delete') }}";
    var data = {id:id};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == 1){
        alert(result.msg);
        return false;
    }else{
      dataHandle(user_id.toString(),false);
      list_ajax.ajax.reload(null,false);
      user_list_ajax.ajax.reload(null,false);
    }
  }
  return false;
}
function selectAll(obj){
    if($(obj).is(':checked')){
        $("input[type='checkbox'][name='class_checkbox']").each(function(){
            var id = $(this).attr('class_id');
            dataHandle(id);
            $(this).prop("checked",true);
        })
    }else{
        $("input[type='checkbox'][name='class_checkbox']").each(function(){
            var id = $(this).attr('class_id');
            dataHandle(id,false);
            $(this).prop("checked",false);
        })
    }
}
function dataHandle(id,status = true){
  var i = $.inArray(id,user_ids_arr);//是否存在
  console.log(i);
  if(status === true){//添加
    if(i < 0){     //不存在，添加到数组
      user_ids_arr.push(id);
      console.log(user_ids_arr);
    }
  }else{              //删除
    if(i >= 0){
      user_ids_arr.splice(i,1);
      console.log(user_ids_arr);
    }
  }
  return true;
}
function selectClass(obj){
  var id = $(obj).attr('class_id');
  if($(obj).is(':checked')){
    dataHandle(id);
  }else{
    dataHandle(id,false);
  }
}
function saveUserRelationData(){
  var url = "{{ URL('admin/user/tag/data/save') }}";
  var data = {id:'<?php echo $id;?>',ids:user_ids_arr.join(',')};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    alert(result.msg);
    return false;
  }else{
    list_ajax.ajax.reload(null,false);
    user_list_ajax.ajax.reload(null,false);
  }
}
</script>
@endsection