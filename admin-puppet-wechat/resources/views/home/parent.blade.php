<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/ionicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/skin-orange.min.css') }}">
  @yield('head_js')
  @yield('head_css')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  @include('home.header')
  @include('home.left')
  @yield('content')
  @include('home.footer')
  @include('home.right')
  <div class="control-sidebar-bg"></div>
  <script src="{{ asset('/js/jquery.min.js').'?v='.env('VERSION') }}"></script>
  <script src="{{ asset('/js/bootstrap.min.js').'?v='.env('VERSION') }}"></script>
  <script src="{{ asset('/js/app.min.js').'?v='.env('VERSION') }}"></script>
  <?php 
    $lang = Cookie::get('puppet_lang');
  ?>
  <script>
    var puppet_lang = {{ $lang }};
  </script>
  @yield('foot_js')
</div>
</body>
</html>
