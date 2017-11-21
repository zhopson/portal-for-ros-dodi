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
                        {{ $ch_status[$chain->status] }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Создан
                    </div>
                    <div class="col-md-1">
                        {{ date('d.m.y H:i',$chain->creation_time) }}
                    </div>
                    <div class="col-md-1 col-md-offset-4">
                        <strong>Категории</strong>
                    </div>
                    @if ($chain->categories != '{NULL}')
                    <div class="col-md-3">
                        @foreach (explode(",",$chain->categories) as $category)
                            <li>{{ trim($category, '{"}') }}</li>
                        @endforeach                        
                    </div>
                    @else
                        Без категорий
                    @endif
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Автор
                    </div>
                    <div class="col-md-1">
                        {{ $chain->avtor }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Пользователь
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('clients.view', ['id' => $chain->client_id]) }}">{{ $chain->surname." ".$chain->c_name." ".$chain->patronymic }}</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:5px" >
                    <div class="col-md-1">
                        Адрес
                    </div>
                    <div class="col-md-3">
                       {{ $address }} <!-- $chain->address-->
                        @if ($chain->dom)
                        {{ ", д. ".$chain->dom }}
                        @endif
                        @if ($chain->korp)
                        {{ "/".$chain->korp }}
                        @endif
                        @if ($chain->kv)
                        {{ ", кв. ".$chain->kv }} 
                        @endif
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:15px" >
                <div>
                @if ($chain->status == 'CLOSED')
                    <div class="col-md-1 col-md-offset-11">
                        <a href="#"><h5>Обновить</h5></a>
                    </div>
                @else
                    <div class="col-md-1">
                        <a href="#"><h6><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span> Заметка</h6></a>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('tasks.new', ['id' => $chain->client_id, 'chain_id' => $id]) }}"><h6><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Задача</h6></a>
                    </div>
                    <div class="col-md-1">
                        <a href="#"><h6><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Звонок</h6></a>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('requests.new', ['id' => $chain->client_id, 'chain_id' => $id]) }}"><h6><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Обращение</h6></a>
                    </div>
                    <div class="col-md-1 col-md-offset-5">
<!--                        <a href="#"><h5>Обновить</h5></a>-->
                        <a href="{{ route('chains.view', ['id' => $id]) }}" class="dropdown-toggle">Обновить</a>
                    </div>
                    <div class="col-md-1">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Управление<span class="caret"></span></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li><a href="{{ route('chains.edit', ['id' => $id]) }}">Редактировать</a></li>
                                <li><a href="{{ route('chains.remove', ['id' => $id]) }}">Удалить</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('chains.close', ['id' => $id]) }}">Закрыть</a></li>
                            </ul>
<!--                        <a href="#"><h6>Управление</h6></a>-->
                    </div>
                @endif
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
                                @if ($item->update_time)
                                <div>{{ date('d.m.y H:i',$item->update_time) }}</div>
                                @endif
                            </td>
                            <td class="table-text">
                                @if ($item->call_id) 
                                <div><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Звонок</div>
                                @elseif ($item->task_id)
                                <div><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Задача</div>
                                @elseif ($item->request_id)
                                <div><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Обращение</div>
                                @elseif ($item->note_id)
                                <div><span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span> Заметка</div>
                                @else
                                <div><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Уведомление</div>
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
                                <div>{{ date('d.m.y',$item->start_time) }}</div>
                                @endif
                            </td>
                            <td class="table-text">
                                @if ($item->deadline_time)
                                <div>{{ date('d.m.y',$item->deadline_time) }}</div>
                                @endif
                            </td>
                            <td class="table-text">
                                @if ($chain->status == 'OPENED')
                                    @if ($item->call_id) 
                                    <a href="{{ route('calls.edit', ['id' => $item->call_id]) }}" data-toggle="tooltip" title="Редактировать звонок">{{ $item->message }}</a>
                                    @elseif ($item->task_id)
                                        @if ($item->task_status == 'PROCESSED' || $item->task_status == 'NEW')
                                            <a href="{{ route('tasks.edit', ['id' => $item->task_id]) }}" data-toggle="tooltip" title="Редактировать задачу">{{ $item->message }}</a>
                                        @else    
                                            <div>{{ $item->message }}</div>
                                        @endif
                                    @elseif ($item->request_id)
                                    <a href="{{ route('requests.edit', ['id' => $item->request_id]) }}" data-toggle="tooltip" title="Редактировать обращение">{{ $item->message }}</a>
                                    @else
                                    <a href="{{ route('notes.edit', ['id' => $item->note_id]) }}" data-toggle="tooltip" title="Редактировать заметку">{{ $item->message }}</a>
                                    @endif
                                @else
                                <div>{{ $item->message }}</div>
                                @endif
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
