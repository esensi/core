@include ( Config::get('esensi::core.namespace', 'esensi::') . 'core.admin.header')

@yield('content')

@include (Config::get('esensi::core.namespace', 'esensi::') . 'core.admin.footer')