@extends('layouts.app')

@section('content')


<div class="container">

	<h1 class="results-title">

		<?php if (isset($_GET['from']) && $_GET['from']=='ratings') { ?>
			<a href="/ratings-report">BFR &amp; Medical Report</a>
		<?php } else { ?>
			<a href="/members">Members</a>
		<?php } ?>

		&raquo; <a href="/members/{{$member_id}}">{{$member['first_name']}} {{$member['last_name']}}</a>  &raquo; Ratings
	</h1>

	@can('club-member')
		<ratings member-id="{{$member_id}}" allows-edit="{{$allows_edit}}"></ratings>
	@else
		<p class="error">Sorry, you must be a club member to view BFR & Medical Ratings.</p>
	@endcan
	
</div>

@endsection