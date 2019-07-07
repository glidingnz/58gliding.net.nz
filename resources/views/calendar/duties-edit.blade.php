@extends('layouts.app')

@section('content')


<div class="container">

	<?php if ($org) { ?>

		<edit-duties org-id="{{$org['id']}}"></edit-duties>

	<?php } else { ?> 

		<div class="jumbotron danger">
			<h1 class="display-4">Club Duties</h1>
			<p class="lead">Sorry you'll need to select a club to edit the club duties</p>
			<hr class="my-4">
			<a href="/" class="btn btn-primary btn-lg">select a club</a>
		</div>

	<?php } ?>

</div>

@endsection