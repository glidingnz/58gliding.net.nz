@extends('layouts.app')

@section('content')

<div class="container">
	<h1><a href="/aircraft">Aircraft</a> &raquo; {{$rego}}</h1>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Registration</label>
		<div class="col-xs-6 col-md-8">{{$rego}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Manufacturer</label>
		<div class="col-xs-6 col-md-8">{{$manufacturer}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">serial</label>
		<div class="col-xs-6 col-md-8">{{$serial}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Model</label>
		<div class="col-xs-6 col-md-8">{{$model}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Owner</label>
		<div class="col-xs-6 col-md-8">{{$owner}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">mctow</label>
		<div class="col-xs-6 col-md-8">{{$mctow}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">CAA Class</label>
		<div class="col-xs-6 col-md-8">{{$class}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Transponder</label>
		<div class="col-xs-6 col-md-8">{{$transponder}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Trailer</label>
		<div class="col-xs-6 col-md-8">{{$trailer}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Seats</label>
		<div class="col-xs-6 col-md-8">{{$seats}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Towplane?</label>
		<div class="col-xs-6 col-md-8">{{$towplane}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Self Launcher?</label>
		<div class="col-xs-6 col-md-8">{{$self_launcher}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Sustainer?</label>
		<div class="col-xs-6 col-md-8">{{$sustainer}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Retractable?</label>
		<div class="col-xs-6 col-md-8">{{$retractable}}</div>
	</div>
	
	<div class="row">
		<label class="col-xs-6 col-md-2">Flarm HEX</label>
		<div class="col-xs-6 col-md-8">{{$flarm}}</div>
	</div>
</div>

@endsection
