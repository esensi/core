@extends('esensi/core::core.public.centered', ['code' => isset($code) ? $code : 500])

@section('card')

<div class="card card-whoops">
    <div class="card-header text-center">
        <h3>{{ $code ?: 500 }} - {{ $message ?: "Internal Server Error" }}</h3>
    </div>
    <div class="card-block">
        <p class="card-text">{{ isset($error) ? $error : null }} If you think you've received this message in error please <a href="mailto:{{ Config::get('mail.from.address') }}">contact us to report this error.</a></p>
        <a class="btn btn-primary" href="{{ route('index') }}">Go to Home Page</a>
    </div>
</div>

@endsection
