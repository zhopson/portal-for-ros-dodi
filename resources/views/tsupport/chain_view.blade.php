@extends('layouts.app')

@section('content')
<div class="panel panel-default" style="margin: -15px 25px 0px 25px">
    <div class="panel-heading">
        <h3 class="panel-title">Протокол №{{ $id }}</h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Статус
                    </div>
                    <div class="col-md-1">
                        Закрыт
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Создан
                    </div>
                    <div class="col-md-1">
                        Дата
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Автор
                    </div>
                    <div class="col-md-1">
                        user
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Пользователь
                    </div>
                    <div class="col-md-1">
                        <div><a href="#">test</a></div>
<!--                        $chain->surname." ".$chain->c_name." ".$chain->patronymic-->
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Адрес
                    </div>
                    <div class="col-md-2">
                        Якутск, ул. Дзержинского д.12/3
                    </div>
                </div>
            </div>
            <div class="row">
                @if (count($chains_items) > 0)
                <table class="table table-hover table-bordered table-striped" style="margin: 15px 5px 0px 5px">
                    <thead>
                        <tr class="active">
                            <th>№</th>
                            <th>Создан</th>
                            <th>Автор</th>
                            <th>Изменен</th>
                            <th>Тип</th>
                            <th>Ответственный</th>
                            <th>Прогресс</th>
                            <th>Абонент</th>
                            <th>Статус</th>
                            <th>Источник</th>
                            <th>Старт</th>
                            <th>Срок</th>
                            <th>Комментарий</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chains_items as $item)
                        <tr>
                            <td class="table-text">
                                <div>{{ $item->id }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ date('d.m.y H:i',$item->creation_time) }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ $item->avtor }}</div>
                            </td>
                            <td class="table-text">
                                <div>{{ date('d.m.y H:i',$item->update_time) }}</div>
                            </td>
                            <td class="table-text">
                                @if ($item->call_id) 
                                <div>Звонок</div>
                                @elseif ($item->task_id)
                                <div>Задача</div>
                                @elseif ($item->request_id)
                                <div>Обращение</div>
                                @elseif ($item->note_id)
                                <div>Заметка</div>
                                @else
                                <div>Уведомление</div>
                                @endif
                            </td>
                            <td class="table-text">
                                <div>{{ $users->find($item->responsible_id)['name'] }}</div>
                            </td>
                            <td class="table-text">
                                @if ($item->progress) 
                                <div>{{ $item->progress }} %</div>
                                @endif
                            </td>
                            <td class="table-text">
                                <div>{{ $item->interlocutor }}</div>
                            </td>
                            <td class="table-text">
                                @if ($item->call_status) 
                                <div>{{ $item->call_status }}</div>
                                @elseif ($item->task_status)
                                <div>{{ $item->task_status }}</div>
                                @else
                                <div></div>
                                @endif
                            </td>
                            <td class="table-text">
                                @if ($item->provider_id == 1) 
                                <div>Клиент</div>
                                @elseif ($item->provider_id == 2)
                                <div>ЦДО</div>
                                @elseif ($item->provider_id == 3)
                                <div>РЦИТ</div>
                                @elseif ($item->provider_id == 4)
                                <div>Обзвон</div>
                                @else
                                <div></div>
                                @endif
                            </td>
                            <td class="table-text">
                                @if ($item->start_time)
                                <div>{{ date('d.m.y H:i',$item->start_time) }}</div>
                                @endif
                            </td>
                            <td class="table-text">
                                @if ($item->deadline_time)
                                <div>{{ date('d.m.y H:i',$item->deadline_time) }}</div>
                                @endif
                            </td>
                            <td class="table-text">
                                <div>{{ $item->message }}</div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table> 
                @else
                <h3>Нет Данных для протокола {{ $id }}!!</h3>
                @endif
            </div>        
        </div>
    </div>
</div>
@endsection
