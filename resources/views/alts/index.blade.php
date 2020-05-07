@extends('master')

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
                <h4 class="mt-5 mb-5">Alts</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('alts.alts.create') }}" class="btn btn-success" title="Create New Alts">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>
        
        @if(count($altsObjects) == 0)
            <div class="panel-body text-center">
                <h4>No Alts Available.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped table-sm ">
                    <thead>
                        <tr>
                            <th>Alt</th>
                            <th>Main</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($altsObjects as $alts)
                        <tr>
                            <td>{{ optional($alts->alt)->name }}</td>
                            <td>{{ optional($alts->main)->name }}</td>

                            <td>

                                <form method="POST" action="{!! route('alts.alts.destroy', $alts->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm pull-right" role="group">
                                        <a href="{{ route('alts.alts.show', $alts->id ) }}" class="btn btn-info" title="Show Alts">
                                            <span aria-hidden="true">{{App\Models\Alts::where('main_id',$alts->main_id)->count()}}</span>
                                        </a>
                                        @role('admin')
                                        <a href="{{ route('alts.alts.edit', $alts->id ) }}" class="btn btn-primary" title="Edit Alts">
                                            <span class="fa fa-pencil-square-o" aria-hidden="true"></span>
                                        </a>
                                        
                                        <button type="submit" class="btn btn-danger" title="Delete Alts" onclick="return confirm(&quot;Click Ok to delete Alts.&quot;)">
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
            {!! $altsObjects->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection