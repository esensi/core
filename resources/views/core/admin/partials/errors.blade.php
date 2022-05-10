@if ($errors->any() || (Session::has('message') && !empty(Session::get('message'))))
    <div class="alert alert-{{ Session::get('code', 200) }} alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            <span class="sr-only">Close</span>
        </button>
        @if ($errors->any())
            @if ($errors->first() != Session::get('message'))
                {!! Session::get('message') !!}
            @endif
            @foreach ($errors->all(':message') as $error)
                {!! $error !!}
            @endforeach
        @else
            {!! Session::get('message') !!}
        @endif
    </div>
@endif
