@section('modal-title')

Filter Users

@stop

@section('modal-body')

{{ Form::open([ 'route' => ['admin.users.index'] , 'method' => 'GET']) }}

	<fieldset>
		<div class="form-group">
			{{ Form::text('keywords', $keywords, ['placeholder' => 'Keywords', 'class' => 'form-control']) }}
		</div>
		<div class="form-group">
			{{ Form::text('names', $names, ['placeholder' => 'Names', 'class' => 'form-control']) }}
		</div>
		<div class="form-group">
			{{ Form::select('roles[]', $rolesOptions, $roles, ['class' => 'form-control', 'multiple' => true]) }}
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					{{ Form::select('active', $activeOptions, $active, ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					{{ Form::select('blocked', $blockedOptions, $blocked, ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					{{ Form::select('trashed', $trashedOptions, $trashed, ['class' => 'form-control']) }}
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					{{ Form::select('order', $orderOptions, $order, ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					{{ Form::select('sort', $sortOptions, $sort, ['class' => 'form-control']) }}
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					{{ Form::select('max', $maxOptions, $max, ['class' => 'form-control']) }}
				</div>
			</div>
		</div>
	</fieldset>

{{ Form::close() }}

@stop

@section('modal-footer')

<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
<button type="button" onclick="$(this).parents('.modal-content').find('form').submit()" class="btn btn-primary">Search</button>

@stop