@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Role Has Permissions' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('role_has_permissions.role_has_permissions.destroy', $roleHasPermissions->permission_id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('role_has_permissions.role_has_permissions.index') }}" class="btn btn-primary" title="Show All Role Has Permissions">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('role_has_permissions.role_has_permissions.create') }}" class="btn btn-success" title="Create New Role Has Permissions">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('role_has_permissions.role_has_permissions.edit', $roleHasPermissions->permission_id ) }}" class="btn btn-primary" title="Edit Role Has Permissions">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Role Has Permissions" onclick="return confirm(&quot;Click Ok to delete Role Has Permissions.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Role</dt>
            <dd>{{ optional($roleHasPermissions->Role)->name }}</dd>

        </dl>

    </div>
</div>

@endsection