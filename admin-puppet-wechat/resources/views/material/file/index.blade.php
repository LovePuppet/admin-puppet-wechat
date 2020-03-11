@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('File list', '文件列表');
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
              <?php echo \App\Components\Tools::sLang('File list', '文件列表');?>
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
              <li class="active"><?php echo \App\Components\Tools::sLang('File list', '文件列表');?></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <div class="form-group">
                                <label for="hot_update_file"><?php echo \App\Components\Tools::sLang('Upload file', '文件上传');?></label>
                                <input type="file" class="btn-info left" multiple="multiple" id="hot_update_file" name="file">
                                <input type="button" class="btn btn-info btn-sm pull-right" style="margin:-30px 70%;" onclick="uploadSamplepics();" value="<?php echo \App\Components\Tools::sLang('Upload', '上传');?>" />
                                <div>
                                   <progress id="samplepics_progress_bar" value="0" max="100" style="width:278px;float: left;margin-top: 2px;display: none"></progress>
                                   <h5 id="samplepics_status"></h5>
                               </div>
                            </div>
                            <h3 class="box-title"><?php echo \App\Components\Tools::sLang('Note: files can be uploaded repeatedly, but will cover the same name file before, and upload 20 files at most each time.', '注：文件可重复上传，但会覆盖之前相同名称文件，每次最多上传20个文件');?></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="data_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('File name', '文件名称');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('File size', '文件大小');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Download address', '下载地址');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Action', '操作');?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                  <th><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('File name', '文件名称');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('File size', '文件大小');?></th>
                                  <th><?php echo \App\Components\Tools::sLang('Download address', '下载地址');?></th>
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
          <h4 class="modal-title"><?php echo \App\Components\Tools::sLang('File address', '文件地址');?></h4>
        </div>
        <div class="modal-body">

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
var image_domain = '{{ env('IMAGE_DOMIAN') }}';
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
                targets:2,
                title: puppet_lang == 2 ? "文件大小" : "File size",
                render:function(data, type, row, meta){
                    return puppet.showFileSize(data);
                }
            },
            {
                targets: 3,
                title: puppet_lang == 2 ? "下载地址" : "Download address",
                render: function (data, type, row, meta) {
                    return '<a href="javascript:" onclick="showFileUrl(&quot;'+data+'&quot;)">'+(puppet_lang == 2 ? "下载地址" : "Download address")+'</a>';
                }
            },
            {
                targets: 4,
                title: puppet_lang == 2 ? "上传时间" : "Upload time",
                render: function (data, type, row, meta) {
                    return puppet.formatDateTime(data*1000);
                }
            },
            {
                targets: 5,
                title: puppet_lang == 2 ? "操作" : "Action",
                render: function (data, type, row, meta) {
                    return "<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+row.file_id+",-1)'><span class='glyphicon glyphicon-trash'></span></a>";
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
                url: "{{ URL('admin/material/file/list/ajax') }}",
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
            {"data": "file_id"},
            {"data": "file_name"},
            {"data": "file_size"},
            {"data": "file_url"},
            {"data": "create_time"},
            {"data":null},
        ]
    }).api();
});
//删除
function actionList(id){
  var content = (puppet_lang == 2 ? "是否确认删除文件?" : "Are you sure you want to delete files?");
  var result = confirm(content);
  if(result){
    var url = "{{URL('admin/material/file/delete')}}";
    var data = {id:id};
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
//截图上传
function uploadSamplepics(){
    var xhr = new XMLHttpRequest();
    //定义表单变量
    var file = document.getElementById('hot_update_file').files;
    if(file.length > 20){
        puppet.mesWarn('文件数量不能多于20张');
        return;
    }
    if(file.length <= 0){
        puppet.mesWarn('请选择文件');
        return;
    }
    //新建一个FormData对象
    var formData = new FormData();
    //追加文件数据
    for(i=0;i<file.length;i++){
        formData.append("file["+i+"]", file[i]);
    }
    xhr.upload.onprogress = function (event) {
        var progress = Math.round(event.loaded/event.total * 100);
        $('#samplepics_progress_bar').show();
        $('#samplepics_progress_bar').val(progress);
        $('#samplepics_status').text(progress + '%');
    };
    //post方式
    xhr.open('POST', '{{ URL('admin/material/file/upload')}}'); //第二步骤
    //发送请求
    xhr.send(formData);  //第三步骤
    //ajax返回
    xhr.onreadystatechange = function(event){ //第四步
        if (xhr.readyState == 4 && xhr.status == 200) {
            var result = event.target.responseText;
            var json = JSON.parse(result);
            if(json.error == true){
                puppet.mesWarn(json.message);
            }else{
                list_ajax.ajax.reload(null,false);// 刷新表格数据，分页信息不会重置
            }
        }
    };
}  /* end  -- 截图上传*/
function showFileUrl(url){
    var file_url = image_domain+url;
    $('.modal-body').html(file_url);
    $('#modal-info').modal();
}
</script>
@endsection