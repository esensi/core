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

			@foreach(array_slice(Config::get('alba::user.panels'), 1) as $panel)
                @include($panel)
            @endforeach

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
			                                ['class' => 'form-control multiselect', 'size' => 1, 'data-default-text' => 'No Roles', 'multiple' => true]) }}
									</div>
									<div class="col-xs-5 col-sm-12 col-md-5">
										<input class="btn btn-sm btn-primary btn-block" type="submit" value="Assign Roles">
									</div>
								</div>
							</fieldset>
						  	{{ Form::close() }}
						@else
						<div class="alert alert-warning" style="margin-bottom:0">
							@lang('alba::user.messages.assign_roles_later')
						</div>
						@endif
					</div>
				</div>
			@endif

			@include('alba::users.panels.permissions')

			@include('alba::users.panels.access-controls')

		</div>
	</div>
</div>

@stop