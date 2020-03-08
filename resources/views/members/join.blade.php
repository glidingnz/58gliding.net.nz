@extends('layouts.app')

@section('content')


<div class="container-fluid" id="members">
	<member-join org-code="{{$org['gnz_code']}}" org-name="{{$org['name']}}"></member-join>
</div>

@endsection