<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<meta name="apple-mobile-web-app-title" content="gliding.net.nz" />
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />

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
				'BASE_URL' => URL::to('/'),
				'messages' => $messages,
				'loggedIn' => (Auth::user()!=null) ? true : false,
				'admin' => (Auth::user()!=null && Auth::user()->can('admin')) ? true : false,
				'contestAdmin' => (Auth::user()!=null && Auth::user()->can('contest-admin')) ? true : false,
				'clubAdmin' => (Auth::user()!=null && Auth::user()->can('club-admin')) ? true : false,
				'clubMember' => (Auth::user()!=null && Auth::user()->can('club-member')) ? true : false,
				'editAwards' => (Auth::user()!=null && Auth::user()->can('edit-awards')) ? true : false,
				'gnzMember' => (Auth::user()!=null && Auth::user()->can('gnz-member')) ? true : false,
				'viewMembership' => (Auth::user()!=null && Auth::user()->can('view-membership')) ? true : false,
				'apple_auth' =>  env('APPLE_MAPS_AUTHORISATION_CALLBACK'),
			]); ?>
		</script>
	</head>
	<body>
		<div id="app" class="<?php if (isset($app_class)) echo $app_class; ?>">

			<nav class="main-nav navbar navbar-dark bg-dark navbar-expand">
				<a class="navbar-brand" href="{{ url('/') }}">
					<?php if (isset($org) && $org->slug!='gnz') { ?>
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
					<li class="nav-item"><a class="nav-link" href="{{ url('/events')}}">Events</a></li>
					@can('experimental-features')
						<li class="nav-item"><a class="nav-link" href="{{ url('/timesheets')}}">Time Sheets</a></li>
					@endcan

					<?php if (isset($org) && $org->slug!='gnz') { ?>
						<li class="nav-item"><a class="nav-link" href="{{ url('/flying-days')}}">Flying Days</a></li>
						<li class="nav-item"><a class="nav-link" href="{{ url('/ratings-report')}}">BFR &amp; Medicals</a></li>
					<?php } ?>

					<li class="nav-messages"></li>
				</ul>

				<!-- Right Side Of Navbar -->
				<ul class="navbar-nav ml-auto">

					<?php if (isset($org->website) && $org->website!='') { ?>
						<?php if ($org->slug=='gnz') { ?>
							<li class="nav-item">
								<a class="nav-link" href="http://{{$org->website}}">GNZ Website</a>
							</li>
						<?php } else { ?>
							<li class="nav-item">
								<a class="nav-link" href="http://{{$org->website}}">Club Website</a>
							</li>
						<?php } ?>
					<?php } ?>

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
			</nav>

			<messages></messages>

			@yield('content')

			<div class="footer pb-4">
				<hr class="ml-4 mr-4 mt-4">

				&copy; <?php echo date('Y'); ?>  <a href="http://gliding.co.nz/">Gliding New Zealand</a>

				<a href="//{{env('APP_DOMAIN')}}/" class="ml-4">Switch Club</a>

				<a href="http://gliding.co.nz/" class="ml-4">GNZ Main Website</a>

				<?php if (isset($org) && $org->slug!='gnz') { ?>
					<a class="ml-4" href="http://{{$org->website}}">{{$org->short_name}} Website</a>
				<?php } ?>
			</div>

		</div>

		<!-- load page specific scripts -->
		@yield('scripts')

		<!-- load page specific scripts -->
		@yield('page-scripts')

	</body>
</html>
