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
<!--    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">-->
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">

  <style>
   .menu-text {
    color: #080; /* Цвет символа */
    /*font-size: 200%;  Размер шрифта */
   } 
   
   .header-text {
    color: whitesmoke; /* Цвет символа */
    /*font-size: 200%;  Размер шрифта */
   } 
/*   p {
    color: rgb(49, 151, 116);  Цвет текста 
   }*/

@media (max-width: 1400px) {
  .navbar-header {
    float: none;
  }
  .navbar-left,.navbar-right {
    float: none !important;
  }
  .navbar-toggle {
    display: block;
  }
  .navbar-collapse {
    border-top: 1px solid transparent;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.1);
  }
  .navbar-fixed-top {
    top: 0;
    border-width: 0 0 1px;
  }
  .navbar-collapse.collapse {
    display: none!important;
  }
  .navbar-nav {
    float: none!important;
    margin-top: 7.5px;
  }
  .navbar-nav>li {
    float: none;
  }
  .navbar-nav>li>a {
    padding-top: 10px;
    padding-bottom: 10px;
  }
  .collapse.in{
    display:block !important;
  }
}

  </style>    
    
    @yield('head')
</head>
<!--<body style="font-family: cursive, Arial, sans-serif !important;">-->
<body style="font-family: Arial, sans-serif;">    
<!--    <div id="app" style="background-image: url({{ asset('images/background/small4.jpg') }});">-->
<!--    <div id="app" style="background-image: url({{ asset('images/background/backgrnd.jpg') }}); background-size: 100% auto">-->
    <div id="app" style="background: url({{ asset('images/background/backgrnd.jpg') }}) no-repeat; background-size: cover;">
        <audio id='audio_remote' autoplay='autoplay'></audio>
        <audio id='ringbacktone' loop src='/sounds/ringbacktone.wav'></audio>
        
<!--        <nav class="navbar navbar-default navbar-static-top" style="background-image: url({{ asset('images/background/big1.jpg') }}); height: 8vh">-->
        <nav class="navbar navbar-default navbar-static-top" style="height: 15vh; -webkit-box-shadow: 6px 6px 6px 0px rgba(0,0,0,0.75); -moz-box-shadow: 6px 6px 6px 0px rgba(0,0,0,0.75); box-shadow: 6px 6px 6px 0px rgba(0,0,0,0.75);">
            <div class="container-fluid">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse1"  aria-expanded="false">
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
                                        @endauth
                                    </div>
                                @endif-->

                    <!-- Branding Image -->
                    <!--                    <a class="navbar-brand" href="{{ url('/') }}">
                                            {{ config('app.name', 'Laravel') }}
                                            <div class="menu-text">Главная</div>
                                        </a>-->
<!--                    <div class="navbar-left">
                        <a class="navbar-brand" href="{{ url('/') }}">
                            <img alt="Brand" src={{ asset('images/logo.png') }} width=150>width=240 height=135vh
                        </a>
                    </div>-->
                    <a href="{{ url('/') }}"><img alt="Brand" src={{ asset('images/logo.png') }} width=210></a> 
<!--                    <a href="{{ url('/') }}"><img class="img-responsive" alt="Brand" src={{ asset('images/logo.png') }} width=20%></a> -->
                </div>
<!--                <p class="navbar-text"> </p>-->
                <div class="collapse navbar-collapse" id="app-navbar-collapse1" style="font-family: sans-serif, Arial; font-size: 17px; background-color: white;">  <!--margin: 0 -15px 0 -15px;-->
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav" style="margin-top:4vh">
<!--                        &nbsp;-->

                        @guest    
                        @else    
<!--                        <li><a href="{{ url('/lc') }}">Личный кабинет</a></li>-->
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="menu-text">Личный кабинет<span class="caret"></span></div></a>
<!--                            <ul class="dropdown-menu"  style="background-image: url({{ asset('images/background/small6.jpg') }});">-->
                            <ul class="dropdown-menu">
                                <li><a href="{{  url('/lc') }}"><div class="menu-text">Профиль</div></a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('lc.personal') }}"><div class="menu-text">Мои данные</div></a></li>
                            </ul>
                        </li>
                        @if ( Auth::user()->hasRole('Сотрудники ТП РОС') )
                        <li><a href="{{ url('/telephony') }}"><div class="menu-text">Телефония</div></a></li><!--route('telephony')-->
<!--                        <li><a href="{{ url('/') }}">Тех.поддержка</a></li>-->
                        @endif
                        @if ( !Auth::user()->hasRole('Ученики') )
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="menu-text">Тех.поддержка<span class="caret"></span></div></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ url('/chains') }}"><div class="menu-text">Протоколы</div></a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ url('/calls') }}"><div class="menu-text">Звонки</div></a></li>
                                <li><a href="{{ url('/requests') }}"><div class="menu-text">Обращения</div></a></li>
                                <li><a href="{{ url('/tasks') }}"><div class="menu-text">Задачи</div></a></li>
                                <li><a href="{{ url('/notes') }}"><div class="menu-text">Заметки</div></a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ url('/clients') }}"><div class="menu-text">Пользователи</div></a></li>
                            </ul>
                        </li>
                        @endif
                        @if ( Auth::user()->hasRole('Сотрудники ТП РОС') )
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="menu-text">Мониторинг <span class="caret"></span></div></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('netflow.clients.graph',[ 'id' => '0','ip' => '0' ]) }}"><div class="menu-text">Трафик Клиентов</div></a></li>
                                <li><a href="{{ route('netflow.common.graph') }}"><div class="menu-text">Трафик Общий</div></a></li>
                            </ul>
                        </li>
<!--                        <li><a href="{{ url('/') }}">Мониторинг</a></li>-->
                        <li><a href="{{ url('/documents') }}"><div class="menu-text">WiKi</div></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="menu-text">Справочники <span class="caret"></span></div></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('groups') }}"><div class="menu-text">Группы</div></a></li>
                                <li><a href="{{ route('providers') }}"><div class="menu-text">Провайдеры</div></a></li>
                                <li><a href="{{ route('contacts') }}"><div class="menu-text">Контакты</div></a></li>
                                <li><a href="{{ route('categories') }}"><div class="menu-text">Категории</div></a></li>
                                <li><a href="{{ route('contracts') }}"><div class="menu-text">Контракты</div></a></li>
                            </ul>
                        </li>
                        @endif
                        @if (Auth::user()->is_admin)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><div class="menu-text">Администрирование <span class="caret"></span></div></a>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('admin.users') }}"><div class="menu-text">Юзеры и группы</div></a></li>
                                <!--                                <li><a href="#">Something else here</a></li>
                                                                <li role="separator" class="divider"></li>
                                                                <li><a href="#">Separated link</a></li>
                                                                <li role="separator" class="divider"></li>
                                                                <li><a href="#">One more separated link</a></li>-->
                            </ul>
                        </li>
                        @endif    

                        @endguest

                        
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right" style="margin-top: 4vh;">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}"><div class="menu-text">Войти</div></a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    <div class="menu-text">{{ Auth::user()->name }} <span class="caret"></span></div>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            <div class="menu-text">Выйти</div>
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
        
<!--модальные диалоги-->

<div class="modal fade" id="id_DBReqModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Информация</h4>
      </div>
      <div class="modal-body">
          <h3 style="padding-left:15px;"><img src="{{ asset('images/wait.gif') }}" alt="">&nbsp;&nbsp; Идет запрос в БД&hellip;</h3>
      </div>
      <div class="modal-footer">
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal --> 


<!--модальные диалоги-->        

        @yield('content')
    </div>
    <div id="footer">
                <nav class="navbar navbar-nav navbar-static-bottom" style="background-color: #DDD; width:100%; height: 6vh">
                    <div class="container">
                        <div class="navbar-header">
                            <p class="navbar-text"><strong>Copyright © <mark>m.a.n.</mark> 2017-2021. All Rights Reserved.</strong></p>
                        </div>
                        <div class="collapse navbar-collapse" id="footer-navbar-collapse">
                            <ul class="nav navbar-nav">
                            </ul>                    
                            <ul class="nav navbar-nav navbar-right">
                            </ul>
                        </div>
                    </div>
                </nav>

<!--        <div class="panel panel-default" style="docked: 'bottom'">
            <div class="panel-body">
                Basic panel example
            </div>
        </div>-->

    </div>

    <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}"></script>
        
    @yield('footer')

</body>
</html>
