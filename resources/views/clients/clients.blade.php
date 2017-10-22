@extends('layouts.app')

@section('content')
<div class="panel panel-default" style="margin: -15px 5px 0px 5px">
    <div class="panel-heading">
        <h3 class="panel-title">Клиенты</h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div style="margin-top:-18px" >
                    <div class="col-md-2">
                        <a href="{{ route('clients.new') }}"><h6><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить</h6></a>
                    </div>
                </div>
            </div>
            <div class="row">
                @if (count($clients) > 0)
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="active">
                            <th>№</th>
                            <th></th>
                            <th style="width: 280px">ФИО/Наименование</th>
                            <th>Тип</th>
                            <th></th>
                            <th>Адрес</th>
                            <th>Провайдер</th>
                            <th>Контактные данные</th>
                            <th>Комментарий</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        @foreach ($clients as $client)
                            <td class="table-text">
                                <div>{{ $client->id }}</div>
                            </td>
                            <td class="table-text">
                            @if($client->active == 1)
                                <div><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></div>
                            @else 
                                <div><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></div>
                            @endif
                            </td>
                            <td class="table-text">
                                <div><a href="{{ route('clients.view', ['id' => $client->id]) }}">{{ $client->clt_name }}</a></div>
                                @if ($client->gr_name) 
                                @foreach (explode(",",$client->gr_name) as $group)
                                <li>{{ $group }}</li>
                                @endforeach
                                @endif
                            </td>
                            <td class="table-text">
                                <div>{{ $client->type_name }}</div>
                            </td>
                            <td class="table-text">
                                @if($client->sex == '1')
                                <div>М</div>
                                @elseif($client->sex == '0')
                                <div>Ж</div>
                                @else
                                <div></div>
                                @endif
                            </td>
                            <td class="table-text">
                                <div>
                                    {{ $client->address }}
                                    @if ($client->address_number)
                                        {{ ", д. ".$client->address_number }}
                                    @endif
                                    @if ($client->address_building)
                                        {{ "/".$client->address_building }}
                                    @endif
                                    @if ($client->address_apartment)
                                        {{ ", кв. ".$client->address_apartment }} 
                                    @endif                                    
                                </div>
                            </td>
                            <td class="table-text">
                                <div>{{ $client->prd_name }}</div>
                            </td>
                            <td class="table-text">
                                @php
                                    $nums_str = '';
                                @endphp
                                @foreach (explode("\n",$client->numbers) as $number)
                                    @php    
                                        $num_arr = (explode(":",$number));
                                        $nums_str = $nums_str.$num_arr[0];
                                        if (  isset($num_arr[1]) &&  $num_arr[1]!='' )
                                            { $nums_str = $nums_str.'('.$num_arr[1].'), '; }
                                        else 
                                            { $nums_str = $nums_str.','; }
                                            
                                    @endphp
                                @endforeach
                                {{ rtrim($nums_str, ", ") }}
                            </td>
                            <td class="table-text">
                                <div>{{ $client->comment }}</div>
                            </td>
                            <td class="table-text">
                                <div><a href="{{ route('clients.edit', ['id' => $client->id]) }}"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></div>
                            </td>
                        </tr>
                        @endforeach
                        {{ $clients->links() }}
                <!--        <tr>
                            <td></td>
                        </tr>-->
                    </tbody>
                </table> 
                @else
                <h3>Нет Клиентов!!!!!</h3>
                @endif
            </div>        
        </div>
    </div>
</div>
@endsection
