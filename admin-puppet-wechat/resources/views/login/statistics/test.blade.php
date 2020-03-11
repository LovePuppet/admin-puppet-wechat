@extends('home.parent')
@section('title','音频列表')
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/dataTables.bootstrap.css') }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
              音频列表
              <small></small>
            </h1>
            <ol class="breadcrumb">
              <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
              <li class="active">音频列表</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title"></h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                          <table id="radio_list" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>分类</th>
                                    <th>专辑名称</th>
                                    <th>音频编号</th>
                                    <th>音频名称</th>
                                    <th>音频URL</th>
                                    <th>音频长度</th>
                                    <th>更新时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>编号</th>
                                    <th>分类</th>
                                    <th>专辑名称</th>
                                    <th>音频编号</th>
                                    <th>音频名称</th>
                                    <th>音频URL</th>
                                    <th>音频长度</th>
                                    <th>更新时间</th>
                                    <th>状态</th>
                                    <th>操作</th>
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
<script src="{{ asset('/js/tools.js') }}"></script>
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('/js/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('/js/fastclick.js') }}"></script>
<script>
var radio_list_ajax;
$(function () {
    //提示信息
    var lang = {
        "sProcessing": "处理中...",
        "sLengthMenu": "每页 _MENU_ 项",
        "sZeroRecords": "没有匹配结果",
        "sInfo": "当前显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项。",
        "sInfoEmpty": "当前显示第 0 至 0 项，共 0 项",
        "sInfoFiltered": "(由 _MAX_ 项结果过滤)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中数据为空",
        "sLoadingRecords": "载入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首页",
            "sPrevious": "上页",
            "sNext": "下页",
            "sLast": "末页",
            "sJump": "跳转"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
    };

    //初始化表格
    radio_list_ajax = $("#radio_list").dataTable({
        language:lang,  //提示信息
        autoWidth: false,  //禁用自动调整列宽
        stripeClasses: ["odd", "even"],  //为奇偶行加上样式，兼容不支持CSS伪类的场合
        processing: true,  //隐藏加载提示,自行处理
        serverSide: true,  //启用服务器端分页
        searching: true,  //禁用原生搜索
        orderMulti: false,  //启用多列排序
        order: [[0,'desc']],  //取消默认排序查询,否则复选框一列会出现小箭头
        renderer: "bootstrap",  //渲染样式：Bootstrap和jquery-ui
        pagingType: "simple_numbers",  //分页样式：simple,simple_numbers,full,full_numbers
        columnDefs: [
            {
            "targets": [8,9],  //列的样式名
            "orderable": false    //包含上样式名‘nosort’的禁止排序
            },
            {
                targets: 7,
                data: "create_time",
                title: "更新时间",
                render: function (data, type, row, meta) {
                    return puppet.formatDateTime(data*1000);
                }
            },
            {
                targets: 8,
                data: "status",
                title: "状态",
                render: function (data, type, row, meta) {
                    return (data == 1) ? '开启' : '关闭';
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
            //console.log(param);
            //ajax请求数据
            $.ajax({
                type: "POST",
                url: '{{ URL('admin/datatable/test/ajax') }}',
                cache: false,  //禁用缓存
                data: param,  //传入组装的参数
                dataType: "json",
                success: function (result) {
                    //console.log(result);
                    //setTimeout仅为测试延迟效果
                    //setTimeout(function () {
                    //}, 200);
                    //封装返回数据
                    var returnData = {};
                    returnData.draw = data.draw;//这里直接自行返回了draw计数器,应该由后台返回
                    returnData.recordsTotal = result.total;//返回数据全部记录
                    returnData.recordsFiltered = result.total;//后台不实现过滤功能，每次查询均视作全部结果
                    returnData.data = result.data;//返回的数据列表
//                    console.log(returnData);
                    //调用DataTables提供的callback方法，代表数据已封装完成并传回DataTables进行渲染
                    //此时的数据需确保正确无误，异常判断应在执行此回调前自行处理完毕
                    callback(returnData);
                }
            });
        },
        //列表表头字段
        columns: [
            {"data":"id"},
            {"data":"category_name"},
            {"data":"album_name"},
            {"data":"radio_id"},
            {"data":"radio_name"},
            {"data":"radio_url"},
            {"data":"radio_size"},
            {"data":"create_time"},
            {"data":"status"},
            {"data":null,render:function(data,type,row){
                    switch(data.examine){
                        case 1:
                            examine_htm = "上架&nbsp;&nbsp;<a href='javascript:void(0);' class='btn btn-danger btn-xs' onclick='actionExamine("+data.id+",2)'>下架</a>";
                            break;
                        case 2:
                            examine_htm = "下架&nbsp;&nbsp;<a href='javascript:void(0);' class='btn btn-success btn-xs' onclick='actionExamine("+data.id+",1)'>上架</a>";
                            break;
                    }
                return  examine_htm+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' title='删除' aria-label='Delete' data-pjax='0' onclick='actionList("+data.id+",-1)'><span class='glyphicon glyphicon-trash'></span></a>";
            }},
        ]
    }).api();
    //此处需调用api()方法,否则返回的是JQuery对象而不是DataTables的API对象
});
//上架/下架
function actionExamine(id,examine){
    var result;
    switch(examine){
        case 1:
            result = confirm("是否确认上架该专辑");
            break;
        case 2:
            result = confirm("是否确认下架该专辑");
            break;
        default:
            result = confirm("是否确认下架该专辑");
            break;
    }
    if(result){
        var url = "{{URL('admin/radio/examine')}}";
        var data = {id:id,examine:examine};
        var is_success = puppet.myajax('post',url,data,false);
        if(is_success.code == true){
            alert(is_success.message);
            return false;
        }else{
            radio_list_ajax.ajax.reload();
        }
    }
    return false;
}
function actionList(id,status){
    var result;
    switch(status){
        case 0:
            result = confirm("是否确认关闭该音频");
            break;
        case 1:
            result = confirm("是否确认启用该音频");
            break;
        case -1:
            result = confirm("是否确认删除该音频");
            break;
        default:
            result = confirm("是否确认删除该音频");
            break;   
    }
    if(result){
        var url = "{{URL('admin/radio/delete')}}";
        var data = {id:id,status:status};
        var is_success = puppet.myajax('post',url,data,false);
        if(is_success.code == true){
            alert(is_success.message);
            return false;
        }else{
            radio_list_ajax.ajax.reload();
        }
    }
    return false;
}
</script>
@endsection