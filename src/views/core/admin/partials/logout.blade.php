@if(Config::get('esensi/core::core.logout', false))
  <li><a href="{{ route('users.logout') }}"><i class="fa fa-power-off fa-fw"></i></a></li>
@else
  <li><a href="{{ route('index') }}"><i class="fa fa-sign-out fa-fw"></i></a></li>
@endif
