@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($invites->name) ? $invites->name : 'Invites' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('invites.invites.destroy', $invites->character_id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('invites.invites.index') }}" class="btn btn-primary" title="Show All Invites">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('invites.invites.create') }}" class="btn btn-success" title="Create New Invites">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('invites.invites.edit', $invites->character_id ) }}" class="btn btn-primary" title="Edit Invites">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Invites" onclick="return confirm(&quot;Click Ok to delete Invites.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $invites->name }}</dd>
            <dt>Invited</dt>
            <dd>{{ ($invites->invited) ? 'Yes' : 'No' }}</dd>

        </dl>

    </div>
</div>

@endsection