@section('modal-title')

{{ isset($permission) ? 'Edit' : 'Create' }} Permission

@stop

@section('modal-body')

<p>Permissions are like keys to the application. Each permission's name is hard-coded into the application code and only users who have been assigned the permission can have access to code that is protected by that permission. Because permissions are hard-coded you can only create new permissions or edit the "display name" for existing permissions. The same permission can be assigned to multiple roles and thereby be assigned to a user.</p>

<hr>

{{ Form::open([ 'route' => (isset($permission) ? ['admin.permissions.update', $permission->id] : ['admin.permissions.store']) ]) }}

    <div class="row">
    	<div class="col-sm-6">
	        {{ Form::text('display_name', isset($permission) ? $permission->display_name : null,
	    		['placeholder' => 'Display Name', 'class' => 'form-control']) }}
	    </div>
	    <div class="col-sm-6">
	        {{ Form::text('name', isset($permission) ? $permission->name : null,
	    		array_merge(['placeholder' => 'Permission Name', 'class' => 'form-control'], isset($permission) ? ['disabled' => true] : [])) }}
	    </div>
	</div>

{{ Form::close() }}

@stop

@section('modal-footer')

<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="button" onclick="$(this).parents('.modal-content').find('form').submit()" class="btn btn-primary">Save</button>

@stop