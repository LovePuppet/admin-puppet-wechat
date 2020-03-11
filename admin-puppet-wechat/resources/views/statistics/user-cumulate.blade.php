@extends('home.parent')
@section('title','Line Chart')
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('/css/morris.css') }}">
@endsection
@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Line Chart
      <small></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{URL('admin')}}"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">Line Chart</li>
    </ol>
  </section>
  <section class="content" style="margin-left:10px;margin-right:10px;">
    <div class="row">
      <div class="col-md-6">
          <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Line Chart</h3>
            <br><br>
            <div class="form-group">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control" id="reservation" style="width:50%;">
                <a href="javascript:void(0)" class="btn btn-success" onclick="searchData()" style="margin-right:10%;">查询</a>
              </div>
            </div>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="box-body chart-responsive">
            <div class="chart" id="line-chart" style="height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
            </div>
          </div>
          <!-- /.box-body -->
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/tools.js').'?v='.env('VERSION') }}"></script>
<script src="{{ asset('/js/moment.min.js') }}"></script>
<script src="{{ asset('/js/daterangepicker.js') }}"></script>
<script src="{{ asset('/js/raphael-min.js') }}"></script>
<script src="{{ asset('/js/fastclick.min.js') }}"></script>
<script src="{{ asset('/js/morris.min.js') }}"></script>
<script type="text/javascript">
$(function(){
  "use strict";
  // LINE CHART
  MorrisLine();
  $('#reservation').daterangepicker({
    locale: {
      format: 'YYYY-MM-DD',
      separator: ' ~ ',
      applyLabel: "确定",
      cancelLabel: "取消",
      resetLabel: "重置",
      daysOfWeek: ['日', '一', '二', '三', '四', '五', '六'],
      monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
    },
//    startDate:getBefore10Date(),
//    endDate:getDate(),
//    maxDate:getDate(),
//    dateLimit: {days: 30},
  });
})
function searchData(){
  var reservation = $("#reservation").val();
  if(reservation == ''){
    puppet.mesWarn("请输入统计日期");
    return false;
  }
  chartStatistics(reservation);
}
function getDate(){
  var myDate = new Date();
  var date = new Date(myDate);
  date.setDate(myDate.getDate()-1);
  //获取昨日年
  var year=date.getFullYear();
  //获取昨日月
  var month=date.getMonth()+1;
  //获取昨日天
  return year+'-'+getNow(month)+"-"+getNow(date);
}
function MorrisLine(){
  var line = new Morris.Line({
    element: 'line-chart',
    resize: true,
    data: [
      {y: '2011 Q1', item1: 2666},
      {y: '2011 Q2', item1: 2778},
      {y: '2011 Q3', item1: 4912},
      {y: '2011 Q4', item1: 3767},
      {y: '2012 Q1', item1: 6810},
      {y: '2012 Q2', item1: 5670},
    ],
    xkey: 'y',
    ykeys: ['item1'],
    labels: ['Item 1'],
    lineColors: ['#3c8dbc'],
    hideHover: 'auto'
  });
}
</script>
@endsection