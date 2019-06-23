@extends('layouts.app')

@section('content')

<div class="container">
	
		<div class="row">
			<div class="col-md-12">
				
				<?php if (!$org) { ?><h1>Clubs</h1><?php } ?>
				<?php if ($org) { ?>
					<h1>{{$org->name}}</h1>
				<?php } ?>

			</div>
		</div>
	
	<div class="row">

		<?php if (!$org) { ?>
			<div class="col-md-6">

				<orgs-component></orgs-component>

			</div>
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
				<a class="list-group-item" href="//{{env('APP_DOMAIN')}}/aircraft"><span class="fa fa-plane"></span> Aircraft Database</a>
				<a class="list-group-item" href="/tracking"><span class="fa fa-map-marker-alt"></span> Tracking</a>
			</div>

		</div>


	</div>
</div>
@endsection
