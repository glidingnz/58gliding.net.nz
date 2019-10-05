@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<view-event org-id="{{$org['id']}}" event-id="{{$id}}"></view-event>

</div>

@endsection