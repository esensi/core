	<div id="albaModal" role="dialog" aria-labelledby="albaModalLabel" aria-hidden="true" class="modal fade"></div>

	@foreach(Config::get('alba::core.javascripts', []) as $collection)
	    <link rel="stylesheet" href="{{ asset('builds/'. $collection . '.js') }}">
	@endforeach

</body>
</html>