function _(el){
    return document.getElementById(el);
}
function uploadFile(filename,url){
    var file = _(filename).files[0];
    if(file == '' || file == undefined){
        puppet.mesWarn('请选择上传文件');
        return false;
    }
    $("#progressBar").css('display','block');
    var formdata = new FormData();
    formdata.append(filename, file);
    var ajax = new XMLHttpRequest();
    ajax.upload.addEventListener("progress", progressHandler, false);
    ajax.addEventListener("load", completeHandler, false);
    ajax.addEventListener("error", errorHandler, false);
    ajax.addEventListener("abort", abortHandler, false);
    ajax.open("POST", url);
    ajax.send(formdata);
}
function progressHandler(event){
    var percent = (event.loaded / event.total) * 100;
    _("progressBar").value = Math.round(percent);
    _("status").innerHTML = Math.round(percent)+'%';
}
function completeHandler(event){
    var result = event.target.responseText;
    var json = eval('('+result+')');
    if(json.error == true){
        _("status").style.color = 'red';
        _("status").innerHTML = json.message;
        _("progressBar").value = 0;
    }else{
        _("status").style.color = 'green';
        _("status").innerHTML = "上传成功";
        _("progressBar").value = 100;
        puppet.appstoreApkUpload(result);
    }
}
function errorHandler(event){
    _("status").innerHTML = "Upload Failed";
}

function abortHandler(event){
    _("status").innerHTML = "Upload Aborted";
}