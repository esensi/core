@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Check Your Email!</h3>
                </div>
                <div class="panel-body">
                    <p>We've sent you an email with instructions on how to reset your password. If you don't receive the email in 10 minutes, please check your spam filter or <a href="{{ route('users.forgot-password') }}">try again</a>.</p>
                </div>
            </div>
            <ul class="nav nav-pills nav-justified">
                <li><a href="{{ route('users.signin') }}">&larr; Back to Login</a></li>
            </ul>
        </div>
    </div>
</div>

@stop