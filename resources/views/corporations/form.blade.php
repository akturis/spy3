@if($corporations == null)
<div class="form-group {{ $errors->has('corpID') ? 'has-error' : '' }}">
    <label for="corpID" class="col-md-2 control-label">Corporation ID</label>
    <div class="col-md-2">
        <input class="form-control" name="corpID" type="numeric" id="corpID" value="{{ old('corpID', optional($corporations)->corpID) }}" maxlength="32" placeholder="Enter id here...">
        {!! $errors->first('corpID', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@endif

<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($corporations)->name) }}" maxlength="255" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('short_name') ? 'has-error' : '' }}">
    <label for="short_name" class="col-md-2 control-label">Short Name</label>
    <div class="col-md-3">
        <input class="form-control" name="short_name" type="text" id="short_name" value="{{ old('short_name', optional($corporations)->short_name) }}" maxlength="10" placeholder="Enter name here...">
        {!! $errors->first('short_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('tracked') ? 'has-error' : '' }}">
    <div>
        <label for="tracked" class="col-md-1 control-label">Tracked</label>
        <input class="col-md-1 form-control" name="tracked" type="checkbox" id="tracked" value="1" 
        @if(old('tracked', optional($corporations)->tracked)==1)
        checked
        @endif
        >
            {!! $errors->first('tracked', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('token') ? 'has-error' : '' }}">
    <label for="token" class="col-md-2 control-label">Token</label>
    <div class="col-md-10">
@if(Session::has('token'))
        <input class="form-control" name="token" type="text"  id="token" placeholder="Enter token here..." value ="{{ session('token') }}"></input>
@else    
        <input class="form-control" name="token" type="text"  id="token" placeholder="Enter token here..." value="{{ old('token', optional($corporations)->token) }}"></input>
@endif
        {!! $errors->first('token', '<p class="help-block">:message</p>') !!}
        <a href="{{ route('login2',['corpid' => optional($corporations)->corpID]) }}">Get token</a>
    </div>
</div>


