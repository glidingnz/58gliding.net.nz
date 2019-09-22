@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<edit-event org-id="{{$org['id']}}" event-id="{{$id}}"></edit-event>

</div>

@endsection