@section('content')

<div class="container">

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.users.index') }}">Users</a></li>
		<li class="active">{{ $user->fullName }}</li>
		<small class="pull-right text-muted">
			<i class="fa fa-clock-o"></i> Created {{ $user->timeSinceCreated }} &nbsp;
			<i class="fa fa-clock-o"></i> Last Updated {{ $user->timeSinceUpdated }}
		</small>
	</ol>
	
	@include('alba::core.errors')
	
	<div class="row">
		<div class="col-sm-8">
			
			@foreach(Config::get('alba::user.panels') as $panel)
                @include($panel)
            @endforeach

		</div>
		<div class="col-sm-4">
			@include('alba::users.panels.roles')

			@include('alba::users.panels.permissions')

			@include('alba::users.panels.access-controls')
		</div>
	</div>
</div>

@stop