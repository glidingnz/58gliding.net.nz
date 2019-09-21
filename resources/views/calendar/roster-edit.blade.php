@extends('layouts.app')

@section('content')


<div class="container-fluid">

	<?php if ($org) { ?>
	
		<edit-roster org-id="{{$org['id']}}"></edit-roster>

	<?php } ?>

</div>

@endsection