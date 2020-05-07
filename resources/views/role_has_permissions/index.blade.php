@extends('layouts.app')

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
                <h4 class="mt-5 mb-5">Role Has Permissions</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('role_has_permissions.role_has_permissions.create') }}" class="btn btn-success" title="Create New Role Has Permissions">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>
        
        @if(count($roleHasPermissionsObjects) == 0)
            <div class="panel-body text-center">
                <h4>No Role Has Permissions Available.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Role</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($roleHasPermissionsObjects as $roleHasPermissions)
                        <tr>
                            <td>{{ optional($roleHasPermissions->Role)->name }}</td>

                            <td>

                                <form method="POST" action="{!! route('role_has_permissions.role_has_permissions.destroy', $roleHasPermissions->permission_id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('role_has_permissions.role_has_permissions.show', $roleHasPermissions->permission_id ) }}" class="btn btn-info" title="Show Role Has Permissions">
                                            <span class="glyphicon glyphicon-open" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('role_has_permissions.role_has_permissions.edit', $roleHasPermissions->permission_id ) }}" class="btn btn-primary" title="Edit Role Has Permissions">
                                            <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Role Has Permissions" onclick="return confirm(&quot;Click Ok to delete Role Has Permissions.&quot;)">
                                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
            {!! $roleHasPermissionsObjects->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection