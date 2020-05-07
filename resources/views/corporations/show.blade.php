@extends('master')
@section('link')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($corporations->name) ? $corporations->name : 'Corporations' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('corporations.corporations.destroy', $corporations->corpID) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('corporations.corporations.index') }}" class="btn btn-primary" title="Show All Corporations">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('corporations.corporations.create') }}" class="btn btn-success" title="Create New Corporations">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('corporations.corporations.edit', $corporations->corpID ) }}" class="btn btn-primary" title="Edit Corporations">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Corporations" onclick="return confirm(&quot;Click Ok to delete Corporations.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Corporation ID</dt>
            <dd>{{ $corporations->corpID }}</dd>
            <dt>Name</dt>
            <dd>{{ $corporations->name }}</dd>
            <dt>Token</dt>
            <dd>{{ $corporations->token }}</dd>

        </dl>

    </div>
</div>

@endsection