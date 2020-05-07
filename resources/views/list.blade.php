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
@if (Auth::check())
  {{Form::label('days', 'Days')}}
  {{Form::select('days', ['30' => 'Default', '365' => '365','180' => '180','90' => '90','60' => '60'], 'Default')}}
@endif
@role('director')
  {{Form::label('pages', 'Pages')}}
  {{Form::select('pages', ['5000' => 'All', 50 => '50', 100 => '100', 500 => '500'], '50')}}
  {{Form::label('corp', 'Corporation')}}
<?php
$corps = App\Models\Corporations::where('tracked','1')->pluck('short_name','corpID');
$corps->prepend('All', 'All');
?>
  {{Form::select('corp', $corps, 'All')}}
  {{Form::label('top', 'Top')}}
  {{Form::select('top', ['none' => 'None', 'topkills' => 'Top kills', 'topmissions' => 'Top missions', 'topgreen' => 'Top anomalies'], 'None')}}
@endrole
  </div>
  <div id="wait" align="center">
    <p><img src="../img/loader.gif" /> Please Wait</p>
  </div>
  <div class="container-fluid"  style="width:95%">
    <p><button class="btn btn mb-2" data-toggle="collapse" href="#options" aria-expanded="false" aria-controls="options">Filters&Changes</button>
    </p>
    <div id="options" class="collapse">
      <p id="count"></p>
      <div class="row">
        <div class="col-md-4">
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
        <div class="col-md-4">
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
        <div class="col-md-4">
            <div id="filter_div10">
            </div>
            <div id="filter_div11">
            </div>
            <div id="filter_div12">
            </div>
        </div>
        <div class="col-md-4">
            <div id="fleetups">
                <label for="fleetup">No FleetUp</label>
                <input type="checkbox" name="fleetup" value="0" id="fleetup" />
            </div>
            <div id="invites">
                <label for="invited">Invited</label>
                <input type="checkbox" name="invited" value="0" id="invited" />
            </div>
        </div>
      </div>
    @if (Auth::check())
      <div class="row">
        <div class="col-md-4">
            <div id="parse_div">
                <form class="navbar-search pull-left" action="">
                    <div class="row">
                        <textarea type="text" rows=7 class="pilots col-md-7" placeholder="Paste" required></textarea>
                    </div>
                    <div class="row">
                        <button id="getparse" class="btn btn-primary" type="button">Parse</button>
                        <button id="clear" class="btn pull-right" type="button">Clear</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-5">
            <h2 id="corp">Change</h2>
            <div class="row">
                <label for="comment_div">Comments</label>
                <div id="comment_div">
                    <form class="navbar-search pull-left" action="">
                        @csrf <!-- {{ csrf_field() }} -->
                        <input type="text" data-provide="typeahead" class="search col-md-3" placeholder="Pilot">
                        <input type="text" data-provide="typeahead" class="comment col-md-5" placeholder="Comment">
                        <button id="comment" class="btn pull-right" type="button">Change</button>
                    </form>
                </div>
            </div>
            <div class="row">
                <label for="alts_div">Alts</label>
                <div id="alts_div">
                    <form class="navbar-search pull-left" action="">
                        @csrf <!-- {{ csrf_field() }} -->
                        <input id="alt_id" type="text" data-provide="typeahead" class="search col-md-4" placeholder="Alt">
                        <input id="main_id" type="text" data-provide="typeahead" class="search col-md-4" placeholder="Main">
                        <button id="alts" class="btn pull-right" type="button">Change</button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    @endif
    </div>
      <div class="row">
      </div>
      <div class="row">
        <h2 id="corp">New pilots</h2>
        <div class="col-md-12">
            <div class="row">
            <table id="membersTable" class="table table-striped table-bordered table-sm" ></table>
            </div>
        </div>
        <div class="col-md-3" id="top">
          <div id="wait1" align="center">
            <p><img src="../img/loader.gif" /> Please Wait</p>
          </div>
        </div>
      </div>

      <hr>
      <footer>
        <p>created in love with laravel, google &amp; bootstrap &copy; banderlogs 2019</p>
      </footer>
  
        <div class="modal fade" tabindex="-1" id="sheet" role="dialog" >
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4>Statistics</h4>
                <button type="button" class="close" data-dismiss="modal" role="dialog" aria-hidden="true" >&times;</button>
              </div>
              <div class="modal-body"></div>
            </div>
          </div>
        </div>
      <div style="display: none;" id="charlist"></div>

    </div> <!-- /container -->
    </div>
@endsection
@section('scripts')
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script src="{{ asset('js/home.js') }}"></script>
@endsection
