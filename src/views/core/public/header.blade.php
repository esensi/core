<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="generator" content="{{ gethostname() }}">
		<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
		<title>Esensi</title>
		
		@styles()
		
		<!--[if lt IE 9]>
		@scripts('ie')
		<![endif]-->
	</head>
<body class="public {{ str_replace('.', '-', Route::currentRouteName()) }}">
	<!--[if lt IE 8]>
	<div class="alert alert-info">
		<strong>Heads up!</strong> You're using an older web browser, so some parts of this site may not work properly. You might want to try to <a href="http://whatbrowser.org/" class="alert-link">upgrade your browser</a>. You'll find that many websites work and look better, and you'll be safer online!
	</div>
	<![endif]-->
