@extends('master')
@section('link')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

<?php
$invites_all = App\Models\Invites::where('invited','1')->count();
?>
            <div class="pull-left">
                <h4 class="mt-5 mb-5">Invited characters - {{ count($invitesObjects) }} / {{ $invites_all }}</h4>
            </div>

        </div>
        
        @if(count($invitesObjects) == 0)
            <div class="panel-body text-center">
                <h4>No Invites Available.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Invited</th>
                            <th>Template</th>
                            <th>Date</th>
                            <th>Corporation</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($invitesObjects as $invites)
                        <tr>
                            <td><a href="https://evewho.com/pilot/{!!$invites->name!!}" target="_blank">{{ $invites->name }}</a></td>
                            <td>{{ ($invites->invited) ? 'Yes' : 'No' }}</td>
                            <td>{{ ($invites->template == "3") ? 'English' : 'Russian' }}</td>
                            <td>{{ $invites->updated_at }}</td>
                            <td>{{ $invites->corporation_name }}</td>

                            <td>


                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="panel-footer">
            {!! $invitesObjects->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection