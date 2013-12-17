@section('modal-title')

{{ isset($role) ? 'Edit' : 'Create' }} Role

@stop

@section('modal-body')

<p>Roles are just a convenient ways to assign a collection of permissions to a user. Users can be assigned multiple roles and thereby are granted all the permissions associated to those roles.</p>

<hr>

{{ Form::open([ 'route' => (isset($role) ? ['admin.roles.update', $role->id] : ['admin.roles.store']) ]) }}

    <div class="row">
	    <div class="col-sm-4">
	        {{ Form::text('name', isset($role) ? $role->name : null, ['placeholder' => 'Role Name', 'class' => 'form-control']) }}
	    </div>
	    <div class="col-sm-8">
	        {{ Form::select('permissions[]', $permissionsOptions, $permissions,
				['class' => 'form-control multiselect', 'size' => 1, 'data-default-text' => 'No Permissions Assigned', 'multiple' => true]) }}
	    </div>
	</div>

{{ Form::close() }}

@stop

@section('modal-footer')

<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="button" onclick="$(this).parents('.modal-content').find('form').submit()" class="btn btn-primary">Save</button>

@stop