<!DOCTYPE html>
<html lang="en">
	<head> 
		<meta charset="utf-8">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Gliding New Zealand</title>

		<!-- Styles -->
		<link href="{{ asset('/css/iframe.css')}}" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css')}}">

	</head>
	<body>
		@yield('content')
	</body>
</html>
