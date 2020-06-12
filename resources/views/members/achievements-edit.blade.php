@extends('layouts.app')

@section('content')


<div class="container">

	<h1 class="results-title"><a href="/members">Members</a> &raquo; <a href="/members/{{$member['id']}}">{{$member['first_name']}} {{$member['last_name']}}</a> &raquo; <a href="/members/{{$member['id']}}/achievements">Achievements</a> &raquo; Edit</h1>

	<edit-achievements member-id="{{$member['id']}}" allows-edit="{{$allows_edit}}"></edit-achievements>

</div>


@endsection