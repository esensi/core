@section('modal-title')

Confirm Password Reset

@stop

@section('modal-body')

<p>Resetting a user's password will send an email to the user with a special reset link that bares a unique reset token. When the user clicks that link, it will prompt them to set a new password for their account. While the account is pending password reset, the user will be deactivated and will not be able to log in.</p>
<p><strong>Are you sure that you want to reset the password for {{$user->fullName}}?</strong></p>

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.reset-password', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">Reset Password</button>
{{ Form::close() }}

@stop