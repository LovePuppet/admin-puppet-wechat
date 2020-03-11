@extends('home.parent')
@section('title','修改规则')
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/bootstrapSwitch.css') }}">
@endsection
@section('content')
<div class="content-wrapper">
<section class="content-header">
    <h1>
        修改规则
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
        <li><a href="{{URL('admin/robot/list')}}">Robot列表</a></li>
        <li class="active">修改规则</li>
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
                        <label for="keyword">关键词<span style="color: red">（关键词为空，任何消息都回复。如果是匹配多个关键词用空格隔开）</span></label>
                        <input type="text" class="form-control" id="keyword" name="keyword" placeholder="关键词" value="{{ $data['keyword'] }}" required>
                    </div>
                    <?php $content = json_decode($data['content'],true);?>
                    <div class="form-group">
                        <label for="content">关注回复1</label>
                        <input type="hidden" class="form-control" id="material_type" value="text">
                        <input type="hidden" class="form-control" id="material_content">
                        <input type="hidden" class="form-control" id="material_type_1" value="{{ $content[0]['msgtype'] }}">
                        <input type="hidden" class="form-control" id="material_content_1" value="{{ $content[0]['content'] }}">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="1">选择</button>
                        <br><br>
                        <div id="show_material_1">{!! \App\Components\Tools::getMaterialHtml($content[0]['msgtype'],$content[0]['content']) !!}</div>
                    </div>
                    <div class="form-group">
                        <label for="content">关注回复2</label>
                        <input type="hidden" class="form-control" id="material_type_2" value="{{ $content[1]['msgtype'] }}">
                        <input type="hidden" class="form-control" id="material_content_2" value="{{ $content[1]['content'] }}">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="2">选择</button>
                        <br><br>
                        <div id="show_material_2">{!! \App\Components\Tools::getMaterialHtml($content[1]['msgtype'],$content[1]['content']) !!}</div>
                    </div>
                    <div class="form-group">
                        <label for="content">关注回复3</label>
                        <input type="hidden" class="form-control" id="material_type_3" value="{{ $content[2]['msgtype'] }}">
                        <input type="hidden" class="form-control" id="material_content_3" value="{{ $content[2]['content'] }}">
                        <br>
                        <button type="button" class="btn btn-success" onclick="selectContent(this)" sort="3">选择</button>
                        <br><br>
                        <div id="show_material_3">{!! \App\Components\Tools::getMaterialHtml($content[2]['msgtype'],$content[2]['content']) !!}</div>
                    </div>
                    <div class="form-group">
                        <label for="content">是否收集用户语言</label>
                        <div class="switch switch-large" id='langSwitch'>
                            <input type="hidden" value="{{ $data['is_lang'] }}" id="is_lang"/>
                            <input type="checkbox" @if($data['is_lang']) checked @endif/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content">是否收集客户类型</label>
                        <div class="switch switch-large" id='customerTypeSwitch'>
                            <input type="hidden" value="{{ $data['is_customer_type'] }}" id="is_customer_type"/>
                            <input type="checkbox" @if($data['is_customer_type']) checked @endif/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content">是否收集用户信息[如 姓名 + 邮箱]</label>
                        <div class="switch switch-large" id='userinfoSwitch'>
                            <input type="hidden" value="{{ $data['userinfo'] }}" id="userinfo"/>
                            <input type="checkbox" @if($data['userinfo']) checked @endif/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="content">是否结束</label>
                        <div class="switch switch-large" id='endSwitch'>
                            <input type="hidden" value="{{ $data['is_end'] }}" id="is_end"/>
                            <input type="checkbox" @if($data['is_end']) checked @endif/>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="button" class="btn btn-primary" onclick="saveRobotData()">提 交</button>
                    <button type="button" class="btn btn-danger pull-right" onclick="history.go(-1)">取 消</button>
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
    var is_lang = $('#is_lang').val();
    var is_customer_type = $('#is_customer_type').val();
    var userinfo = $('#userinfo').val();
    var is_end = $('#is_end').val();
    if(content_1 == '' && content_2 == '' && content_3 == ''){
        alert('请设置回复内容');
        return false;
    }
    var url = "{{ URL('admin/robot/edit/save').'/'.$data['robot_id'].'?fid='.$fid }}";
    var data = {keyword:keyword,type_1:type_1,content_1:content_1,type_2:type_2,content_2:content_2,type_3:type_3,content_3:content_3,
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