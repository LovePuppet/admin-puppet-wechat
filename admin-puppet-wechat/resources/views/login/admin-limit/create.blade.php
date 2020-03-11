@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Add Limit', '新增管理员权限');
?>
@section('title',$title)
@section('head_js')
@endsection
@section('head_css')
@endsection
@section('content')
<div class="content-wrapper">
<section class="content-header">
    <h1>
        <?php echo \App\Components\Tools::sLang('Add Limit', '新增管理员权限');?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
        <li><a href="{{URL('admin/limit/list')}}"><?php echo \App\Components\Tools::sLang('Role list', '管理员角色列表');?></a></li>
        <li class="active"><?php echo \App\Components\Tools::sLang('Add Limit', '新增管理员权限');?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <!--
                <div class="box-header with-border">
                  <h3 class="box-title"></h3>
                </div>
                -->
                <!-- /.box-header -->
                <!-- form start -->
                @if(Session::has('error_msg'))
                <div class="callout callout-danger" id="login-box-msg" style="display:block;">
                    <h4 id="show_error_msg">{{ session('error_msg') }}</h4>
                </div>
                @else
                <div class="callout callout-danger" id="login-box-msg" style="display:none;">
                    <h4 id="show_error_msg"></h4>
                </div>
                @endif
                <form role="form" action="{{URL('admin/limit/create/save')}}" method="post" id="admin_limit_create_form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="limit_name"><?php echo \App\Components\Tools::sLang('Limit name', '权限名');?></label>
                            <input type="text" class="form-control" id="limit_name" name="limit_name" placeholder="<?php echo \App\Components\Tools::sLang('Limit name', '权限名');?>" required>
                        </div>
                        <div class="form-group">
                            <label for="limit_url"><?php echo \App\Components\Tools::sLang('Limit address', '权限地址');?></label>
                            <input type="text" class="form-control" id="limit_url" name="limit_url" placeholder="<?php echo \App\Components\Tools::sLang('Limit address', '权限地址');?>" required>
                        </div>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary"><?php echo \App\Components\Tools::sLang('Submit', '提 交');?></button>
                        <button type="button" class="btn btn-danger pull-right" onclick="history.go(-1)"><?php echo \App\Components\Tools::sLang('Cancel', '取 消');?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/tools.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#admin_limit_create_form').submit(function(){
        var limit_name  =  $.trim($("#limit_name").val());
        var limit_url  =  $.trim($("#limit_url").val());
        if(limit_name == ''){
            $('#show_error_msg').html('请输入权限名');
            $("#login-box-msg").show();
            return false;
        }
        if(limit_url == ''){
            $('#show_error_msg').html('请输入权限地址');
            $("#login-box-msg").show();
            return false;
        }
        var url = "{{URL('admin/limit/valid/limiturl')}}";
        var data = {limit_url:limit_url};
        var is_success = puppet.myajax('post',url,data,false);
        if(is_success.code == true){
            $("#show_error_msg").html(is_success.message);
            $("#login-box-msg").show();
            return false;
        }
    })
})
</script>
@endsection