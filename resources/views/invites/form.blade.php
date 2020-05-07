
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($invites)->name) }}" minlength="1" maxlength="32" required="true" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('invited') ? 'has-error' : '' }}">
    <label for="invited" class="col-md-2 control-label">Invited</label>
    <div class="col-md-10">
        <div class="checkbox">
            <label for="invited_1">
            	<input id="invited_1" class="" name="invited" type="checkbox" value="1" {{ old('invited', optional($invites)->invited) == '1' ? 'checked' : '' }}>
                Yes
            </label>
        </div>

        {!! $errors->first('invited', '<p class="help-block">:message</p>') !!}
    </div>
</div>

