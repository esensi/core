@extends(Config::get('esensi/core::core.namespace', 'esensi/core::').'core.public.default')

@section('content')

<div class="container">
  <div class="row">
    <div class="panel-container">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title text-center">We'll Be Right Back</h3>
        </div>
        <div class="panel-body">
          <p>We are conducting a bit of maintenance right now. This web application will be back just as soon as we're finished!</p>
        </div>
      </div>
      <ul class="nav nav-pills nav-justified">
        <li><a href="mailto:{{ Config::get('mail.from.address') }}"><i class="fa fa-fw fa-envelope"></i> Contact Support</a></li>
      </ul>
    </div>
  </div>
</div>

@stop
