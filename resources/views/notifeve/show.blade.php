@extends('master')
@section('link')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($title) ? $title : 'Notificaton Eves' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('notificaton_eves.notificaton_eves.destroy', $notificatonEves->character_id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('notificaton_eves.notificaton_eves.index') }}" class="btn btn-primary" title="Show All Notificaton Eves">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('notificaton_eves.notificaton_eves.create') }}" class="btn btn-success" title="Create New Notificaton Eves">
                        <span class="fa fa-check" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('notificaton_eves.notificaton_eves.edit', $notificatonEves->character_id ) }}" class="btn btn-primary" title="Edit Notificaton Eves">
                        <span class="fa fa-pencil-square-o" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Notificaton Eves" onclick="return confirm(&quot;Click Ok to delete Notificaton Eves.?&quot;)">
                        <span class="fa fa-trash-o" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Enabled</dt>
            <dd>{{ ($notificatonEves->enabled) ? 'Yes' : 'No' }}</dd>
            <dt>Type</dt>
            <dd>{{ $notificatonEves->type }}</dd>

        </dl>

    </div>
</div>

@endsection