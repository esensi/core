  <div class="container">
    <div class="attribution legal">
      @if(config('esensi/core::core.attribution.enable', true))
        <a href="{{ config('esensi/core::core.attribution.url', 'http://esen.si') }}" target="_blank">{{ config('esensi/core::core.attribution.name') }}</a>
      @endif
    </div>
  </div>

  @scripts('public')

</body>
</html>
