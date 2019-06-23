@extends('layouts.app')

@section('content')


<div class="container">

	<h1 class="results-title">BFRs and Medical Ratings Report</h1>

	<ratings-report org="{{$org['gnz_code']}}" allows-edit="{{$allows_edit}}"></ratings-report>

</div>

@endsection