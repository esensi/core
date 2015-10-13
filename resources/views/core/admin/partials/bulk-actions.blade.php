<div class="bulk-actions" data-toggle="bulk">
  @if( Route::currentRouteName() == 'admin.'. $package .'.dumpster' )
    <a data-bulk-url="{{ route('admin.'. $package .'.restore.bulk.confirm', ':ids') }}" class="bulk-restore" data-toggle="modal" data-target="#esensiModal">
      <span>Restore</span>
    </a>
    <a data-bulk-url="{{ route('admin.'. $package .'.delete.bulk.confirm', ':ids') }}" class="bulk-delete" data-toggle="modal" data-target="#esensiModal">
      <span>Delete</span>
    </a>
  @else
    @if( Route::has('admin.'. $package . '.dumpster') )
      <a data-bulk-url="{{ route('admin.'. $package .'.trash.bulk.confirm', ':ids') }}" class="bulk-trash" data-toggle="modal" data-target="#esensiModal">
        <span>Trash</span>
      </a>
    @else
      <a data-bulk-url="{{ route('admin.'. $package .'.delete.bulk.confirm', ':ids') }}" class="bulk-delete" data-toggle="modal" data-target="#esensiModal">
        <span>Delete</span>
      </a>
    @endif
  @endif
</div>
