/**
 * puppet
 * 郭钊林
 * 2017.03.31
 */
var puppet = {
    mail_filter:/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
    pwd_filter:/([a-zA-Z0-9]{2,4})/,
    mobile_filter:/^1\d{10}$/,
    //ajax提交
    myajax:function(method = 'post',url = '',data = '',async = true,dataType = 'json'){
        var result;
        $.ajax({
            type:method,
            url:url,
            data:data,
            dataType:dataType,
            async:async,
            success:function(data){
                result = data;
            }
        });
        return result;
    },
    //验证邮箱
    checkMail:function(mail) {
        if (this.mail_filter.test(mail))
            return true;
        else
            return false;
    },
    //验证特殊字符
    checkusrandpwd(inp) {
        if(this.pwd_filter.test(inp))
            return true;
        else
            return false;
    },
    //验证手机号码
    checkMobile(mobile) {
        if(this.mobile_filter.test(mobile))
            return true;
        else
            return false;
    },
    //时间戳转时间格式为 2017-04-10 14:09:20
    dataFormat(timestamp){
        var time = new Date(timestamp*1000);
        var year = time.getFullYear();
        var month = time.getMonth()+1;
        var date = time.getDate();
        var hours = time.getHours();
        var minutes = time.getMinutes();
        var seconds = time.getSeconds();
        return year+'-'+month+'-'+date+' '+hours+':'+minutes+':'+seconds;
    },
    //消息提示框
    showMessage(tip, type){
        var $tip = $('#tip');
        if ($tip.length == 0) {
            $tip = $('<span id="tip" style="font-weight:bold;position:fixed;top:35%;left: 50%;z-index:9999;"></span>');
            $('body').append($tip);
        }
        $tip.stop(true).attr('class', 'alert alert-' + type).text(tip).css('margin-left', -$tip.outerWidth() / 2).fadeIn(500).delay(3000).fadeOut(500);
    },
    mesInfo(msg){
        this.showMessage(msg,'info');
    },
    mesSuccess(msg){
        this.showMessage(msg,'success');
    },
    mesFailure(msg){
        this.showMessage(msg,'danger');
    },
    mesWarn(msg){
        this.showMessage(msg,'warning');
    },
    appstoreApkUpload(result){
        var json = eval('('+result+')');
        $('#file_size').val(json.data.size);
        $('#apk_url').val(json.data.apk_url);
        $('#icon_url').val(json.data.icon_url);
        $('#package_name').val(json.data.package_name);
        $('#version').val(json.data.version);
        $('#version_code').val(json.data.version_code);
        $('#show_icon_img').attr('src',json.data.show_icon);
        if($('#file_name').length > 0){//文件原名
            $('#file_name').val(json.data.name);
        }
	if($('#duration').length > 0){
            $('#duration').val(json.data.duration);
        }
        if($('#name').length > 0){
            $('#name').val(json.data.name);
        }
        if($('#en_name').length > 0){
            $('#en_name').val(json.data.en_name);
        }
        if($('#tr_name').length > 0){
            $('#tr_name').val(json.data.tr_name);
        }
        if($('#ge_name').length > 0){
            $('#ge_name').val(json.data.ge_name);
        }
        if($('#fr_name').length > 0){
            $('#fr_name').val(json.data.fr_name);
        }
        if($('#sp_name').length > 0){
            $('#sp_name').val(json.data.sp_name);
        }
        if($('#ja_name').length > 0){
            $('#ja_name').val(json.data.ja_name);
        }
        if($('#ko_name').length > 0){
            $('#ko_name').val(json.data.ko_name);
        }
    },
    showFileSize(size){
        var result; 
        var size_ = parseInt(size);
        var k_size_ = parseInt(size_/1024);
        var m_size_ = parseInt(k_size_/1024);
        var g_size_ = parseInt(m_size_/1024);
        if(g_size_ > 0){
            result = (size_/1024/1024/1024).toFixed(2)+'GB';
        }else if(m_size_ > 0 && g_size_ <= 0){
            result = (size_/1024/1024).toFixed(2)+'MB';
        }else if(k_size_ > 0 && m_size_ <= 0){
            result = (size_/1024).toFixed(2)+'KB';
        }else{
            result = size+'B';
        }
        return result;
    },
    formatDateTime(inputTime) {    
        var date = new Date(inputTime);  
        var y = date.getFullYear();    
        var m = date.getMonth() + 1;    
        m = m < 10 ? ('0' + m) : m;    
        var d = date.getDate();    
        d = d < 10 ? ('0' + d) : d;    
        var h = date.getHours();  
        h = h < 10 ? ('0' + h) : h;  
        var minute = date.getMinutes();  
        var second = date.getSeconds();  
        minute = minute < 10 ? ('0' + minute) : minute;    
        second = second < 10 ? ('0' + second) : second;   
        return y + '-' + m + '-' + d+' '+h+':'+minute+':'+second;    
    },
    languageConversionEnToCn(lang){
        var result = '';
        switch(lang){
            case 'cn':
                result = '中文简体';
                break;
            case 'en':
                result = '英语';
                break;
            case 'tr':
                result = '中文繁体';
                break;
            case 'ge':
                result = '德语';
                break;
            case 'fr':
                result = '法语';
                break;
            case 'sp':
                result = '西班牙语';
                break;
            case 'ja':
                result = '日语';
                break;
            case 'ko':
                result = '韩语';
                break;
            default:
                break;
        }
        return result;
    },
    langShow(puppet_lang){
        return {
            "sProcessing": puppet_lang == 2 ? "处理中..." : "Processing...",
            "sLengthMenu": puppet_lang == 2 ? "每页 _MENU_ 项" : "Show _MENU_ entries",
            "sZeroRecords": puppet_lang == 2 ? "没有匹配结果" : "No matching records found",
            "sInfo": puppet_lang == 2 ? "当前显示第 _START_ 至 _END_ 项，共 _TOTAL_ 项。" : "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty": puppet_lang == 2 ? "当前显示第 0 至 0 项，共 0 项" : "Showing 0 to 0 of 0 entries",
            "sInfoFiltered": puppet_lang == 2 ? "(由 _MAX_ 项结果过滤)" : "(filtered from _MAX_ total entries)",
            "sInfoPostFix": "",
            "sSearch": puppet_lang == 2 ? "搜索:" : "Search:",
            "sUrl": "",
            "sEmptyTable": puppet_lang == 2 ? "无数据" : "No data available in table",
            "sLoadingRecords": puppet_lang == 2 ? "载入中..." : "Loading...",
            "sInfoThousands": ",",
            "oPaginate": {
                "sFirst": puppet_lang == 2 ? "首页" : "First",
                "sPrevious": puppet_lang == 2 ? "上页" : "Previous",
                "sNext": puppet_lang == 2 ? "下页" : "Next",
                "sLast": puppet_lang == 2 ? "末页" : "Last",
                "sJump": puppet_lang == 2 ? "跳转" : "Jump"
            },
            "oAria": {
                "sSortAscending": puppet_lang == 2 ? ": 以升序排列此列" : ": activate to sort column ascending",
                "sSortDescending": puppet_lang == 2 ? ": 以降序排列此列" : ": activate to sort column descending"
            }
        };
    },
}



