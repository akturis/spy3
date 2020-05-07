@extends('layouts.app')
@section('content')
  <div class="container">{{Form::label('pages', 'Pages')}}
  {{Form::select('pages', ['5000' => 'All', 50 => '50', 100 => '100', 500 => '500'], '50')}}
  {{Form::label('corp', 'Corporation')}}
  {{Form::select('corp', ['All' => 'All', '604035876' => 'FSPT','1268814498' => 'FSP-B'], 'All')}}
  {{Form::label('days', 'Days')}}
  {{Form::select('days', ['30' => 'Default', '365' => '365','180' => '180','120' => '120','60' => '60'], 'Default')}}
  </div>
  <div id="wait" align="center">
    <p><img src="../img/loader.gif" /> Please Wait</p>
  </div>
    <!--Div that will hold the dashboard-->
  <div id="dashboard_div">
  <div class="container">
      <div id="options"></div>
      <div class="row">
        <h2>Filters</h2>
        <div class="col-md-6">
                <div id="filter_div">
                </div>
                <div id="filter_div1">
                </div>
                <div id="filter_div2">
                </div>
                <div id="filter_div3">
                </div>
                <div id="filter_div4">
                </div>
        </div>
        <div class="col-md-6">
                <div id="filter_div5">
                </div>
                <div id="filter_div6">
                </div>
                <div id="filter_div7">
                </div>
                <div id="filter_div8">
                </div>
                <div id="filter_div9">
                </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
                <div id="filter_div10">
                </div>
                <div id="filter_div11">
                </div>
        </div>
        <div class="col-md-6">
        </div>
      </div>
      <div class="row">
        <div class="span9">
        </div>
      </div>
      <div class="row">
        <div class="span9">
          <h2 id="corp">New pilots</h2>
            <div class="span9">
                <div id="comment_div">
                    <form class="navbar-search pull-left" action="">
                        <input type="text" data-provide="typeahead" class="search span2" placeholder="Pilot">
                        <input type="text" data-provide="typeahead" class="comment span5" placeholder="Comment">
                        <button id="comment" class="btn pull-right" type="button">Change</button>
                    </form>
                </div>
            </div>
                <table id="membersTable" class="table table-striped table-condensed"></table>
        </div>
        <div class="span3" id="top">
          <div id="wait1" align="center">
            <p><img src="../img/loader.gif" /> Please Wait</p>
          </div>
        </div>
      </div>

      <div class="hidden-phone row">
        <div class="span3 counter"></div>
        <div class="offset1 span8">
          <h2>Activity stats</h2>
          <div class="chart" id="onlineChart"></div>
        </div>
        <div id="wait2" align="center">
          <p><img src="../img/loader.gif" /> Please Wait</p>
        </div>
      </div>
      <hr>
      <h2>Balance</h2>
      <div class="chart" id="Balance"></div>
      <div id="wait3" align="center">
         <p><img src="../img/loader.gif" /> Please Wait</p>
      </div>
      <hr>
      <h2>Prime time</h2>
      <div class="chart" id="Prime"></div>
      <div id="wait4" align="center">
         <p><img src="../img/loader.gif" /> Please Wait</p>
      </div>
      <hr>
      <footer>
        <p>created in love with twitter, google &amp; jquery &copy; banderlogs 2017</p>
      </footer>

    </div> <!-- /container -->
    </div>
@endsection
@section('scripts')
  <script src="https://www.google.com/jsapi"></script>
  <script src="{{ asset('js/home.js') }}"></script>
@endsection
