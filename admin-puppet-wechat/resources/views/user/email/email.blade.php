<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>用户提交信息邮件通知</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/blue.css') }}">
  <style type="text/css">
    .icon_img_css{
        max-width:60px;
        max-height:60px;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="content-wrapper">
  <section class="invoice">
    <div class="row">
      <div class="col-xs-6">
        <p class="lead"></p>
        <div class="table-responsive">
          <table class="table">
            <tbody>
              <tr>
                <th>昵称</th>
                <td>{{ $data['nick_name'] }}</td>
              </tr>
              <tr>
                <th>头像</th>
                <td><img src="{{ $data['head_img'] }}" class="icon_img_css"/></td>
              </tr>
              <tr>
                <th>姓名</th>
                <td>{{ $data['real_name'] }}</td>
              </tr>
              <tr>
                <th>手机号</th>
                <td>{{ $data['mobile'] }}</td>
              </tr>
              <tr>
                <th>邮箱</th>
                <td>{{ $data['email'] }}</td>
              </tr>
              <tr>
                <th>地址</th>
                <td>{{ $data['address'] }}</td>
              </tr>
              <tr>
                <th>我们能为您做些什么？</th>
                <td>{{ $data['remark'] }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </section>
</div>
</body>
</html>