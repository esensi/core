@section('content')

<div class="container">

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.users.index') }}">Users</a></li>
		<li><a href="{{ route('admin.roles.index') }}">Roles</a></li>
		<li class="active">{{ $role->name }} &nbsp;
			<a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#albaModal"><i class="fa fa-pencil"></i> Edit</a> &nbsp;
			<a href="{{ route('admin.roles.destroy.confirm', $role->id) }}" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#albaModal"><i class="fa fa-trash-o"></i> Delete</a>
		</li>
		<small class="pull-right text-muted">
			<i class="fa fa-clock-o"></i> Created {{ $role->timeSinceCreated }} &nbsp;
			<i class="fa fa-clock-o"></i> Last Updated {{ $role->timeSinceUpdated }}
		</small>
	</ol>
	
	@include('alba::core.errors')
	
	<div class="row">
		
		@if(Entrust::can('module_permissions') && $role->perms->count())
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">Permissions Assigned to This Role
						<span class="badge badge-default pull-right">{{$role->perms->count()}}</span>
					</h3>
				</div>
				<div class="panel-body">
					<?php
  						$perms = [];
	  					foreach($role->perms as $permission):
	  						$perms[] = '<a href="'.route('admin.permissions.show', $permission->id).'">'.$permission->display_name.'</a>';
	  					endforeach;
	  					echo implode(', ', $perms);
	  				?>
				</div>
			</div>
		</div>
		@endif

		@if(Entrust::can('module_users') && $role->users->count())
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">Users Having This Role
						<span class="badge badge-default pull-right">{{$role->users->count()}}</span>
					</h3>
				</div>
				<div class="panel-body">
					<?php
  						$users = [];
	  					foreach($role->users as $user):
	  						$users[] = '<a href="'.route('admin.users.show', $user->id).'">'.$user->fullName.'</a>';
	  					endforeach;
	  					echo implode(', ', $users);
	  				?>
				</div>
			</div>
		</div>
		@endif

	</div>

</div>

@stop