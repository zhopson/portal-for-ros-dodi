@extends('layouts.app')
@section('head')

@endsection
@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
    <form method="POST" action="{{ route('requests.store', ['id' => $client->id]) }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-3"><h3 style="margin-top:25px"><div class="header-text">Новое обращение</div></h3></div>
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
                                    @if ($addresses != null)
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
                            @if ($addresses != null)
                            @if (count($addresses)>1)
                            <tr>
                                <td class="table-text">
                                    <div class="pull-right">Предыдущие адреса</div>
                                </td>
                                <td class="table-text">
                                    @if ($addresses != null)
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
                                    @endif
                                </td>
                            </tr>                                
                            @endif  
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
                    @if (!$add2exist_chain_id)
                    <div id="id_req_new_category_section">
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Категория</div>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="id_req_new_category" name="v_req_new_category" required >
                                <option ></option>
                                @foreach ($chain_categories as $category) 
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
                        <div class="col-md-3">
                            <div class="pull-right">Источник</div>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="id_req_new_source" name="v_req_new_source">
                                <option ></option>
                                @foreach ($req_sources as $val => $name) 
                                <option value="{{ $val }}">{{ $name }}</option>
                                @endforeach
                            </select>                             
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Комментарий</div>
                        </div>
                        <div class="col-md-9"> 
                            <textarea rows="6" cols="50" class="form-control" id="id_req_new_comment" name="v_req_new_comment" required ></textarea>
                        </div>
                    </div>
                    </div>
                    @if ($chains_opened != null)
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="pull-right">Открытые протоколы</div>
                        </div>
                        <div class="col-md-9">
                            <select class="form-control" id="id_req_new_open_chains" name="v_req_new_open_chains">
                                <option value="0" selected>- Новый протокол -</option>
                                @foreach ($chains_opened as $chain) 
                                @php 
                                    //$date = date('d.m.y',$chain->creation_time);
                                    //$time = date('H:i',$chain->creation_time);
                                    $date = substr($chain->creation_time,0,10);
                                    $time = substr($chain->creation_time,11,5);;
                                @endphp
                                <option value="{{ $chain->id }}">{{ '#'.$chain->id.' '.$chain->last_comment.' // '.$date.' в '.$time.' ('.$chain->user->name.')' }}</option>
                                @endforeach
                            </select>                             
                        </div>
                    </div>
                    </div>
                    @elseif ($add2exist_chain_id)
                    <input type="hidden" class="form-control" id="id_req_new_exist_chain" name="v_req_new_exist_chain" value="{{$add2exist_chain_id}}">
                    @endif
                
                </div>
            </div>
            <div class="row">
                <div class="col-md-2 col-md-offset-8">
                    <button type="submit" class="btn btn-primary" id="id_req_new_btn_save">Сохранить</button>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-default" href="{{ url()->previous() }}" role="button" id="id_req_new_btn_cancel">Отмена</a>
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

    $('#id_req_new_open_chains').change(function () {
        if ( $('#id_req_new_open_chains').val() === "0" ) {
            $('#id_req_new_category_section').css('display', 'inline');
            $("#id_req_new_category").prop("required", true);
        }
        else {
            $('#id_req_new_category_section').css('display', 'none');
            $("#id_req_new_category").prop("required", false);
        }
        $('#id_req_new_category').val('');
        
    });
        
});

window.onload = function () {
    $('#id_req_new_source').val('1')

};
</script>
@endsection
