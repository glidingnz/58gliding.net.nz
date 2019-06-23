@extends('layouts.app')

@section('scripts')
	<script>
		window.Laravel.allowsEdit = <?php echo json_encode(isset($allows_edit) && $allows_edit==true ? true : false); ?>;
	</script>
@endsection

@section('content')



<div class="container">

	<?php if (isset($allows_edit) && $allows_edit) { ?>
		<a class="btn btn-default" style="float:right;" href="/members/{{$member_id}}/achievements/edit">Edit Achievements</a>
	<?php } ?>

	<h1 class="results-title"><a href="/members">Members</a> &raquo; <a href="/members/{{$member_id}}">{{$member['first_name']}} {{$member['last_name']}}</a>  &raquo; Achievements</h1>

	<achievements member-id="{{$member_id}}"></achievements>

</div>

@endsection