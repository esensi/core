@section('content')

<div class="container">
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li class="active">Users</li>
		<span class="pull-right text-muted">
			Showing {{$paginator->getFrom()}} to {{$paginator->getTo()}} of {{$paginator->getTotal()}}
		</span>
	</ol>

	@include('alba::core.errors')

	<div class="table-responsive table-striped table-hover">
		<table class="table">
	  		<thead>
	  			<tr>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'id']) }}">ID</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'email']) }}">Email</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'name']) }}">Name</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'active']) }}">Active</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'blocked']) }}">Blocked</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'authenticated_at']) }}">Last Login</a></th>
	  				<th>Actions</th>
	  			</tr>
	  		</thead>
	  		<tbody>
	  			@if ($collection->count())
		  			@foreach ($collection as $item)
		  			<tr>
		  				<td><a href="{{ route('admin.users.show', $item->id) }}">{{ $item->id }}</a></td>
		  				<td><a href="mailto:{{ $item->email }}">{{ $item->email }}</a></td>
		  				<td><a href="{{ route('admin.users.show', $item->id) }}">{{ $item->fullName }}</a></td>
		  				<td><a href="{{ HTML::paginationUrl($paginator, ['active' => $item->active]) }}">{{ $item->activeStatus }}</a></td>
		  				<td><a href="{{ HTML::paginationUrl($paginator, ['blocked' => $item->blocked]) }}">{{ $item->blockedStatus }}</a></td>
		  				<td>{{ $item->timeSinceLastAuthenticated }}</td>
		  				<td>
		  					<div class="btn-group">
							  <a href="{{ route('admin.users.show', $item->id) }}" type="button" class="btn btn-sm btn-default">
							  	<i class="fa fa-eye fa-fw"></i> View</a>
							  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
							    <span class="caret"></span>
							    <span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							    <li><a href="{{ route('admin.users.edit', $item->id) }}">
							    	<i class="fa fa-pencil fa-fw"></i> Edit User</a></li>
							    @if($item->isLoginAllowed())
							    <li><a href="{{ route('admin.users.reset-activation.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-envelope-o fa-fw"></i> Reset Activation</a></li>
							    @endif
							    @if($item->isPasswordResetAllowed())
							    <li><a href="{{ route('admin.users.reset-password.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-lock fa-fw"></i> Reset Password</a></li>
							    @endif
							    <li class="divider"></li>
							    <li class="dropdown-header">Access Controls</li>
							    @if(Entrust::can('module_roles'))
							    	<li><a href="{{ route('admin.users.edit.roles', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    		<i class="fa fa-key fa-fw"></i> Assign Roles</a></li>
							    @endif
							    @if($item->active)
							    <li><a href="{{ route('admin.users.deactivate.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-times-circle-o fa-fw"></i> Deactivate</a></li>
							    @else
							    <li><a href="{{ route('admin.users.activate.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-check-circle-o fa-fw"></i> Activate</a></li>
							    @endif
							    @if($item->blocked)
							    <li><a href="{{ route('admin.users.unblock.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-power-off fa-fw"></i> Unblock</a></li>
							    @else
							    <li><a href="{{ route('admin.users.block.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-ban fa-fw"></i> Block</a></li>
							    @endif
							    <li><a href="{{ route('admin.users.destroy.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-trash-o fa-fw"></i> Delete</a></li>
							    @if($item->trashed())
							    <li><a href="{{ route('admin.users.restore.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-refresh fa-fw"></i> Restore</a></li>
							    @endif
							  </ul>
							</div>
						</td>
					</tr>
					@endforeach
				@else
					<tr class="warning">
						<td colspan="7">@lang('alba::user.errors.no_results')</td>
					</tr>
				@endif
	  		</tbody>
		</table>
		@if ($collection->count())
			{{ $paginator->links() }}
		@endif
	</div>
</div>

@stop