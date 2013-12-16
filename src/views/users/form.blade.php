@section('content')

<div class="container">

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.users.index') }}">Users</a></li>
		@if(isset($user))
		<li><a href="{{ route('admin.users.show', $user->id) }}">{{ $user->fullName }}</a></li>
		<li class="active">Edit User</li>
		<small class="pull-right text-muted">
			<i class="fa fa-clock-o"></i> Created {{ $user->timeSinceCreated }} &nbsp;
			<i class="fa fa-clock-o"></i> Last Updated {{ $user->timeSinceUpdated }}
		</small>
		@else
		<li class="active">Create User</li>
		@endif
	</ol>
	
	@include('alba::core.errors')
	
	<div class="row">
		<div class="col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">User Account Information</h3>
				</div>
				<div class="panel-body">
					{{ Form::open([ 'route' => isset($user) ? ['admin.users.update', $user->id] : 'admin.users.store' ]) }}
				    <fieldset>
						<div class="row">
							<div class="form-group col-sm-3 col-md-2">
							    {{ Form::select('title', $titlesOptions, isset($user) ? $user->name->title : null, ['class' => 'form-control']) }}
							</div>
							<div class="form-group col-sm-3 col-md-4">
							    {{ Form::text('first_name', isset($user) ? $user->name->first_name : null, ['placeholder' => 'First name', 'class' => 'form-control']) }}
							</div>
							<div class="form-group col-sm-3 col-md-4">
							    {{ Form::text('last_name', isset($user) ? $user->name->last_name : null, ['placeholder' => 'Last name', 'class' => 'form-control']) }}
							</div>
							<div class="form-group col-sm-3 col-md-2">
							    {{ Form::select('suffix', $suffixesOptions, isset($user) ? $user->name->suffix : null, ['class' => 'form-control']) }}
							</div>
						</div>
						<div class="form-group">
						    {{ Form::text('email', isset($user) ? $user->email : null, ['placeholder' => 'E-mail', 'class' => 'form-control']) }}
						</div>
						<input class="btn btn-primary" type="submit" value="Save">
					</fieldset>
				  	{{ Form::close() }}
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			@if(Entrust::can('module_roles'))
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Assigned Roles</h3>
					</div>
					<div class="panel-body">
						@if(isset($user))
							{{ Form::open([ 'route' => ['admin.users.assign.roles', $user->id] ]) }}
						    <fieldset>
								<div class="row">
									<div class="col-xs-7 col-sm-12 col-md-7">
									    {{ Form::select('roles[]', $rolesOptions, $roles,
			                                ['class' => 'form-control multiselect', 'size' => 1, 'data-default-text' => 'No Roles Assigned', 'multiple' => true]) }}
									</div>
									<div class="col-xs-5 col-sm-12 col-md-5">
										<input class="btn btn-sm btn-primary btn-block" type="submit" value="Assign Roles">
									</div>
								</div>
							</fieldset>
						  	{{ Form::close() }}
						@else
						<div class="alert alert-warning" style="margin-bottom:0">You can assign roles after the user has been created. Until then the default user roles will be assigned to the new user.</div>
						@endif
					</div>
				</div>
			@endif

			@if(isset($user) && Entrust::can('module_permissions') && $user->permissions->count())
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Assigned Permissions</h3>
					</div>
					<div class="panel-body">
						<?php
	  						$perms = [];
		  					foreach($user->permissions as $permission):
		  						$perms[] = '<a href="'.route('admin.roles.index').'?permissions='.$permission->id.'">'.$permission->display_name.'</a>';
		  					endforeach;
		  					echo implode(', ', $perms);
		  				?>
					</div>
				</div>
			@endif

			@if(isset($user))
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Access Controls</h3>
				</div>
				<ul class="list-group">
					<li class="list-group-item">
						<div class="row">
							<div class="col-xs-6 col-sm-12 col-md-6">
								<p>{{ $user->loginStatus }}<br>
									<small class="text-muted"><i class="fa fa-clock-o"></i> Last {{ $user->timeSinceLastAuthenticated }}</small></p>
							</div>
							<div class="col-xs-6 col-sm-12 col-md-6">
								@if($user->isActivationAllowed())
								<a href="{{ route('admin.users.reset-activation.confirm', $user->id) }}" class="btn btn-sm btn-block btn-warning" data-toggle="modal" data-target="#albaModal">
								  	<i class="fa fa-envelope-o fa-fw"></i> Send Activation</a>
								@endif
							</div>
						</div>
					</li>
					<li class="list-group-item">
						<div class="row">
							<div class="col-xs-6 col-sm-12 col-md-6">
								<p>{{ $user->passwordStatus }}<br>
									<small class="text-muted"><i class="fa fa-clock-o"></i> {{ $user->timeSinceLastPasswordUpdate }}</small></p>
							</div>
							<div class="col-xs-6 col-sm-12 col-md-6">
								@if($user->isPasswordResetAllowed())
								<a href="{{ route('admin.users.activate.confirm', $user->id) }}" class="btn btn-sm btn-block btn-warning" data-toggle="modal" data-target="#albaModal">
								  	<i class="fa fa-lock fa-fw"></i> Reset Password</a>
								@endif
							</div>
						</div>
					</li>
					<li class="list-group-item">
						<div class="row">
							<div class="col-xs-6 col-sm-12 col-md-6">
								<p>{{ $user->activeStatus }}<br>
									<small class="text-muted"><i class="fa fa-clock-o"></i> {{ $user->timeSinceLastActivated }}</small></p>
							</div>
							<div class="col-xs-6 col-sm-12 col-md-6">
								@if($user->isDeactivationAllowed())
									<a href="{{ route('admin.users.deactivate.confirm', $user->id) }}" class="btn btn-sm btn-block btn-danger" data-toggle="modal" data-target="#albaModal">
										  	<i class="fa fa-times-circle-o fa-fw"></i> Deactivate</a>
								@elseif($user->isActivationAllowed())
									<a href="{{ route('admin.users.activate.confirm', $user->id) }}" class="btn btn-sm btn-block btn-success" data-toggle="modal" data-target="#albaModal">
										  	<i class="fa fa-check-circle-o fa-fw"></i> Activate</a>
								@endif
							</div>
						</div>
					</li>
					<li class="list-group-item">
						<div class="row">
							<div class="col-xs-6 col-sm-12 col-md-6">
								<p>{{ $user->blockedStatus }}</p>
							</div>
							<div class="col-xs-6 col-sm-12 col-md-6">
								@if($user->isUnblockingAllowed())
									<a href="{{ route('admin.users.unblock.confirm', $user->id) }}" class="btn btn-sm btn-block btn-success" data-toggle="modal" data-target="#albaModal">
										  	<i class="fa fa-power-off fa-fw"></i> Unblock</a>
								@elseif($user->isBlockingAllowed())
									<a href="{{ route('admin.users.block.confirm', $user->id) }}" class="btn btn-sm btn-block btn-danger" data-toggle="modal" data-target="#albaModal">
										  	<i class="fa fa-ban fa-fw"></i> Block</a>
								@endif
							</div>
						</div>
					</li>
					@if($user->isTrashingAllowed())
					<li class="list-group-item">
						@if($user->trashed())
						<p>Trashed<br>
							<small class="text-muted"><i class="fa fa-clock-o"></i> {{ $user->timeSinceDeleted }}</small></p>
						<div class="btn-group btn-group-justified">
							<a href="{{ route('admin.users.restore.confirm', $user->id) }}" class="btn btn-sm btn-success" data-toggle="modal" data-target="#albaModal">
							  	<i class="fa fa-refresh fa-fw"></i> Restore</a>
							<a href="{{ route('admin.users.destroy.confirm', $user->id) }}" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#albaModal">
							   	<i class="fa fa-trash-o fa-fw"></i> Delete</a>
						</div>
						@else
						<a href="{{ route('admin.users.destroy.confirm', $user->id) }}" class="btn btn-sm btn-block btn-danger" data-toggle="modal" data-target="#albaModal">
							<i class="fa fa-trash-o fa-fw"></i> Send to Trash</a>
						@endif
					</li>
					@endif
				</ul>
			</div>
			@endif

			@if(isset($user) && $user->tokens->count() && Entrust::can('module_tokens'))
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Token Log</h3>
					</div>
					<table class="table table-striped">
						<thead>
				  			<tr>
				  				<th width="33%">&nbsp;</th>
				  				<th width="33%">Created</th>
				  				<th width="33%">Expires</th>
				  			</tr>
				  		</thead>
				  		<tbody>
				  			@foreach ($user->tokens as $token)
				  			<tr>
				  				<td>
					  				@if($token->isExpired)
					  					{{ $token->type }}
					  				@else
					  					<a href="{{ $token->route }}">{{ $token->type }}</a>
					  				@endif
				  					<br>
				  					<small class="text-muted">{{ substr($token->token, 0, 16) }}...</small></td>
				  				<td><small>{{ $token->timeSinceCreated }}</small></td>
				  				<td><small>{{ $token->timeTillExpires }}</small></td>
							</tr>
							@endforeach
				  		</tbody>
					</table>
				</div>
			@endif
		</div>
	</div>
</div>

@stop