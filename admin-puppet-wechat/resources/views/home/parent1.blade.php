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
    <link rel="stylesheet" href="{{ asset('/css/_all-skins.min.css') }}">
    @yield('head_js')
    @yield('head_css')
</head>
<body class="hold-transition skin-blue layout-boxed sidebar-mini">
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('/js/fastclick.min.js') }}"></script>
    <script src="{{ asset('/js/app.min.js') }}"></script>
    <script src="{{ asset('/js/demo.js') }}"></script>
    @yield('content')
    @yield('foot_js')
</body>
</html>