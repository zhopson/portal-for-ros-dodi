@extends('layouts.app')

<style>
    html, body {
        background: url({{ asset('images/background/backgrnd.jpg') }}) no-repeat;
        background-size: 100% 110%;  
        
        background-color: #fff;
/*        color: #636b6f;*/
/*        color: #636b6f;*/
/*        font-family: 'American Typewriter', Courier, monospace;*/
        font-weight: 100;
        /*height: 100vh;*/
        margin: 0;
    }

    .full-height {
        margin-top: -20px;
        height: 78vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .top-right {
        position: absolute;
        right: 10px;
        top: 18px;
    }

    .content {
        text-align: center;
    }

    .title {
        font-size: 74px;
        font-family: Arial, monospace;
        color: #F5F5F5; /* Цвет символа */
/*        text-shadow: 2px 2px 4px #000000;*/
    }

   .shadowtext {
        text-shadow: 6px 6px 6px black, 0 0; /* Параметры тени */
   }

   .shadowtext-links {
        text-shadow: 2px 2px 4px black, 0 0; /* Параметры тени */
   }
   
    .links > a {
/*        color: #636b6f;*/
        color: #F5F5F5;
        padding: 0px 105px 0 75px;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: .1rem;
        text-decoration: none;
        text-transform: uppercase;
    }

    .m-b-md {
        margin-bottom: 50px;
    }
    
/*   .menu-text {
    color: #F5F5F5;  Цвет символа 
    font-size: 200%;  Размер шрифта 
   } */
   
</style>

@section('content')
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="col-md-8 col-md-offset-2">
            <div class="title m-b-md shadowtext">
                Добро пожаловать на портал
                РОС ДОДИ!
            </div>
            <div class="links shadowtext-links">
                <a href="#">Информация о проекте</a>
                <a href="#">Контакты</a>
            </div>
        </div>
    </div>
</div>    
@endsection

