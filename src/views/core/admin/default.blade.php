@include ( Config::get('esensi/core::core.namespace', 'esensi/core::') . 'core.admin.header')

@yield('content')

@include (Config::get('esensi/core::core.namespace', 'esensi/core::') . 'core.admin.footer')
