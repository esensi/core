@section('content')

<div class="container" style="margin-top: 20%">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Please Sign In</h3>
			 	</div>
			  	<div class="panel-body">
			    	{{ Form::open([ 'route' => ['users.login'] ]) }}
			    	@include('alba::core.errors')
                    <fieldset>
			    	  	<div class="form-group">
			    		    {{ Form::text('email', null, ['placeholder' => 'E-mail', 'class' => 'form-control']) }}
			    		</div>
			    		<div class="form-group">
			    			{{ Form::password('password', ['placeholder' => 'Password', 'class' => 'form-control']) }}
			    		</div>
			    		<div class="checkbox">
			    	    	<label>
			    	    		{{ Form::checkbox('remember', true, false) }} Remember Me
			    	    	</label>
			    	    </div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
			    	</fieldset>
			      	{{ Form::close() }}
			    </div>
			</div>
			<ul class="nav nav-pills nav-justified">
				<li><a href="{{ route('users.forgot-password') }}">Forgot your password?</a></li>
				<li><a href="{{ route('users.signup') }}">I need to register &rarr;</a></li>
			</ul>
		</div>
	</div>
</div>

@stop