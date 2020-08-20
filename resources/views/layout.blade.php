<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#343a40"> {{-- bg-dark --}}

    <title>@yield ('title')</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ URL::asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ URL::asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ URL::asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ URL::asset('favicon/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ URL::asset('favicon/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="Mangapie">
    <meta name="application-name" content="Mangapie">
    <meta name="msapplication-TileColor" content="#3d3f4c">
    <meta name="theme-color" content="#ffffff">

    <link href="{{ URL::asset('assets/mangapie.css') }}" rel="stylesheet">

    @yield ('stylesheets')

    <script src="{{ URL::asset('assets/mangapie.js') }}"></script>
</head>
<body>

<nav class="navbar navbar-dark bg-dark sticky-top @if (isset($archive, $page, $pageCount)) d-none @endif">
    <div class="container">
        <a class="navbar-brand" href="{{ URL::action('HomeController@index') }}"><img style="width: 32px;" src="{{ URL::asset('favicon/favicon.svg') }}">&nbsp;Mangapie</a>

        <div class="d-none d-sm-block">
            @component ('shared.searchbar', ['searchbarId' => 'searchbar'])
            @endcomponent
        </div>

        @include ('shared.notifications')

        @admin
            <a class="ml-1" href="{{ URL::action('AdminController@index') }}" title="Admin">
                <button class="navbar-toggler btn btn-outline-secondary" type="button">
                    <span class="fa fa-wrench"></span>
                </button>
            </a>
        @endadmin

        <div class="ml-1 mr-1"></div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu-collapse" aria-expanded="false">
            <span class="fa fa-navicon"></span>
        </button>

        @admin
            <div class="collapse navbar-collapse" id="admin-collapse">
                <ul class="nav navbar-nav text-right">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('AdminController@index') }}">
                            <span class="fa fa-dashboard"></span>
                            &nbsp;Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('AdminController@users') }}">
                            <span class="fa fa-users"></span>
                            &nbsp;Users
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('AdminController@libraries') }}">
                            <span class="fa fa-book"></span>
                            &nbsp;Libraries
                        </a>
                    </li>
                </ul>
            </div>
        @endadmin

        <div class="collapse navbar-collapse" id="menu-collapse">
            <ul class="nav navbar-nav text-right">
                <div class="d-block d-sm-none mt-3">
                    @component ('shared.searchbar', ['searchbarId' => 'searchbar-small'])
                    @endcomponent
                </div>

                @auth
                    <li class="nav-item">
                        <span class="navbar-text">Signed in as <strong>{{ auth()->user()->name }}</strong></span>
                    </li>
                    <li class="nav-item">
                        <hr class="m-1">
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('UserController@show', [auth()->user()]) }}"><span class="fa fa-user"></span>&nbsp;Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('UserController@history', [auth()->user()]) }}"><span class="fa fa-history"></span>&nbsp;History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('FavoriteController@index') }}"><span class="fa fa-heart"></span>&nbsp;Favorites</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('UserController@statistics', [auth()->user()]) }}"><span class="fa fa-list-alt"></span>&nbsp;Lists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ URL::action('UserSettingsController@index') }}"><span class="fa fa-cog"></span>&nbsp;Settings</a>
                    </li>

                    <li class="nav-item">
                        {{ Form::open(['action' => 'Auth\LoginController@logout']) }}
                        <button class="nav-link form-control bg-transparent border-0 text-right" type="submit" style="cursor: pointer;"><span class="fa fa-sign-out"></span>&nbsp;Logout</button>
                        {{ Form::close() }}
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

@yield ('header-contents')

@yield ('content')

@yield ('footer-contents')

@auth
    @include ('shared.autocomplete')
    @yield ('scripts')
@endauth

</body>
