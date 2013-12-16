@section('content')

<div class="container">

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.users.index') }}">Users</a></li>
		<li><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
		<li class="active">{{ $permission->display_name }} ({{ $permission->name}}) &nbsp;
			<a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn btn-xs btn-success" data-toggle="modal" data-target="#albaModal"><i class="fa fa-pencil"></i> Edit</a>
		</li>
		<small class="pull-right text-muted">
			<i class="fa fa-clock-o"></i> Created {{ $permission->timeSinceCreated }} &nbsp;
			<i class="fa fa-clock-o"></i> Last Updated {{ $permission->timeSinceUpdated }}
		</small>
	</ol>
	
	@include('alba::core.errors')
	
	<div class="row">
		
		@if(Entrust::can('module_roles'))
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">Roles Having This Permission
						<span class="badge badge-default pull-right">{{$permission->roles->count()}}</span>
					</h3>
				</div>
				<div class="panel-body">
					<?php
  						$roles = [];
	  					foreach($permission->roles as $role):
	  						$roles[] = '<a href="'.route('admin.permissions.index').'?roles='.$role->id.'">'.$role->name.'</a>';
	  					endforeach;
	  					echo implode(', ', $roles);
	  				?>
				</div>
			</div>
		</div>
		@endif

		@if(Entrust::can('module_users'))
		<div class="col-sm-6">
			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<h3 class="panel-title">Users Having This Permission
						<span class="badge badge-default pull-right">{{$permission->users->count()}}</span>
					</h3>
				</div>
				<div class="panel-body">
					<?php
  						$users = [];
	  					foreach($permission->users as $user):
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