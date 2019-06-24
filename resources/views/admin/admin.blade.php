@extends('layouts.app')

@section('content')

<div class="container">
	<h1>Admin</h1>

	<div class="list-group">
		@can('admin') <a class="list-group-item" href="/admin/users">Users</a> @endcan
		<a class="list-group-item" href="/oauth">OAuth Clients</a>
	</div>
	
	@can('admin') 
		<h2>Import Features</h2>

		<form action="/admin/import-flarm" method="POST">
			{{ csrf_field() }}
			<input type="submit" class="btn btn-outline-dark" value="Import Flarm Codes">
		</form>
		
		<br>

		<form action="/admin/import-badges" method="POST">
			{{ csrf_field() }}
			<input type="submit" class="btn btn-outline-dark" value="Import Badges">
		</form>
		
		<br>

		<form action="/admin/import-qgps" method="POST">
			{{ csrf_field() }}
			<input type="submit" class="btn btn-outline-dark" value="Import QGPs from GNZ database to ratings">
		</form>

		<form action="/admin/sync-qgps" method="POST">
			{{ csrf_field() }}
			<input type="submit" class="btn btn-outline-dark" value="Sync QGPs from ratings to badges">
		</form>

		<br>
		
		<form action="/admin/import-aircraft-from-caa" method="POST">
			{{ csrf_field() }}
			<input type="submit" class="btn btn-outline-dark" value="Import Aircraft from CAA">
		</form>
		
		<br>
		
		<form action="/admin/email-address-changes" method="POST">
			{{ csrf_field() }}
			<input type="submit" class="btn btn-outline-dark" value="Email Address Changes">
		</form>
	@endcan
</div>


@endsection