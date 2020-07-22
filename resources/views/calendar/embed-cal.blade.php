@extends('layouts.iframe')


@section('content')

<h2>Upcoming Events</h2>

<ul>
@foreach ($events as $event)
	<li>
		<h4><a href="/events/{{ $event->slug }}" target="_parent">{{ $event->name }}</a></h4>
		<p>
			@if($event->location) {{ $event->location }}, @endif {{ $event->nice_start_date }} 
			@if($event->start_time) {{ $event->start_time }} @endif
			@if($event->start_date!=$event->end_date)
				 to {{ $event->nice_end_date }} 
				@if($event->end_time) {{ $event->end_time }} @endif
			@endif
		</p>
	</li>
@endforeach
</ul>
@endsection