
<div class="form-group {{ $errors->has('role_id') ? 'has-error' : '' }}">
    <label for="role_id" class="col-md-2 control-label">Role</label>
    <div class="col-md-10">
        <select class="form-control" id="role_id" name="role_id" required="true">
        	    <option value="" style="display: none;" {{ old('role_id', optional($roleHasPermissions)->role_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select role</option>
        	@foreach ($Roles as $key => $Role)
			    <option value="{{ $key }}" {{ old('role_id', optional($roleHasPermissions)->role_id) == $key ? 'selected' : '' }}>
			    	{{ $Role }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

