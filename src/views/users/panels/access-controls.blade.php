@if(isset($user) && Entrust::can('module_users'))
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
						<a href="{{ route('admin.users.reset-password.confirm', $user->id) }}" class="btn btn-sm btn-block btn-warning" data-toggle="modal" data-target="#albaModal">
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