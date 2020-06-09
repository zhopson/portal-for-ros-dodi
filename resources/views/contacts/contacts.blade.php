@extends('layouts.app')
@section('head')
<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"> </script>
@endsection

@section('content')


<div class="modal fade " id="id_CallModal" tabindex="-1" role="dialog" aria-labelledby="CallModalLabel" data-backdrop="static">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="CallModalLabel">Позвонить</h4>
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
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="id_call_phone" name="v_call_phone" readonly="true">                        
                        </div>
                    </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="pull-right">Статус SIP</div>
                        </div>
                        <div class="col-md-7">
                            <input type="text" class="form-control" id="id_call_anytel_sip_log" name="v_call_sip" readonly="true">                        
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="id_call_btn" class="btn btn-primary">Позвонить</button>
                <button type="button" id="id_call_hang_btn" class="btn btn-default">Положить</button>
            </div>
        </div>
    </div>
</div>


<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
    </div>
<div class="panel panel-default" style="margin: 5px 55px 60px 5px">
    <div class="panel-heading">
        
        @if ($punkt)
            <h3 class="panel-title">Контакты внештатных помощников</h3>
        @else
            <h3 class="panel-title">Контакты </h3>
        @endif        
        
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div style="margin-top:-18px" >
                    <div class="col-md-2">
                        <a href="{{ route('contacts.new') }}"><h6><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить</h6></a>
                    </div>
                </div>
            </div>
            <div class="row">
<!--                <table class="table table-hover table-bordered">-->
                <div class="table-responsive">
                <table class="display" id="id_contacts_td"  cellspacing="0" width="100%">
                    <thead>
                        <tr class="active">
                            <th style="width: 280px">ФИО/Наименование</th>
                            <th>Адрес</th>
                            <th>Место работы</th>
                            <th>Должность</th>
                            <th>Электронная почта</th>
                            <th>Телефоны</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="active">
                            <th style="width: 280px">ФИО/Наименование</th>
                            <th>Адрес</th>
                            <th>Место работы</th>
                            <th>Должность</th>
                            <th>Электронная почта</th>
                            <th>Телефоны</th>
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
        <script src="{{ asset('js/sip.js?svn=3') }}" type="text/javascript"> </script>
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

function call_client(pclt_id,pname,ptel){

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

    $(document).ready(function () {
        
//    $.ajaxSetup({
//        headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//        }
//    });    
    
 
        //on page load do init
            $('#id_call_btn').click(function () {
                //alert('call');
                makeCall($('#id_call_phone').val());
                is_called = 1;                
            });
            $('#id_call_hang_btn').click(function () {
                sipHangUp();
            });
            
        $('#id_contacts_td').DataTable({
            
            "columnDefs": [{
                    "targets": [0],
                    "visible": false,
                    "searchable": false                
                },
            ],             
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
            
            @if ($punkt)
            "ajax": "/contacts/json4clt/"+'{{ $punkt }}', 
            @else
            "ajax": "/contacts/json", 
            @endif
            "deferRender": true            
        });            
            
            $('#id_CallModal').on('hidden.bs.modal', function (e) {
                call_id = null;
                is_called = 0;                
                //alert('Close');
            });                

    });



        </script>
        
@endsection            