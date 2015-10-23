<div class="drawer-row">
  <div class="drawer-col drawer-col-order">
    <label>Order By</label>
    {!! Form::select('order', $orderOptions, $order, ['class' => 'form-control']) !!}
  </div>
  <div class="drawer-col drawer-col-sort">
    <label>Sort Results</label>
    {!! Form::select('sort', $sortOptions, $sort, ['class' => 'form-control']) !!}
  </div>
  <div class="drawer-col drawer-col-max">
    <label>Max Results</label>
    {!! Form::select('max', $maxOptions, $max, ['class' => 'form-control']) !!}
  </div>
  <div class="drawer-col drawer-col-search">
    <label>&nbsp;</label>
    <button type="submit" class="btn btn-search">Search</button>
  </div>
</div>
