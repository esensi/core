<div class="drawer-row">
  <div class="drawer-col drawer-col-order">
    <label>
      @lang('esensi/core::core.labels.order_by')
    </label>
    {!! Form::select('order', $orderOptions, $order, ['class' => 'form-control']) !!}
  </div>
  <div class="drawer-col drawer-col-sort">
    <label>
      @lang('esensi/core::core.labels.sort_results')
    </label>
    {!! Form::select('sort', $sortOptions, $sort, ['class' => 'form-control']) !!}
  </div>
  <div class="drawer-col drawer-col-max">
    <label>
      @lang('esensi/core::core.labels.max_results')
    </label>
    {!! Form::select('max', $maxOptions, $max, ['class' => 'form-control']) !!}
  </div>
  <div class="drawer-col drawer-col-search">
    <label>&nbsp;</label>
    <button type="submit" class="btn btn-search">
      @lang('esensi/core::core.buttons.search')
    </button>
  </div>
</div>
