@section('content')

<div class="container">
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.users.index') }}">Users</a></li>
		<li class="active">Roles &nbsp;
			<a href="{{ route('admin.roles.create') }}" class="btn btn-xs btn-success" data-toggle="modal" data-target="#albaModal"><i class="fa fa-plus-circle"></i> New</a></li>
		@if($collection->count())
		<span class="pull-right text-muted">
			Showing {{$paginator->getFrom()}} to {{$paginator->getTo()}} of {{$paginator->getTotal()}}
		</span>
		@endif
		<span class="clearfix"></span>
	</ol>

	@include('alba::core.errors')

	<div class="table-responsive">
		<table class="table table-striped table-hover">
	  		<thead>
	  			<tr>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'id']) }}">ID</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'name']) }}">Name</a></th>
	  				<th>Permissions</th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'users']) }}">Users</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'updated_at']) }}">Last Updated</a></th>
	  				<th>Actions</th>
	  			</tr>
	  		</thead>
	  		<tbody>
	  			@if ($collection->count())
		  			@foreach ($collection as $item)
		  			<tr>
		  				<td><a href="{{ route('admin.roles.show', $item->id) }}">{{ $item->id }}</a></td>
		  				<td><a href="{{ route('admin.roles.show', $item->id) }}">{{ $item->name }}</a></td>
		  				<td>
		  					<?php
	  						$perms = [];
		  					foreach($item->perms as $permission):
		  						$perms[] = '<a href="'.route('admin.roles.index').'?permissions='.$permission->id.'">'.$permission->display_name.'</a>';
		  					endforeach;
		  					echo implode(', ', $perms);
		  					?>
		  				</td>
		  				<td><a href="{{ route('admin.users.index') }}?roles={{ $item->id }}">{{ $item->users->count() }}</a></td>
		  				<td>{{ $item->timeSinceUpdated }}</td>
		  				<td>
		  					<div class="btn-group">
							  <a href="{{ route('admin.roles.show', $item->id) }}" class="btn btn-sm btn-default">
							  	<i class="fa fa-eye fa-fw"></i> View</a>
							  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
							    <span class="caret"></span>
							    <span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu pull-right" role="menu">
							    <li><a href="{{ route('admin.roles.edit', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-pencil fa-fw"></i> Edit Role</a></li>
							    <li><a href="{{ route('admin.roles.destroy.confirm', $item->id) }}" data-toggle="modal" data-target="#albaModal">
							    	<i class="fa fa-trash-o fa-fw"></i> Delete</a></li>
							  </ul>
							</div>
						</td>
					</tr>
					@endforeach
				@else
					<tr class="warning">
						<td colspan="6">@lang('alba::role.errors.no_results')</td>
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