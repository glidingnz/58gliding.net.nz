@extends('layouts.app')

@section('content')

<div class="container">

		<div class="row">
			<div class="col-md-12">

				<?php if (!$org) { ?><h1>Welcome to gliding.net.nz</h1><?php } ?>
				<?php if ($org) { ?>
					<h1>{{$org->name}}</h1>
				<?php } ?>

			</div>
		</div>

	<div class="row">

		<?php if (!$org) { ?>
			<div class="col-md-6">
				<h2>New Zealand Clubs</h2>
				<orgs-component></orgs-component>
				{{-- <example-component></example-component> --}}
			</div>
		<?php } else { ?>


		<?php } ?>


		<?php if ($org) { ?>
			<div class="col-md-6">

				<h2>Club Tools</h2>
				<div class="list-group app-list">
					<a class="list-group-item" href="/ratings-report"><span class="fa fa-clipboard-check"></span> BFRs, Medicals &amp; Ratings</a>
					<a class="list-group-item" href="/members"><span class="fa fa-users"></span> Members</a>
{{-- 					<a class="list-group-item" href="/calendar">Calendar</a>
	--}}
				</div>

				<a href="//{{env('APP_DOMAIN')}}/">Switch Club</a>
			</div>
		<?php } ?>

		<div class="col-md-6">

			<h2>Nationwide Tools</h2>

			<div class="list-group app-list">
                <a class="list-group-item" href="{{ url('contests')}}"><span class="fa fa-trophy"></span> Events</a>
				<a class="list-group-item" href="{{ url('aircraft')}}"><span class="fa fa-plane"></span> Aircraft Database</a>
				<a class="list-group-item" href="{{ url('tracking')}}"><span class="fa fa-map"></span> Tracking</a>
                <a class="list-group-item" href="{{ url('cups')}}"><span class="fa fa-map-marker-alt"></span> Turnpoints</a>
			</div>

		</div>


	</div>
</div>
@endsection
