@extends('layouts.app')

@section('content')


<div class="container">

	<h1 class="results-title">BFRs and Medical Ratings Report</h1>

	@can('club-member')
		<ratings-report org="{{$org['gnz_code']}}" allows-edit="{{$allows_edit}}"></ratings-report>
	@else
		<p class="error">Sorry, you must be a club member to view BFR & Medical Ratings.</p>
	@endcan

	

</div>

@endsection