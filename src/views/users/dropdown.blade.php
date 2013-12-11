<li class="dropdown @if(starts_with(Route::currentRouteName(),  ['admin.users', 'admin.roles', 'admin.permissions', 'admin.tokens'])) active @endif">
  <a href="{{ route('admin.users.index') }}" class="dropdown-toggle" data-toggle="dropdown">Users <b class="caret"></b></a>
  <ul class="dropdown-menu">
    @if(Entrust::can('module_roles'))
    <li><a href="{{ route('admin.users.create') }}" data-toggle="modal" data-target="#albaModal">Create User</a></li>
    <li><a href="{{ route('admin.users.index') }}">Browse Users</a></li>
    <li><a href="{{ route('admin.users.search') }}?{{http_build_query(Input::all())}}" data-toggle="modal" data-target="#albaModal">Search Users</a></li>
    <li><a href="{{ route('admin.users.trash') }}">Trash Can</a></li>
    @endif
    @if(Auth::user()->ability([], ['module_roles','module_permissions','module_tokens']))
      <li class="divider"></li>
      <li class="dropdown-header">Access Controls</li>
      @if(Entrust::can('module_roles'))
        <li><a href="{{ route('admin.roles.index') }}">Role Management</a></li>
      @endif
      @if(Entrust::can('module_permissions'))
        <li><a href="{{ route('admin.permissions.index') }}">Permission Management</a></li>
      @endif
      @if(Entrust::can('module_tokens'))
        <li><a href="{{ route('admin.tokens.index') }}">Token Log</a></li>
      @endif
    @endif
  </ul>
</li>