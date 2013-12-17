@if(isset($user) && $user->tokens->count() && Entrust::can('module_tokens'))
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">Token Log</h3>
		</div>
		<table class="table table-striped">
			<thead>
	  			<tr>
	  				<th width="50%">&nbsp;</th>
	  				<th width="25%">Created</th>
	  				<th width="25%">Expires</th>
	  			</tr>
	  		</thead>
	  		<tbody>
	  			@foreach ($user->tokens as $token)
	  			<tr>
	  				<td>
		  				@if($token->isExpired)
		  					{{ $token->type }}
		  				@else
		  					<a href="{{ $token->route }}">{{ $token->type }}</a>
		  				@endif
	  					<br>
	  					<small class="text-muted">{{ substr($token->token, 0, 32) }}...</small></td>
	  				<td><small>{{ $token->timeSinceCreated }}</small></td>
	  				<td><small>{{ $token->timeTillExpires }}</small></td>
				</tr>
				@endforeach
	  		</tbody>
		</table>
	</div>
@endif