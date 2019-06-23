@extends('layouts.app')

@section('content')


<div class="container" id="members">

	@can('gnz-member')
		<members org-code="{{$org['gnz_code']}}"></members>
	@else
		<p class="error">Sorry, you must be a validated GNZ member to see the membership list.</p>

		<p>If you are a member of GNZ, use your <a href="/user/account">user account</a> page to validate your GNZ membership.
	@endcan
</div>

@endsection