<h1>Users with changed addresses today</h1>

@foreach ($users as $user)

	<h2>{{$user['first_name']}} {{$user['last_name']}}</a></h2>

	<b>New Address:</b><br>
	{{$user['address_1']}}<br>
	@if($user['address_2']!=='') 
		{{$user['address_2']}}<br>
	@endif
	{{$user['city']}} {{$user['zip_post']}}<br>
	{{$user['country']}}<br>
	<br>

	<b>Changed Fields:</b><br>
	@foreach ($user['changes'] AS $key=>$change)
		{{$key}}: '{{$change['old']}}' -> '{{$change['new']}}'<br>
	@endforeach
	<br>

	<a href="http://members.gliding.co.nz/index.php?r=member/update&id={{$user['id']}}">Old Edit</a> | <a href="http://www.gliding.net.nz/members/{{$user['id']}}/edit">New Edit</a>

	<hr>

@endforeach