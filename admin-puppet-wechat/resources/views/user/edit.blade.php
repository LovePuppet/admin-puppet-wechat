@extends('home.parent')
@section('title','修改用户信息')
@section('head_js')
@endsection
@section('head_css')
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            修改用户信息
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="{{URL('admin/user/list')}}">用户列表</a></li>
            <li class="active">修改用户</li>
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
                    <form role="form" action="{{URL('admin/user/edit/save').'/'.$data['user_id'] }}" method="post" id="admin_edit_form">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="role_name">姓名</label>
                                <input type="text" class="form-control" id="true_name" name="true_name" value="{{ $data['true_name'] }}" placeholder="请输入姓名" required>
                            </div>
                            <div class="form-group">
                                <label for="mobile">手机号</label>
                                <input type="text" class="form-control" id="mobile" name="mobile" value="{{ $data['mobile'] }}" placeholder="请输入手机号" required>
                            </div>
                            <div class="form-group">
                                <label for="email">邮箱</label>
                                <input type="text" class="form-control" id="email" name="email" value="{{ $data['email'] }}" placeholder="请输入邮箱" required>
                            </div>

                            <div class="form-group">
                                <label for="nickname">孩子昵称</label>
                                <input type="text" class="form-control" id="nickname" name="nickname" value="{{ $data['nickname'] }}" placeholder="请输入孩子昵称" required>
                            </div>

                            <div class="form-group">
                                <label for="country">国家</label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ $data['country'] }}" placeholder="请输入国家" required>
                            </div>

                            <div class="form-group">
                                <label for="province">省</label>
                                <input type="text" class="form-control" id="province" name="province" value="{{ $data['province'] }}" placeholder="请输入省" required>
                            </div>

                            <div class="form-group">
                                <label for="city">市</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ $data['city'] }}" placeholder="请输入市" required>
                            </div>

                            <div class="form-group">
                                <label for="area">区/县</label>
                                <input type="text" class="form-control" id="area" name="area" value="{{ $data['area'] }}" placeholder="请输入区/县" required>
                            </div> 				  
                            <div class="form-group">
                                <label for="avatar">头像</label>
                                <input  class="form-control" id="avatar" name="avatar" src="{{ $data['avatar'] }}" placeholder="请输入头像" />
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary">提 交</button>
                            <button type="button" class="btn btn-danger pull-right" onclick="history.go(-1)">取 消</button>
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
    $(document).ready(function () {
        $('#admin_edit_form').submit(function () {
            var true_name = $.trim($("#true_name").val());
            if (true_name == '') {
                $('#show_error_msg').html('请输入姓名');
                $("#login-box-msg").show();
                return false;
            }
            var mobile = $.trim($("#mobile").val());
            if (mobile == '') {
                $('#show_error_msg').html('请输入手机号码');
                $("#login-box-msg").show();
                return false;
            }
            var email = $.trim($("#email").val());
            if (email == '') {
                $('#show_error_msg').html('请输入手机号码');
                $("#login-box-msg").show();
                return false;
            }
        });
    });
</script>
@endsection