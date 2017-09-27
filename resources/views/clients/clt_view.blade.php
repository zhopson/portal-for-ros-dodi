@extends('layouts.app')

@section('content')
@if (count($clients) > 0)
<div class="container">
    @foreach ($clients as $client)
    <div class="row">
        <h3 style="margin-top:-10px">{{ $client->clt_name }}</h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <a href="#" style="margin-left:20px"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a>    
                    </div>
                    <div class="row">
                        <h4 style="margin-left:18px">Сведения о пользователе</h4>
                    </div>
                    <div class="row">
                        <table class="table table-hover">
                            @if($client->problematic == 1)
                            <tr>
                                <td class="table-text">
                                </td>
                                <td class="table-text">
                                    Проблемный
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Добавлен</div>
                                </td>
                                <td class="table-text">
                                    {{$client->creation_time}}
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                </td>
                                <td class="table-text">
                                    @if($client->active == 1)
                                    Активный
                                    @else
                                    Неактивный
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Контракт</div>
                                </td>
                                <td class="table-text">
                                    <ins>{{$client->contract_name}}</ins>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Отец</div>
                                </td>
                                <td class="table-text">
                                    {{$client->father}}
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Мать</div>
                                </td>
                                <td class="table-text">
                                    {{$client->mother}}
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Адрес</div>
                                </td>
                                <td class="table-text">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Комментарий</div>
                                </td>
                                <td class="table-text">
                                    {{$client->comment}}
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Провайдер</div>
                                </td>
                                <td class="table-text">
                                    {{$client->prd_name}}
                                </td>
                            </tr>
                            @if($client->ip_addresses != '{NULL}')
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">IP Адреса</div>
                                </td>
                                <td class="table-text">
                                    {{$client->ip_addresses}}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Группы</div>
                                </td>
                                <td class="table-text">
                                    
                                </td>
                            </tr>                            
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Телефоны</div>
                                </td>
                                <td class="table-text">
                                    <a href="#"><span class="glyphicon glyphicon-phone" aria-hidden="true"></span> Внештатные помощники</a><br>
                                    @foreach (explode("\n",$client->numbers) as $number)
                                    @php    
                                        $nums_str = '';
                                        $num_arr = (explode(":",$number));
                                        $nums_str = $nums_str.$num_arr[0];
                                        if (  isset($num_arr[1]) &&  $num_arr[1]!='' )
                                            { $nums_str = $nums_str.'('.$num_arr[1].')'; }
                                        echo e($nums_str).'<br>';
                                    @endphp
                                    @endforeach
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                        <div class="col-md-3">
                            <a href="#" style="margin-left:-10px"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Звонок</a>    
                        </div>
                        <div class="col-md-3">
                            <a href="#"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Задача</a>    
                        </div>
                        <div class="col-md-3">
                            <a href="#"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Обращение</a>    
                        </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<h3>Запись отсутствует!</h3>
@endif    

@endsection
