@section('modal-title')

Confirm Delete

@stop

@section('modal-body')

<p>Deleting a role will permanantly delete the role and consequently the link between the users that have the role with the permissions that have been assigned to the role. Deleting this role will remove these permissions &mdash; <em>{{ implode(', ', $role->perms()->lists('display_name')) }}</em> &mdash; from {{ $role->users->count() }} users. Unless the user otherwise has a role that provides them access to these permissions, the user will no longer be able to access the features that require these permissions.</p>

<p><strong>Are you sure that you want to delete the {{$role->name}} role?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.roles.destroy', $role->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Delete</button>
{{ Form::close() }}

@stop