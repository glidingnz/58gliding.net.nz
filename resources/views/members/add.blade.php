@extends('layouts.app')

@section('content')


<div class="container-fluid" id="members">
	<member-add org-id="{{$org['id']}}" org-name="{{$org['name']}}"></member-add>
</div>

@endsection