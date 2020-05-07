@if(Session::has('userid'))
<div class="form-group {{ $errors->has('character_id') ? 'has-error' : '' }}">
    <label for="character_id" class="col-md-2 control-label">Character ID</label>
    <div class="col-md-2">
        <input class="form-control" name="character_id" type="numeric" id="character_id" value="{{ session('userid') }}" maxlength="32" placeholder="Enter id here..." readonly>
        {!! $errors->first('character_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@endif
<div class="form-group {{ $errors->has('enabled') ? 'has-error' : '' }}">
    <label for="enabled" class="col-md-2 control-label">Enabled</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="enabled_1">
            	<input id="enabled_1" class="" name="enabled" type="checkbox" value="1" {{ old('enabled', optional($notifeves)->enabled) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('enabled', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
    <label for="type" class="col-md-2 control-label">Type</label>
    <div class="col-md-10">
        <textarea class="form-control" name="type" cols="50" rows="5" id="type" placeholder="Enter type here...">{{ old('type', optional($notifeves)->type) }}</textarea>
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('token') ? 'has-error' : '' }}">
    <label for="token" class="col-md-2 control-label">Type</label>
    <div class="col-md-10">
        <input class="form-control" name="token" id="token" type="text" placeholder="Enter token here..." value="{{ Session::has('token')?session('token'):old('token', optional($notifeves)->token) }}">
        {!! $errors->first('token', '<p class="help-block">:message</p>') !!}
        <a href="{{ route('login3',1) }}">Get token</a>
    </div>
</div>
