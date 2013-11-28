@if( $errors->any() || Session::has('message'))
	<div class="alert alert-danger">
		@if ($errors->any())
		@foreach($errors->all(':message ') as $error)
		{{ $error }}
		@endforeach
		@else
		{{ Session::get('message') }}
		@endif
	</div>
@endif