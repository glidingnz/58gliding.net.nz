@extends('layouts.app')

@section('content')

<div class="container">

		<div class="row">
			<div class="col-md-6">

				<?php if (!$org || $org->slug=='gnz') { ?><h1>Welcome to gliding.net.nz</h1><?php } ?>
				<?php if ($org && $org->slug!='gnz') { ?>
					<h1>{{$org->name}}</h1>
				<?php } ?>

			</div>
			<div class="col-md-6">
				
				<?php if (isset($org->website) && $org->website!='') { ?>
					<h2 class="float-right">
						<a href="http://{{$org->website}}">{{$org->website}}</a>
					</h2>
				<?php } ?>

			</div>
		</div>

	<div class="row">

		<?php if (!isset($org->slug) || $org->slug=='gnz') { ?>
			<div class="col-md-6">
				<h2>New Zealand Clubs</h2>
				<orgs-component></orgs-component>
			</div>
		<?php } else { ?>

		<?php } ?>

		<?php if ($org && isset($org->slug) && $org->slug!='gnz') { ?>
			<div class="col-md-6">

				<h2>Club Tools</h2>
				<div class="list-group app-list mb-2">
					<a class="list-group-item" href="/ratings-report"><span class="fa fa-clipboard-check"></span> BFRs, Medicals &amp; Ratings</a>
					<a class="list-group-item" href="/members"><span class="fa fa-users"></span> Membership List</a>
					<a class="list-group-item" href="/events"><span class="fa fa-calendar-day"></span>Events Calendar</a>
					<a class="list-group-item" href="/events"><span class="fa fa-calendar-alt"></span>Flying Calendar</a>
				</div>
			</div>
		<?php } ?>

		<div class="col-md-6">

			<h2>Nationwide Tools</h2>

			<div class="list-group app-list">
				<a class="list-group-item" href="{{ url('contests')}}"><span class="fa fa-trophy"></span> Contests</a>
				<a class="list-group-item" href="{{ url('aircraft')}}"><span class="fa fa-plane"></span> Aircraft Database</a>
				<a class="list-group-item" href="{{ url('tracking')}}"><span class="fa fa-globe-asia"></span> Tracking</a>
				<a class="list-group-item" href="{{ url('cups')}}"><span class="fa fa-map-marker-alt"></span> Waypoints</a>
			</div>

		</div>


	</div>
</div>
@endsection
