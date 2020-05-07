@extends('master')
@section('content')
@php ($count = session('count'))
@php ($pilots = session('pilots'))
@php ($template = session('template'))
    @if(Session::has('total'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            Total time is {!!  session('total') !!} s
            Total characters <b>{{ $count['total'] }}</b>
            Already sent <b><a class="text-danger">{{ $count['already'] }}</a></b>
            Invited characters <b><a class="text-success">{{ $count['invited'] }}</a></b>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

<div id="dashboard_div">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
            <div class="col-md-4">
                <div id="parse_div">
                    <form name="invite" class="navbar-search pull-left" method="POST" action="{{ route('invite.invite.send') }}">
                        {{ csrf_field() }}
                        <select class="form-control" id="template" name="template">
@hasanyrole(['admin','invite russian'])
                            <option value="1" @if($template == "1") selected @endif>Newbie Russian</option>
                            <option value="3" @if($template == "3") selected @endif>NPC corporation Russian</option>
@endif                            
@hasanyrole(['admin','invite english'])
                            <option value="2" @if($template == "2") selected @endif>Newbie English</option>
@endif                            
                        </select>
                        <textarea name='pilots' type="text" rows=20 cols="41" class="pilots" placeholder="Paste" required>{{ $pilots }}</textarea>
                        <div class="btn-group btn-group-xs pull-right" role="group">
                            <button id="invite" name="check" value=0 class="btn btn-primary" type="submit">Send Invite Mail</button>
                            <button id="check"  name="check" value=1 class="btn btn-warning" type"submit">Check Invite</button>
                            <button id="clear" class="btn pull-right" type="reset">Clear</button>
                        </div>
                    </form>
                </div>
            </div>
            </div>
            <div class="col">
    @if(Session::has('sended'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
@php ($days = session('sended'))
@foreach ($days as $day)
<div class="pilot">
<p><b><a href="https://evewho.com/pilot/{{$day['pilot']}}" target="_blank">{{$day['pilot']}}</a> </b>
@if ($day['searched'] == 1)
<a class="text-success">Found npc corp</a>
@elseif ($day['searched'] == 2)
<a class="text-danger">Not npc corp</a>
@else
<a class="text-danger">Not found character</a>
@endif
@if ($day['invited'] == 1)
<a class="text-warning">{{$day['days']}} days</a>
<a class="text-success">For pilot mail sent</a>
@elseif ($day['invited'] == 4)
<a class="text-warning">{{$day['days']}} days</a>
<a class="text-success">For pilot mail will be sent</a>
@elseif ($day['invited'] == 2)
<a class="text-danger">Already sent</a></p>
@elseif ($day['invited'] == 3)
<a class="text-warning">{{$day['days']}} days</a>
<a class="text-danger">Error</a></p>
@elseif ($day['invited'] == 0)
<a class="text-warning">{{$day['days']}} days</a>
@endif
</div>
@endforeach
        </div>
    @endif
            </div>
        </div>
    </div>
</div>
@endsection
