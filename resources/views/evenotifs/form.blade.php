
<div class="form-group {{ $errors->has('sender_id') ? 'has-error' : '' }}">
    <label for="sender_id" class="col-md-2 control-label">Sender</label>
    <div class="col-md-10">
        <select class="form-control" id="sender_id" name="sender_id">
        	    <option value="" style="display: none;" {{ old('sender_id', optional($evenotifs)->sender_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select sender</option>
        	@foreach ($senders as $key => $sender)
			    <option value="{{ $key }}" {{ old('sender_id', optional($evenotifs)->sender_id) == $key ? 'selected' : '' }}>
			    	{{ $sender }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('sender_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sender_type') ? 'has-error' : '' }}">
    <label for="sender_type" class="col-md-2 control-label">Sender Type</label>
    <div class="col-md-10">
        <textarea class="form-control" name="sender_type" cols="50" rows="10" id="sender_type" placeholder="Enter sender type here...">{{ old('sender_type', optional($evenotifs)->sender_type) }}</textarea>
        {!! $errors->first('sender_type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('text') ? 'has-error' : '' }}">
    <label for="text" class="col-md-2 control-label">Text</label>
    <div class="col-md-10">
        <textarea class="form-control" name="text" cols="50" rows="10" id="text" placeholder="Enter text here...">{{ old('text', optional($evenotifs)->text) }}</textarea>
        {!! $errors->first('text', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
    <label for="type" class="col-md-2 control-label">Type</label>
    <div class="col-md-10">
        <input class="form-control" name="type" type="text" id="type" value="{{ old('type', optional($evenotifs)->type) }}" maxlength="32" placeholder="Enter type here...">
        {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
    </div>
</div>

