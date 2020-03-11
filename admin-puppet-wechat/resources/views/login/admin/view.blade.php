@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Administrator view', '查看管理员信息');
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
            <?php echo \App\Components\Tools::sLang('Administrator view', '查看管理员信息');?>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
            <li><a href="{{URL('admin/list')}}"><?php echo \App\Components\Tools::sLang('Administrator list', '管理员列表');?></a></li>
            <li class="active"><?php echo \App\Components\Tools::sLang('Administrator view', '查看管理员信息');?></li>
        </ol>
    </section> 
    <section class="invoice">
        <div class="row">
            <div class="col-xs-6">
                <a href="{{URL('admin/list')}}" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo \App\Components\Tools::sLang('Return', '返回');?></a>
                <p class="lead"></p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width:30%"><?php echo \App\Components\Tools::sLang('Return', '返回');?>编号</th>
                                <td>{{ $data['admin_id'] }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Head image', '头像');?></th>
                                <td>{{ $data['head_img'] }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Account', '账号');?></th>
                                <td>{{ $data['admin_name'] }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Real name', '真实姓名');?></th>
                                <td>{{ $data['real_name'] }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Role', '角色');?></th>
                                <td>{{ $data['role_name'] }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Mobile', '手机号码');?></th>
                                <td>{{ $data['mobile'] }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                <td>{{ date('Y-m-d H:i:s',$data['create_time']) }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Update time', '修改时间');?></th>
                                <td>{{ date('Y-m-d H:i:s',$data['update_time']) }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Last login time', '最后登录时间');?></th>
                                <td>{{ date('Y-m-d H:i:s',$data['last_login_time']) }}</td>
                            </tr>
                            <tr>
                                <th><?php echo \App\Components\Tools::sLang('Return', '返回');?>状态</th>
                                <td>
                                    @if($data['status'] == 0)
                                        <?php echo \App\Components\Tools::sLang('Close', '关闭');?>
                                    @elseif($data['status'] == 1)
                                        <?php echo \App\Components\Tools::sLang('Open', '开启');?>
                                    @else
                                        <?php echo \App\Components\Tools::sLang('Delete', '已删除');?>
                                    @endif    
                                </td>
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