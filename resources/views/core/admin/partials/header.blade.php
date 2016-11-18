<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @foreach( config('esensi/core::core.metadata', []) as $name => $value)
      <meta name="{{ $name }}" content="{{ $value }}">
    @endforeach
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>{{ config('esensi/core::core.metadata.author', 'Esensi') }} – Administration</title>

    @styles('admin')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body class="admin {{ str_replace('.', '-', Route::currentRouteName()) }} {{ isset($code) && $code !== 200 ? 'status-' . $code : null }}">
    <!--[if lt IE 9]>
      <div class="alert alert-info">
        <strong>Heads up!</strong> You're using an older web browser, so some parts of this site may not work properly.
        You might want to try to <a href="http://whatbrowser.org/" class="alert-link">upgrade your browser</a>.
        You'll find that many websites work and look better, and you'll be safer online!
      </div>
    <![endif]-->

    @if (Auth::check())

      <div class="sidebar">

        <a class="logo" href="{{ route('admin.dashboard') }}">
          <span>{{ config('esensi/core::core.metadata.author', 'Esensi')}}</span>
        </a>

        <ul id="sidebarMenu" class="sidebar-menu nav nav-stacked nav-pills">
          @if(config('esensi/core::core.dashboard', true))
            <li class="dashboard-menu nav-item @if(starts_with(Route::currentRouteName(),  ['index', 'admin.dashboard'])) active @endif">
              <a href="{{ route('admin.dashboard') }}" class="nav-link">
                @lang('esensi/core::core.labels.dashboard')
              </a>
            </li>
          @endif

          @foreach(config('esensi/core::core.packages') as $package)
            @if(Config::has('esensi/'.$package . '::'.$package.'.dropdown.admin'))
              @include(config('esensi/'.$package . '::'.$package.'.dropdown.admin'))
            @elseif(Config::has($package.'.dropdown.admin'))
              @include(config($package.'.dropdown.admin'))
            @endif
          @endforeach
        </ul>

        @if(config('esensi/core::core.attribution.enable', true))
          <div class="attribution">
            <a href="{{ config('esensi/core::core.attribution.url', 'http://esen.si') }}" target="_blank">
              {!! config('esensi/core::core.attribution.name', 'Powered by Esensi') !!}
            </a>
          </div>
        @endif

      </div>

      <div class="header">
        <button type="button" class="sidebar-toggle" data-toggle="offcanvas" data-target=".sidebar" data-canvas="body">
          <span class="sr-only">
            @lang('esensi/core::core.buttons.toggle_menu')
          </span>
        </button>

        <ul class="header-menu nav nav-pills">
          <li class="account-menu nav-item">
            <a href="{{ route('admin.users.account') }}" class="account-menu-toggle nav-link" data-toggle="dropdown">
              {{ Auth::user()->display_name }}
            </a>
            @include(config('esensi/core::core.partials.admin.account'))
          </li>
          @include(config('esensi/core::core.partials.admin.logout'))
        </ul>
      </div>

    @endif
