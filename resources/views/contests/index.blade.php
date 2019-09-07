@extends('layouts.grid')

@section('content')

<div class="container-fluid" id="gridview">
	<a  href="{{ url('/contestclasses')}}" class="float-right btn btn-outline-dark">Contest Classes</a>
	<h1>Contests</h1>

    {!! $grid !!}
</div>



@endsection