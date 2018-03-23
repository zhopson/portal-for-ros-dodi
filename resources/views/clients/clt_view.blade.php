@extends('layouts.app')

@section('head')
<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"></script>
@endsection

@section('content')
@if (count($clients) > 0)

<div class="modal fade " id="id_CallModal" tabindex="-1" role="dialog" aria-labelledby="CallModalLabel" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="CallModalLabel">Позвонить клиенту</h4>
                <h4 id="CallModalLabel"><mark class="class_clt_name"></mark></h4>
            </div>        
            <div class="modal-body">
                <div class="row">
                <div class="col-md-12" id="id_call_error" style="display:none">
                <div class="alert alert-danger">
<!--                    <div class="pull-right"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>-->                    
                    <label class="control-label" style="margin:0 0 0 10px">Ошибка создания протокола</label>
                </div>
                </div>
                <div class="col-md-12" id="id_call_success" style="display:none">
                <div class="alert alert-success">
<!--                    <div class="pull-right"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>-->
                    <label class="control-label" style="margin:0 0 0 10px">Протокол создан</label>
                </div>
                </div>
                </div>
<!--                <div class="row">
                    <div class="col-md-7 col-md-offset-1">
                        <div class="form-group">
                            <label for="id_call_phone" class="control-label">Телефон:</label>
                            <input type="text" class="form-control" id="id_call_phone" readonly="true">
                        </div>
                    </div>
                </div>-->
                <div class="row">
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="pull-right">Телефон</div>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="id_call_phone" name="v_call_new_abon" readonly="true">                        
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row">
                            <div class="form-group">
                                <label for="id_call_new_create_chain" class="col-sm-3 control-label"></label>
                                <div class="col-md-5">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" id="id_call_new_create_chain" name="v_call_new_create_chain" checked>
                                            Создавать протокол
                                        </label>
                                    </div>
                                </div>
                            </div>
                </div>
            <div id='id_call_new_chain_section'>
                <div class="row">
                    @if (!$add2exist_chain_id)
                    <div id="id_call_new_category_section">
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="pull-right">Категория</div>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control" id="id_call_new_category" name="v_call_new_category">
                                <option ></option>
                                @foreach (App\Category::all() as $category) 
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>                             
                        </div>
                    </div>
                    </div>
                    </div>
                    @endif
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="pull-right">Комментарий</div>
                        </div>
                        <div class="col-md-7"> 
                            <textarea rows="6" cols="50" class="form-control" id="id_call_new_comment" name="v_call_new_comment"></textarea>
                        </div>
                    </div>
                    </div>
                    @if (count($chains_opened)>0)
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="pull-right">Открытые протоколы</div>
                        </div>
                        <div class="col-md-7">
                            <select class="form-control" id="id_call_new_open_chains" name="v_call_new_open_chains">
                                <option value="0" selected>- Новый протокол -</option>
                                @foreach ($chains_opened as $chain) 
                                @php 
                                    $date = date('d.m.y',$chain->creation_time);
                                    $time = date('H:i',$chain->creation_time);
                                    //$date = substr($chain->creation_time,0,10);
                                    //$time = substr($chain->creation_time,11,5);
                                @endphp
                                <option value="{{ $chain->id }}">{{ '#'.$chain->id.' '.$chain->last_comment.' // '.$date.' в '.$time.' ('.$chain->avtor.')' }}</option>
                                @endforeach
                            </select>                             
                        </div>
                    </div>
                    </div>
                    @elseif ($add2exist_chain_id)
                    <input type="hidden" class="form-control" id="id_call_new_exist_chain" name="v_call_new_exist_chain" value="{{$add2exist_chain_id}}">
                    @endif                    
                </div>
            </div>    
            </div>
            <div class="modal-footer">
                <button type="button" id="id_call_btn" class="btn btn-primary">Позвонить</button>
                <button type="button" id="id_call_hang_btn" class="btn btn-default">Положить</button>
            </div>
        </div>
    </div>
</div>


@if (session('status'))
  <div class="alert alert-success">
        {{ session('status') }}
  </div>
@endif
<div class="container-fluid" style="margin:0 30px 0 30px">
    @foreach ($clients as $client)
    <div class="row">
        <h3 style="margin-top:25px"><div class="header-text">{{ $client->clt_name }}</div></h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if ( !Auth::user()->hasRole('Учителя') && !Auth::user()->hasRole('Ученики') )
                    <div class="row">
                        <a href="{{ route('clients.edit', ['id' => $client->id]) }}" style="margin-left:20px"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Редактировать</a>    
                    </div>
                    @endif
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
                                    <div class="pull-right">Привязка к логину</div>
                                </td>
                                <td class="table-text">
                                    @if (App\User::where('client_id',$client->id)->first())
                                    E-Mail:<mark>{{ App\User::where('client_id',$client->id)->first()->email }}</mark>
                                    {{ ';    ' }}
                                    имя:<mark>{{ App\User::where('client_id',$client->id)->first()->name }}</mark>
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
                                        @if (strpos(trim($ip_address,'/{}"'), '/'))
                                           @if (isset($ip_last_active[trim(substr(trim($ip_address,'/{}"'),0,strpos(trim($ip_address,'/{}"'), '/')))]))
                                                <a href="{{ route( 'netflow.clients.graph', [ 'id' => '0','ip' => trim(substr(trim($ip_address,'/{}"'),0,strpos(trim($ip_address,'/{}"'), '/'))) ] ) }}" data-toggle="tooltip" title="{{ 'Время последней активности:'.$ip_last_active[trim(substr(trim($ip_address,'/{}"'),0,strpos(trim($ip_address,'/{}"'), '/')))] }}">{{ trim($ip_address,'{}"') }}</a>  
                                           @else
                                                <a href="{{ route( 'netflow.clients.graph', [ 'id' => '0','ip' => trim(substr(trim($ip_address,'/{}"'),0,strpos(trim($ip_address,'/{}"'), '/'))) ] ) }}" data-toggle="tooltip" title="Время последней активности не найдено">{{ trim($ip_address,'{}"') }}</a>  
                                           @endif
                                        @else
                                           @if (isset($ip_last_active[trim($ip_address,'{}"')]))
                                                <a href="{{ route( 'netflow.clients.graph', [ 'id' => '0','ip' => trim($ip_address,'{}"') ] ) }}" data-toggle="tooltip" title="{{ 'Время последней активности:'.$ip_last_active[trim($ip_address,'{}"')] }}">{{ trim($ip_address,'{}"') }}</a>  
                                           @else
                                                <a href="{{ route( 'netflow.clients.graph', [ 'id' => '0','ip' => trim($ip_address,'{}"') ] ) }}" data-toggle="tooltip" title="Время последней активности не найдено">{{ trim($ip_address,'{}"') }}</a>  
                                           @endif
                                        @endif
                                        <br>
                                    @endforeach                                      
                                    <a href="{{ route( 'netflow.clients.graph', [ 'id' => $client->id,'ip' => '0' ] ) }}"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Статистика по пользователю</a>
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
                                        $clt_name_clear =  str_replace('"', '', $client->clt_name);
                                        if (Auth::user()->hasRole('Учителя'))
                                            $nums_str = $nums_str.$num_arr[0];
                                        else
                                            $nums_str = $nums_str."<a href=\"JavaScript:call_client('".$clt_name_clear."','".$num_arr[0]."');\">".$num_arr[0]."</a>";
                                        if (  isset($num_arr[1]) &&  trim($num_arr[1])!='' )
                                            { $nums_str = $nums_str.'('.trim($num_arr[1]).')'; }
                                        echo $nums_str.'<br>';
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
                        @if ( !Auth::user()->hasRole('Учителя') && !Auth::user()->hasRole('Ученики') )
                        <div class="col-md-3">
                            <a href="{{ route('calls.new', ['id' => $client->id]) }}" style="margin-left:8px"><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Звонок</a>    
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('tasks.new', ['id' => $client->id]) }}"><span class="glyphicon glyphicon-tasks" aria-hidden="true"></span> Задача</a>    
                        </div>
                        @endif
                        <div class="col-md-3">
                            <a href="{{ route('requests.new', ['id' => $client->id]) }}"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Обращение</a>    
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
                                        {{ date('d.m.y',$task->deadline_time) }}
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
                        <a style="margin-left:18px" class="btn btn-info" href="{{ route('chains_clt',[ 'clt_id' => $client->id ]) }}" role="button"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span> Все протоколы пользователя</a>
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
@section('footer')
<!--<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/sip.js?svn=3') }}" type="text/javascript"></script>
<script type="text/javascript">

var call_id = null;
var is_called = 0;

function call_client(pname,ptel){
    
    $('#id_CallModal').on('show.bs.modal', function () {
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.class_clt_name').text(g_name);
        $('#id_call_phone').val(g_tel);
    });

    g_name = pname;
    g_tel = ptel;
    $('#id_CallModal').modal('show');
    //alert(phost+" "+pnet);
}    

$(document).ready(function () {
        
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 

function CreateChainbyCall(){
        
    if ($('#id_call_new_create_chain').is(":checked")) {    
        var clt_id = '{{ $client->id }}';
        var category = $('#id_call_new_category').val();
        //var call_status = $('#id_call_new_status').val();
        var interlocutor = $('#id_call_phone').val();
        var comment = $('#id_call_new_comment').val();
        var open_chain_id = $('#id_call_new_open_chains').val();
        //var chain_id_exist = $('#id_call_new_exist_chain').val();
        var chain_id_exist = 'null';

        //alert(clt_id+'; open_chain_id:'+open_chain_id+'; chain_id_exist:'+chain_id_exist+'; category:'+category+'; interlocutor:'+interlocutor+'; comment:'+comment);
        $.ajax({
            url: '/clients/ajax_create_chain_by_call',
            type: 'POST',
            data: {'client_id': clt_id,'category': category,'interlocutor': interlocutor,'comment': comment,'open_chain_id': open_chain_id,'chain_id_exist': chain_id_exist},
            dataType: 'json',
            success: function (result) {
                if (result.status === 1) {
                    //alert(result.new_call_id);
                    call_id = result.new_call_id;
//                    var rows = result.chains_opened;
//                    //console.log('3 ajax_start ' + (new Date().toISOString().slice(11, -1)));
//                    for (loop = 0; loop < rows.length; loop++) {
//                            $('#id_call_new_open_chains')
//                                    .append($('<option>', {value: rows[loop].id}) 
//                                    .text('#'+rows[loop].id+' '+rows[loop].last_comment+' // '+rows[loop].create_dt+' ('+rows[loop].avtor+')'));
//                    }// '#'.$chain->id.' '.$chain->last_comment.' // '.$date.' в '.$time.' ('.$chain->avtor.')'
                    $('#id_call_success').css('display', 'inline');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_call_error').css('display', 'inline');
            }
        });        
    }
};

function UpdateCallStatusByTel(){
        
    if ($('#id_call_new_create_chain').is(":checked")) {    
        //var clt_id = '{{ $client->id }}';
        //var category = $('#id_call_new_category').val();
        //var call_status = $('#id_call_new_status').val();
        var interlocutor = $('#id_call_phone').val();
        //var comment = $('#id_call_new_comment').val();
        //var open_chain_id = $('#id_call_new_open_chains').val();
        //var chain_id_exist = $('#id_call_new_exist_chain').val();
        //var chain_id_exist = 'null';

        //alert(clt_id+'; open_chain_id:'+open_chain_id+'; chain_id_exist:'+chain_id_exist+'; category:'+category+'; interlocutor:'+interlocutor+'; comment:'+comment);
        //alert(call_id);
        if (call_id) {
        $.ajax({
            url: '/clients/ajax_update_call_status_by_tel',
            type: 'POST',
            data: {'call_id': call_id,'interlocutor': interlocutor},
            dataType: 'json',
            success: function (result) {
                if (result.status === 1) {
                    //alert(result.call_id);
                   // alert(result.res);
//                    $('#id_call_success').css('display', 'inline');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                //$('#id_call_error').css('display', 'inline');
            }
        });        
        }
        //else alert('undef');
    }
    else {
        if (is_called === 1 ) {
            var interlocutor = $('#id_call_phone').val();
        $.ajax({
            url: '/clients/ajax_update_cdr_user',
            type: 'POST',
            data: {'interlocutor': interlocutor},
            dataType: 'json',
            success: function (result) {
                if (result.status === 1) {
                    //alert(result.call_id);
                   // alert(result.res);
//                    $('#id_call_success').css('display', 'inline');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                //$('#id_call_error').css('display', 'inline');
            }
        });             
        }
        //alert('no protocol');
    }
    
};
        
        //on page load do init
            $('#id_call_btn').click(function () {
                //alert('call');
                makeCall($('#id_call_phone').val());
                CreateChainbyCall();
                is_called = 1;
            });
            $('#id_call_hang_btn').click(function () {
                sipHangUp();
            });
            
            $('#id_CallModal').on('hidden.bs.modal', function (e) {
                UpdateCallStatusByTel();
                call_id = null;
                is_called = 0;
                //here reload parent page clients/view/
                //alert('Close, call id:'+call_id);
            });  
            
            $('#id_call_new_create_chain').click(function () {

                if ($('#id_call_new_create_chain').is(":checked")) $('#id_call_new_chain_section').css('display', 'inline');
                else $('#id_call_new_chain_section').css('display', 'none');

            });
            
    $('#id_call_new_open_chains').change(function () {
        if ( $('#id_call_new_open_chains').val() === "0" ) {
            $('#id_call_new_category_section').css('display', 'inline');
            //$("#id_req_new_category").prop("required", true);
        }
        else {
            $('#id_call_new_category_section').css('display', 'none');
            //$("#id_req_new_category").prop("required", false);
        }
        $('#id_call_new_category').val('');
        
    });


});

        window.onload = function () {
            
            SetVar1('{{ base64_encode ( Auth::user()->sip_number ) }}');
            SetVar2('{{ base64_encode ( Auth::user()->sip_secret ) }}');
            
        //init sip stack
            SIPml.init(readyCallback, errorCallback);

        //start stip stack
            sipStack.start();

        //do login
        login();

        };
    
</script>
@endsection