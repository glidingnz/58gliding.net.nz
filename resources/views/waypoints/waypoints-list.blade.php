@extends('layouts.app')

@section('content')


<div class="container" id="waypoints">

	<form action="/waypoints-upload" method="post" enctype="multipart/form-data">
		{{ csrf_field() }}
		Upload
		<input type="file" name="waypoints">
		<input type="submit" value="Upload Waypoints" class="btn btn-default">
	</form>

	
	<waypoints></waypoints>


</div>



@endsection