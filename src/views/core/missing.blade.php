@extends('alba::core.default')

@section('content')

<div class="container">
    <div class="row">
    	<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-lg-4 col-lg-offset-4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title text-center">Page Not Found</h3>
				</div>
				<div class="panel-body">
					<p>It looks like you have gotten lost and wound up at a missing page. Please point yourself <a href="{{ route('index') }}">back to the home page</a> and find your way. If you think you've received this message in error please <a href="mailto:{{ Config::get('mail.from.address') }}">contact us to report this error.</a>
				</div>
			</div>
			<ul class="nav nav-pills nav-justified">
				<li><a href="{{ route('index') }}">&larr; Return to Home Page</a></li>
			</ul>
    	</div>
    </div>
</div>

@stop