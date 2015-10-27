@extends('esensi/core::core.public.centered')

@section('card')

  <div class="card card-maintenance">
    <div class="card-header text-center">
      <h3>We'll Be Right Back</h3>
    </div>
    <div class="card-block">
      <p class="card-text">We are conducting a bit of maintenance right now. This web application will be back just as soon as we're finished!</p>
      <a class="btn btn-primary" href="mailto:{{ Config::get('mail.from.address') }}">Contact Support</a>
    </div>
  </div>

@stop
