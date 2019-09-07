<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" >

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Gliding New Zealand</title>

		<!-- Styles -->
		<link href="{{ asset('/css/app.css')}}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css')}}">

		<?php
		// get messages defined by flash data
		if (Session::has('error')) Messages::error(Session::get('error'));
		if (Session::has('success')) Messages::success(Session::get('success'));
		if (Session::has('warning')) Messages::warning(Session::get('warning'));
		if (Session::has('note')) Messages::note(Session::get('note'));

		// get all validation errors
		foreach ($errors->all() as $error)
		{
			Messages::error($error);
		}

		// fetch all messages stored in the messages system to hand over to javascript
		$messages = Messages::fetch();
		?>

		<!-- Scripts -->
		<script>
			window.Laravel = <?php echo json_encode([
				'csrfToken' => csrf_token(),
				'APP_DOMAIN' => env('APP_DOMAIN'),
				'messages' => $messages,
				'admin' => (Auth::user()!=null && Auth::user()->can('admin')) ? true : false,
				'clubAdmin' => (Auth::user()!=null && Auth::user()->can('club-admin')) ? true : false,
				'clubMember' => (Auth::user()!=null && Auth::user()->can('club-member')) ? true : false,
				'apple_auth' =>  env('APPLE_MAPS_AUTHORISATION_CALLBACK'),
			]); ?>
		</script>
	</head>
	<body>
		<div id="app">
			<nav class="navbar navbar-dark bg-dark navbar-expand-md">
				<a class="navbar-brand" href="{{ url('/') }}">
					<?php if (isset($org) && $org) { ?>
						{{$org->name}}
					<?php } else { ?>
						Gliding.net.nz
					<?php } ?>
				</a>
				<!-- Left Side Of Navbar -->
				<ul class="navbar-nav">
					<li class="nav-item"><a class="nav-link" href="{{ url('/aircraft')}}">Aircraft</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ url('/members')}}">Members</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ url('/tracking')}}">Tracking</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ url('/cups')}}">Waypoints</a></li>
					<li class="nav-item"><a class="nav-link" href="{{ url('/contests')}}">Contests</a></li>

					{{-- <li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Waypoints</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="{{ url('/cups')}}">Download Waypoints</a>
							<a class="dropdown-item" href="{{ url('/waypoints')}}">Waypoint Database</a>
						</div>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">Contests</a>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="{{ url('/contests')}}">Contests</a>
							<a class="dropdown-item" href="{{ url('/contestclasses')}}">Classes</a>
						</div>
					</li> --}}
					
					<li class="nav-messages"></li>
				</ul>

				<!-- Right Side Of Navbar -->
				<ul class="navbar-nav ml-auto">
					<!-- Authentication Links -->
					@if (Auth::guest())
					<li class="nav-item"><a class="nav-link " href="{{ url('/login') }}">Login</a></li>
					<li class="nav-item"><a class="nav-link " href="{{ url('/register') }}">Register</a></li>
					@else
					@can('admin') <li class="nav-item"><a class="nav-link" href="{{ url('/admin') }}">Admin</a></li> @endcan
					<li class="nav-item"><a class="nav-link" href="{{ url('/user/account') }}"><span class="fa fa-user"></span> {{ Auth::user()->first_name }} </a></li>
					<li class="nav-item">
						<a class="nav-link" href="{{ url('/logout') }}"
							onclick="event.preventDefault();
							document.getElementById('logout-form').submit();">
							Logout
						</a>
						<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
							{{ csrf_field() }}
						</form>
					</li>
					@endif
				</ul>
				<messages></messages>
			</nav>

			@yield('content')

		</div>

		<!-- load page specific scripts -->
		@yield('scripts')

	</body>
</html>
