@extends('layouts.app')

@section('content')

<div class="container">

	<h1 class="results-title">

		<?php if (isset($_GET['from']) && $_GET['from']=='ratings') { ?>
			<a href="/ratings-report">BFR &amp; Medical Report</a>
		<?php } else { ?>
			<a href="/members">Members</a>
		<?php } ?>

		&raquo; <a href="/members/{{$member_id}}">{{$member['first_name']}} {{$member['last_name']}}</a>  
		&raquo; <a href="/members/{{$member_id}}/ratings">Ratings</a>

		&raquo; {{$rating['name']}}
	</h1>


	@if(Gate::check('club-member') || Gate::check('edit-awards'))
		<rating member-id="{{$member_id}}" rating-member-id="{{$rating_member_id}}" allows-edit="{{$allows_edit}}"></rating>
	@else
		<p class="error">Sorry, you must be a club member to view BFR & Medical Ratings.</p>
	@endif
	

</div>

@endsection