<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="{{ gethostname() }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <title>Esensi</title>

    @styles('application')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
      <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
  </head>
<body class="public {{ str_replace('.', '-', Route::currentRouteName()) }}">
  <!--[if lt IE 8]>
  <div class="alert alert-info">
    <strong>Heads up!</strong> You're using an older web browser, so some parts of this site may not work properly. You might want to try to <a href="http://whatbrowser.org/" class="alert-link">upgrade your browser</a>. You'll find that many websites work and look better, and you'll be safer online!
  </div>
  <![endif]-->

  @if (Auth::check())
  <div class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle Menu</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Esensi</a>
      </div>
      <div class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          @if(Config::get('esensi/core::core.dashboard', true))
          <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          @endif

          @foreach(Config::get('esensi/core::core.packages') as $package)
            <?php $namespace = Config::get('esensi/'.$package.'::'.$package.'.namespace', Config::get('esensi/core::core.namespace')); ?>
            @if(Config::has($namespace . $package . '.dropdown.admin'))
              @include($namespace . Config::get($namespace . $package . '.dropdown.admin'))
            @elseif(Config::has('esensi/'.$package.'::'.$package.'.dropdown.admin'))
              @include('esensi/'.$package.'::'.Config::get('esensi/'.$package.'::'.$package.'.dropdown.admin'))
            @endif
          @endforeach

        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{{ route('admin.users.show', Auth::user()->id) }}">
            <i class="fa fa-user fa-fw"></i> {{ Auth::user()->fullName }}</a></li>
          <li class="dropdown">
            <a href="{{ route('admin.users.show', Auth::user()->id) }}" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-fw"></i></a>
            <ul class="dropdown-menu pull-right">
                <li><a href="{{ route('admin.users.edit', Auth::user()->id) }}">Edit My User</a></li>
                <li><a href="{{ route('users.logout') }}">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
  @endif
