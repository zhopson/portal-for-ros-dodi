@extends('layouts.app')

@section('content')
<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
        <div class="col-md-3"><h3 style="margin-top:25px"><div class="header-text">Заметки</div></h3></div>
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
                <table class="display" id="id_notes_td"  cellspacing="0" width="100%">
<!--                <table class="table table-hover table-bordered table-condensed table-responsive" id="id_chains_td">-->
                    <thead>
                        <tr class="active">
                            <th>Дата Создания</th>
                            <th>Пользователь</th>
                            <th>Автор</th>
                            <th>Текст</th>
                            <th>Статус протокола</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Дата Создания</th>
                            <th>Пользователь</th>
                            <th>Автор</th>
                            <th>Текст</th>
                            <th>Статус протокола</th>
                            <th></th>
                            <th></th>
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
$(document).ready(function() {
    
        $('#id_notes_td').DataTable({
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
            "order": [[ 0, "desc" ]],
            "ajax": "/notes/json", 
            "deferRender": true            
        });
     
    // Event listener to the two range filtering inputs to redraw on input
//    $('#min, #max').keyup( function() {
//        table.draw();
//    } );
} );
</script>    
@endsection