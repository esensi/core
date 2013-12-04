@section('content')

<div class="container">

	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.users.index') }}">Users</a></li>
		<li class="active">{{ $user->fullName }}</li>
	</ol>

	@include('alba::core.errors')

    <p class="lead">This needs to be built out.</p>
</div>

@stop