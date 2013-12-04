@section('modal-title')

Confirm Activation

@stop

@section('modal-body')

<p>Activating a user account will change the active status to "activated" and will allow the user to log in. An administrator can deactivate the account at a later time.</p>
<p><strong>Are you sure that you want to activate {{$user->fullName}}?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.activate', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Activate</button>
{{ Form::close() }}

@stop