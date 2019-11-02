@extends('layouts.app')

@section('content')


<div class="container-fluid" id="fleets">

	<fleets org-id="{{$org['id']}}"></fleets>

</div>

@endsection