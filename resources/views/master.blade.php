<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tracker Eve Online') }}</title>

        <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
     @yield('link')

    </head>
    <body>
        <div class="container-fluid" height ="20">
            @if (Route::has('login'))
                <div class="navbar navbar-expand-lg navbar-light bg-light box-shadow" >
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_t" aria-controls="navbar_t" aria-expanded="false" aria-label="Toggle">
                    <span class="navbar-toggler-icon"></span>
                  </button>        
                  <a class="navbar-brand" href="{{ url('/') }}">Tracker</a>
                  <div class="collapse navbar-collapse" id="navbar_t">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="navbar-brand" href="{{ url('/main') }}">Main</a>
                        </li>
                        <li class="nav-item">
                            <a class="navbar-brand" href="{{ url('/stats') }}">Graph</a>
                        </li>
                        @hasanyrole(['invite russian','invite english'])
                        <li class="nav-item">
                            <a class="navbar-brand" href="{{ route('invite.invite.index') }}">Invites</a>
                        </li>
                        @endhasanyrole
                    @if (Auth::check())
                        @hasanyrole(['admin','director'])
                            <li class="nav-item dropdown">
                                <a class="navbar-brand dropdown-toggle waves-effect waves-light" id="navbarDropdownMenuLink"               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Admin</a>
                                <div class="dropdown-menu dropdown-info" aria-labelledby="navbarDropdownMenuLink">
                                    <a class="dropdown-item waves-effect waves-light" href="{{ route('corporations.corporations.index') }}">Corporations</a>
                                    <a class="dropdown-item waves-effect waves-light" href="{{ route('alts.alts.index') }}">Alts</a> </a>
                                    <a class="dropdown-item waves-effect waves-light" href="{{ route('invite.invite.index') }}">Invite mail</a> </a>
                                    @role('admin')
                                    <a class="dropdown-item waves-effect waves-light" href="{{ route('invite.invite.list') }}">Invited characters</a> </a>
                                    <a class="dropdown-item waves-effect waves-light" href="{{ route('user_has_roles.user_has_roles.index') }}">User Has Roles</a>
                                    <a class="dropdown-item waves-effect waves-light" href="{{ route('notificaton_eves.notificaton_eves.index') }}">Notifications</a>
                                    <a class="dropdown-item waves-effect waves-light" href="#">...</a>
                                    @endrole
                                </div>
                            </li>
                        @endhasanyrole
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <p class="navbar-brand pull-left">{{ Auth::user()->name }}</a>
                        </li>
                        <li class="nav-item">
                        <form method="post" action="{{ route('logout') }}">
                            @csrf <!-- {{ csrf_field() }} -->
                            <button type='submit' class="btn mb-2">Logout</button>
                        </form>
                        </li>
                    @else
                    </ul>
                    <ul class="navbar-nav ml-auto">
                        <form method="post" action="{{ route('login') }}">
                            @csrf <!-- {{ csrf_field() }} -->
                            <input type="image" src="https://images.contentful.com/idjq7aai9ylm/18BxKSXCymyqY4QKo8KwKe/c2bdded6118472dd587c8107f24104d7/EVE_SSO_Login_Buttons_Small_White.png?w=195&amp;h=30" value="Login">
                        </form>
                    @endif
                    </ul>
                  </div>
                </div>
            @endif
        </div>
        <div class="container-fluid" height ="95%">
          @yield('content') 
        </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/typeahead.js') }}"></script>
     @yield('scripts')
    </body>
</html>
