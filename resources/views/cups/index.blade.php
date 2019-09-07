@extends('layouts.grid')

@section('content')

<div class="container-fluid" id="gridview">

	<a  href="{{ url('/waypoints')}}" class="float-right btn btn-outline-dark">All Waypoints</a>
	<h1>Download Waypoints</h1>

    {!! $grid !!}

</div>

@endsection

