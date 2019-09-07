@extends('layouts.grid')

@section('content')

<div class="container-fluid" id="gridview">
	<a  href="{{ url('/contests')}}" class="float-right btn btn-outline-dark">Contests</a>
	<h1>Contest Classes</h1>

    {!! $grid !!}
</div>

@endsection