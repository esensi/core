@section('modal-title')

Confirm Restore

@stop

@section('modal-body')

<p>Restoring a user account will "untrash" the user and restore all privileges that the user had prior to being deleted. Only administrators can restore a user. Remember there was probably a good reason that the user was trashed to begin with!</p>
<p><strong>Are you sure that you want to restore {{$user->fullName}}?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.restore', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Restore</button>
{{ Form::close() }}

@stop