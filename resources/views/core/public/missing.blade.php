@extends('esensi/core::core.public.centered')

@section('card')

  <div class="card card-missing">
    <div class="card-header text-center">
      <h3>@lang('esensi/core::core.cards.missing.title')</h3>
    </div>
    <div class="card-block">
      <p class="card-text">It looks like you have gotten lost and wound up at a missing page. Please point yourself <a href="{{ route('index') }}">back to the home page</a> and find your way from here. If you think you've received this message in error please <a href="mailto:{{ Config::get('mail.from.address') }}">contact us to report this error.</a></p>
      <a class="btn btn-primary" href="{{ route('index') }}">Go to Home Page</a>
    </div>
  </div>

@stop
