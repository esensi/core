@section('modal-title')

Confirm Activation Reset

@stop

@section('modal-body')

<p>Resetting a user's activation status will send an email to the user with a special activation link that bares a unique activation token. When the user clicks that link, it will confirm their email address and activate their account again. While the account is pending activation, the user will be deactivated and will not be able to log in.</p>
<p><strong>Are you sure that you want to reset activation for {{$user->fullName}}?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.reset-activation', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Reset Activation</button>
{{ Form::close() }}

@stop