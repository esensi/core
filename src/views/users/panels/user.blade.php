@if(Entrust::can('module_users'))
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			@if(!$user->trashed())
			<a href="{{route('admin.users.edit', $user->id)}}" class="btn btn-sm btn-primary pull-right">
					<i class="fa fa-pencil fa-fw"></i> Edit User</a>
			@endif
			<h3 class="panel-title">User Account Information</h3>
		</div>
		<ul class="list-group">
			<li class="list-group-item">
				{{ $user->extendedName }}<br>
				<a href="mailto::{{$user->email}}">{{ $user->email }}</a><br>
			</li>
		</ul>
	</div>
@endif