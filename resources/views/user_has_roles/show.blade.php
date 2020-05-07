@extends('master')
@section('link')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($userHasRoles->user_id) ? $userHasRoles->User->name : 'User Has Roles' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('user_has_roles.user_has_roles.destroy', $userHasRoles->role_id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('user_has_roles.user_has_roles.index') }}" class="btn btn-primary" title="Show All User Has Roles">
                        <span class="fa fa-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('user_has_roles.user_has_roles.create') }}" class="btn btn-success" title="Create New User Has Roles">
                        <span class="fa fa-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('user_has_roles.user_has_roles.edit', [$userHasRoles->user_id,$userHasRoles->role_id] ) }}" class="btn btn-primary" title="Edit User Has Roles">
                        <span class="fa fa-pencil-square-o" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete User Has Roles" onclick="return confirm(&quot;Click Ok to delete User Has Roles.?&quot;)">
                        <span class="fa fa-trash-o" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Role</dt>
            <dd>{{ optional($userHasRoles->Role)->name }}</dd>
            <dt>User</dt>
            <dd>{{ optional($userHasRoles->User)->name }}</dd>

        </dl>

    </div>
</div>

@endsection