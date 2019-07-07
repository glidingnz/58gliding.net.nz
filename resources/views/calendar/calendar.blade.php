@extends('layouts.app')

@section('content')


<div class="container">

	<?php if ($org) { ?>

		<h1>Calendar</h1>

		<a class="btn btn-primary" href="/calendar/edit">Edit Flying Days Calendar</a>
		<a class="btn btn-primary" href="/calendar/duties/edit">Edit Default Club Duties</a>
		<a class="btn btn-primary" href="/calendar/roster/edit">Edit Roster</a>

		<flying-calendar org-id="{{$org['id']}}"></flying-calendar>


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