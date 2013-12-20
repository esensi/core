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
		<title>Administration</title>
		
		@stylesheets('jqueryui', 'bootstrap', 'fontawesome', 'application')
		
		<!--[if lt IE 9]>
		@javascripts('ie')
		<![endif]-->
	</head>
<body>
	<!--[if lt IE 8]>
		<p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->

	<!-- Static navbar -->
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
          <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Administration</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
          
            @foreach(Config::get('alba::core.modules') as $module)
              <?php $package = Config::get($module.'.package', 'alba::'); ?>
              @if(Config::has($package.$module.'.dropdown'))
                @include(Config::get($package.$module.'.dropdown'))
              @endif
            @endforeach

          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="{{ route('admin.users.show', Auth::user()->id) }}">
              <i class="fa fa-user fa-fw"></i> {{ Auth::user()->fullName }}</a></li>
            <li class="dropdown">
            	<a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-cog fa-fw"></i></a>
            	<ul class="dropdown-menu pull-right">
	                <li><a href="{{ route('admin.users.edit', Auth::user()->id) }}">Edit My User</a></li>
	                <li><a href="{{ route('users.logout') }}">Logout</a></li>
	            </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    @endif