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
<!--                <table class="table table-hover table-bordered">-->
                <div class="table-responsive">
                <table class="display" id="id_clients_td"  cellspacing="0" width="100%">
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
@endsection

@section('footer')
<!--        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
        <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
        <script src="{{ asset('js/modal.js') }}"></script>
        <script src="{{ asset('js/sip.js?svn=3') }}" type="text/javascript"> </script>
        <script type="text/javascript">
            
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
            
        $('#id_clients_td').DataTable({
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
            "ajax": "/clients/json", 
            "deferRender": true            
        });            
            
            
        });



        </script>
        
@endsection            