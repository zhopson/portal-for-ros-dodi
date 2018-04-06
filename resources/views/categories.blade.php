@extends('layouts.app')
@section('head')
@endsection

@section('content')

<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
    </div>
<div class="panel panel-default" style="margin: 5px 205px 60px 5px">
    <div class="panel-heading">
        <h3 class="panel-title">Категории протоколов </h3>
    </div>
    
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row" id='id_msg_block_category'>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" id ='id_btn_close_category' class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Ошибка!</strong> <label id="id_error_msg_category"></label>
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
                                    <input type="hidden" class="form-control" id="id_id_category">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id='id_name_category' placeholder="Наименование категории"  style="width: 300px">
                                    </div>
                                </form>
                                <ul class="nav navbar-nav">
                                    <li id = 'id_li_cancel_category' class="active"><a href="Javascript:Cancel_category()"><span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span> Отменить  <span class="sr-only">(current)</span></a></li>
                                    <li id = 'id_li_save_category' class="active"><a href="Javascript:Save_category()"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Сохранить  <span class="sr-only">(current)</span></a></li>
                                </ul>
                                <ul class="nav navbar-nav">
                                    <li id = 'id_li_add_category' class="active"><a href="Javascript:Add_category()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить <span class="sr-only">(current)</span></a></li>
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
                    <table class="display" id="id_categorys_td" cellspacing="0" width="100%">
                        <thead>
                            <tr class="active">
                                <th style="width: 10px"></th>
                                <th style="width: 580px">Наименование</th>
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

function Add_category() {

        name = $('#id_name_category').val();

        $('#id_msg_block_category').css('display', 'none');
        if (name==='') {
            $('#id_error_msg_category').text('Не введено поле Наименование');
            $('#id_msg_block_category').css('display', 'inline');
            return;
        }
        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму значение
        data1.append('name', name);
        $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
            url: '/categories/ajax_add',
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
                    table.ajax.url( '/categories/json' ).load();
                    
                    $('#id_name_category').val('');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_category').text('Данные в БД не добавлены');
                $('#id_msg_block_category').css('display', 'inline');
            }
        });
}

function Edit_category(pid) {
    //n = $(obj).parent().parent();
    //alert(id);
    
        $.ajax({
            url: '/categories/ajax_edit',
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
                    
                    $('#id_msg_block_category').css('display', 'none');
                    
                    $('#id_id_category').val(result.id);
                    $('#id_name_category').val(result.name);
                    
                    $('#id_li_save_category').css('display', 'inline');
                    $('#id_li_cancel_category').css('display', 'inline');
                    $('#id_li_add_category').css('display', 'none');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_category').text('Данные из БД не получены');
                $('#id_msg_block_category').css('display', 'inline');
            }
        });    
    
}

function Cancel_category() {
    
}

function Save_category() {
    
        pid = $('#id_id_category').val();
        name = $('#id_name_category').val();

        $('#id_msg_block_category').css('display', 'none');
        if (name==='') {
            $('#id_error_msg_category').text('Не введено поле Наименование');
            $('#id_msg_block_category').css('display', 'inline');
            return;
        }

        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму значение
        data1.append('id', pid);
        data1.append('name', name);
        
        if (!pid) {
                $('#id_error_msg_category').text('Данные в БД не сохранены');
                $('#id_msg_block_category').css('display', 'inline');
                return;
        }
        $.ajax({
            url: '/categories/ajax_save',
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
                    table.ajax.url( '/categories/json' ).load();
                    
                    $('#id_id_category').val('');
                    $('#id_name_category').val('');
                    
                    $('#id_li_add_category').css('display', 'inline');
                    $('#id_li_cancel_category').css('display', 'none');
                    $('#id_li_save_category').css('display', 'none');    
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_category').text('Данные в БД не сохранены');
                $('#id_msg_block_category').css('display', 'inline');
            }
        });    
    
}


window.onload = function () {
    
    $('#id_li_cancel_category').css('display', 'none');
    $('#id_li_save_category').css('display', 'none');
    $('#id_msg_block_category').css('display', 'none');

};

$(document).ready(function () {

$('#id_btn_close_category').click(function () {
    $('#id_msg_block_category').css('display', 'none');
});

$('#id_li_cancel_category').click(function () {
    $('#id_li_add_category').css('display', 'inline');
    $('#id_li_cancel_category').css('display', 'none');
    $('#id_li_save_category').css('display', 'none');
    $('#id_msg_block_category').css('display', 'none');

    $('#id_name_category').val('');
});


$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

table = $('#id_categorys_td').DataTable({
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
"pageLength": 10,
"ajax": "/categories/json",
"deferRender": true
});

});



</script>

@endsection            