@extends('layouts.app')

@section('content')


<div class="container">

	<?php if ($org) { ?>

		<a class="btn btn-primary" href="/calendar/edit">Edit Club Calendar</a>

	<?php } else { ?> 

		<div class="jumbotron danger">
			<h1 class="display-4">Club Calendar</h1>
			<p class="lead">Sorry you'll need to select a club to view the club calendar</p>
			<hr class="my-4">
			<a href="/" class="btn btn-primary btn-lg">select a club</a>
		</div>

	<?php } ?>


</div>

@endsection