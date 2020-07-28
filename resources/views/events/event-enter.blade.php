@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<enter-event org-id="{{$org['id']}}" event-id="{{$id}}"></enter-event>

</div>

@endsection