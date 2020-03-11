@extends('home.parent')
<?php 
$title = env('SYSTEM_NAME','PUPPET');
$title .= \App\Components\Tools::sLang('Background Management System', '后台管理系统');
?>
@section('title',$title)
@section('head_js')
@endsection
@section('head_css')
<link rel="stylesheet" href="{{ asset('/css/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('/css/morris.css') }}">
<style>
    .users-list img{
        min-width:80px;
        min-height:80px;
        max-width:80px;
        max-height:80px;
    }
    .skillstore>li img{
        border-radius:0%;
    }
</style>
@endsection
@section('content')
<!--
<div class="content-wrapper" style="background-image:url(../images/dreamstime.jpg);background-size:cover;background-repeat:no-repeat;">
    <section class="content-header">
     <h3 style='margin:10% 0 0 5%;width:60%;font-size:28px;font-weight:100;color:#fff;line-height:1.5;letter-spacing:1px;font-family: "SF Pro Display","SF Pro Icons","Helvetica Neue","Helvetica","Arial",sans-serif;'>We believe that the best way for an organization to stand out is by finding an area of meaningful differentiation and delivering against it — without fail, every day.</h3>  
    </section>
</div>
-->
<div class="content-wrapper">
  <div class="row">
    <div style="margin:20px 0 20px 30%">
      <input type="text" class="form-control" id="reservation" style="width:50%;">
    </div>
        <div class="col-md-6">
          <!-- AREA CHART -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Area Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="revenue-chart" style="height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- DONUT CHART -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Donut Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="sales-chart" style="height: 300px; position: relative;"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (LEFT) -->
        <div class="col-md-6">
          <!-- LINE CHART -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Line Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="line-chart" style="height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- BAR CHART -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Bar Chart</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body chart-responsive">
              <div class="chart" id="bar-chart" style="height: 300px; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col (RIGHT) -->
      </div>
    </div>
@endsection
@section('foot_js')
<script src="{{ asset('/js/tools.js').'?v='.env('VERSION') }}"></script>
<script src="{{ asset('/js/moment.min.js') }}"></script>
<script src="{{ asset('/js/daterangepicker.js') }}"></script>
<script src="{{ asset('/js/fastclick.min.js') }}"></script>
<script src="{{ asset('/js/raphael-min.js') }}"></script>
<script src="{{ asset('/js/morris.min.js') }}"></script>
<script src="{{ asset('/js/demo.js') }}"></script>
<script type="text/javascript">
$(function () {
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
    startDate:getBefore30Date(),
    endDate:getDate(),
    maxDate:getDate(),
    dateLimit: {days: 30},
  });
    
  "use strict";
  // AREA CHART
  var area = new Morris.Area({
    element: 'revenue-chart',
    resize: true,
    data: [
      {y: '2011 Q1', item1: 2666, item2: 2666},
      {y: '2011 Q2', item1: 2778, item2: 2294},
      {y: '2011 Q3', item1: 4912, item2: 1969},
      {y: '2011 Q4', item1: 3767, item2: 3597},
      {y: '2012 Q1', item1: 6810, item2: 1914},
      {y: '2012 Q2', item1: 5670, item2: 4293},
      {y: '2012 Q3', item1: 4820, item2: 3795},
      {y: '2012 Q4', item1: 15073, item2: 5967},
      {y: '2013 Q1', item1: 10687, item2: 4460},
      {y: '2013 Q2', item1: 8432, item2: 5713}
    ],
    xkey: 'y',
    ykeys: ['item1', 'item2'],
    labels: ['Item 1', 'Item 2'],
    lineColors: ['#a0d0e0', '#3c8dbc'],
    hideHover: 'auto'
  });

  // LINE CHART
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
      {y: '2012 Q3', item1: 4820},
      {y: '2012 Q4', item1: 15073},
      {y: '2013 Q1', item1: 10687},
      {y: '2013 Q2', item1: 8432}
    ],
    xkey: 'y',
    ykeys: ['item1'],
    labels: ['Item 1'],
    lineColors: ['#3c8dbc'],
    hideHover: 'auto'
  });

  //DONUT CHART
  var donut = new Morris.Donut({
    element: 'sales-chart',
    resize: true,
    colors: ["#3c8dbc", "#f56954", "#00a65a"],
    data: [
      {label: "Download Sales", value: 12},
      {label: "In-Store Sales", value: 30},
      {label: "Mail-Order Sales", value: 20}
    ],
    hideHover: 'auto'
  });
  //BAR CHART
  var bar = new Morris.Bar({
    element: 'bar-chart',
    resize: true,
    data: [
      {y: '2006', a: 100, b: 90},
      {y: '2007', a: 75, b: 65},
      {y: '2008', a: 50, b: 40},
      {y: '2009', a: 75, b: 65},
      {y: '2010', a: 50, b: 40},
      {y: '2011', a: 75, b: 65},
      {y: '2012', a: 100, b: 90}
    ],
    barColors: ['#00a65a', '#f56954'],
    xkey: 'y',
    ykeys: ['a', 'b'],
    labels: ['CPU', 'DISK'],
    hideHover: 'auto'
  });
})
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
function getNow(s){
  return s < 10 ? '0' + s: s;
}
function getBefore30Date(){
  var date1 = new Date();
  var date2 = new Date(date1);
  date2.setDate(date1.getDate() - 30);
  return date2.getFullYear() + "-" + (date2.getMonth() + 1) + "-" + date2.getDate() + "-"+ date2.getDay();
}
</script>
@endsection