@section('content')

<div class="container">
	<ol class="breadcrumb">
		<li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
		<li><a href="{{ route('admin.users.index') }}">Users</a></li>
		<li class="active">Tokens</li>
		@if($collection->count())
		<span class="pull-right text-muted">
			Showing {{$paginator->getFrom()}} to {{$paginator->getTo()}} of {{$paginator->getTotal()}}
		</span>
		@endif
		<span class="clearfix"></span>
	</ol>

	@include('alba::core.errors')

	<div class="table-responsive table-striped table-hover">
		<table class="table">
	  		<thead>
	  			<tr>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'id']) }}">ID</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'type']) }}">Type</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'token']) }}">Token</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'created_at']) }}">Created</a></th>
	  				<th><a href="{{ HTML::paginationUrl($paginator, ['order' => 'expires_at']) }}">Expires</a></th>
	  			</tr>
	  		</thead>
	  		<tbody>
	  			@if ($collection->count())
		  			@foreach ($collection as $item)
		  			<tr>
		  				<td><a href="{{ $item->route }}">{{ $item->id }}</a></a></td>
		  				<td><a href="{{ HTML::paginationUrl($paginator, ['types' => $item->type]) }}">{{ $item->type }}</a></a></td>
		  				<td><a href="{{ $item->route }}">{{ $item->token }}</a></a></td>
		  				<td>{{ $item->timeSinceCreated }}</a></td>
		  				<td>{{ $item->timeTillExpires }}</a></td>
					</tr>
					@endforeach
				@else
					<tr class="warning">
						<td colspan="7">@lang('alba::token.errors.no_results')</td>
					</tr>
				@endif
	  		</tbody>
		</table>
		@if ($collection->count())
			{{ $paginator->links() }}
		@endif
	</div>
</div>

@stop