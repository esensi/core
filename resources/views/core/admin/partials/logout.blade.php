@if(config('esensi/core::core.logout', false))
  <li class="logout-menu">
    <a href="{{ route('users.logout') }}">
      <span class="sr-only">Log Out</span>
    </a>
  </li>
@else
  <li class="redirect-menu">
    <a href="{{ route('index') }}">
      <span class="sr-only">Public Site</span>
    </a>
  </li>
@endif
