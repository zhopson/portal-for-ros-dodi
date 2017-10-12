<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

<!--    <title>{{ config('app.name', 'Laravel') }}</title>-->
    <title>Портал РОС ДОДИ</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body>
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

<!--            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif-->
                    
                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
<!--                        {{ config('app.name', 'Laravel') }}-->
                        Главная
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;

                        @guest    
                        @else    
                        <li><a href="{{ url('/lc') }}">Личный кабинет</a></li>
                        <li><a href="{{ url('/telephony') }}">Телефония</a></li><!--route('telephony')-->
<!--                        <li><a href="{{ url('/') }}">Тех.поддержка</a></li>-->

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Тех.поддержка<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/chains') }}">Протоколы</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Звонки</a></li>
                                <li><a href="#">Обращения</a></li>
                                <li><a href="#">Задачи</a></li>
                                <li><a href="#">Заметки</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ url('/clients') }}">Пользователи</a></li>
                            </ul>
                        </li>
                        
                        <li><a href="{{ url('/') }}">Мониторинг</a></li>
                        <li><a href="{{ url('/') }}">WiKi</a></li>
                        @if (Auth::user()->is_admin)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Администрирование <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('register') }}">Юзеры и группы</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                            </ul>
                        </li>
                        @endif    

                        @endguest

                        
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Войти</a></li>
<!--                            <li><a href="{{ route('register') }}">Зарегистрироваться</a></li>-->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Выйти
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
        <nav class="navbar navbar-default navbar-fixed-bottom">
            	<div class="container">
                <div class="navbar-header">
                    <p class="navbar-text"><strong>Copyright © <mark>m.a.n.</mark> 2017. All Rights Reserved.</strong></p>
		</div>
		</div>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
