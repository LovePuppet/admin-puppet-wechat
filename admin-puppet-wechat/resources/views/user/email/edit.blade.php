@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Update Email', '新增邮箱');
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
    <?php echo \App\Components\Tools::sLang('Update Email', '修改邮箱');?>
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
    <li><a href="{{URL('admin/email/list')}}"><?php echo \App\Components\Tools::sLang('Email list', '邮箱列表');?></a></li>
    <li class="active"><?php echo \App\Components\Tools::sLang('Update Email', '修改邮箱');?></li>
  </ol>
</section>
<section class="content">
  <div class="row">
    <div class="col-md-6">
      <!-- general form elements -->
      <div class="box box-primary">
        @if(Session::has('error_msg'))
        <div class="callout callout-danger" id="login-box-msg" style="display:block;">
            <h4 id="show_error_msg">{{ session('error_msg') }}</h4>
        </div>
        @else
        <div class="callout callout-danger" id="login-box-msg" style="display:none;">
            <h4 id="show_error_msg"></h4>
        </div>
        @endif
        <form role="form" action="{{URL('admin/email/edit/save').'/'.$data['email_id'] }}" method="post" id="data_form">
          <div class="box-body">
            <div class="form-group">
              <label for="name"><?php echo \App\Components\Tools::sLang('Name', '名称');?></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo \App\Components\Tools::sLang('Name', '名称');?>" value="{{ $data['name'] }}">
            </div>
            <div class="form-group">
              <label for="name"><?php echo \App\Components\Tools::sLang('Email', '邮箱');?><span style="color: red">*</span></label>
              <input type="text" class="form-control" id="email" name="email" placeholder="<?php echo \App\Components\Tools::sLang('Email', '邮箱');?>" value="{{ $data['email'] }}" required>
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
  $('#data_form').submit(function(){
    var id = {{ $data['email_id'] }};
    var isEmail = /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
    var email  =  $.trim($("#email").val());
    if(email == ''){
      puppet.mesFailure('请输入邮箱');
      return false;
    }
    if(!(isEmail.test(email))){
      puppet.mesFailure('邮箱格式不正确');
      return false;
    }
    var url = "{{URL('admin/email/valid')}}";
    var data = {id:id,email:email};
    var result = puppet.myajax('post',url,data,false);
    if(result.data == true){
      puppet.mesFailure('邮箱已存在');
      return false;
    }
  })
})
</script>
@endsection