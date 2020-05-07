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
                <h4 class="mt-5 mb-5">Corporations</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('corporations.corporations.create') }}" class="btn btn-success" title="Create New Corporations">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>
        
        @if(count($corporationsObjects) == 0)
            <div class="panel-body text-center">
                <h4>No Corporations Available.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Corporation ID</th>
                            <th>Name</th>
                            <th>Tracked</th>
                            <th>Token</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($corporationsObjects as $corporations)
                        <tr>
                            <td>{{ $corporations->corpID }}</td>
                            <td>{{ $corporations->name }}</td>
                            <td>{{ ($corporations->tracked==1)?'Tracked':'' }}</td>
                            <td>{{ (!is_null($corporations->token))?'Has token':'' }}</td>

                            <td>

                                <form method="POST" action="{!! route('corporations.corporations.destroy', $corporations->corpID) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('corporations.corporations.show', $corporations->corpID ) }}" class="btn btn-info" title="Show Corporations">
                                            <span class="fa fa-check" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('corporations.corporations.edit', $corporations->corpID ) }}" class="btn btn-primary" title="Edit Corporations">
                                            <span class="fa fa-pencil-square-o" aria-hidden="true"></span>
                                        </a>
@hasrole('admin')
                                        <button type="submit" class="btn btn-danger" title="Delete Corporations" onclick="return confirm(&quot;Click Ok to delete Corporations.&quot;)">
                                            <span class="fa fa-trash-o" aria-hidden="true"></span>
                                        </button>
@endrole
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
            {!! $corporationsObjects->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection