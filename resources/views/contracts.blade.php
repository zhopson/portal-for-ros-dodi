@extends('layouts.app')
@section('head')
@endsection

@section('content')

<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
    </div>
<div class="panel panel-default" style="margin: 5px 205px 60px 5px">
    <div class="panel-heading">
        <h3 class="panel-title">Контракты </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row" id='id_msg_block_contract'>
                <div class="alert alert-danger alert-dismissible" role="alert">
                    <button type="button" id ='id_btn_close_contract' class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<!--                    <button type="button" id ='id_btn_close_contract' class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>-->
                    <strong>Ошибка!</strong> <label id="id_error_msg_contract"></label>
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
<!--                                    <div id='id_label_find_doc' class="form-group">
                                        <label>Результаты поиска по ключевым словам</label>
                                    </div>-->
                                    <input type="hidden" class="form-control" id="id_id_contract">
                                    <div class="form-group">
                                        <input type="text" class="form-control" id='id_name_contract' placeholder="Наименование партнера"  style="width: 300px">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id='id_num_contract' placeholder="Номер"  style="width: 150px">
                                    </div>
                                    <div class="form-group">
                                        <input type="date" class="form-control" id='id_date_contract' placeholder="Дата"  style="width: 200px">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id='id_desc_contract' placeholder="Описание"  style="width: 400px">
                                    </div>
<!--                                    <button type="submit" class="btn btn-default">Submit</button>-->
                                </form>
                                <ul class="nav navbar-nav">
<!--                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Поиск <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="Javascript:FindDoc(1)">Поиск в заголовке</a></li>
                                            <li><a href="Javascript:FindDoc(2)">Поиск в описании</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="Javascript:FindDoc(3)">Поиск в заголовке и описании</a></li>
                                        </ul>
                                    </li>-->
                                    <li id = 'id_li_cancel_contract' class="active"><a href="Javascript:Cancel_contract()"><span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span> Отменить  <span class="sr-only">(current)</span></a></li>
                                    <li id = 'id_li_save_contract' class="active"><a href="Javascript:Save_contract()"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Сохранить  <span class="sr-only">(current)</span></a></li>
                                </ul>
                                <ul class="nav navbar-nav">
                                    <li id = 'id_li_add_contract' class="active"><a href="Javascript:Add_contract()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить <span class="sr-only">(current)</span></a></li>
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
                    <table class="display" id="id_contracts_td"  cellspacing="0" width="100%">
                        <thead>
                            <tr class="active">
                                <th style="width: 280px">Наименование партнера</th>
                                <th>Номер</th>
                                <th>Дата</th>
                                <th>Описание</th>
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

function Add_contract() {

        name = $('#id_name_contract').val();
        num = $('#id_num_contract').val();
        date = $('#id_date_contract').val();
        desc = $('#id_desc_contract').val();

        $('#id_msg_block_contract').css('display', 'none');
        if (name==='') {
            $('#id_error_msg_contract').text('Не введено поле Наименование');
            $('#id_msg_block_contract').css('display', 'inline');
            return;
        }
        else if (num==='') {
            $('#id_error_msg_contract').text('Не введено поле Номер');
            $('#id_msg_block_contract').css('display', 'inline');
            return;
        }
        else if (date==='') {
            $('#id_error_msg_contract').text('Не введено поле Дата');
            $('#id_msg_block_contract').css('display', 'inline');
            return;
        }

        //alert('name:'+name+';num:'+num+';date:'+date+';desc:'+desc); return;
        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму значение
        data1.append('name', name);
        data1.append('num', num);
        data1.append('date', date);
        data1.append('desc', desc);
        $.ajax({
//                headers: {
//                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                },
            url: '/contracts/ajax_add',
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
                    table.ajax.url( '/contracts/json' ).load();
                    
                    $('#id_name_contract').val('');
                    $('#id_num_contract').val('');
                    $('#id_date_contract').val('');
                    $('#id_desc_contract').val('');
                    
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_contract').text('Данные в БД не добавлены');
                $('#id_msg_block_contract').css('display', 'inline');
            }
        });
}

function Edit_contract(pid) {
    //n = $(obj).parent().parent();
    //alert(id);
    
        $.ajax({
            url: '/contracts/ajax_edit',
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
                    
                    $('#id_msg_block_contract').css('display', 'none');
                    
                    $('#id_id_contract').val(result.id);
                    $('#id_name_contract').val(result.name);
                    $('#id_num_contract').val(result.num);
                    $('#id_date_contract').val(result.date);
                    $('#id_desc_contract').val(result.desc);
                    
                    $('#id_li_save_contract').css('display', 'inline');
                    $('#id_li_cancel_contract').css('display', 'inline');
                    $('#id_li_add_contract').css('display', 'none');
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_contract').text('Данные из БД не получены');
                $('#id_msg_block_contract').css('display', 'inline');
            }
        });    
    
}

function Cancel_contract() {
    
}

function Save_contract() {
    
        pid = $('#id_id_contract').val();
        name = $('#id_name_contract').val();
        num = $('#id_num_contract').val();
        date = $('#id_date_contract').val();
        desc = $('#id_desc_contract').val();

        $('#id_msg_block_contract').css('display', 'none');
        if (name==='') {
            $('#id_error_msg_contract').text('Не введено поле Наименование');
            $('#id_msg_block_contract').css('display', 'inline');
            return;
        }
        else if (num==='') {
            $('#id_error_msg_contract').text('Не введено поле Номер');
            $('#id_msg_block_contract').css('display', 'inline');
            return;
        }
        else if (date==='') {
            $('#id_error_msg_contract').text('Не введено поле Дата');
            $('#id_msg_block_contract').css('display', 'inline');
            return;
        }

        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму значение
        data1.append('id', pid);
        data1.append('name', name);
        data1.append('num', num);
        data1.append('date', date);
        data1.append('desc', desc);
        
        if (!pid) {
                $('#id_error_msg_contract').text('Данные в БД не сохранены');
                $('#id_msg_block_contract').css('display', 'inline');
                return;
        }
        $.ajax({
            url: '/contracts/ajax_save',
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
                    table.ajax.url( '/contracts/json' ).load();
                    
                    $('#id_id_contract').val('');
                    $('#id_name_contract').val('');
                    $('#id_num_contract').val('');
                    $('#id_date_contract').val('');
                    $('#id_desc_contract').val('');
                    
                    $('#id_li_add_contract').css('display', 'inline');
                    $('#id_li_cancel_contract').css('display', 'none');
                    $('#id_li_save_contract').css('display', 'none');    
                }
            },
            // Что-то пошло не так
            error: function (result) {
                $('#id_error_msg_contract').text('Данные в БД не сохранены');
                $('#id_msg_block_contract').css('display', 'inline');
            }
        });    
    
}


window.onload = function () {
    
    $('#id_li_cancel_contract').css('display', 'none');
    $('#id_li_save_contract').css('display', 'none');
    $('#id_msg_block_contract').css('display', 'none');

};

$(document).ready(function () {

$('#id_btn_close_contract').click(function () {
    $('#id_msg_block_contract').css('display', 'none');
});

$('#id_li_cancel_contract').click(function () {
    $('#id_li_add_contract').css('display', 'inline');
    $('#id_li_cancel_contract').css('display', 'none');
    $('#id_li_save_contract').css('display', 'none');
    $('#id_msg_block_contract').css('display', 'none');

                    $('#id_name_contract').val('');
                    $('#id_num_contract').val('');
                    $('#id_date_contract').val('');
                    $('#id_desc_contract').val('');        
});


$.ajaxSetup({
headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

table = $('#id_contracts_td').DataTable({
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
"ajax": "/contracts/json",
"deferRender": true
});

});



</script>

@endsection            