@extends('layouts.app')

@section('content')

<div class="container" id="edit-settings">

	<edit-member-types org-id="{{$org['id']}}" org-name="{{$org['name']}}"></edit-settings>

</div>

@endsection
