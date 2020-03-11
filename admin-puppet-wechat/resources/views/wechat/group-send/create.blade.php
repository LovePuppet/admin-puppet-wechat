@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Add group', '新建群发');
?>
@section('title',$title)
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/iCheck/all.css') }}">
@endsection
@section('content')
<div class="content-wrapper">
<section class="content-header">
    <h1>
        <?php echo \App\Components\Tools::sLang('Add group', '新建群发');?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
        <li><a href="{{URL('admin/group/send/list')}}"><?php echo \App\Components\Tools::sLang('Group record', '群发记录');?></a></li>
        <li class="active"><?php echo \App\Components\Tools::sLang('Add group', '新建群发');?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
              <div class="box-body">
                <!--<div class="form-group">
                  <label for="lang">角色</label>
                  <select class="form-control" name="lang" id="lang" style="width:30%">
                    <option value="0">All</option>
                    <option value="1">English</option>
                    <option value="2">Chinese</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="customer_type">客户类型</label>
                  <select class="form-control" name="customer_type" id="customer_type" style="width:30%">
                    <option value="0">All</option>
                    <option value="1">Agency</option>
                    <option value="2">Brand</option>
                  </select>
                </div><br><br>-->
                <div class="form-group" id="choose_use_model">
                  <label for="choose_use_model"><?php echo \App\Components\Tools::sLang('Tags (multiple tags for group selection)', '标签(多个标签群发为选择多个标签用户的并集)');?></label>
                  <br><br>
                  @if(!empty($tagList))
                    @foreach($tagList as $key => $tag)
                      <label @if($key > 0) style='margin-left:20px;' @endif>
                          <input type="checkbox" name="tag" class="minimal" val="{{$tag['tag_id']}}">
                          {{$tag['name']}}
                      </label>
                    @endforeach
                  @endif
                </div>
                <div class="form-group">
                  <label for="content"><?php echo \App\Components\Tools::sLang('Group content', '群发内容');?></label>
                  <input type="hidden" class="form-control" id="material_type" name="material_type" value="text">
                  <input type="hidden" class="form-control" id="material_content" name="material_content">
                  <br>
                  <button type="button" class="btn btn-success" onclick="selectContent()"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                  <br><br>
                  <div id="show_material"></div>
                </div>
                <button type="button" class="btn btn-primary" onclick="groupSend()" style="margin-top:30px;"><?php echo \App\Components\Tools::sLang('Send out', '群 发');?></button>
                <button type="button" class="btn btn-danger pull-right" onclick="history.go(-1)" style="margin-top:30px;"><?php echo \App\Components\Tools::sLang('Cancel', '取 消');?></button>
                <!--<button type="button" class="btn btn-success" onclick="groupPreview()" style="margin-top:30px;margin-left:30px;">预 览</button>-->
              </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/css/iCheck/icheck.min.js')}}"></script>
<script src="{{ asset('/js/tools.js') }}"></script>
<script type="text/javascript">
$(function(){
  $('input[type="checkbox"].minimal').iCheck({
    checkboxClass: 'icheckbox_minimal-blue'
  });
})
function selectContent(){
  $('.v-modal').show();
  $('#menue-set').show();
}
</script>
@include('wechat.select-material-head')
@include('robot.select-replay-body')
<script type="text/javascript">
var image_domain = '{{ env('IMAGE_DOMIAN')}}';
function saveMaterialData(){
  var type = $.trim($('#material_type').val());
  var content = $.trim($('#material_content').val());
  if(content == ''){
    $('#show_material').html('');
  }else{
    $('#material_type').val(type);
    $('#material_content').val(content);
    switch(type){
      case 'text':
          var url = "{{URL('admin/material/text/crc_token/get')}}";
          var data = {crc_token:content};
          var result = puppet.myajax('post',url,data,false);
          if(result.code == 1){
              alert(result.msg);
              return false;
          }else{
              $('#show_material').html('<textarea class="form-control" rows="6" id="text_content_val">'+result.data.content+'</textarea>');
          }
          break;
      case 'image':
          var url = "{{URL('admin/material/image/get')}}";
          var data = {media_id:content};
          var result = puppet.myajax('post',url,data,false);
          if(result.code == 1){
              alert(result.msg);
              return false;
          }else{
              $('#show_material').html('<li class="item"><div class="content"><div class="avatar" style="background-image: url('+image_domain+result.data.url+')"></div></div></li>');
          }
          break;
      case 'news':
          var url = "{{URL('admin/material/news/get')}}";
          var data = {media_id:content};
          var result = puppet.myajax('post',url,data,false);
          if(result.code == 1){
              alert(result.msg);
              return false;
          }else{
              var this_material_html= '<li class="item"><div class="content"><div class="avatar" style="background-image: url('+image_domain+result.data.url[0]+')"><p class="title">'+result.data.title[0]+'</p></div></div>';
              if(result.data.url.length > 1){
                  for(var j = 1;j<result.data.url.length;j++){
                      this_material_html += '<div class="line"><p class="txt">'+result.data.title[j]+'</p><div class="avatar" style="background-image: url('+image_domain+result.data.url[j]+')"></div></div>';
                  }
              }
              this_material_html += '</li>';
              $('#show_material').html(this_material_html);
          }
          break;
      case 'video':
        var url = "{{URL('admin/material/video/get')}}";
        var data = {media_id:content};
        var result = puppet.myajax('post',url,data,false);
        if(result.code == 1){
          alert(result.msg);
          return false;
        }else{
          $('#show_material').html('<li class="item"><div class="content"><p>'+result.data.title+'</p></div></li>');
        }
        break;
    }
  }
  $('.v-modal').hide();
  $('#menue-set').hide();
  return true;
}
function groupSend(){
  var tag_arr = [];
  $('.minimal').each(function(){
    if($(this).is(':checked')){
      tag_arr.push($(this).attr('val'));
    }
  });
  console.log(tag_arr);
  var type = $.trim($('#material_type').val());
  var content = $.trim($('#material_content').val());
//  var lang = $('#lang').val();
//  var customer_type = $('#customer_type').val();
  if(type == '' || content == ''){
    puppet.mesWarn('请选择群发内容');
    return false;
  }
  var url = "{{URL('admin/group/send/all')}}";
//  var data = {type:type,content:content,lang:lang,customer_type:customer_type};
  var data = {type:type,content:content,tag_ids:tag_arr.join(',')};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    puppet.mesFailure(result.msg);
    return false;
  }else{
    puppet.mesSuccess('发送成功');
  }
}
function groupPreview(){
  var type = $.trim($('#material_type').val());
  var content = $.trim($('#material_content').val());
  if(type == '' || content == ''){
    puppet.mesWarn('请选择群发内容');
    return false;
  }
  var url = "{{URL('admin/group/preview')}}";
  var data = {type:type,content:content};
  var result = puppet.myajax('post',url,data,false);
  if(result.code == 1){
    puppet.mesFailure(result.msg);
    return false;
  }else{
    puppet.mesSuccess('发送成功');
  }
}
</script>
@endsection