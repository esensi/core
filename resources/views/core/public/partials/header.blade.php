<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @foreach( config('esensi/core::core.metadata', []) as $name => $value)
      <meta name="{{ $name }}" content="{{ $value }}">
    @endforeach
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('esensi/core::core.metadata.author', 'Esensi') }}</title>

    @styles('public')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <script src="http://cdnjs.cloudflare.com/ajax/libs/es5-shim/2.0.8/es5-shim.min.js">
    <![endif]-->
  </head>
  <body class="public {{ str_replace('.', '-', Route::currentRouteName()) }} {{ isset($code) && $code !== 200 ? 'status-' . $code : null }}">
    <!--[if lt IE 9]>
      <div class="alert alert-info">
        <strong>Heads up!</strong> You're using an older web browser, so some parts of this site may not work properly.
        You might want to try to <a href="http://whatbrowser.org/" class="alert-link">upgrade your browser</a>.
        You'll find that many websites work and look better, and you'll be safer online!
      </div>
    <![endif]-->
