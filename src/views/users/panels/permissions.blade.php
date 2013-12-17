@if(isset($user) && $user->permissions->count() && Entrust::can('module_permissions'))
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Assigned Permissions</h3>
		</div>
		<div class="panel-body">
			<?php
					$perms = [];
					foreach($user->permissions as $permission):
						$perms[] = '<a href="'.route('admin.permissions.show', $permission->id).'">'.$permission->display_name.'</a>';
					endforeach;
					echo implode(', ', $perms);
				?>
		</div>
	</div>
@endif