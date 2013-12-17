@if(isset($user) && Entrust::can('module_roles'))
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			@if(!$user->trashed())
			<a href="{{route('admin.users.edit.roles', $user->id)}}" class="btn btn-sm btn-primary pull-right" data-toggle="modal" data-target="#albaModal">
				<i class="fa fa-key fa-fw"></i> Assign Roles</a>
			@endif
			<h3 class="panel-title">Assigned Roles</h3>
		</div>
		<div class="panel-body">
			<?php
				$roles = [];
				foreach($user->roles as $role):
					$roles[] = '<a href="'.route('admin.roles.show', $role->id).'">'.$role->name.'</a>';
				endforeach;
				if($roles):
				?>
					{{ implode(', ', $roles) }}
				<?php else: ?>
					<div class="alert alert-warning" style="margin-bottom: 0">@lang('alba::user.errors.no_roles')</div>
				<?php endif;
			?>
		</div>
	</div>
@endif