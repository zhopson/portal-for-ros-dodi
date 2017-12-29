@extends('layouts.app')
@section('head')
<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"> </script>
@endsection

@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
    <div class="row">
        <h3 style="margin-top:-10px">Голосовой портал</h3>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Общий список звонков</h3>
            </div>            
            <div class="panel-body">
                <div class="col-md-2">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation"><a href="{{ url('/telephony') }}">Позвонить</a></li>                        
                        <li role="presentation"><a href="{{ url('/telephony/stat') }}">Статистика и Отчеты</a></li>
<!--                        <li role="presentation"><a href="{{-- url('/telephony/rep') --}}">Отчеты</a></li>-->
                        <li role="presentation"><a href="{{ url('/telephony/call_list') }}">Обзвон</a></li>                        
                    </ul>
                </div>
                <div class="col-md-10">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li role="presentation"><a href="{{ url('/telephony/stat') }}">Общие отчеты</a></li>
                                <li role="presentation"><a href="#">Отчеты по оператору</a></li>
                                <li role="presentation" class="active"><a href="{{ url('/telephony/common_call_list') }}">Список всех вызовов</a></li>
<!--                                <li role="presentation"><a href="#">Profile</a></li>
                                <li role="presentation"><a href="#">Messages</a></li>-->
                            </ul>
                            <div class="row">
                                <div class="table-responsive"  style="margin:20px 20px 10px 20px">
                                    <table class="display" id="id_call_stat_td"  cellspacing="0" width="100%">
                                        <thead>
                                            <tr class="active">
                                                <th>Дата</th>
<!--                                                <th style="width: 280px">ФИО/Наименование</th>-->
                                                <th>Источник</th>
                                                <th>Абонент</th>
                                                <th>Длительность</th>
                                                <th>Результат вызова</th>
                                                <th></th>
                                                <th></th>
                                                <th>Пользователь</th>
                                                <th>Комментарий</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr class="active">
                                                <th>Дата</th>
<!--                                                <th style="width: 280px">ФИО/Наименование</th>-->
                                                <th>Источник</th>
                                                <th>Абонент</th>
                                                <th>Длительность</th>
                                                <th>Результат вызова</th>
                                                <th></th>
                                                <th></th>
                                                <th>Пользователь</th>
                                                <th>Комментарий</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>                                
                            </div>
                        </div>
                    </div>
                    <!--                    <form class="form-inline">-->
                    <!--                    </form>-->

                    <!--                    <audio id='audio_remote' autoplay='autoplay'></audio>
                                        <audio id='ringbacktone' loop src='sounds/ringbacktone.wav'></audio>-->

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/sip.js?svn=3') }}" type="text/javascript"> </script>
        
        <script type="text/javascript">
            
        var myAudio=null;
        function call_play_rec(file){
            stop_play_rec();
            if (file!=='') {
                myAudio = new Audio;
                myAudio.src = file;
                myAudio.play();
            }
        }

        function stop_play_rec(){
            if (myAudio) {
                myAudio.pause();
                myAudio.currentTime = 0;
                myAudio=null;
            }
        }

        $(document).ready(function () {
        //on page load do init
//            $('#id_tel_call').click(function () {
//                //alert('call');
//                makeCall($('#id_tel_phone').val());
//            });
//            $('#id_tel_hangup').click(function () {
//                sipHangUp();
//            });
            
            $('#id_call_stat_td').DataTable({
                "language": {
                //"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json",
                "processing": "Подождите...",
                "search": "Поиск:",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 до 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                }                
                },            
                "ajax": "/telephony/calls_list/json", 
                "deferRender": true            
            });              
            
            
        });

        window.onload = function () {
//            SetVar1('{{ base64_encode ( Auth::user()->sip_number ) }}');
//            SetVar2('{{ base64_encode ( Auth::user()->sip_secret ) }}');
//            
//        //init sip stack
//            SIPml.init(readyCallback, errorCallback);
//
//        //start stip stack
//            sipStack.start();
//
//        //do login
//            login();

        };


        </script>
        
@endsection