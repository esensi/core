@section('content')

<div class="container" style="margin-top: 20%">
    <div class="row">
		<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
			  	<div class="panel-heading">
			    	<h3 class="panel-title">Forget Your Password?</h3>
			 	</div>
			  	<div class="panel-body">
					<p>To retrieve your password for this site, enter your email address below. We'll send you instructions on how to reset your password.</p>
			  		<hr>

			    	{{ Form::open([ 'route' => ['users.reset-password'] ]) }}
			    	@if( Session::has('error') )
			    		<div class="alert alert-danger">{{ Session::get('message') }}</div>
			    	@endif
                    <fieldset>
			    	  	<div class="form-group">
			    		    {{ Form::text('email', null, ['placeholder' => 'E-mail', 'class' => 'form-control']) }}
			    		</div>
			    		<input class="btn btn-lg btn-success btn-block" type="submit" value="Reset Password">
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