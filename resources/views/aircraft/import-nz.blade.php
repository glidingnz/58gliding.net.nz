@extends('layouts.app')

@section('content')


<div class="container">

	<form method="POST" action="/aircraft/import-nz">
		<input type="submit" class="btn btn-default" value="Import CAA DB">
	</form>

</div>

@endsection


