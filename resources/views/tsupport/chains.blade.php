@extends('layouts.app')

@section('content')

@php
$clt = ''; $clt_name = '';
if ($clt_id) {
$clt = App\Client::find($clt_id);
if ($clt) $clt_name = $clt->surname.' '.$clt->name.' '.$clt->patronymic;
$clt_name = trim($clt_name);
}
@endphp

<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
        @if ($clt_id)
        <div class="col-md-7"><h3 style="margin-top:25px"><div class="header-text">Протоколы на пользователя <mark>{{ $clt_name }}</mark></div></h3></div>
        @elseif ($usr_id)
        <div class="col-md-7"><h3 style="margin-top:25px"><div class="header-text">Протоколы созданные <mark>{{  App\User::find($usr_id)->name }}</mark></div></h3></div>
        @else
        <div class="col-md-3"><h3 style="margin-top:25px"><div class="header-text">Протоколы</div></h3></div>
        @endif
    </div>
    <div class="panel panel-default" style="margin: -5px 5px 60px 5px">
        <!--    <div class="panel-heading">
                <h3 class="panel-title">Протоколы</h3>
            </div>-->
        <div class="panel-body">
            <!--            <div class="row">
                            <div style="margin-top:-18px" >
                                <div class="col-md-2">
                                    <a href="#"><h6><span class="label label-default">Новый</span> Исходящий звонок</h6></a>
                                </div>
                                <div class="col-md-1">
                                    <a href="#"><h6><span class="label label-default">Новая</span> Задача</h6></a>
                                </div>
                                <div class="col-md-2">
                                    <a href="#"><h6><span class="label label-default">Новое</span> Обращение</h6></a>
                                </div>
                            </div>
                        </div>-->
            <div class="table-responsive">
                <table class="display responsive" id="id_chains_td"  cellspacing="0" width="100%">
<!--                <table class="table table-hover table-bordered table-condensed table-responsive" id="id_chains_td">-->
                    <thead>
                        <tr class="active">
                            <th>№</th>
                            <th>Изменен</th>
<!--                            <th>Пользователь</th>-->
                            <th>Нас.Пункт</th>
                            <th>Автор</th>
                            <th>Оператор</th>
                            <th>Статус</th>
                            <th>Открыт</th>
                            <th>Последний комментарий</th>
                            <th>Категория</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>№</th>
                            <th>Изменен</th>
<!--                            <th>Пользователь</th>-->
                            <th>Нас.Пункт</th>
                            <th>Автор</th>
                            <th>Оператор</th>
                            <th>Статус</th>
                            <th>Открыт</th>
                            <th>Последний комментарий</th>
                            <th>Категория</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<!--<script src="{{ asset('js/jquery-1.12.4.js') }}"></script>-->
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<!--<script src="{{ asset('js/datatables.min.js') }}"></script>-->

<script type="text/javascript">

var users;
var ch_status = {
        'OPENED' : 'Открыт',
        'CLOSED' : 'Закрыт'
};
window.onload = function () {
@php
if ($users) {
        $usersdef = '';
$usersdef = $usersdef.'users = {';
foreach ($users as $user) {
$usersdef = $usersdef.($user->id).':'.'"'.($user->name).'",';
}
$usersdef = rtrim($usersdef, ',');
$usersdef = $usersdef.'};';
echo $usersdef;
}
@endphp
        };
$(document).ready(function() {

$('#id_chains_td').DataTable({

//            "columnDefs": [{
//                    "targets": [2],
//                    "visible": false,
//                    "searchable": false                },
//            ],

        @if ($clt_id)
        "ajax": "/chains/json4clt/" + '{{ $clt_id }}',
        @elseif ($usr_id)
        "ajax": "/chains/json4usr/" + '{{ $usr_id }}',
        @else
        "ajax": {
        "type": "GET",
                "url": '/chains/json',
                "contentType": 'application/json; charset=utf-8',
                "data": {"_token": ""}
        },
        "columns": [
        {"data": "id", "name": "id"},
        {"data": "utime", "name": "utime"},
        {"data": "clt_adr", "name": "clt_adr"},
        {"data": "u_name", "name": "u_name"},
        {"data":  function(data){
        return users[data.operator];
        //data.action_view.replace(/^[\"]+|[\"]+$/g, "");
        }, "name": "operator"},
        {"data": function(data){
        return ch_status[data.status]
                //data.action_view.replace(/^[\"]+|[\"]+$/g, "");
        }, "name": "status"},
        {"data": "otime", "name": "otime"},
        {"data": "action_view", "name": "action_view", "orderable": false, "searchable": false},
//                {"data": function(data){
//                    return '<a href="' + '{{ url('/chains/view') }}' + '/' + data.id + '">' + data.last_comment + '</a>';
//                    //data.action_view.replace(/^[\"]+|[\"]+$/g, "");
//                }, "name": "action_view", "orderable": false, "searchable": false},
        {"data": "categories", "name": "categories"}
        ],
        "columnDefs": [{
        "defaultContent": "-",
                "targets": "_all"
        }],
    
//        "ajax": "/chains/json",
        @endif

        "dataType": "jsonp",
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
        "pageLength": 25,
        "order": [[ 1, "desc" ]],
        "paging": true,
        "deferRender": true
        });
// Event listener to the two range filtering inputs to redraw on input
//    $('#min, #max').keyup( function() {
//        table.draw();
//    } );
});
</script>    
@endsection