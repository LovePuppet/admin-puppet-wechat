@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Add Channel', '新增渠道');
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
        <?php echo \App\Components\Tools::sLang('Add Channel', '新增渠道');?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
        <li><a href="{{URL('admin/channel/list')}}"><?php echo \App\Components\Tools::sLang('Channel list', '渠道列表');?></a></li>
        <li class="active"><?php echo \App\Components\Tools::sLang('Add Channel', '新增渠道');?></li>
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
                <form role="form" action="{{URL('admin/channel/create/save')}}" method="post" id="data_form">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="channel_name"><?php echo \App\Components\Tools::sLang('Channel name', '渠道名称');?></label>
                            <input type="text" class="form-control" id="channel_name" name="channel_name" placeholder="<?php echo \App\Components\Tools::sLang('Channel name', '渠道名称');?>" required>
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
    var channel_name  =  $.trim($("#channel_name").val());
    if(channel_name == ''){
      puppet.mesWarn('请输入渠道名称');
      return false;
    }
    var url = "{{ URL('admin/channel/valid') }}";
    var data = {channel_name:channel_name};
    var result = puppet.myajax('post',url,data,false);
    if(result.data == true){
      puppet.mesWarn('渠道名已存在');
      return false;
    }
  })
})
</script>
@endsection