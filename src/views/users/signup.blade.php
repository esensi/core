@section('content')

<div class="container" style="margin-top: 20%">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Register as a User</h3>
			 	</div>
			  	<div class="panel-body">
			    	{{ Form::open([ 'route' => ['users.register'] ]) }}
			    	@include('alba::core.errors')
                    <fieldset>
			    	  	<div class="row">
			    	  		<div class="form-group col-sm-6">
		    		    		{{ Form::text('first_name', null, ['placeholder' => 'First Name', 'class' => 'form-control']) }}
			    		    </div>
			    		    <div class="form-group col-sm-6">
			    		    	{{ Form::text('last_name', null, ['placeholder' => 'Last Name', 'class' => 'form-control']) }}
			    		    </div>
			    		</div>
			    	  	<div class="form-group">
			    		    {{ Form::text('email', null, ['placeholder' => 'E-mail', 'class' => 'form-control']) }}
			    		</div>
			    		<div class="form-group">
			    			{{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}
			    		</div>
			    		<div class="form-group">
			    			{{ Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'form-control']) }}
			    		</div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Create Account">
			    	</fieldset>
			      	{{ Form::close() }}
			    </div>
			</div>
			<ul class="nav nav-pills nav-justified">
				<li><a href="{{ route('users.signin') }}">&larr; I already have an account</a></li>
			</ul>
		</div>
	</div>
</div>

@stop