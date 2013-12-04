@section('modal-title')

Confirm Deactivation

@stop

@section('modal-body')

<p>Deactivating a user account will change the active status to "deactivated" and will prevent the user from being able to log in. An administrator can re-activate the account at a later time.</p>
<p><strong>Are you sure that you want to deactivate {{$user->fullName}}?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.deactivate', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Deactivate</button>
{{ Form::close() }}

@stop