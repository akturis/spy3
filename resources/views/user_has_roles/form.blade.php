
<div class="form-group {{ $errors->has('role_id') ? 'has-error' : '' }}">
    <label for="role_id" class="col-md-2 control-label">Role</label>
    <div class="col-md-10">
        <select class="form-control" id="role_id" name="role_id" required="true">
        	    <option value="" style="display: none;" {{ old('role_id', optional($userHasRoles)->role_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select role</option>
        	@foreach ($Roles as $key => $Role)
			    <option value="{{ $key }}" {{ old('role_id', optional($userHasRoles)->role_id) == $key ? 'selected' : '' }}>
			    	{{ $Role }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('user_id') ? 'has-error' : '' }}">
    <label for="user_id" class="col-md-2 control-label">User</label>
    <div class="col-md-10">
        <select class="form-control" id="user_id" name="user_id" required="true">
        	    <option value="" style="display: none;" {{ old('user_id', optional($userHasRoles)->user_id ?: '') == '' ? 'selected' : '' }} disabled selected>Select user</option>
        	@foreach ($Users as $key => $User)
			    <option value="{{ $key }}" {{ old('user_id', optional($userHasRoles)->user_id) == $key ? 'selected' : '' }}>
			    	{{ $User }}
			    </option>
			@endforeach
        </select>
        
        {!! $errors->first('user_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>

