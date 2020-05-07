@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Evenotifs' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('evenotifs.evenotifs.destroy', $evenotifs->notification_id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('evenotifs.evenotifs.index') }}" class="btn btn-primary" title="Show All Evenotifs">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('evenotifs.evenotifs.create') }}" class="btn btn-success" title="Create New Evenotifs">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('evenotifs.evenotifs.edit', $evenotifs->notification_id ) }}" class="btn btn-primary" title="Edit Evenotifs">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Evenotifs" onclick="return confirm(&quot;Click Ok to delete Evenotifs.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Sender</dt>
            <dd>{{ optional($evenotifs->sender)->id }}</dd>
            <dt>Sender Type</dt>
            <dd>{{ $evenotifs->sender_type }}</dd>
            <dt>Text</dt>
            <dd>{{ $evenotifs->text }}</dd>
            <dt>Type</dt>
            <dd>{{ $evenotifs->type }}</dd>
            <dt>Created At</dt>
            <dd>{{ $evenotifs->created_at }}</dd>
            <dt>Updated At</dt>
            <dd>{{ $evenotifs->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection