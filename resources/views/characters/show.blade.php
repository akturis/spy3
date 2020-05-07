@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($characters->name) ? $characters->name : 'Characters' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('characters.characters.destroy', $characters->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('characters.characters.index') }}" class="btn btn-primary" title="Show All Characters">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('characters.characters.create') }}" class="btn btn-success" title="Create New Characters">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('characters.characters.edit', $characters->id ) }}" class="btn btn-primary" title="Edit Characters">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Characters" onclick="return confirm(&quot;Click Ok to delete Characters.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $characters->name }}</dd>
            <dt>Start Date Time</dt>
            <dd>{{ $characters->startDateTime }}</dd>
            <dt>Roles</dt>
            <dd>{{ $characters->roles }}</dd>
            <dt>Title</dt>
            <dd>{{ $characters->title }}</dd>
            <dt>Corporation I D</dt>
            <dd>{{ $characters->corporationID }}</dd>
            <dt>S S</dt>
            <dd>{{ $characters->SS }}</dd>

        </dl>

    </div>
</div>

@endsection