@extends('login.parent')
@section('title','修改密码')
@section('head_js')
@endsection
@section('head_css')
@endsection
@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0);"><b>{{ env('SYSTEM_NAME','PUPPET') }}</b><?php echo \App\Components\Tools::sLang('Administrator to modify password', '管理员修改密码');?></a>
    </div>
    <div class="login-box-body">
        <p></p>
        @if(Session::has('error_msg'))
        <div class="callout callout-danger" id="login-box-msg" style="display:block;">
            <h4 id="show_error_msg">{{ session('error_msg') }}</h4>
        </div>
        @else
        <div class="callout callout-danger" id="login-box-msg" style="display:none;">
            <h4 id="show_error_msg"></h4>
        </div>
        @endif
        <form action="{{URL('admin/update/password/form')}}" method="post" id="update_password_form">
            <div class="form-group has-feedback">
                <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo \App\Components\Tools::sLang('Original Possword', '原密码');?>" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" id="new_password" name="new_password" class="form-control" placeholder="<?php echo \App\Components\Tools::sLang('New Possword', '新密码');?>" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" class="form-control" id="repassword" name="repassword" placeholder="<?php echo \App\Components\Tools::sLang('Confirm New Password', '确认新密码');?>" required>
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo \App\Components\Tools::sLang('Submit', '提 交');?></button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/tools.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    $('#update_password_form').submit(function(){
        var password = $.trim($("#password").val());
        var new_password = $.trim($("#new_password").val());
        var repassword = $.trim($("#repassword").val());
        if(password == ''){
            $('#show_error_msg').html('请输入原密码');
            $("#login-box-msg").show();
            return false;
        }
        if(new_password == ''){
            $('#show_error_msg').html('请输入新密码');
            $("#login-box-msg").show();
            return false;
        }
        if(repassword == ''){
            $('#show_error_msg').html('请确认新密码');
            $("#login-box-msg").show();
            return false;
        }
        if(password.length <6 || new_password.length < 6 || repassword < 6) {
            $('#show_error_msg').html('密码不能少于6位');
            $("#login-box-msg").show();
            return false;
        }
        if(!puppet.checkusrandpwd(password) || !puppet.checkusrandpwd(new_password) || !puppet.checkusrandpwd(repassword)){
            $("#show_error_msg").html("密码含有非法字符");
            $("#login-box-msg").show();
            return false;
        }
        if(new_password != repassword){
            $("#show_error_msg").html("两次密码输入不一致");
            $("#login-box-msg").show();
            return false;
        }
        if(new_password == password){
            $("#show_error_msg").html("新密码不能与原密码相同");
            $("#login-box-msg").show();
            return false;
        }
        var url = "{{URL('admin/update/password/ajax')}}";
        var data = {password:password};
        var data = puppet.myajax('post',url,data,false);
        if(data.code == true){
            $("#show_error_msg").html(data.message);
            $("#login-box-msg").show();
            return false;
        }
    })
})
</script>
@endsection