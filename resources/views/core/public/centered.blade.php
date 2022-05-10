@extends('esensi/core::core.public.default')

@section('content')

    <div class="card-container">
        <div class="logo">
            <img src="{{ asset('img/public/logo.png') }}" alt="{{ config('esensi/core::core.metadata.author', 'Esensi') }}" />
        </div>

        @yield('card')

        @if (config('esensi/core::core.attribution.enable', true))
        <div class="attribution">
            <a href="{{ config('esensi/core::core.attribution.url', 'https://esen.si') }}" target="_blank">
                {!! config('esensi/core::core.attribution.name') !!}
            </a>
        </div>
        @endif

    </div>

@endsection
