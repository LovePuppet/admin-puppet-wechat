@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Add rule', '新增规则');
?>
@section('title',$title)
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/bootstrapSwitch.css') }}">
@endsection
@section('content')
<div class="content-wrapper">
<section class="content-header">
    <h1>
        <?php echo \App\Components\Tools::sLang('Add rule', '新增规则');?>
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
        <li><a href="{{URL('admin/robot/list')}}"><?php echo \App\Components\Tools::sLang('Robot List', 'Robot列表');?></a></li>
        <li class="active"><?php echo \App\Components\Tools::sLang('Add rule', '新增规则');?></li>
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
                <div class="box-body" style="min-height:500px;">
                    <div class="form-group">
                        <label for="keyword"><?php echo \App\Components\Tools::sLang('Key word', '关键词');?><span style="color: red"><?php echo \App\Components\Tools::sLang('(Keywords are empty, any message will be returned. If it matches multiple keywords, use spaces to separate them.)', '（关键词为空，任何消息都回复。如果是匹配多个关键词用空格隔开）');?></span></label>
                        <input type="text" class="form-control" id="keyword" name="keyword" placeholder="<?php echo \App\Components\Tools::sLang('Key word', '关键词');?>" required>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply1', '回复1');?></label>
                        <input type="hidden" class="form-control" id="material_type" value="text">
                        <input type="hidden" class="form-control" id="material_content">
                        <input type="hidden" class="form-control" id="material_type_1" value="text">
                        <input type="hidden" class="form-control" id="material_content_1">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="1"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_1"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply2', '回复2');?></label>
                        <input type="hidden" class="form-control" id="material_type_2" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_2" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="2"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_2"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply3', '回复3');?></label>
                        <input type="hidden" class="form-control" id="material_type_3" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_3" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="3"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_3"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply4', '回复4');?></label>
                        <input type="hidden" class="form-control" id="material_type_4" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_4" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="4"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_4"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply5', '回复5');?></label>
                        <input type="hidden" class="form-control" id="material_type_5" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_5" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="5"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_5"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply6', '回复6');?></label>
                        <input type="hidden" class="form-control" id="material_type_6" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_6" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="6"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_6"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply7', '回复7');?></label>
                        <input type="hidden" class="form-control" id="material_type_7" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_7" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="7"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_7"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply8', '回复8');?></label>
                        <input type="hidden" class="form-control" id="material_type_8" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_8" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="8"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_8"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply9', '回复9');?></label>
                        <input type="hidden" class="form-control" id="material_type_9" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_9" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="9"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_9"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply10', '回复10');?></label>
                        <input type="hidden" class="form-control" id="material_type_10" name="type" value="text">
                        <input type="hidden" class="form-control" id="material_content_10" name="content">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="10"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_10"></div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Is user language collected?', '是否收集用户语言');?></label>
                        <div class="switch switch-large" id='langSwitch'>
                            <input type="hidden" value="0" id="is_lang"/>
                            <input type="checkbox" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Are customer types collected?', '是否收集客户类型');?></label>
                        <div class="switch switch-large" id='customerTypeSwitch'>
                            <input type="hidden" value="0" id="is_customer_type"/>
                            <input type="checkbox" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Do you collect user information [name + mailbox]?', '是否收集用户信息[如 姓名 + 邮箱]');?></label>
                        <div class="switch switch-large" id='userinfoSwitch'>
                            <input type="hidden" value="0" id="userinfo"/>
                            <input type="checkbox" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Whether or not it is over', '是否结束');?></label>
                        <div class="switch switch-large" id='endSwitch'>
                            <input type="hidden" value="0" id="is_end"/>
                            <input type="checkbox" />
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-primary" onclick="saveRobotData()"><?php echo \App\Components\Tools::sLang('Submit', '提 交');?></button>
                    <button type="button" class="btn btn-danger pull-right" onclick="history.go(-1)"><?php echo \App\Components\Tools::sLang('Cancel', '取 消');?></button>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/tools.js') }}"></script>
<script src="{{ asset('/js/bootstrapSwitch.js') }}"></script>
<script type="text/javascript">
var t_sort = 1;
function selectContent(obj){
  t_sort = $(obj).attr('sort');
  $('.v-modal').show();
  $('#menue-set').show();
}
$(function(){
    $('#langSwitch').on('switch-change', function (e,data) {
        var value = (data.value == true) ? 1 : 0;
        $('#is_lang').val(value);
    });
    $('#customerTypeSwitch').on('switch-change', function (e,data) {
        var value = (data.value == true) ? 1 : 0;
        $('#is_customer_type').val(value);
    });
    $('#userinfoSwitch').on('switch-change', function (e,data) {
        var value = (data.value == true) ? 1 : 0;
        $('#userinfo').val(value);
    });
    $('#endSwitch').on('switch-change', function (e,data) {
        var value = (data.value == true) ? 1 : 0;
        $('#is_end').val(value);
    });
})
</script>
@include('wechat.select-material-head')
@include('robot.select-replay-body')
<script>
var image_domain = '{{ env('IMAGE_DOMIAN')}}';
function saveMaterialData(){
  var type = $.trim($('#material_type').val());
  var content = $.trim($('#material_content').val());
  console.log(t_sort);
  console.log(type);
  console.log(content);
  $('#material_type_'+t_sort).val(type);
  $('#material_content_'+t_sort).val(content);
  if(content == ''){
    $('#show_material_'+t_sort).html('');
  }else{
    switch(type){
      case 'text':
        var url = "{{URL('admin/material/text/crc_token/get')}}";
        var data = {crc_token:content};
        var result = puppet.myajax('post',url,data,false);
        if(result.code == 1){
          alert(result.msg);
          return false;
        }else{
          $('#show_material_'+t_sort).html('<textarea class="form-control" rows="6" id="text_content_val">'+result.data.content+'</textarea>');
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
          $('#show_material_'+t_sort).html('<li class="item"><div class="content"><div class="avatar" style="background-image: url('+image_domain+result.data.url+')"></div></div></li>');
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
          $('#show_material_'+t_sort).html(this_material_html);
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
          $('#show_material_'+t_sort).html('<li class="item"><div class="content"><p>'+result.data.title+'</p></div></li>');
        }
        break;
    }
  }
  $('.v-modal').hide();
  $('#menue-set').hide();
  return true;
}
function saveRobotData(){
    var keyword = $.trim($('#keyword').val());
    var type_1 = $('#material_type_1').val();
    var content_1 = $('#material_content_1').val();
    var type_2 = $('#material_type_2').val();
    var content_2 = $('#material_content_2').val();
    var type_3 = $('#material_type_3').val();
    var content_3 = $('#material_content_3').val();
    var type_4 = $('#material_type_4').val();
    var content_4 = $('#material_content_4').val();
    var type_5 = $('#material_type_5').val();
    var content_5 = $('#material_content_5').val();
    var type_6 = $('#material_type_6').val();
    var content_6 = $('#material_content_6').val();
    var type_7 = $('#material_type_7').val();
    var content_7 = $('#material_content_7').val();
    var type_8 = $('#material_type_8').val();
    var content_8 = $('#material_content_8').val();
    var type_9 = $('#material_type_9').val();
    var content_9 = $('#material_content_9').val();
    var type_10 = $('#material_type_10').val();
    var content_10 = $('#material_content_10').val();
    var is_lang = $('#is_lang').val();
    var is_customer_type = $('#is_customer_type').val();
    var userinfo = $('#userinfo').val();
    var is_end = $('#is_end').val();
    if(content_1 == '' && content_2 == '' && content_3 == ''){
        alert('请设置回复内容');
        return false;
    }
    var url = "{{ URL('admin/robot/create/save').'?fid='.$fid }}";
    var data = {keyword:keyword,type_1:type_1,content_1:content_1,type_2:type_2,content_2:content_2,type_3:type_3,content_3:content_3,
                type_4:type_4,content_4:content_4,type_5:type_5,content_5:content_5,type_6:type_6,content_6:content_6,type_7:type_7,content_7:content_7,
                type_8:type_8,content_8:content_8,type_9:type_9,content_9:content_9,type_10:type_10,content_10:content_10,
                is_lang:is_lang,is_customer_type:is_customer_type,userinfo:userinfo,is_end:is_end};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == 1){
        alert(result.msg);
        return false;
    }else{
        location.href= "{{ URL('admin/robot/list').'?fid='.$fid }}";
    }
}
</script>
@endsection