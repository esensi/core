@section('content')

<div class="container">
  <div class="row">
    <div class="panel-container">
      <div class="panel panel-default">
        <div class="panel-body">
          @include(config('esensi/core::core.partials.public.errors'))
          <p class="lead text-center" style="margin-bottom:0;">n. <em>essence</em>, an awesome Laravel boilerplate application</p>
        </div>
      </div>
    </div>
  </div>
</div>

@stop
