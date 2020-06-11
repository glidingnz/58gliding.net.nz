@extends('layouts.app')


@section('content')


<div class="container">

	<h1 class="results-title"><a href="/members">Members</a> &raquo; <a href="/members/{{$member_id}}">{{$member['first_name']}} {{$member['last_name']}}</a>  &raquo; Achievements</h1>

	<achievements member-id="{{$member_id}}" allows-edit="{{$allows_edit}}"></achievements>

</div>

@endsection