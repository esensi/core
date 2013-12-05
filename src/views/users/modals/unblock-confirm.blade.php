@section('modal-title')

Confirm Unblock

@stop

@section('modal-body')

<p>Unblockiing a user account will change the blocked status to "unblocked" and will allow the user to log in. An administrator can block the account again at a later time. Remember, the user was probably blocked for good reason!</p>
<p><strong>Are you sure that you want to unblock {{$user->fullName}}?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.unblock', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Unblock</button>
{{ Form::close() }}

@stop