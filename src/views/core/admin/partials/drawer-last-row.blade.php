<div class="col-xs-12">
  <div class="row">
    <div class="col-sm-3">
      <div class="form-group">
        <label>Order By</label>
        {{ Form::select('order', $orderOptions, $order, ['class' => 'form-control']) }}
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <label>Sort Results</label>
        {{ Form::select('sort', $sortOptions, $sort, ['class' => 'form-control']) }}
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <label>Max Results</label>
        {{ Form::select('max', $maxOptions, $max, ['class' => 'form-control']) }}
      </div>
    </div>
    <div class="col-sm-3">
      <div class="form-group">
        <label class="hidden-xs" style="display:block;">&nbsp;</label>
        <button type="submit" class="btn btn-default"><i class="fa fa-fw fa-search text-primary"></i> Submit</button>
      </div>
    </div>
  </div>
</div>
