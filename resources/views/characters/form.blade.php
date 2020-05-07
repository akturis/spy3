
<div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
    <label for="name" class="col-md-2 control-label">Name</label>
    <div class="col-md-10">
        <input class="form-control" name="name" type="text" id="name" value="{{ old('name', optional($characters)->name) }}" maxlength="256" placeholder="Enter name here...">
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('startDateTime') ? 'has-error' : '' }}">
    <label for="startDateTime" class="col-md-2 control-label">Start Date Time</label>
    <div class="col-md-10">
        <input class="form-control" name="startDateTime" type="text" id="startDateTime" value="{{ old('startDateTime', optional($characters)->startDateTime) }}" placeholder="Enter start date time here...">
        {!! $errors->first('startDateTime', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
    <label for="roles" class="col-md-2 control-label">Roles</label>
    <div class="col-md-10">
        <input class="form-control" name="roles" type="text" id="roles" value="{{ old('roles', optional($characters)->roles) }}" maxlength="20" placeholder="Enter roles here...">
        {!! $errors->first('roles', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
    <label for="title" class="col-md-2 control-label">Title</label>
    <div class="col-md-10">
        <input class="form-control" name="title" type="text" id="title" value="{{ old('title', optional($characters)->title) }}" maxlength="512" placeholder="Enter title here...">
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('corporationID') ? 'has-error' : '' }}">
    <label for="corporationID" class="col-md-2 control-label">Corporation I D</label>
    <div class="col-md-10">
        <input class="form-control" name="corporationID" type="number" id="corporationID" value="{{ old('corporationID', optional($characters)->corporationID) }}" min="-2147483648" max="2147483647" required="true" placeholder="Enter corporation i d here...">
        {!! $errors->first('corporationID', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('SS') ? 'has-error' : '' }}">
    <label for="SS" class="col-md-2 control-label">S S</label>
    <div class="col-md-10">
        <input class="form-control" name="SS" type="number" id="SS" value="{{ old('SS', optional($characters)->SS) }}" min="-999999" max="999999" required="true" placeholder="Enter s s here..." step="any">
        {!! $errors->first('SS', '<p class="help-block">:message</p>') !!}
    </div>
</div>

