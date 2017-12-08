@extends('layouts.app')

@section('content')
<div class="container" style="margin:0 30px 45px 30px">
    <div class="row">
        <div class="col-md-6">
            <div class="page-header" style="margin-top:-18px">
                <h2>Личный кабинет</h2>
            </div>            
        </div>
    </div>
    <!--    <div class="row">
            <div class="col-md-6">
                Уровень доступа:
            </div>
        </div>-->
    <div class="row">
        <!--        <div class="col-md-8 col-md-offset-2">-->
        <div class="panel panel-default">
            <div class="panel-heading">Профиль</div>

            <div class="panel-body">
                @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
                @endif

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-5">
                            <h4 style="margin: 25px 0 20px 18px">Общая информация</h4>
                            <table class="table table-hover">
                                <tr>
                                    <td class="table-text">
                                        <div class="pull-right">Имя пользователя</div>
                                    </td>
                                    <td class="table-text">
                                        {{ Auth::user()->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-text">
                                        <div class="pull-right">E-Mail</div>
                                    </td>
                                    <td class="table-text">
                                        {{ Auth::user()->email }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-text">
                                        <div class="pull-right">Права</div>
                                    </td>
                                    <td class="table-text">
                                        @if ( Auth::user()->is_admin )
                                            Администратор
                                        @else 
                                            Пользователь
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="table-text">
                                        <div class="pull-right">Дата создания</div>
                                    </td>
                                    <td class="table-text">
                                        {{ Auth::user()->created_at }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-1">

                        </div>
                        <div class="col-md-3">
                            <h4 style="margin: 25px 0 20px 18px">Группы</h4>
                            <table class="table table-hover">
                                
                                @foreach ( Auth::user()->roles as $role)
                                <tr>
                                    <td class="table-text">
                                        <li>{{ $role->role }}</li>
                                    </td>
                                </tr>
                                @endforeach                                    
                            </table>

                        </div>
                    </div>

<!--                    <p>Вы вошли, как <mark>{{ Auth::user()->name }}</mark></p>-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
