@extends('home.parent')
@section('title','查看用户')
@section('head_js')
@endsection
@section('head_css')
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            查看用户信息
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
            <li><a href="{{URL('admin/user/list')}}">用户列表</a></li>
            <li class="active">查看用户</li>
        </ol>
    </section> 
    <section class="invoice">
        <div class="row">
            <div class="col-xs-6">
                <a href="{{URL('admin/user/list')}}" class="btn btn-default"><i class="fa fa-reply"></i> 返回</a>
                <p class="lead"></p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width:30%">姓名</th>
                                <td>{{ $data['true_name'] }}</td>
                            </tr>
                            <tr>
                                <th>手机号码</th>
                                <td>{{ $data['mobile'] }}</td>
                            </tr>
                            <tr>
                                <th>邮箱</th>
                                <td>{{ $data['email'] }}</td>
                            </tr>
                            <tr>
                                <th style="width:30%">孩子昵称</th>
                                <td>{{ $data['nickname'] }}</td>
                            </tr>

                            <tr>
                                <th style="width:30%">国家</th>
                                <td>{{ $data['country'] }}</td>
                            </tr>

                            <tr>
                                <th style="width:30%">省</th>
                                <td>{{ $data['province'] }}</td>
                            </tr>

                            <tr>
                                <th style="width:30%">市</th>
                                <td>{{ $data['city'] }}</td>
                            </tr>

                            <tr>
                                <th style="width:30%">区/县</th>
                                <td>{{ $data['area'] }}</td>
                            </tr>

                            <tr>
                                <th style="width:30%">头像</th>
                                <td><img src="{{ $data['avatar'] }}" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('foot_js')
@endsection