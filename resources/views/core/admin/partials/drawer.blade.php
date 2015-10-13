<div class="drawer">
  {!! Form::open([ 'route' => isset($route) ? $route : Route::currentRouteName() , 'method' => 'GET']) !!}
    <div class="drawer-row">
      <div class="drawer-col-keywords">
        <label>Keyword Search</label>
        {!! Form::text('keywords', $keywords, ['class' => 'form-control esensi-tags']) !!}
      </div>
    </div>
    @include('esensi/core::core.admin.partials.drawer-last-row')
  {!! Form::close() !!}
</div>
