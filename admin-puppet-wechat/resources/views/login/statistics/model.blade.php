@extends('home.parent')
@section('title','数据统计')
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/sweetalert.css') }}">
@endsection
@section('content')
<div class="content-wrapper" style="min-height: 976px;">
    <div>
        <div style="margin-left: 40%;">
            <button type="button" onclick="model()" class="btn btn-success">model</button><button type="button" onclick="del()" class="btn btn-danger">del</button>
        </div>
        <div style="margin-left: 40%;margin-top:100px;">
        <button type="button" onclick="success()" class="btn btn-success">success</button><button type="button" onclick="fail()" class="btn btn-danger">fail</button>
        </div>
    </div>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/jquery.dialog.js') }}"></script>
<script src="{{ asset('/js/sweetalert.min.js') }}"></script>
<script type="text/javascript">
function model(){
    swal({
        title: "管理员详情",
        text: '<div><h1>puppet</h1></div>',
        html: true,
        showConfirmButton: true
    });
}
function del(){
    swal({
       title: "",
       text: "您确定要操作吗？",
       type: "success",
       showCancelButton: true,
       closeOnConfirm: false,
       confirmButtonText: "确定",
       confirmButtonColor: "#ec6c62"
       }, function() {
//               $.ajax({
//                    url: "/system/setstatus",
//                    type: "POST",
//                    data:{user_id:user_id,status:status }
//                    }).done(function(data) {
//                                swal({
//                                    title: "",
//                                    text: '操作成功!',
//                                    html: true,
//                                    showConfirmButton: false
//                                });
//                               setTimeout(refresh_window, 500);
//               }).error(function(data) {
//                    swal("OMG", "操作失败了!", "error");
//               });
       });
}
function success(){
    swal({
        title: "",
        text: '操作成功!',
        html: true,
        showConfirmButton: false
    });
    setTimeout(swalhide, 1000);
}
function fail(){
    swal("OMG", "操作失败了!", "error");
}
function swalhide(){
    $('.showSweetAlert').hide();
    $('.sweet-overlay').hide();
}
</script>
@endsection