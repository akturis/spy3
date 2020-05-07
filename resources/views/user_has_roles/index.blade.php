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
                <h4 class="mt-5 mb-5">User Has Roles</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('user_has_roles.user_has_roles.create') }}" class="btn btn-success" title="Create New User Has Roles">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>
        
        @if(count($userHasRolesObjects) == 0)
            <div class="panel-body text-center">
                <h4>No User Has Roles Available.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>Role</th>
                            <th>User</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($userHasRolesObjects as $userHasRoles)
                        <tr>
                            <td>{{ optional($userHasRoles->Role)->name }}</td>
                            <td>{{ optional($userHasRoles->User)->name }}</td>

                            <td>

                                <form method="POST" action="{{ route('user_has_roles.user_has_roles.destroy') }}" accept-charset="UTF-8">
                                <input type="hidden" name="user_id" value="{{$userHasRoles->user_id}}">
                                <input type="hidden" name="role_id" value="{{$userHasRoles->role_id}}">
                                {{ csrf_field() }}
                                @method('delete')
                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('user_has_roles.user_has_roles.show', [$userHasRoles->user_id,$userHasRoles->role_id] ) }}" class="btn btn-info" title="Show User Has Roles">
                                            <span class="fa fa-check" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('user_has_roles.user_has_roles.edit', [$userHasRoles->user_id,$userHasRoles->role_id]) }}" class="btn btn-primary" title="Edit User Has Roles">
                                            <span class="fa fa-pencil-square-o" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete User Has Roles" onclick="return confirm(&quot;Click Ok to delete User Has Roles.&quot;)">
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
            {!! $userHasRolesObjects->render() !!}
        </div>
        
        @endif
    
    </div>
@endsection