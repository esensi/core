@section('content')

<div class="container">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Request Activation by Email</h3>
			 	</div>
			  	<div class="panel-body">
					<p>To activate your account for this site, enter your email address below. We'll send you instructions on how to activate your account.</p>
			  		<hr>

			    	{{ Form::open([ 'route' => ['users.reset-activation'] ]) }}
			    	@include('alba::core.errors')
                    <fieldset>
			    	  	<div class="form-group">
			    		    {{ Form::text('email', null, ['placeholder' => 'E-mail', 'class' => 'form-control']) }}
			    		</div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Request Activation">
			    	</fieldset>
			      	{{ Form::close() }}
			    </div>
			</div>
			<ul class="nav nav-pills nav-justified">
				<li><a href="{{ route('users.signin') }}">&larr; Back to Login</a></li>
				<li><a href="{{ route('users.signup') }}">I need to register &rarr;</a></li>
			</ul>
		</div>
	</div>
</div>

@stop