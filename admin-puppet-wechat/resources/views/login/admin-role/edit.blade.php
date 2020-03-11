@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Update Role', '修改管理员角色');
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
        <?php echo \App\Components\Tools::sLang('Update Role', '修改管理员角色');?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
        <li><a href="{{URL('admin/role/list')}}"><?php echo \App\Components\Tools::sLang('Role list', '管理员角色列表');?></a></li>
        <li class="active"><?php echo \App\Components\Tools::sLang('Update Role', '修改管理员角色');?></li>
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
                <form role="form" action="{{URL('admin/role/edit/save').'/'.$data['admin_role_id'] }}" method="post" id="admin_role_edit_form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="role_name"><?php echo \App\Components\Tools::sLang('Role name', '角色名');?></label>
                            <input type="text" class="form-control" id="role_name" name="role_name" value="{{ $data['role_name'] }}" placeholder="<?php echo \App\Components\Tools::sLang('Role name', '角色名');?>" required>
                        </div>
                        <div class="form-group">
                            <label for="limits_ids"><?php echo \App\Components\Tools::sLang('Limit', '权限');?></label>
                            <input type="hidden" class="form-control" id="limits_ids" name="limits_ids" value="{{ $data['limits_ids'] }}">
                            <div class="form-group">
                                <div class="checkbox">
                                <?php $limit_ids_arr = json_decode($data['limits_ids'],true); ?>
                                @foreach($limits as $limit)
                                    <label>
                                        @if(in_array($limit['admin_limit_id'],$limit_ids_arr))
                                        <input type="checkbox" class="limit_url_checkbox" value="{{ $limit['admin_limit_id'] }}" checked>
                                        @else
                                        <input type="checkbox" class="limit_url_checkbox" value="{{ $limit['admin_limit_id'] }}">
                                        @endif
                                        {{ $limit['limit_name'] }}
                                    </label>
                                @endforeach
                                </div>
                            </div>
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
<script type="text/javascript">
$(document).ready(function(){
    $('#admin_role_edit_form').submit(function(){
        var role_name  =  $.trim($("#role_name").val());
        if(role_name == ''){
            $('#show_error_msg').html('请输入角色名');
            $("#login-box-msg").show();
            return false;
        }
        
        var limits_ids;
        var limits_ids_arr = [];
        $(".limit_url_checkbox").each(function(){
            if($(this).is(':checked')){
                limits_ids_arr.push($(this).val());
            }
        });
        if(limits_ids_arr != ''){
            limits_ids = limits_ids_arr.join(',');
            $('#limits_ids').val(limits_ids);
        }else{
            $("#show_error_msg").html('请勾选权限');
            $("#login-box-msg").show();
            return false;
        }
    })
})
</script>
@endsection