@extends('layouts.app')
@section('head')
<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"> </script>
@endsection

@section('content')
<div class="panel panel-default" style="margin: -15px 5px 25px 5px">
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
                                foreach (explode("\n",$client->numbers) as $number) {
                                        $num_arr = (explode(":",$number));
                                        $nums_str = $nums_str."<a href=\"JavaScript:call_client('".$client->clt_name."','".$num_arr[0]."');\">".$num_arr[0]."</a>";
                                        if (  isset($num_arr[1]) &&  $num_arr[1]!='' )
                                            { $nums_str = $nums_str.'('.$num_arr[1].'), '; }
                                        else 
                                            { $nums_str = $nums_str.','; }
                                            
                                }
                                echo rtrim($nums_str, ", "); 
                                @endphp
                                
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

@section('footer')
        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
        <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
        <script src="{{ asset('js/modal.js') }}"></script>
        <script src="{{ asset('js/sip.js?svn=2') }}" type="text/javascript"> </script>
        <script type="text/javascript">
            
        window.onload = function () {
            //base64_encode ( Auth::user()->sip_number )
            //base64_encode ( Auth::user()->sip_secret )
            SetVar1('{{Auth::user()->sip_number}}');
            SetVar2('{{Auth::user()->sip_secret}}');
            //var p = '{{ base64_encode ( 'sip:109@sip.viasakha.ru' ) }}';
            //alert(Decode_des3(p));
        //init sip stack
            SIPml.init(readyCallback, errorCallback);

        //start stip stack
            sipStack.start();

        //do login
        login();

        };

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
        //on page load do init
            $('#id_call_btn').click(function () {
                //alert('call');
                makeCall($('#id_call_phone').val());
            });
            $('#id_call_hang_btn').click(function () {
                sipHangUp();
            });
        });



        </script>
        
@endsection            