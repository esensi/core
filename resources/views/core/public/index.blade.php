@extends('esensi/core::core.public.centered')

@section('card')

  <div class="card card-intro">
    <div class="card-block">
      @include(config('esensi/core::core.partials.public.errors'))
      <p class="card-text lead text-center">Build Better Laravel Apps</p>
    </div>
  </div>

@stop
