<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @foreach( Config::get('esensi/core::core.metadata', []) as $name => $value)
      <meta name="{{ $name }}" content="{{ $value }}">
    @endforeach
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>{{ Config::get('esensi/core::core.metadata.author', 'Esensi') }} – Administration</title>

    @styles('admin')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
  <body class="admin {{ str_replace('.', '-', Route::currentRouteName()) }}">
    <!--[if lt IE 9]>
    <div class="alert alert-info">
      <strong>Heads up!</strong> You're using an older web browser, so some parts of this site may not work properly.
      You might want to try to <a href="http://whatbrowser.org/" class="alert-link">upgrade your browser</a>.
      You'll find that many websites work and look better, and you'll be safer online!
    </div>
    <![endif]-->

    @if (Auth::check())
      <div class="sidebar navmenu navmenu-default navmenu-fixed-left offcanvas-sm">

        <a class="sidebar-logo navbar-brand" href="{{ route('admin.dashboard') }}">
          {{ Config::get('esensi/core::core.metadata.author', 'Esensi')}}
        </a>

        <ul id="sidebarMenu" class="sidebar-menu nav navmenu-nav">
          @if(Config::get('esensi/core::core.dashboard', true))
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          @endif

          @foreach(Config::get('esensi/core::core.packages') as $package)
            @if(Config::has('esensi/'.$package . '::'.$package.'.dropdown.admin'))
              @include(Config::get('esensi/'.$package . '::'.$package.'.dropdown.admin'))
            @elseif(Config::has($package.'.dropdown.admin'))
              @include(Config::get($package.'.dropdown.admin'))
            @endif
          @endforeach

        </ul>

        @if(Config::get('esensi/core::core.attribution.enable', true))
          <div class="sidebar-attribution">
            <a href="{{ Config::get('esensi/core::core.attribution.url', 'http://esen.si') }}" target="_blank">{{ Config::get('esensi/core::core.attribution.name', 'Powered by Esensi') }}</a>
          </div>
        @endif

      </div>

      <div class="header navbar navbar-default navbar-fixed-top">
        <button type="button" class="header-toggle navbar-toggle hidden-md hidden-lg" data-toggle="offcanvas" data-target=".navmenu" data-canvas="body">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>

        <ul class="header-menu nav navbar-nav navbar-right">
          <li class="dropdown hidden-xs">

            <a href="{{ route('admin.users.account') }}" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-user fa-fw"></i>
              {{ Auth::user()->display_name or 'User Account' }}
            </a>

            @include(Config::get('esensi/core::core.partials.admin.account'))

          </li>

          @include(Config::get('esensi/core::core.partials.admin.logout'))

        </ul>
      </div>

    @endif
