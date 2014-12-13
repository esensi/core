<div class="drawer">
  {{ Form::open([ 'route' => isset($route) ? $route : Route::currentRouteName() , 'method' => 'GET']) }}
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label>Keyword Search</label>
          {{ Form::text('keywords', $keywords, ['class' => 'form-control esensi-tags']) }}
        </div>
      </div>
      @include('esensi/core::core.admin.partials.drawer-last-row')
    </div>
  {{ Form::close() }}
</div>
