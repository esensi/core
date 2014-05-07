@include ( Config::get('esensi::core.namespace', 'esensi::') . 'core.public.header')

@yield('content')

@include (Config::get('esensi::core.namespace', 'esensi::') . 'core.public.footer')