@extends('layouts.app')

@section('content')


<div class="container" id="members">
	<member-log member-id="{{ $member_id }}"></member-log>
</div>

@endsection