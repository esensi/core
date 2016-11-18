@if(config('esensi/core::core.logout', false))
  <li class="logout-menu nav-item">
    <a href="{{ route('users.logout') }}" class="nav-link">
      <span class="sr-only">
        @lang('esensi/core::core.buttons.log_out')
      </span>
    </a>
  </li>
@else
  <li class="redirect-menu nav-item">
    <a href="{{ route('index') }}" class="nav-link">
      <span class="sr-only">
        @lang('esensi/core::core.buttons.public_site')
      </span>
    </a>
  </li>
@endif
