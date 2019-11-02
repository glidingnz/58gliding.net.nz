@extends('layouts.app')

@section('content')

<div class="container" id="aircraft">

	@can('logged-in')
		<edit-fleet fleet-id="{{ $id }}"></edit-fleet>
	@else
		<p class="error">Sorry, you must be logged in to edit fleet details. Click 'Register' top right to create an account, or 'login' if you already have an account.</p>
	@endcan
</div>

@endsection
