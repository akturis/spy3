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

            <div class="pull-left">
                <h4 class="mt-5 mb-5">Notification from EVE</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('notificaton_eves.notificaton_eves.create') }}" class="btn btn-success" title="Create New Notificaton Eves">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>
        
        @if(count($notifeveObjects) == 0)
            <div class="panel-body text-center">
                <h4>No Notificaton Eves Available.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Character ID</th>
                            <th>Enabled</th>
                            <th>Token</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($notifeveObjects as $notificatonEves)
                        <tr>
                            <td>{{ $notificatonEves->character_id }}</td>
                            <td>{{ ($notificatonEves->enabled) ? 'Yes' : 'No' }}</td>
                            <td>{{ (!is_null($notificatonEves->token))?'Has token':'' }}</td>

                            <td>

                                <form method="POST" action="{!! route('notificaton_eves.notificaton_eves.destroy', $notificatonEves->character_id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('notificaton_eves.notificaton_eves.show', $notificatonEves->character_id ) }}" class="btn btn-info" title="Show Notificaton Eves">
                                            <span class="fa fa-check" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('notificaton_eves.notificaton_eves.edit', $notificatonEves->character_id ) }}" class="btn btn-primary" title="Edit Notificaton Eves">
                                            <span class="fa fa-pencil-square-o" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Notificaton Eves" onclick="return confirm(&quot;Click Ok to delete Notificaton Eves.&quot;)">
                                            <span class="fa fa-trash-o" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        <div class="panel-footer">
            {!! $notifeveObjects->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection