@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<events org-id="{{$org['id']}}" org-name="{{$org['name']}}"></events>

</div>

@endsection