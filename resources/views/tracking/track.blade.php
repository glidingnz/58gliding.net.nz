@extends('layouts.app')

@section('content')

<div class="container-fluid">

	<?php if (isset($year) && isset($month) && isset($day) && isset($rego) && isset($hex)) { ?>
		<track year="{{$year}}" month="{{$month}}" day="{{$day}}" rego="{{$rego}}" hex="{{$hex}}"></track>
	<?php } else { ?>
		Aircraft on that day not found
	<?php } ?>
</div>

@endsection
