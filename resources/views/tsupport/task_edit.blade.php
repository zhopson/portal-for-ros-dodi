@extends('layouts.app')
@section('head')

@endsection
@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
    <form method="POST" action="{{ route('tasks.update', ['id' => $task->id]) }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-3"><h3 style="margin-top:25px"><div class="header-text">Изменить задачу</div></h3></div>
    </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                        <table class="table table-hover">
                            <tr>
                                <th class="table-text">
                                    <div class="pull-right">Пользователь</div>
                                </th>
                                <th class="table-text">
                                    {{ $client->clt_name }}
                                </th>
                            </tr>
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
                                    @if (count($addresses)>0)
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
                            @if ($client->diagnose)
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Диагноз</div>
                                </td>
                                <td class="table-text">
                                    {{$client->diagnose}}
                                </td>
                            </tr>
                            @endif
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
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Ответственный</div>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="id_task_edit_otvetstv" name="v_task_edit_otvetstv" required >
                                <option ></option>
                                @foreach ($users as $user) 
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>                             
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Статус</div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="id_task_edit_status" name="v_task_edit_status" required >
                                <option ></option>
                                @foreach ($tsk_status as $item => $value) 
                                <option value="{{ $item }}">{{ $value }}</option>
                                @endforeach
                            </select>                             
                        </div>
                        <div class="col-md-2 col-md-offset-1">
                            <div class="pull-right">Приоритет</div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" id="id_task_edit_priority" name="v_task_edit_priority" required >
                                <option ></option>
                                @foreach ($tsk_priority as $item => $value) 
                                <option value="{{ $item }}">{{ $value }}</option>
                                @endforeach
                            </select>                             
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Старт задачи</div>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" id="id_task_edit_start_d" name="v_task_edit_start_d" value="{{date('Y-m-d',$task->start_time)}}">
                            <input type="hidden" class="form-control" id="id_task_edit_start_t" name="v_task_edit_start_t" value="{{date('H:i',$task->start_time)}}">
                        </div>
                        <div class="col-md-2 col-md-offset-1">
                            <div class="pull-right">Срок</div>
                        </div>
                        <div class="col-md-3">
                           <input type="date" class="form-control" id="id_task_edit_srok_d" name="v_task_edit_srok_d" value="{{date('Y-m-d',$task->deadline_time)}}" required >                          
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Прогресс</div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="id_task_edit_tsk_progress" name="v_task_edit_tsk_progress">
                                <option value="0" selected> 0% </option>
                                <option value="10"> 10% </option>
                                <option value="20"> 20% </option>
                                <option value="30"> 30% </option>
                                <option value="40"> 40% </option>
                                <option value="50"> 50% </option>
                                <option value="60"> 60% </option>
                                <option value="70"> 70% </option>
                                <option value="80"> 80% </option>
                                <option value="90"> 90% </option>
                                <option value="100"> 100% </option>
                            </select>                             
                        </div>
                        <div class="col-md-7">
<!--                            <div id="sliderProgress" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all">
                                <div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-min" style="width: 70%;"></div>
                                <span class="ui-slider-handle ui-state-default ui-corner-all" tabindex="0" style="left: 70%;"></span>
                            </div>                            -->
                            <input type="range" name="v_task_edit_tsk_progress_range" id="id_task_edit_tsk_progress_range" step="10" value="0" min="0" max="100">
                        </div>
                    </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 col-md-offset-3">
                            <div class="checkbox">
                                <label>
                                    @if ($task->departure)
                                    <input type="checkbox" name="v_task_edit_tsk_dep" id="id_task_edit_tsk_dep" checked>
                                    @else
                                    <input type="checkbox" name="v_task_edit_tsk_dep" id="id_task_edit_tsk_dep">
                                    @endif
                                    Выезд
                                </label>
                            </div>                            
                        </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Сообщение</div>
                        </div>
                        <div class="col-md-9"> 
                            <textarea rows="6" cols="50" class="form-control" id="id_task_edit_msg" name="v_task_edit_msg" required ></textarea>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-md-offset-8">
                    <button type="submit" class="btn btn-primary" id="id_task_edit_btn_save">Сохранить</button>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-default" href="{{ url()->previous() }}" role="button" id="id_task_edit_btn_cancel">Отмена</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('footer')
<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {

    $('#id_task_edit_tsk_progress_range').change(function () {
        $('#id_task_edit_tsk_progress').val($('#id_task_edit_tsk_progress_range').val());
        if ( $('#id_task_edit_tsk_progress_range').val() > 0 && $('#id_task_edit_tsk_progress_range').val() < 100 )
            $('#id_task_edit_status').val('PROCESSED');
        else if ( $('#id_task_edit_tsk_progress_range').val() === "100" )
            $('#id_task_edit_status').val('SOLVED');
        else if ( $('#id_task_edit_tsk_progress_range').val() === "0" )
            $('#id_task_edit_status').val('PROCESSED');
        //alert($('#id_task_edit_tsk_progress_range').val());
    });

    $('#id_task_edit_tsk_progress').change(function () {
        $('#id_task_edit_tsk_progress_range').val($(this).val());
        if ( $('#id_task_edit_tsk_progress').val() > 0 && $('#id_task_edit_tsk_progress').val() < 100 )
            $('#id_task_edit_status').val('PROCESSED');
        else if ( $('#id_task_edit_tsk_progress').val() === "100" )
            $('#id_task_edit_status').val('SOLVED');
        else if ( $('#id_task_edit_tsk_progress').val() === "0" )
            $('#id_task_edit_status').val('PROCESSED');
    });

    $('#id_task_edit_start_d').change(function () {
        var now = new Date();
        now.setHours(now.getHours() + 9);
        var ddd = now.toISOString();
        var strt = ddd.substr(11, 5);
        
        $('#id_task_edit_start_t').val(strt);
    });

});

window.onload = function () {
    @if (count($task)>0)
        $('#id_task_edit_otvetstv').val('{{$task->responsible_id}}');
        $('#id_task_edit_status').val('{{$task->status}}');
        $('#id_task_edit_priority').val('{{$task->priority}}');
        //$('#id_task_edit_start_d').val('{{$task->start_time}}');
        //$('#id_task_edit_start_t').val('{{$task->start_time.substr(11, 5)}}');
        $('#id_task_edit_tsk_progress').val('{{$task->progress}}');
        $('#id_task_edit_tsk_progress_range').val('{{$task->progress}}');
        $('#id_task_edit_tsk_dep').val('{{$task->departure}}');
        $('#id_task_edit_msg').val('{{$task->message}}');
    @endif
};
</script>
@endsection
