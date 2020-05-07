@extends('master')

@section('content')

<div class="panel panel-default">

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Main</dt>
            <dd>{{ optional($main)->name }}</dd>
        </dl>

    </div>
        @if(count($alts) == 0)
            <div class="panel-body text-center">
                <h4>No Alts Available.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">
            <div class="table-responsive">

                <table class="table table-striped table-sm ">
                    <thead>
                        <tr>
                            <th>Alts</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($alts as $alt)
                        <tr>
                            <td>{{ optional($alt->alt)->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

        @endif
    <div class="btn-group btn-group-sm pull-left" role="group">
        <a href="{{ URL::previous() }}" class="btn btn-primary" title="Back">
            <span class="fa fa-check" aria-hidden="true"></span>
        </a>
    </div>

</div>

@endsection