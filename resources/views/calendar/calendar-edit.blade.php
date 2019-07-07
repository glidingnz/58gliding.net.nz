@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<edit-calendar org-id="{{$org['id']}}"></edit-calendar>

</div>

@endsection