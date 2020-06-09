@extends('layouts.app')
@section('head')
<!--<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"> </script>-->
@endsection

@section('content')

<div class="modal fade" id="id_removeDocModal" tabindex="-1" role="dialog" aria-labelledby="DocModalLabel" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3><mark class="class_clt_name">Действительно удалить документ?</mark></h3>
                <span class="label label-warning">Предупреждение</span>
                <h4>Также будут удалены все вложенные файлы</h4>
            </div>
            <div class="modal-body"  align="right">
                <input type="hidden" class="form-control" id="id_removed_doc" name="v_removed_doc">
                <button type="button" id="id_remove_doc_btn" onclick="Javascript:removeDoc($('#id_removed_doc').val())" class="btn btn-primary">Удалить</button>
                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
        <div class="col-md-3"><h3 style="margin-top:25px"><div class="header-text">Раздел документации</div></h3></div>
    </div>
    <div class="panel panel-default" style="margin: -5px 5px 60px 5px">
<!--    <div class="panel-heading">
        <h3 class="panel-title">Раздел документации </h3>
    </div>-->
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
<!--                <div style="margin-top:-18px" >-->
                    
                    

                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="{{ url('/documents') }}">WiKi</a>
                            </div>

                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                
                                <ul class="nav navbar-nav">
                                    <li class="active"><a href="{{ route('documents.new') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить документ <span class="sr-only">(current)</span></a></li>
<!--                                    <li><a href="#">Link</a></li>-->
                                </ul>
                                <form class="navbar-form navbar-left">
                                    <div id='id_label_find_doc' class="form-group">
                                        <label>Результаты поиска по ключевым словам</label>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id='id_keywords_find_doc' placeholder="Поиск по ключевым словам, разделенных пробелами"  style="width: 500px">
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
                                    <li id = 'id_li_find' class="active"><a href="Javascript:FindDoc(3)"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Поиск  <span class="sr-only">(current)</span></a></li>
                                    <li id = 'id_li_show_all' class="active"><a href="Javascript:ShowAll()"> Очистить поиск  <span class="sr-only">(current)</span></a></li>
                                </ul>
                                
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                    </nav>                    

<!--                    <div class="col-md-2">
                        <a href="{{ route('documents.new') }}"><h6><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить</h6></a>
                    </div>-->
                </div>
<!--            </div>-->
            <div class="row">
<!--                <table class="table table-hover table-bordered">-->
                <div class="table-responsive">
                <table class="display" id="id_documents_td"  cellspacing="0" width="100%">
                    <thead>
                        <tr class="active">
                            <th style="width: 30px">Номер</th>
                            <th>Категория</th>
                            <th style="width: 480px">Заголовок</th>
                            <th>Дата изменения</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="active">
                            <th style="width: 30px">Номер</th>
                            <th>Категория</th>
                            <th style="width: 480px">Заголовок</th>
                            <th>Дата изменения</th>
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
</div>
</div>
@endsection

@section('footer')
<!--        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
        <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
        <script src="{{ asset('js/modal.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
<!--        <script src="{{ asset('js/modal.js') }}"></script>-->
        <script type="text/javascript">
            
var table;

function AskRemoveDoc(id) {
    $('#id_removeDocModal').on('show.bs.modal', function () {
        $('#id_removed_doc').val(id);
    });

    $('#id_removeDocModal').modal('show');
    //$('#id_td_files_edit_doc').find('tr#'+id).remove();
}

function removeDoc(id) {

        // Создадим новый объект типа FormData
        var data1 = new FormData();
        // Добавим в новую форму значение
        data1.append('doc_id', id);
        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '/documents/ajax_remove_doc',
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
                    table.ajax.url( '/documents/json' ).load();
                    $('#id_removeDocModal').modal('hide');
                }
            },
            // Что-то пошло не так
            error: function (result) {
//                $('#id_error_file_panel').css('display', 'inline');
            }
        });
}

    function FindDoc(mode) {
        keywords = $('#id_keywords_find_doc').val();
//        alert(keywords);
        if (keywords!='') {
            table.ajax.url( '/documents/json/'+keywords+'/'+ mode).load();
        $('#id_label_find_doc').css('display', 'inline');
            $('#id_li_show_all').css('display', 'inline');
            $('#id_li_find').css('display', 'none');
        }
    }

    function ShowAll() {
        table.ajax.url( '/documents/json').load();
        $('#id_label_find_doc').css('display', 'none');
        $('#id_li_show_all').css('display', 'none');
        $('#id_li_find').css('display', 'inline');
        $('#id_keywords_find_doc').val('');
    }

    window.onload = function () {
        $('#id_label_find_doc').css('display', 'none');
        $('#id_li_show_all').css('display', 'none');
        $('#id_li_find').css('display', 'inline');
        $('#id_keywords_find_doc').css('display', 'inline');
    };

    $(document).ready(function () {
        
//    $.ajaxSetup({
//        headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//        }
//    });    
    
        table = $('#id_documents_td').DataTable({
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
            "ajax": "/documents/json", 
            "deferRender": true            
        });

    });



        </script>
        
@endsection            