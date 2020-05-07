@extends('master')
@section('link')

@section('content')

    <div class="panel panel-default">
  
        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ !empty($corporations->name) ? $corporations->name : 'Corporations' }}</h4>
                <h5 class="mt-5 mb-5">{{ !empty($corporations->corpID) ? $corporations->corpID : 'ID' }}</h5>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('corporations.corporations.index') }}" class="btn btn-primary" title="Show All Corporations">
                    <span class="fa fa-check" aria-hidden="true"></span>
                </a>

                <a href="{{ route('corporations.corporations.create') }}" class="btn btn-success" title="Create New Corporations">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('corporations.corporations.update', $corporations->corpID) }}" id="edit_corporations_form" name="edit_corporations_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            <input name="tracked" type="hidden" value="{{old('tracked', optional($corporations)->tracked)}}">
            @include ('corporations.form', [
                                        'corporations' => $corporations,
                                      ])
            
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Update">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection