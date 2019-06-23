@extends('layouts.app')

@section('scripts')
	<script>
		window.Laravel.allowsEdit = <?php echo json_encode(isset($allows_edit) && $allows_edit==true ? true : false); ?>;
	</script>
@endsection

@section('content')


<div class="container" id="members">

	@can('gnz-member')
		<member member-id="{{ $member_id }}"></member>
	@else
		<p class="error">Sorry, you must be a validated GNZ member to see the member details.</p>

		<p>If you are a member of GNZ, use your <a href="/user/account">user account</a> page to validate your GNZ membership.
	@endcan
</div>

@endsection