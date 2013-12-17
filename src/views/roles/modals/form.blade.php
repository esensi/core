@section('modal-title')

{{ isset($role) ? 'Edit' : 'Create' }} Role

@stop

@section('modal-body')

<p>Roles are just convenient ways to assign a collection of permission to a user. Users can be assigned to multiple roles. The user has the abilities defined by the intersection of all those permissions assigned to the user's roles.</p>

<hr>

{{ Form::open([ 'route' => (isset($role) ? ['admin.roles.update', $role->id] : ['admin.roles.store']) ]) }}

    <div class="row">
	    <div class="col-sm-6">
	        {{ Form::text('name', isset($role) ? $role->name : null, ['placeholder' => 'Role Name', 'class' => 'form-control']) }}
	    </div>
	</div>

{{ Form::close() }}

@stop

@section('modal-footer')

<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="button" onclick="$(this).parents('.modal-content').find('form').submit()" class="btn btn-primary">Save</button>

@stop