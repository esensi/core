@extends('esensi/core::core.public.default')

@section('content')

<div class="card-container">
  <div class="card card-whoops">
    <div class="card-header text-center">
      <h3>{{ $code or 500 }} - {{ $message or "Internal Server Error"}}</h3>
    </div>
    <div class="card-block">
      <p class="card-text">{{ $error or null }} If you think you've received this message in error please <a href="mailto:{{ Config::get('mail.from.address') }}">contact us to report this error.</a></p>
      <a class="card-link" href="{{ route('index') }}">Go to Home Page</a>
    </div>
  </div>
</div>

@stop
