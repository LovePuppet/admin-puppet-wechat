@extends('home.parent')
<?php 
$title = \App\Components\Tools::sLang('Limit view', '查看管理员权限');
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
            <?php echo \App\Components\Tools::sLang('Limit view', '查看管理员权限信息');?>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
            <li><a href="{{URL('admin/limit/list')}}"><?php echo \App\Components\Tools::sLang('Role list', '管理员角色列表');?></a></li>
            <li class="active"><?php echo \App\Components\Tools::sLang('Limit view', '查看管理员权限信息');?></li>
        </ol>
    </section> 
    <section class="invoice">
      <div class="row">
        <div class="col-xs-6">
          <a href="{{URL('admin/limit/list')}}" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo \App\Components\Tools::sLang('Return', '返回');?></a>
          <p class="lead"></p>
          <div class="table-responsive">
            <table class="table">
              <tbody>
                <tr>
                  <th style="width:30%"><?php echo \App\Components\Tools::sLang('ID', '编号');?></th>
                  <td>{{ $data['admin_limit_id'] }}</td>
                </tr>
                <tr>
                  <th><?php echo \App\Components\Tools::sLang('Limit name', '权限名');?></th>
                  <td>{{ $data['limit_name'] }}</td>
                </tr>
                <tr>
                  <th><?php echo \App\Components\Tools::sLang('Limit address', '权限地址');?></th>
                  <td>{{ $data['limit_url'] }}</td>
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