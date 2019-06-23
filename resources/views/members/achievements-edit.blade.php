@extends('layouts.app')

@section('content')

<script>
	window.Laravel.allowsEdit = <?php echo json_encode(isset($allows_edit) && $allows_edit==true ? true : false); ?>;
</script>


<div class="container">

	<h1 class="results-title"><a href="/members">Members</a> &raquo; <a href="/members/{{$member_id}}">{{$member['first_name']}} {{$member['last_name']}}</a> &raquo; Edit Achievements &amp; Badges</h1>

	<edit-achievements member-id="{{$member_id}}"></edit-achievements>

</div>

@endsection