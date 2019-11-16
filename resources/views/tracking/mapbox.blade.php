@extends('layouts.app')

@section('content')

	<?php if (isset($year) && isset($month) && isset($day)) { ?>
		<tracking2 year="{{$year}}" month="{{$month}}" day="{{$day}}"></tracking2>
	<?php } else { ?>
		<tracking2></tracking2>
	<?php } ?>

@endsection

@section('page-scripts')
<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.5.0/mapbox-gl.css' rel='stylesheet' />
@endsection