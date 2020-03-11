<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <title>请填写以下表单</title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
    <meta name="description" content='请填写以下表单'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="stylesheet" href="{{ asset('/css/user-form.css').'?v=1.0.0' }}">
</head>
<!-- artcle start -->
<div class="artcle">
    <!-- container start -->
    <div class="container">
        <div class="content">
            <div class="header"></div>
            <div class="inner">
                <h3 class="title">请填写以下表单
                    <br/>APPLY FORM</h3>
                <p class="txt">
                    请填写下表，我们会尽快处理您的请求，并向您邮寄扑克牌。
                </p>
                <p class="txt">
                    Please fill out the form and we'll address your request and send the poker to you as soon as possible.
                </p>
            </div>
            <div class="hr"></div>
            <form class="form" onsubmit="return false">
                <div class="field field-name-field" id="name_error">
                    <div class="form-group">
                        <div class="field-label-container">
                            <label class="field-label" for="name">
                                姓名 Name :<front style="color:red;">*</front>
                            </label>
                        </div>
                        <div class="field-content">
                            <div class="gd-input-container">
                                <input class="input-with-icon enhanced-input" type="text" name="name" id="name">
                                <i class="gd-input-icon icon-contact"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field field-name-field" id="mobile_error">
                    <div class="form-group">
                        <div class="field-label-container">
                            <label class="field-label" for="mobile">
                                手机号 Mobile :<front style="color:red;">*</front>
                            </label>
                        </div>
                        <div class="field-content">
                            <div class="gd-input-container">
                                <input class="input-with-icon enhanced-input" type="text" name="mobile" id="mobile">
                                <i class="gd-input-icon icon-contact"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field field-name-field" id="email_error">
                    <div class="form-group">
                        <div class="field-label-container">
                            <label class="field-label" for="entry_field_1">
                                邮箱 Email :<front style="color:red;">*</front>
                            </label>
                        </div>
                        <div class="field-content">
                            <div class="gd-input-container">
                                <input class="input-with-icon enhanced-input" type="text" name="email" id="email">
                                <i class="gd-input-icon icon-mail"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field field-name-field" id="address_error">
                    <div class="form-group">
                        <div class="field-label-container">
                            <label class="field-label" for="entry_field_1">
                                地址 Address :<front style="color:red;">*</front>
                            </label>
                        </div>
                        <div class="field-content">
                            <div class="gd-input-container">
                                <input class="input-with-icon enhanced-input" type="text" name="address" id="address">
                                <i class="gd-input-icon icon-position"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="field field-name-field" id="remark_error">
                    <div class="form-group">
                        <div class="field-label-container">
                            <label class="field-label" for="entry_field_1">
                                我们能为您做些什么？
                                <br/>How can we help you？
                            </label>
                        </div>
                        <div class="field-content">
                            <div class="gd-input-container">
                                <textarea class="input-with-icon enhanced-input" type="text" name="remark" id="remark"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hr" style="margin-top: 20px;"></div>
                <input type="button" name="commit" value="提交" data-disable-with="提交中..." class="submit gd-btn gd-btn-primary-solid " onclick="userinfoSubmit()">
            </form>
        </div>
    </div>
    <!-- container end -->
</div>
<!-- artcle end -->
</body>
<script src="{{ asset('/js/jquery.min.js') }}"></script>
<script src="{{ asset('/js/tools.js') }}"></script>
<script>
function userinfoSubmit(){
    var user_id = {{ $user_id }};
    var name = $.trim($('#name').val());
    var mobile = $.trim($('#mobile').val());
    var email = $.trim($('#email').val());
    var address = $.trim($('#address').val());
    var remark = $.trim($('#remark').val());
    if(name == ''){
        $('#name_error').addClass('error');
        return false;
    }
    if(mobile == ''){
        $('#mobile_error').addClass('error');
        return false;
    }
    if(email == ''){
        $('#email_error').addClass('error');
        return false;
    }
    if(address == ''){
        $('#address_error').addClass('error');
        return false;
    }
    var url = "{{URL('wechat/oauth/post')}}";
    var data = {user_id:user_id,name:name,mobile:mobile,email:email,address:address,remark:remark};
    var result = puppet.myajax('post',url,data,false);
    if(result.code == 1){
      alert(result.msg);
      return false;
    }else{
      alert('success');
    }
}
</script>
</html>