@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<enter-event current-member-id="{{$member_id}}" event-id="{{$event_id}}"></enter-event>

</div>

@endsection