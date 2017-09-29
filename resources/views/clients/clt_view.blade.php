@extends('layouts.app')

@section('content')
@if (count($clients) > 0)
<div class="container-fluid" style="margin:0 30px 0 30px">
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
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Активный
                                    @else
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Неактивный
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
                            @if ($client->father)
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Отец</div>
                                </td>
                                <td class="table-text">
                                    {{$client->father}}
                                </td>
                            </tr>
                            @endif
                            @if ($client->mother)
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Мать</div>
                                </td>
                                <td class="table-text">
                                    {{$client->mother}}
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Адрес</div>
                                </td>
                                <td class="table-text">
                                    {{ $addresses[0]->date }}
                                    @foreach (explode(",",$addresses[0]->adr) as $address)
                                        @if ($address!='""') 
                                            {{ trim($address,'()"') }}
                                        @endif
                                    @endforeach                                    
                                    @if ($addresses[0]->address_number)
                                        {{ ", д. ".$addresses[0]->address_number }}
                                    @endif
                                    @if ($addresses[0]->address_building)
                                        {{ "/".$addresses[0]->address_building }}
                                    @endif
                                    @if ($addresses[0]->address_apartment)
                                        {{ ", кв. ".$addresses[0]->address_apartment }} 
                                    @endif  
                                </td>
                            </tr>
                            @if (count($addresses)>1)
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Предыдущие адреса</div>
                                </td>
                                <td class="table-text">
                                    @for ($i = 1; $i < count($addresses); $i++)
                                        {{ $addresses[$i]->date }}
                                        @foreach (explode(",",$addresses[$i]->adr) as $address)
                                            @if ($address!='""') 
                                                {{ trim($address,'()"') }}
                                            @endif
                                        @endforeach                                    
                                        @if ($addresses[$i]->address_number)
                                            {{ ", д. ".$addresses[$i]->address_number }}
                                        @endif
                                        @if ($addresses[$i]->address_building)
                                            {{ "/".$addresses[$i]->address_building }}
                                        @endif
                                        @if ($addresses[$i]->address_apartment)
                                            {{ ", кв. ".$addresses[$i]->address_apartment }} 
                                        @endif
                                        <br>
                                    @endfor
                                </td>
                            </tr>                                
                            @endif  
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Диагноз</div>
                                </td>
                                <td class="table-text">
                                    {{$client->diagnose}}
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
                                    @foreach (explode(",",$client->ip_addresses) as $ip_address)
                                        <a href="#">{{ trim($ip_address,'{}"') }}</a>  
                                            <br>
                                    @endforeach                                      
                                    <a href="#"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Статистика по пользователю</a>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Группы</div>
                                </td>
                                <td class="table-text">
                                    @foreach (App\Client::find($client->id)->groups()->get() as $group)
                                        <li style="margin-left:8px">{{ $group->name }}</li>
                                    @endforeach
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
                    <div class="row">
                        <div class="col-md-3">
                            <a href="#" style="margin-left:8px"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Звонок</a>    
                        </div>
                        <div class="col-md-3">
                            <a href="#"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Задача</a>    
                        </div>
                        <div class="col-md-3">
                            <a href="#"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Обращение</a>    
                        </div>
                    </div>
                    @if (count($chains_opened) > 0)
                    <div class="row">
                        <h4 style="margin-left:18px">Открытые протоколы</h4>
                    </div>
                    <div class="row">
                        <table class="table table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Автор</th>
                                    <th></th>
                                    <th>Последний комментарий</th>
                                    <th>Оператор</th>
                                    <th></th>
                                    <th>Открыт</th>
                                    <th>Изменен</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chains_opened as $chain_op)
                                <tr>
                                    <td class="table-text">
                                        {{ $chain_op->avtor }}
                                    </td>
                                    <td class="table-text">
                                        @if (App\ChainItems::find($chain_op->last_item_id)->task_id)
                                            <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
                                        @elseif (App\ChainItems::find($chain_op->last_item_id)->request_id)   
                                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                        @elseif (App\ChainItems::find($chain_op->last_item_id)->call_id)   
                                            <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                                        @elseif (App\ChainItems::find($chain_op->last_item_id)->note_id)   
                                            <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
                                        @else
                                            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                                        @endif                                        
                                    </td>
                                    <td class="table-text">
                                        <div><a href="{{ route('chains.view', ['id' => $chain_op->id]) }}">{{ $chain_op->last_comment }}</a></div>
                                    </td>
                                    <td class="table-text">
                                        {{ App\User::find(App\ChainItems::find($chain_op->last_item_id)->user_id)->name }}
                                    </td>
                                    <td class="table-text">
                                        @if ($chain_op->progress)
                                            {{ $chain_op->progress.'%' }}
                                        @endif
                                    </td>
                                    <td class="table-text">{{date('d.m.y H:i',$chain_op->opening_time)}}</td>
                                    <td class="table-text">{{date('d.m.y H:i',$chain_op->update_time)}}</td>
                                </tr>
                                @endforeach                
                            </tbody>                            
                        </table>
                    </div>
                    @else
                    <div class="row">
                        <h4 style="margin-left:18px">Нет открытых протоколов</h4>    
                    </div>
                    @endif    
                    @if (count($tasks) > 0)
                    <div class="row">
                        <h4 style="margin-left:18px">Открытые задачи</h4>
                    </div>
                    <div class="row">
                        <table class="table table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Дата создания</th>
                                    <th>Ответственный</th>
                                    <th></th>
                                    <th>Приоритет</th>
                                    <th>Срок</th>
                                    <th>Сообщение</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                <tr>
                                    <td class="table-text">
                                        {{ date('d.m.y H:i',$task->creation_time) }}
                                    </td>
                                    <td class="table-text">
                                        {{ $task->otvetstv }}
                                    </td>
                                    <td class="table-text">
                                        {{ $task->progress.'%' }}
                                    </td>
                                    <td class="table-text">
                                        {{ $task->priority }}
                                    </td>
                                    <td class="table-text">
                                        {{ date('d.m.y H:i',$task->deadline_time) }}
                                    </td>
                                    <td class="table-text">{{ $task->message }}</td>
                                    <td class="table-text"><a href="{{ route('chains.view', ['id' => $task->chain_id]) }}"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span></a></td>
                                </tr>
                                @endforeach                
                            </tbody>                            
                        </table>
                    </div>
                    @else
                    <div class="row">
                        <h4 style="margin-left:18px">Нет открытых задач</h4>    
                    </div>
                    @endif                  
                    @if (count($chains_closed) > 0)
                    <div class="row">
                        <h4 style="margin-left:18px">Последние закрытые протоколы</h4>
                    </div>
                    <div class="row">
                        <table class="table table-hover">
                            <thead>
                                <tr class="active">
                                    <th>Автор</th>
                                    <th></th>
                                    <th>Последний комментарий</th>
                                    <th>Оператор</th>
                                    <th></th>
                                    <th>Закрыт</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($chains_closed as $chain_cl)
                                <tr>
                                    <td class="table-text">
                                        {{ $chain_cl->avtor }}
                                    </td>
                                    <td class="table-text">
                                        @if (App\ChainItems::find($chain_cl->last_item_id)->task_id)
                                            <span class="glyphicon glyphicon-tasks" aria-hidden="true"></span>
                                        @elseif (App\ChainItems::find($chain_cl->last_item_id)->request_id)   
                                            <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>
                                        @elseif (App\ChainItems::find($chain_cl->last_item_id)->call_id)   
                                            <span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>
                                        @elseif (App\ChainItems::find($chain_cl->last_item_id)->note_id)   
                                            <span class="glyphicon glyphicon-paperclip" aria-hidden="true"></span>
                                        @else
                                            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                        @endif                                        
                                    </td>
                                    <td class="table-text">
                                        <div><a href="{{ route('chains.view', ['id' => $chain_cl->id]) }}">{{ $chain_cl->last_comment }}</a></div>
                                    </td>
                                    <td class="table-text">
                                        {{ App\User::find(App\ChainItems::find($chain_cl->last_item_id)->user_id)->name }}
                                    </td>
                                    <td class="table-text">
                                        @if ($chain_cl->progress)
                                            {{ $chain_cl->progress.'%' }}
                                        @endif
                                    </td>
                                    <td class="table-text">{{date('d.m.y H:i',$chain_cl->closing_time)}}</td>
                                </tr>
                                @endforeach
                            </tbody>                            
                        </table>
                        <button style="margin-left:18px" type="button" class="btn btn-info"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Все протоколы пользователя</button>
                    </div>
                    @endif                  
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
