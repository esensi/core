@section('modal-title')

Assign Roles to {{ $user->fullName }}

@stop

@section('modal-body')

<p>Roles are just a convenient ways to assign a collection of permissions to a user. Users can be assigned multiple roles and thereby are granted all the permissions associated to those roles.</p>

<hr>

{{ Form::open([ 'route' => ['admin.users.assign.roles', $user->id] ]) }}

    {{ Form::select('roles[]', $rolesOptions, $roles,
		['class' => 'form-control multiselect', 'size' => 1, 'data-default-text' => 'No Roles Assigned', 'multiple' => true]) }}

{{ Form::close() }}

@stop

@section('modal-footer')

<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="button" onclick="$(this).parents('.modal-content').find('form').submit()" class="btn btn-primary">Assign Roles</button>

@stop