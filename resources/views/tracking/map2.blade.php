@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<?php if (isset($year) && isset($month) && isset($day)) { ?>
		<tracking2 year="{{$year}}" month="{{$month}}" day="{{$day}}"></tracking2>
	<?php } else { ?>
		<tracking2></tracking2>
	<?php } ?>
</div>

@endsection
