@section('content')

<div class="container">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Create a New Password</h3>
			 	</div>
			  	<div class="panel-body">
			    	{{ Form::open([ 'route' => ['users.activate', $token] ]) }}
			    	@include('alba::core.errors')
                    <fieldset>
			    		<div class="form-group">
			    			{{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}
			    		</div>
			    		<div class="form-group">
			    			{{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
			    		</div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Activate Account">
			    	</fieldset>
			      	{{ Form::close() }}
			    </div>
			</div>
		</div>
	</div>
</div>

@stop