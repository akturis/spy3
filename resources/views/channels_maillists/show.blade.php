@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Channels Maillist' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('channels_maillists.channels_maillist.destroy', $channelsMaillist->channel_id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('channels_maillists.channels_maillist.index') }}" class="btn btn-primary" title="Show All Channels Maillist">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('channels_maillists.channels_maillist.create') }}" class="btn btn-success" title="Create New Channels Maillist">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('channels_maillists.channels_maillist.edit', $channelsMaillist->channel_id ) }}" class="btn btn-primary" title="Edit Channels Maillist">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Channels Maillist" onclick="return confirm(&quot;Click Ok to delete Channels Maillist.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Maillist</dt>
            <dd>{{ $channelsMaillist->maillist }}</dd>

        </dl>

    </div>
</div>

@endsection