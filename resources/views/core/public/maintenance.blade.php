@extends('esensi/core::core.public.centered')

@section('card')

  <div class="card card-maintenance">
    <div class="card-header text-center">
      <h3>@lang('esensi/core::core.cards.maintenance.title')</h3>
    </div>
    <div class="card-block">
      <p class="card-text">
        @lang('esensi/core::core.cards.maintenance.message')
      </p>
      <a class="btn btn-primary" href="mailto:{{ Config::get('mail.from.address') }}">@lang('esensi/core::core.cards.maintenance.button')</a>
    </div>
  </div>

@stop
