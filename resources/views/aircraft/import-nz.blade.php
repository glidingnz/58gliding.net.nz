@extends('layouts.app')

@section('content')


<div class="container">

	<form method="POST" action="/aircraft/import-nz">
		<input type="submit" class="btn btn-outline-dark" value="Import CAA DB">
	</form>

</div>

@endsection


