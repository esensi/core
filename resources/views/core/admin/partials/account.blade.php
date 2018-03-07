<div class="account-menu-dropdown">
  <a class="account-menu-profile-view dropdown-item" href="{{ route('admin.users.account') }}">
    @lang('esensi/user::user.buttons.view_profile')
  </a>
  <a class="account-menu-profile-edit dropdown-item" href="{{ route('admin.users.account.edit') }}">
    @lang('esensi/user::user.buttons.edit_profile')
  </a>
  <a class="account-menu-logout dropdown-item" href="{{ route('users.logout') }}">
    @lang('esensi/user::user.buttons.logout')
  </a>
</div>
