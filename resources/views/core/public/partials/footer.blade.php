  @if(config('esensi/core::core.attribution.enable', true))
    <div class="container">
      <div class="attribution">
        <a href="{{ config('esensi/core::core.attribution.url', 'http://esen.si') }}" target="_blank">
          {{ config('esensi/core::core.attribution.name') }}
        </a>
      </div>
    </div>
  @endif

  @scripts('public')

</body>
</html>
