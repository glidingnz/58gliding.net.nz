@extends('layouts.header')


@section('scripts')

	<!-- modal container (required if you need to render dynamic bootstrap modals) -->
	@include('leantony::modal.container')

	<script src="{{ asset('/js/grid-app.js')}}"></script>

	<!-- Following Scripts Requred by Laravel Grid -->
	<!-- progress bar js (not required, but cool) -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
	<!-- moment js (required by datepicker library) -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
	<!-- pjax js (required) -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.pjax/2.0.1/jquery.pjax.min.js"></script>
	<!-- datepicker js (required for datepickers) -->
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<!-- required to supply js functionality for the grid -->
	<script src="{{ asset('vendor/leantony/grid/js/grid.js') }}"></script>
	<script>
	    // send csrf token (see https://laravel.com/docs/5.6/csrf#csrf-x-csrf-token) - this is required
	    $.ajaxSetup({
	        headers: {
	            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	        }
	    });

	    // for the progress bar (required for progress bar functionality)
	    $(document).on('pjax:start', function () {
	        NProgress.start();
	    });
	    $(document).on('pjax:end', function () {
	        NProgress.done();
	    });
	</script>
	<!-- End Laravel Grid Scripts -->

	<!-- entry point for all scripts injected by the generated grids (required) -->
	@stack('grid_js')

@endsection

