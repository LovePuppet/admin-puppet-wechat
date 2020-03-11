@extends('home.parent')
<?php 
  $title = \App\Components\Tools::sLang('Channel view', '查看渠道');
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
            <?php echo \App\Components\Tools::sLang('Channel view', '查看渠道');?>
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> <?php echo \App\Components\Tools::sLang('Home page', '首页');?></a></li>
            <li><a href="{{URL('admin/channel/list')}}"><?php echo \App\Components\Tools::sLang('Channel list', '渠道列表');?></a></li>
            <li class="active"><?php echo \App\Components\Tools::sLang('Channel view', '查看渠道');?></li>
        </ol>
    </section> 
    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
                <a href="{{URL('admin/channel/list')}}" class="btn btn-default"><i class="fa fa-reply"></i> <?php echo \App\Components\Tools::sLang('Return', '返回');?></a>
                <p class="lead"></p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th style="width:10%"><?php echo \App\Components\Tools::sLang('Channel name', '渠道名称');?></th>
                                <td>{{ $data['channel_name'] }}</td>
                            </tr>
                            <tr>
                                <th style="width:10%"><?php echo \App\Components\Tools::sLang('Event key', '事件key');?></th>
                                <td>{{ $data['scene_str'] }}</td>
                            </tr>
                            <tr>
                                <th style="width:10%">ticket</th>
                                <td>{{ $data['ticket'] }}</td>
                            </tr>
                            <tr>
                                <th style="width:10%"><?php echo \App\Components\Tools::sLang('Channel code', '渠道二维码');?></th>
                                <td><img src="{{ env('IMAGE_DOMIAN').$data['local_url'] }}" /></td>
                            </tr>
                            <tr>
                                <th style="width:10%"><?php echo \App\Components\Tools::sLang('Create time', '创建时间');?></th>
                                <td>{{ date('Y-m-d H:i:s',$data['create_time']) }}</td>
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