@extends('layouts.app')

@section('content')


<div class="container-fluid">

	Edit Entry!

	<entry-edit editcode="{{$entry->editcode}}"></entry-edit>
	

</div>

@endsection