<div class="bulk-actions btn-group" data-toggle="bulk">
  @if( Route::currentRouteName() == 'admin.'. $package .'.dumpster' )
    <a data-bulk-url="{{ route('admin.'. $package .'.restore.bulk.confirm', ':ids') }}" class="bulk-action btn btn-restore" data-toggle="modal" data-target="#esensiModal">
      <span>
        @lang('esensi/core::core.buttons.restore')
      </span>
    </a>
    <a data-bulk-url="{{ route('admin.'. $package .'.delete.bulk.confirm', ':ids') }}" class="bulk-action btn btn-delete" data-toggle="modal" data-target="#esensiModal">
      <span>
        @lang('esensi/core::core.buttons.delete')
      </span>
    </a>
  @else
    @if( Route::has('admin.'. $package . '.dumpster') )
      <a data-bulk-url="{{ route('admin.'. $package .'.trash.bulk.confirm', ':ids') }}" class="bulk-action btn btn-trash" data-toggle="modal" data-target="#esensiModal">
        <span>
          @lang('esensi/core::core.buttons.trash')
        </span>
      </a>
    @else
      <a data-bulk-url="{{ route('admin.'. $package .'.delete.bulk.confirm', ':ids') }}" class="bulk-action btn btn-delete" data-toggle="modal" data-target="#esensiModal">
        <span>
          @lang('esensi/core::core.buttons.delete')
        </span>
      </a>
    @endif
  @endif
</div>
