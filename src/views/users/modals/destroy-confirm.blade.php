@section('modal-title')

Confirm {{ $user->trashed() ? 'Delete' : 'Send to Trash' }}

@stop

@section('modal-body')

<p>Deleting a user account will mark the user as "trashed" and will retain the user's account. A "trashed" user cannot log in and will not show up in non-administrative interfaces: only administrators will be able to see "trashed" users. If the user is "trashed" then only administrators can restore or "untrash" a user's account. To permanantly delete a user's account, the user must be "trashed" first and then an administrator must delete the "trashed" user.</p>
@if ($user->trashed())
<p><strong>Are you sure that you want to delete {{$user->fullName}}?</strong></p>
@else
<p><strong>Are you sure that you want to send {{$user->fullName}} to the trash?</strong></p>
@endif

@stop

@section('modal-footer')

{{ Form::open([ 'route' => ['admin.users.destroy', $user->id] ]) }}
<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-danger">{{ $user->trashed() ? 'Delete' : 'Send to Trash' }}</button>
{{ Form::close() }}

@stop