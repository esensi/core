@include ( Config::get('esensi/core::core.namespace', 'esensi/core::') . 'core.public.header')

@yield('content')

@include (Config::get('esensi/core::core.namespace', 'esensi/core::') . 'core.public.footer')
