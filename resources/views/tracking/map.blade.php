@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<?php if (isset($year) && isset($month) && isset($day)) { ?>
		<tracking year="{{$year}}" month="{{$month}}" day="{{$day}}"></tracking>
	<?php } else { ?>
		<tracking></tracking>
	<?php } ?>
</div>

@endsection
