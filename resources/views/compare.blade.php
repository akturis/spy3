@extends('master')
@section('link')
<style type='text/css'>
    .large-font {
        font-size: 1.5vmin;
    }
    .small-font {
        font-size: 1.5vmin;
    }
  
    .table td.demo {
       max-width: 220px;
    }
    .table td.demo span.date1 {
       max-width: 50px;
    }
    .table td.demo span {
       overflow: hidden;
       text-overflow: ellipsis;
       display: inline-block;
       white-space: nowrap;
       max-width: 100%;
    }  
</style>  
@section('content')
    <!--Div that will hold the dashboard-->
  <div id="dashboard_div">
      <div class="container-fluid">
          <div id="gauge_div" style="width:280px; height: 140px;"></div>
      </div>
      <div id="wait" align="center">
        <p><img src="../img/loader.gif" /> Please Wait</p>
      </div>
      <div class="container-fluid"  style="width:95%">

          <hr>
          <footer>
            <p>created in love with laravel, google &amp; bootstrap &copy; banderlogs 2019</p>
          </footer>
  
          <div style="display: none;" id="charlist"></div>

      </div> <!-- /container -->
    </div>
@endsection
@section('scripts')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load('current', {'packages':['gauge']});
    google.charts.setOnLoadCallback(drawGauge);

    var gaugeOptions = {min: 0, max: 100, yellowFrom: 50, yellowTo: 80,
      greenFrom: 80, greenTo: 100, redFrom: 0, redTo: 50, minorTicks: 5, animation: { duration:1000 } };
    var gauge;

    function drawGauge() {
      gaugeData = new google.visualization.DataTable();
      gaugeData.addColumn('number', 'Engine');
      gaugeData.addColumn('number', 'Torpedo');
      gaugeData.addRows(2);
      gaugeData.setCell(0, 0, 0);
      gaugeData.setCell(0, 1, 0);

      gauge = new google.visualization.Gauge(document.getElementById('gauge_div'));
      gauge.draw(gaugeData, gaugeOptions);
      gaugeData.setCell(0, 0, 90);
      gaugeData.setCell(0, 1, 70);
      gauge.draw(gaugeData, gaugeOptions);
    }

  </script>
@endsection
