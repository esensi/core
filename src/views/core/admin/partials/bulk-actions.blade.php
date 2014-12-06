<div class="btn-bulk btn-group" data-toggle="bulk">
    @if( Route::currentRouteName() == 'admin.'. $package .'.dumpster' )
      <a data-url="{{ route('admin.'. $package .'.restore.bulk.confirm', 'ids') }}" class="btn btn-sm btn-default" data-toggle="modal" data-target="#esensiModal">
        <i class="fa fa-fw text-success fa-refresh"></i><span class="hidden-xs"> Restore</span>
      </a>
      <a data-url="{{ route('admin.'. $package .'.delete.bulk.confirm', 'ids') }}" class="btn btn-sm btn-default" data-toggle="modal" data-target="#esensiModal">
        <i class="fa fa-fw text-danger fa-trash-o"></i><span class="hidden-xs"> Delete</span>
      </a>
    @else
      @if( Route::has('admin.'. $package . '.dumpster') )
        <a data-url="{{ route('admin.'. $package .'.trash.bulk.confirm', 'ids') }}" class="btn btn-sm btn-default" data-toggle="modal" data-target="#esensiModal">
          <i class="fa fa-fw text-danger fa-trash-o"></i><span class="hidden-xs"> Trash</span>
        </a>
      @else
        <a data-url="{{ route('admin.'. $package .'.delete.bulk.confirm', 'ids') }}" class="btn btn-sm btn-default" data-toggle="modal" data-target="#esensiModal">
          <i class="fa fa-fw text-danger fa-trash-o"></i><span class="hidden-xs"> Delete</span>
        </a>
      @endif
    @endif
</div>
