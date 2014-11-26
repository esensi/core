  <div class="container">
    <div class="attribution legal">
      @if(Config::get('esensi/core::core.attribution.enable', true))
        <a href="{{ Config::get('esensi/core::core.attribution.url', 'http://esen.si') }}" target="_blank">{{ Config::get('esensi/core::core.attribution.name') }}</a>
      @endif
    </div>
  </div>

  @scripts('public')

</body>
</html>
