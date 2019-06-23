@extends('layouts.app')

@section('content')

<div class="container" id="aircraft">

	{{-- @can('logged-in') --}}
		<edit-aircraft aircraft-id="{{ $id }}"></edit-member>
	{{-- @else
		<p class="error">Sorry, you must be logged in to edit aircraft details. Click 'Register' top right to create an account, or 'login' if you already have an account.</p>
	@endcan --}}
</div>

@endsection
