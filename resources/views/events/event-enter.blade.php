@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<entry-add current-member-id="{{$member_id}}" event-id="{{$event_id}}"></entry-add>

</div>

@endsection