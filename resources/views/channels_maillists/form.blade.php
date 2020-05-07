
<div class="form-group {{ $errors->has('maillist') ? 'has-error' : '' }}">
    <label for="maillist" class="col-md-2 control-label">Maillist</label>
    <div class="col-md-10">
        <input class="form-control" name="maillist" type="text" id="maillist" value="{{ old('maillist', optional($channelsMaillist)->maillist) }}" minlength="1" required="true" placeholder="Enter maillist here...">
        {!! $errors->first('maillist', '<p class="help-block">:message</p>') !!}
    </div>
</div>

