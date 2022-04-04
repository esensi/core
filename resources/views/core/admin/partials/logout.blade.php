@if (config('esensi/core::core.logout', false))
    <li class="logout-menu nav-item">
        <a href="{{ route('users.logout') }}" class="nav-link">
            <span class="sr-only">Log Out</span>
        </a>
    </li>
@else
    <li class="redirect-menu nav-item">
        <a href="{{ route('index') }}" class="nav-link">
            <span class="sr-only">Public Site</span>
        </a>
    </li>
@endif
