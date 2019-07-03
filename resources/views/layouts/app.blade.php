<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Gliding New Zealand</title>

        <!-- Styles -->
        <link href="{{ asset('/css/app.css')}}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css')}}">
        <!-- grid's css (required) -->
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/leantony/grid/css/grid.css') }}" />

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
            <nav class="navbar navbar-dark bg-dark navbar-expand">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <?php if (isset($org) && $org) { ?>
                        {{$org->name}}
                    <?php } else { ?>
                        Gliding.net.nz
                    <?php } ?>
                </a>

                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ url('/aircraft')}}">Aircraft</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/tracking')}}">Tracking</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/waypoints')}}">Turnpoints</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ url('/members')}}">Members</a></li>
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

        <!-- modal container (required if you need to render dynamic bootstrap modals) -->
        @include('leantony::modal.container')

        <script src="{{ asset('/js/app.js')}}"></script>

        <!-- required to supply js functionality for the grid -->
        <script src="{{ asset('vendor/leantony/grid/js/grid.js') }}"></script>
        <script>
            // send csrf token (see https://laravel.com/docs/5.6/csrf#csrf-x-csrf-token) - this is required
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // for the progress bar (required for progress bar functionality)
            $(document).on('pjax:start', function () {
                NProgress.start();
            });
            $(document).on('pjax:end', function () {
                NProgress.done();
            });
        </script>

        <!-- entry point for all scripts injected by the generated grids (required) -->
        @stack('grid_js')

        <!-- load page specific scripts -->
        @yield('scripts')

    </body>
</html>
