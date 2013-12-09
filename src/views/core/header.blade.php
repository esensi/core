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
		<title></title>
		
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
            <li class="dropdown @if(starts_with(Route::currentRouteName(), 'admin.users')) active @endif">
              <a href="{{ route('admin.users.index') }}" class="dropdown-toggle" data-toggle="dropdown">Users <b class="caret"></b></a>
              <ul class="dropdown-menu">
                @if(Entrust::can('module_roles'))
                <li><a href="{{ route('admin.users.create') }}" data-toggle="modal" data-target="#albaModal">Create User</a></li>
                <li><a href="{{ route('admin.users.index') }}">Browse Users</a></li>
                <li><a href="{{ route('admin.users.search') }}?{{http_build_query(Input::all())}}" data-toggle="modal" data-target="#albaModal">Search Users</a></li>
                <li><a href="{{ route('admin.users.trash') }}">Trash Can</a></li>
                @endif
                @if(Auth::user()->ability([], ['module_roles','module_permissions','module_tokens']))
                  <li class="divider"></li>
                  <li class="dropdown-header">Access Controls</li>
                  @if(Entrust::can('module_roles'))
                    <li><a href="{{ route('admin.roles.index') }}">Role Management</a></li>
                  @endif
                  @if(Entrust::can('module_permissions'))
                    <li><a href="{{ route('admin.permissions.index') }}">Permission Management</a></li>
                  @endif
                  @if(Entrust::can('module_tokens'))
                    <li><a href="{{ route('admin.tokens.index') }}">Tokens Management</a></li>
                  @endif
                @endif
              </ul>
            </li>
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