@extends('login.parent')
<?php $title = \App\Components\Tools::sLang('Login', '登录');?>
@section('title',$title)
@section('head_js')
@endsection
@section('head_css')
@endsection
@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="javascript:void(0);"><b>{{ env('SYSTEM_NAME','PUPPET') }}</b> <?php echo \App\Components\Tools::sLang('Admin Login', '管理员登录');?></a>
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
        <form action="{{URL('admin/login/submit')}}" method="post" id="login_form">
            <div class="form-group has-feedback">
                <input type="text" id="username" name="username" class="form-control" placeholder="<?php echo \App\Components\Tools::sLang('Account', '账号');?>" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" id="password" name="password" class="form-control" placeholder="<?php echo \App\Components\Tools::sLang('Password', '密码');?>" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo \App\Components\Tools::sLang('Login', '登 录');?></button>
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
    $('#login_form').submit(function(){
        var username  =  $.trim($("#username").val());
        var password  =  $.trim($("#password").val());
        if(username == ''){
            $('#show_error_msg').html('请输入账号');
            $("#login-box-msg").show();
            return false;
        }
        if(password == ''){
            $('#show_error_msg').html('请输入密码');
            $("#login-box-msg").show();
            return false;
        }
        if(password.length <6) {
            $('#show_error_msg').html('密码不能少于6位');
            $("#login-box-msg").show();
            return false;
        }
        if(!puppet.checkusrandpwd(password)){
            $("#show_error_msg").html("密码含有非法字符");
            $("#login-box-msg").show();
            return false;
        }
        var url = "{{URL('admin/login/ajax')}}";
        var data = {username:username,password:password};
        var is_login = puppet.myajax('post',url,data,false);
        if(is_login.code == true){
            $("#show_error_msg").html(is_login.message);
            $("#login-box-msg").show();
            return false;
        }
    })
})
</script>
@endsection