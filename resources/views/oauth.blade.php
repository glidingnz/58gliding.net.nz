@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<passport-clients></passport-clients>
		<passport-authorized-clients></passport-authorized-clients>
		<passport-personal-access-tokens></passport-personal-access-tokens>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
@endsection

