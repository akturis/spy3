@extends('master')
@section('link')
@section('content')
    <!--Div that will hold the dashboard-->
  <div id="dashboard_div">
  <div class="container-fluid"  style="width:95%">
      <h2>Average Pilot</h2>
      <div id="Gauge" style="width:380px; height: 140px;" class="w-auto h-25"></div>
      <hr>
        <div id="wait2" align="center">
          <p><img src="../img/loader.gif" /> Please Wait</p>
        </div>
      <h2>Activity stats</h2>
      <div class="chart col-md-8" id="onlineChart"></div>
      <hr>
      <h2>Prime time</h2>
      <div class="chart col-md-8" id="Prime"></div>
      <hr>
@role('director')
      <h2>Income by week</h2>
      <div class="chart col-md-8" id="Balance"></div>
      <hr>
@endrole
      <footer>
        <p>created in love with laravel, google &amp; bootstrap &copy; banderlogs 2019</p>
      </footer>
  
      <div style="display: none;" id="charlist"></div>

    </div> <!-- /container -->
    </div>
@endsection
@section('scripts')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="{{ asset('js/home.js') }}"></script>
@endsection
