@section('modal-title')

Confirm Block

@stop

@section('modal-body')

<p>Blocking a user account will change the blocked status to "blocked" and will prevent the user from being able to log in. An administrator can unblock the account at a later time.</p>
<p><strong>Are you sure that you want to block {{$user->fullName}}?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.block', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Block</button>
{{ Form::close() }}

@stop