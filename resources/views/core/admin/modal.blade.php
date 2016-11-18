<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    <span class="sr-only">
      @lang('esensi/core::core.buttons.close')
    </span>
  </button>
  <h4 class="modal-title" id="esensiModalTitle">@yield('modal-title')</h4>
</div>
<div class="modal-body">
  @include(config('esensi/core::core.partials.admin.errors'))
  @yield('modal-body')
</div>
<div class="modal-footer">
  @yield('modal-footer')
</div>

<script type="application/javascript">
  Esensi.modal.init();
</script>
