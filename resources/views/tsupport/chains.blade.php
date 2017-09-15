@extends('layouts.app')

@section('content')
<div class="panel panel-default" style="margin: -15px 5px 0px 5px">
    <div class="panel-heading">
        <h3 class="panel-title">Протоколы</h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div style="margin-top:-18px" >
                    <div class="col-md-2">
                        <a href="#"><h6><span class="label label-default">Новый</span>&nbsp;Исходящий звонок</h6></a>
                    </div>
                    <div class="col-md-1">
                        <a href="#"><h6><span class="label label-default">Новая</span>&nbsp;Задача</h6></a>
                    </div>
                    <div class="col-md-1">
                        <a href="#"><h6><span class="label label-default">Новое</span>&nbsp;Обращение</h6></a>
                    </div>
                </div>
            </div>
            <div class="row">
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
                                <div><a href="#">{{ $chain->surname." ".$chain->c_name." ".$chain->patronymic }}</a></div>
                            </td>
                            <td class="table-text">
                                <div>{{ $chain->address_id }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $chain->u_name }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $users->find($chain->operator_id)->name }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $chain->status }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ date('d.m.y H:i',$chain->opening_time) }}</div>
                            </td>
                            <td class="table-text">
                                <div><a href="{{ route('chains.view', ['id' => $chain->id]) }}">{{ $chain->last_comment }}</a></div>
                            </td>
                            <td class="table-text">
                                <div>{{ $chain->cat_name }}</div>
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
</div>
@endsection
