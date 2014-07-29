@section('content')

<div class="container">
  <div class="content">
    <h1>Esensi</h1>
    <p class="lead">n. <em>essence</em>, an awesome Laravel boilerplate application</p>
    @include(Config::get('esensi/core::core.namespace', 'esensi/core::') . 'core.public.errors')
  </div>
</div>

@stop
