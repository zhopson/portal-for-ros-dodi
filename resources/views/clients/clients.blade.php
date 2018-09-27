@extends('layouts.app')
@section('head')
<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"></script>
@endsection

@section('content')


<div class="modal fade " id="id_CallModal" tabindex="-1" role="dialog" aria-labelledby="CallModalLabel" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="CallModalLabel">Позвонить клиенту</h4>
                <h4 id="CallModalLabel"><mark class="class_clt_name"></mark></h4>
            </div>        
            <div class="modal-body">
                <input type="hidden" class="form-control" id="id_call_clt_id" name="v_call_clt_id">
                <div class="row">
                    <div class="col-md-12" id="id_call_error" style="display:none">
                        <div class="alert alert-danger">
        <!--                    <div class="pull-right"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>-->                    
                            <label class="control-label" style="margin:0 0 0 10px">Ошибка создания протокола</label>
                        </div>
                    </div>
                    <div class="col-md-12" id="id_call_success" style="display:none">
                        <div class="alert alert-success">
        <!--                    <div class="pull-right"><button type="button" class="close" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>-->
                            <label class="control-label" style="margin:0 0 0 10px">Протокол создан</label>
                        </div>
                    </div>
                </div>                
                <!--                <div class="row">
                                    <div class="col-md-7 col-md-offset-1">
                                        <div class="form-group">
                                            <label for="id_call_phone" class="control-label">Телефон:</label>
                                            <input type="text" class="form-control" id="id_call_phone" readonly="true">
                                        </div>
                                    </div>
                                </div>-->
                <div class="row">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="pull-right">Телефон</div>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control" id="id_call_phone" name="v_call_new_abon" readonly="true">                        
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="id_call_new_create_chain" class="col-sm-3 control-label"></label>
                        <div class="col-md-5">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="id_call_new_create_chain" name="v_call_new_create_chain" checked>
                                    Создавать протокол
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div id='id_call_new_chain_section'>
                    <div class="row">
                        @if (!$add2exist_chain_id)
                        <div id="id_call_new_category_section">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="pull-right">Категория</div>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="form-control" id="id_call_new_category" name="v_call_new_category">
                                            <option ></option>
                                            @foreach (App\Category::all() as $category) 
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
                                <div class="col-md-4">
                                    <div class="pull-right">Комментарий</div>
                                </div>
                                <div class="col-md-7"> 
                                    <textarea rows="6" cols="50" class="form-control" id="id_call_new_comment" name="v_call_new_comment"></textarea>
                                </div>
                            </div>
                        </div>
                        <div id='id_call_chains_opened_section'>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="pull-right">Открытые протоколы</div>
                                    </div>
                                    <div class="col-md-7">
                                        <select class="form-control" id="id_call_new_open_chains" name="v_call_new_open_chains">
                                            <option value="0" selected>- Новый протокол -</option>
                                        </select>                             
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($add2exist_chain_id)
                        <input type="hidden" class="form-control" id="id_call_new_exist_chain" name="v_call_new_exist_chain" value="{{$add2exist_chain_id}}">
                        @endif                    
                    </div>
                </div>    
            </div>
            <div class="modal-footer">

                <div class="form-group has-warning">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="pull-right">Статус SIP</div>
                        </div>
                        <div class="col-md-7"> 
                            <input type="text" class="form-control" id="id_call_anytel_sip_log">
                        </div>
                    </div>
                </div>

                <button type="button" id="id_call_btn" class="btn btn-primary">Позвонить</button>
                <button type="button" id="id_call_hang_btn" class="btn btn-default">Положить</button>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
        <div class="col-md-3"><h3 style="margin-top:25px"><div class="header-text">Клиенты</div></h3></div>
    </div>
    <div class="panel panel-default" style="margin: -5px 5px 60px 5px">
        <!--    <div class="panel-heading">
                <h3 class="panel-title">Клиенты</h3>
            </div>-->
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
    <!--                <table class="table table-hover table-bordered">-->
                    <div class="table-responsive">

<!--                        <table id="id_clients_find_panel_td"  cellspacing="0" width="100%" style="margin: 0 auto 2em auto;">
                            <thead>
                                <tr>
                                    <th> </th>
                                    <th> </th>
                                    <th>Поиск по ФИО</th>
                                    <th></th>
                                    <th>Поиск по адресу</th>
                                    <th></th>
                                    <th>Поиск по провайдеру</th>
                                    <th>Поиск по контактным данным</th>
                                </tr>                        
                            </thead> 
                            <tbody>
                                <tr id="id_filter_fields">
                                    <td> </td>
                                    <td> </td>
                                    <td align="left"><input type="text" id="id_filter_fio"></td>
                                    <td></td>
                                    <td align="left"><input type="text" id="id_filter_adr"></td>
                                    <td></td>
                                    <td align="left"><input type="text" id="id_filter_prd"></td>
                                    <td align="left"><input type="text" id="id_filter_cnt"></td>
                                </tr>                        
                            </tbody>
                        </table>-->

                        <table class="display responsiv" id="id_clients_td"  cellspacing="0" width="100%">
                            <thead>
                                <tr id="id_filter_fields">
                                    <td> </td>
                                    <td> </td>
                                    <td align="left"><input type="text" class="column_filter" style="width: 250px" name="v_clt_find_fio" id="id_col2_filter" data-column="2" placeholder="Поиск по ФИО"></td>
                                    <td></td>
                                    <td></td>
                                    <td align="left"><input type="text" class="column_filter" style="width: 280px" name="v_clt_find_adr" id="id_col5_filter" data-column="5" placeholder="Поиск по адресу"></td>
                                    <td align="left"><select class="column_filter_sel" style="width: 150px; height: 28px" name="v_clt_find_prd" id="id_col6_filter"><option value=""></option></select></td>
                                    <td align="left"><input type="text" class="column_filter" style="width: 250px" name="v_clt_find_cnt" id="id_col7_filter" data-column="7" placeholder="Поиск по контактам"></td>
                                    <td><a href="{{ route('clients.advsearch') }}"><h4><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> </h4></a></td>
                                </tr>                        

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
                            <tfoot>
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
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/sip.js?svn=3') }}" type="text/javascript"></script>
<script type="text/javascript">

var call_id = null;
var is_called = 0;

window.onload = function () {

    SetVar1('{{ base64_encode ( Auth::user()->sip_number ) }}');
    SetVar2('{{ base64_encode ( Auth::user()->sip_secret ) }}');

//init sip stack
    SIPml.init(readyCallback, errorCallback);

//start stip stack
    sipStack.start();

//do login
    login();

};

function call_client(pclt_id, pname, ptel) {

    $.ajax({
        url: '/clients/ajax_get_chains_opened',
        type: 'POST',
        data: {'client_id': pclt_id},
        dataType: 'json',
        success: function (result) {
            if (result.status === 1) {
                var rows = result.chains_opened;
                //console.log('3 ajax_start ' + (new Date().toISOString().slice(11, -1)));
                for (loop = 0; loop < rows.length; loop++) {
                    $('#id_call_new_open_chains')
                            .append($('<option>', {value: rows[loop].id})
                                    .text('#' + rows[loop].id + ' ' + rows[loop].last_comment + ' // ' + rows[loop].create_dt + ' (' + rows[loop].avtor + ')'));
                }// '#'.$chain->id.' '.$chain->last_comment.' // '.$date.' в '.$time.' ('.$chain->avtor.')'
            }
        },
        // Что-то пошло не так
        error: function (result) {

        }
    });


    $('#id_CallModal').on('show.bs.modal', function () {
// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this);
        modal.find('.class_clt_name').text(g_name);
        $('#id_call_phone').val(g_tel);
        $('#id_call_clt_id').val(pclt_id);

        $('#id_call_success').css('display', 'none');
        $('#id_call_error').css('display', 'none');

    });

    g_name = pname;
    g_tel = ptel;
    $('#id_CallModal').modal('show');
//alert(phost+" "+pnet);
}

function filterColumn ( i ) {
    $('#id_clients_td').DataTable().column( i ).search(
        $('#id_col'+i+'_filter').val()
    ).draw();
}

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    function CreateChainbyCall() {

        if ($('#id_call_new_create_chain').is(":checked")) {
            var clt_id = $('#id_call_clt_id').val();
            var category = $('#id_call_new_category').val();
//var call_status = $('#id_call_new_status').val();
            var interlocutor = $('#id_call_phone').val();
            var comment = $('#id_call_new_comment').val();
            var open_chain_id = $('#id_call_new_open_chains').val();
//var chain_id_exist = $('#id_call_new_exist_chain').val();
            var chain_id_exist = 'null';

            if (comment === '') {
                alert('При создании протокола поле комментарий должно быть заполнено!');
                return;
            }
//alert(clt_id);
//alert(clt_id+'; open_chain_id:'+open_chain_id+'; chain_id_exist:'+chain_id_exist+'; category:'+category+'; interlocutor:'+interlocutor+'; comment:'+comment);
            $.ajax({
                url: '/clients/ajax_create_chain_by_call',
                type: 'POST',
                data: {'client_id': clt_id, 'category': category, 'interlocutor': interlocutor, 'comment': comment, 'open_chain_id': open_chain_id, 'chain_id_exist': chain_id_exist},
                dataType: 'json',
                success: function (result) {
                    if (result.status === 1) {
                        //alert(result.new_call_id);
                        call_id = result.new_call_id;
//                    var rows = result.chains_opened;
//                    //console.log('3 ajax_start ' + (new Date().toISOString().slice(11, -1)));
//                    for (loop = 0; loop < rows.length; loop++) {
//                            $('#id_call_new_open_chains')
//                                    .append($('<option>', {value: rows[loop].id}) 
//                                    .text('#'+rows[loop].id+' '+rows[loop].last_comment+' // '+rows[loop].create_dt+' ('+rows[loop].avtor+')'));
//                    }// '#'.$chain->id.' '.$chain->last_comment.' // '.$date.' в '.$time.' ('.$chain->avtor.')'
                        $('#id_call_success').css('display', 'inline');
                    }
                },
                // Что-то пошло не так
                error: function (result) {
                    $('#id_call_error').css('display', 'inline');
                }
            });
        }
    }
    ;

    function UpdateCallStatusByTel() {

        if ($('#id_call_new_create_chain').is(":checked")) {

//var category = $('#id_call_new_category').val();
//var call_status = $('#id_call_new_status').val();
            var interlocutor = $('#id_call_phone').val();
//var comment = $('#id_call_new_comment').val();
//var open_chain_id = $('#id_call_new_open_chains').val();
//var chain_id_exist = $('#id_call_new_exist_chain').val();
//var chain_id_exist = 'null';

//alert(clt_id+'; open_chain_id:'+open_chain_id+'; chain_id_exist:'+chain_id_exist+'; category:'+category+'; interlocutor:'+interlocutor+'; comment:'+comment);
//alert(call_id);
            if (call_id) {
                $.ajax({
                    url: '/clients/ajax_update_call_status_by_tel',
                    type: 'POST',
                    data: {'call_id': call_id, 'interlocutor': interlocutor},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === 1) {
                            //alert(result.call_id);
                            // alert(result.res);
//                    $('#id_call_success').css('display', 'inline');
                        }
                    },
                    // Что-то пошло не так
                    error: function (result) {
                        //$('#id_call_error').css('display', 'inline');
                    }
                });
            }
//else alert('undef');
        } else {
            if (is_called === 1) {
                var interlocutor = $('#id_call_phone').val();
                $.ajax({
                    url: '/clients/ajax_update_cdr_user',
                    type: 'POST',
                    data: {'interlocutor': interlocutor},
                    dataType: 'json',
                    success: function (result) {
                        if (result.status === 1) {
                            //alert(result.call_id);
                            // alert(result.res);
//                    $('#id_call_success').css('display', 'inline');
                        }
                    },
                    // Что-то пошло не так
                    error: function (result) {
                        //$('#id_call_error').css('display', 'inline');
                    }
                });
            }
//alert('no protocol');
        }

    }
    ;

//on page load do init
    $('#id_call_btn').click(function () {
        //alert('call');
        makeCall($('#id_call_phone').val());
        CreateChainbyCall();
        is_called = 1;
    });
    $('#id_call_hang_btn').click(function () {
        sipHangUp();
    });


//        $('#id_clients_td thead th').each( function () {
//            var title = $(this).text();
//            $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
//        } );            



    var table = $('#id_clients_td').DataTable({
        initComplete: function () {
//            this.api().columns().every( function () {
//                var column = this;
                var column = table.column(6);
                var select = $('#id_col6_filter');
//                var select = $('<select><option value=""></option></select>')
//                    .appendTo( $(column.footer()).empty() )
                    select.on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' );
                } );
//            } );            
        },
        "language": {
            //"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json",
            "processing": "Подождите...",
            "search": "Поиск по всем полям:",
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
        "ajax": "/clients/json",
        "deferRender": true
    });

    $('input.column_filter').on( 'keyup click', function () {
        filterColumn( $(this).attr('data-column') );
    } );

//    $('input.column_filter_sel').on( 'change', function () {
//        filterColumn( $(this).attr('data-column') );
//    } );


    $('#id_CallModal').on('hidden.bs.modal', function (e) {
        UpdateCallStatusByTel();
        call_id = null;
        is_called = 0;
        //alert('Close');
    });

    $('#id_call_new_create_chain').click(function () {

        if ($('#id_call_new_create_chain').is(":checked"))
            $('#id_call_new_chain_section').css('display', 'inline');
        else
            $('#id_call_new_chain_section').css('display', 'none');

    });

    $('#id_call_new_open_chains').change(function () {
        if ($('#id_call_new_open_chains').val() === "0") {
            $('#id_call_new_category_section').css('display', 'inline');
            //$("#id_req_new_category").prop("required", true);
        } else {
            $('#id_call_new_category_section').css('display', 'none');
            //$("#id_req_new_category").prop("required", false);
        }
        $('#id_call_new_category').val('');

    });

});



</script>

@endsection            