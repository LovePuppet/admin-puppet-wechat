@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Follow reply', '关注回复');
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
        <a href="{{URL('admin/wechat/follow/list')}}" class="btn btn-default"><?php echo \App\Components\Tools::sLang('New follower reply message setting', '新用户关注设置');?></a>
        <a href="{{URL('admin/wechat/follow/repeat')}}" class="btn btn-info"><?php echo \App\Components\Tools::sLang('Refollower reply message setting', '取关后重新关注设置');?></a>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
        <li class="active"><?php echo \App\Components\Tools::sLang('Follow reply', '关注回复');?></li>
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-body" style="min-height:500px;">
                    @if(!empty($data))
                    <?php $content = json_decode($data['content'],true);?>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply1', '回复1');?></label>
                        <input type="hidden" class="form-control" id="material_type" value="text">
                        <input type="hidden" class="form-control" id="material_content">
                        <input type="hidden" class="form-control" id="material_type_1" value="{{ $content[0]['msgtype'] }}">
                        <input type="hidden" class="form-control" id="material_content_1" value="{{ $content[0]['content'] }}">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="1"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_1">{!! \App\Components\Tools::getMaterialHtml($content[0]['msgtype'],$content[0]['content']) !!}</div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply2', '回复2');?></label>
                        <input type="hidden" class="form-control" id="material_type_2" value="{{ $content[1]['msgtype'] }}">
                        <input type="hidden" class="form-control" id="material_content_2" value="{{ $content[1]['content'] }}">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="2"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_2">{!! \App\Components\Tools::getMaterialHtml($content[1]['msgtype'],$content[1]['content']) !!}</div>
                    </div>
                    <div class="form-group">
                        <label for="content"><?php echo \App\Components\Tools::sLang('Reply3', '回复3');?></label>
                        <input type="hidden" class="form-control" id="material_type_3" value="{{ $content[2]['msgtype'] }}">
                        <input type="hidden" class="form-control" id="material_content_3" value="{{ $content[2]['content'] }}">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="3"><?php echo \App\Components\Tools::sLang('Select', '选择');?></button>
                        <br><br>
                        <div id="show_material_3">{!! \App\Components\Tools::getMaterialHtml($content[2]['msgtype'],$content[2]['content']) !!}</div>
                    </div>
                    @else
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
                    @endif
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-primary" onclick="saveFollowData()"><?php echo \App\Components\Tools::sLang('Publish', '发 布');?></button>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/tools.js') }}"></script>
<script type="text/javascript">
var t_sort = 1;
function selectContent(obj){
    t_sort = $(obj).attr('sort');
    $('.v-modal').show();
    $('#menue-set').show();
}
</script>
@include('wechat.select-material-head')
@include('robot.select-replay-body')
<script>
var image_domain = '{{ env('IMAGE_DOMIAN')}}';
function saveMaterialData(){
    var type = $.trim($('#material_type').val());
    var content = $.trim($('#material_content').val());
    if(content == ''){
        $('#show_material_'+t_sort).html('');
    }else{
        $('#material_type_'+t_sort).val(type);
        $('#material_content_'+t_sort).val(content);
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
function saveFollowData(){
    var type_1 = $('#material_type_1').val();
    var content_1 = $('#material_content_1').val();
    var type_2 = $('#material_type_2').val();
    var content_2 = $('#material_content_2').val();
    var type_3 = $('#material_type_3').val();
    var content_3 = $('#material_content_3').val();
    if(content_1 == '' && content_2 == '' && content_3 == ''){
        alert('请设置回复内容');
        return false;
    }
    var url = "{{URL('admin/wechat/follow/repeat/save')}}";
    var data = {type_1:type_1,content_1:content_1,type_2:type_2,content_2:content_2,type_3:type_3,content_3:content_3};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == 1){
        alert(result.msg);
        return false;
    }else{
        alert('保存成功');
    }
}
</script>
@endsection