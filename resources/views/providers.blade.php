@extends('layouts.app')
@section('head')
@endsection

@section('content')

<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
    </div>
<div class="panel panel-default" style="margin: 5px 205px 60px 5px">
    <div class="panel-heading">
        <h3 class="panel-title">Провайдеры </h3>
    </div>
    
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row" id='id_msg_block_provider'>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" id ='id_btn_close_provider' class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<!--                    <button type="button" id ='id_btn_close_provider' class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                    <strong>Ошибка!</strong> <label id="id_error_msg_provider"></label>
                </div>
            </div>
            <div class="row">
                <div style="margin-top:10px" >

                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
<!--                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="{{ url('/documents') }}">Операции</a>
                            </div>-->
                                <form class="navbar-form navbar-left">
                                    <label>Операции </label>
                                </form>
                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                
                                <form class="navbar-form navbar-left">
                                    <input type="hidden" class="form-control" id="id_id_provider">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id='id_name_provider' placeholder="Наименование провайдера"  style="width: 300px">
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" id="id_watch_provider">
                                                Отслеживать
                                            </label>
                                        </div>
                                    </div>
                                </form>
                                <ul class="nav navbar-nav">
                                    <li id = 'id_li_cancel_provider' class="active"><a href="Javascript:Cancel_provider()"><span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span> Отменить  <span class="sr-only">(current)</span></a></li>
                                    <li id = 'id_li_save_provider' class="active"><a href="Javascript:Save_provider()"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Сохранить  <span class="sr-only">(current)</span></a></li>
                                </ul>
                                <ul class="nav navbar-nav">
                                    <li id = 'id_li_add_provider' class="active"><a href="Javascript:Add_provider()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить <span class="sr-only">(current)</span></a></li>
<!--                                    <li><a href="#">Link</a></li>-->
                                </ul>
                                
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                    </nav>                     
                    
                    
                </div>
            </div>
            <div class="row">
<!--                <table class="table table-hover table-bordered">-->
                <div class="table-responsive">
                    <table class="display" id="id_providers_td" cellspacing="0" width="100%">
                        <thead>
                            <tr class="active">
                                <th style="width: 280px">Наименование</th>
                                <th>Отслеживать</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>        
        </div>
    </div>
</div>
</div>
@endsection

@section('footer')
<!--        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
<script src="{{ asset('js/modal.js') }}"></script>
<script type="text/javascript">

var table;

function Add_provider() {

        name = $('#id_name_provider').val();
        watched = $('#id_watch_provider').prop('checked');
        
        if (watched) watch = 'on';
        else  watch = 'off';
        //alert(watch); return;

        $('#id_msg_block_provider').css('display', 'none');
        if (name==='') {
            $('#id_error_msg_provider').text('Не введено поле Наименование');
            $('#id_msg_block_provider').css('display', 'inline');
            return;
        }
        //alert('name:'+name+';num:'+num+';date:'+date+';desc:'+desc); return;
        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму значение
        data1.append('name', name);
        data1.append('watch', watch);
        $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
            url: '/providers/ajax_add',
            type: 'POST',
            data: data1,
			// Эта опция не разрешает jQuery изменять данные
			processData: false,
			// Эта опция не разрешает jQuery изменять типы данных
			contentType: false,	            
            dataType: 'json',
            success: function (result) {
//                console.log('8 ajax_start np_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    table.ajax.url( '/providers/json' ).load();
                    
                    $('#id_name_provider').val('');
                    $('#id_watch_provider').prop('checked', false);
                    
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_provider').text('Данные в БД не добавлены');
                $('#id_msg_block_provider').css('display', 'inline');
            }
        });
}

function Edit_provider(pid) {
    //n = $(obj).parent().parent();
    //alert(id);
    
        $.ajax({
            url: '/providers/ajax_edit',
            type: 'POST',
            data: {'id': pid},
//			// Эта опция не разрешает jQuery изменять данные
//			processData: false,
//			// Эта опция не разрешает jQuery изменять типы данных
//			contentType: false,	            
            dataType: 'json',
            success: function (result) {
//                console.log('8 ajax_start np_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    
                    $('#id_msg_block_provider').css('display', 'none');
                    
                    $('#id_id_provider').val(result.id);
                    $('#id_name_provider').val(result.name);
                    if (result.watch === 1)
                        $('#id_watch_provider').prop('checked', true);
                    else 
                        $('#id_watch_provider').prop('checked', false);
                    
                    $('#id_li_save_provider').css('display', 'inline');
                    $('#id_li_cancel_provider').css('display', 'inline');
                    $('#id_li_add_provider').css('display', 'none');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_provider').text('Данные из БД не получены');
                $('#id_msg_block_provider').css('display', 'inline');
            }
        });    
    
}

function Cancel_provider() {
    
}

function Save_provider() {
    
        pid = $('#id_id_provider').val();
        name = $('#id_name_provider').val();
        watched = $('#id_watch_provider').prop('checked');
        
        if (watched) watch = 'on';
        else  watch = 'off';        

        $('#id_msg_block_provider').css('display', 'none');
        if (name==='') {
            $('#id_error_msg_provider').text('Не введено поле Наименование');
            $('#id_msg_block_provider').css('display', 'inline');
            return;
        }

        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму значение
        data1.append('id', pid);
        data1.append('name', name);
        data1.append('watch', watch);
        
        if (!pid) {
                $('#id_error_msg_provider').text('Данные в БД не сохранены');
                $('#id_msg_block_provider').css('display', 'inline');
                return;
        }
        $.ajax({
            url: '/providers/ajax_save',
            type: 'POST',
            data: data1,
			// Эта опция не разрешает jQuery изменять данные
			processData: false,
			// Эта опция не разрешает jQuery изменять типы данных
			contentType: false,	            
            dataType: 'json',
            success: function (result) {
//                console.log('8 ajax_start np_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    table.ajax.url( '/providers/json' ).load();
                    
                    $('#id_id_provider').val('');
                    $('#id_name_provider').val('');
                    $('#id_watch_provider').prop('checked', false);
                    
                    $('#id_li_add_provider').css('display', 'inline');
                    $('#id_li_cancel_provider').css('display', 'none');
                    $('#id_li_save_provider').css('display', 'none');    
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_provider').text('Данные в БД не сохранены');
                $('#id_msg_block_provider').css('display', 'inline');
            }
        });    
    
}


window.onload = function () {
    
    $('#id_li_cancel_provider').css('display', 'none');
    $('#id_li_save_provider').css('display', 'none');
    $('#id_msg_block_provider').css('display', 'none');

};

$(document).ready(function () {

$('#id_btn_close_provider').click(function () {
    $('#id_msg_block_provider').css('display', 'none');
});

$('#id_li_cancel_provider').click(function () {
    $('#id_li_add_provider').css('display', 'inline');
    $('#id_li_cancel_provider').css('display', 'none');
    $('#id_li_save_provider').css('display', 'none');
    $('#id_msg_block_provider').css('display', 'none');

    $('#id_name_provider').val('');
    $('#id_watch_provider').prop('checked', false);
});


$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

table = $('#id_providers_td').DataTable({
"language": {
//    "columns": [
//        null,
//        null,
//        { "type": "date" },
//        null,
//        null
//    ],
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
"ajax": "/providers/json",
"deferRender": true
});

});



</script>

@endsection            