@extends('layouts.app')

@section('content')
<div class="container-fluid" style="margin:0 15px 30px 15px">
    <div class="row">
        <div class="col-md-3"><h3 style="margin-top:-10px">Протоколы</h3></div>
    </div>
<div class="panel panel-default" style="margin: -5px 5px 60px 5px">
<!--    <div class="panel-heading">
        <h3 class="panel-title">Протоколы</h3>
    </div>-->
    <div class="panel-body">
<!--            <div class="row">
                <div style="margin-top:-18px" >
                    <div class="col-md-2">
                        <a href="#"><h6><span class="label label-default">Новый</span> Исходящий звонок</h6></a>
                    </div>
                    <div class="col-md-1">
                        <a href="#"><h6><span class="label label-default">Новая</span> Задача</h6></a>
                    </div>
                    <div class="col-md-2">
                        <a href="#"><h6><span class="label label-default">Новое</span> Обращение</h6></a>
                    </div>
                </div>
            </div>-->
                @if (count($chains) > 0)
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="active">
                            <th>№</th>
                            <th>Изменен</th>
                            <th>Пользователь</th>
                            <th>Нас.Пункт</th>
                            <th>Автор</th>
                            <th>Оператор</th>
                            <th>Статус</th>
                            <th>Открыт</th>
                            <th>Последний комментарий</th>
                            <th>Категория</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chains as $chain)
                        @if($chain->status == 'OPENED')
                          <tr class="warning">
                        @else 
                          <tr>
                        @endif
                            <td class="table-text">
                                <div>{{ $chain->id }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ date('d.m.y H:i',$chain->update_time) }}</div>
                            </td>
                            <td class="table-text">
                                <div><a href="{{ route('clients.view', ['id' => $chain->client_id]) }}">{{ $chain->surname." ".$chain->c_name." ".$chain->patronymic }}</a></div>
                            </td>
                            <td class="table-text">
                                <div>{{ $chain->address }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $chain->u_name }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $users->find($chain->operator_id)->name }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $ch_status[$chain->status] }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ date('d.m.y H:i',$chain->opening_time) }}</div>
                            </td>
                            <td class="table-text">
                                <div><a href="{{ route('chains.view', ['id' => $chain->id]) }}">{{ $chain->last_comment }}</a></div>
                            </td>
                            <td class="table-text">
                                @foreach (explode(",",$chain->cat_names) as $cat_name)
                                    @if($cat_name != 'NULL')
                                        <li>{{ rtrim($cat_name, ", ") }}</li>
                                    @endif    
                                @endforeach                                
                            </td>
                        </tr>
                        @endforeach
                        {{ $chains->links() }}
                <!--        <tr>
                            <td></td>
                        </tr>-->
                    </tbody>
                </table> 
                @else
                <h3>Нет Протоколов!!!!!</h3>
                @endif
    </div>
</div>
</div>
@endsection
