
<div class="form-group {{ $errors->has('id') ? 'has-error' : '' }}">
    <label for="id" class="col-md-2 control-label">Alt</label>
    <div class="col-md-10">
        <select class="form-control" id="id" name="id" required="true">
        	    <option value="" style="display: none;" {{ old('id', optional($alts)->id ?: '') == '' ? 'selected' : '' }} disabled selected>Select alt</option>
        	@foreach ($mains as $key => $main)
			    <option value="{{ $key }}" {{ old('id', optional($alts)->id) == $key ? 'selected' : '' }}>
			    	{{ $main }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('main_id') ? 'has-error' : '' }}">
    <label for="main_id" class="col-md-2 control-label">Main</label>
    <div class="col-md-10">
        <select class="form-control" id="main_id" name="main_id" required="true">
        	    <option value="" style="display: none;" {{ old('main_id', optional($alts)->main_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select main</option>
        	@foreach ($mains as $key => $main)
			    <option value="{{ $key }}" {{ old('main_id', optional($alts)->main_id) == $key ? 'selected' : '' }}>
			    	{{ $main }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('main_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

