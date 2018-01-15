<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/breadcrumbs.css') }}" rel="stylesheet">
    <link href="{{ asset('css/icheck/skins/square/blue.css') }}" rel="stylesheet">


    <!-- Scripts -->
    <script src="{{ asset('js/moment.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/angular.min.js') }}"></script>
    <script src="{{ asset('js/angular-sanitize.min.js') }}"></script>
    <script src="{{ asset('js/angular-route.min.js') }}"></script>
    <script src="{{ asset('js/underscore-min.js') }}"></script>
    <script src="{{ asset('js/patch.js') }}"></script>
    <script src="{{ asset('js/icheck.min.js') }}"></script>
    <script src="{{ asset('js/app/main.js') }}"></script>

    <!-- UTILS -->

    <script src="{{ asset('js/app/helpers/utils.js') }}"></script>

</head>
<body ng-app="ExchangeApp">
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Exchange') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Войти</a></li>
                        @else
                        @can('is-admin')
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <i class="fa fa-dashboard"></i> Панель администратора<span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="dashboard/#!/admin/users/list"><i class="fa fa-users"></i> Список пользователей</a>
                                    </li>
                                    <li>
                                        <a href="dashboard/#!/admin/currency/list"><i class="fa fa-money"></i> Список валют</a>
                                    </li>
                                    <li>
                                        <a href="dashboard/#!/admin/settings/list"><i class="fa fa-gears"></i> Список настроек</a>
                                    </li>
                                </ul>
                            </li>

                        @endcan
                                <li><a href="home/#!/dashboard/invoices">Все заявки</a></li>
                                <li><a href="home/#!/">Мои заявки</a></li>
                                <li><a href="home/#!/my-offers">Мои предложения</a></li>
                                <li class="dropdown">
                                    <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        <i class="fa fa-gears"></i> Настройки<span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="home/#!/user/settings"><i class="fa fa-user"></i> Мои настройки</a>
                                        </li>
                                        <li>
                                            <a href="home/#!/user/accounts"><i class="fa fa-credit-card"></i> Мои счета</a>
                                        </li>
                                        <li>
                                            <a href="home/#!/user/partners"><i class="fa fa-users"></i> Мои партнеры</a>
                                        </li>
                                    </ul>
                                </li>

                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">

                                    <i class="fa fa-user"></i> {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">

                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <i class="fa fa-sign-out"></i> Выйти
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

</body>
</html>
