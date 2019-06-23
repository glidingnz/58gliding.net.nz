@extends('layouts.app')

@section('content')

<div class="container" id="users">
	
	<h1>{{ $user->first_name }} {{ $user->last_name }} Roles</h1>

	<user-roles user-id="{{ $user->id }}"></user-roles>

</div>


@endsection