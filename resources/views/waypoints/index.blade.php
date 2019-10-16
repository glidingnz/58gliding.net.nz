@extends('layouts.grid')

@section('content')


<div class="container-fluid" id="gridview">

	<a  href="{{ url('/cups')}}" class="float-right btn btn-outline-dark">Waypoint Lists</a>
	<h1>All Waypoints</h1>
	{!! $grid !!}
</div>



@endsection