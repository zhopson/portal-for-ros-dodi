@extends('layouts.app')
@section('head')
@endsection

@section('content')

<div class="container-fluid" style="margin:0 15px 0 15px">
    <div class="row">
        <div class="col-md-3"><h3 style="margin-top:25px"><div class="header-text">Расширенный поиск клиентов</div></h3></div>
    </div>
    <div class="panel panel-default" style="margin: -5px 5px 60px 5px">
        <!--    <div class="panel-heading">
                <h3 class="panel-title">Клиенты</h3>
            </div>-->
        <div class="panel-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="container-fluid">
                                
                                <div class="row">
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_clt_advsearch_col2_filter">ФИО</label>
                                                <input type="text" class="form-control input-sm column_filter_r" id="id_clt_advsearch_col2_filter" data-column="2" name="v_clt_advsearch_fio" >
                                            </div>                                                
                                    </div>
                                    <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="id_clt_advsearch_col4_filter">Адрес</label>
                                                <input type="text" class="form-control input-sm column_filter_r" id="id_clt_advsearch_col4_filter" data-column="4" name="v_clt_advsearch_adr" >
                                            </div>                                                
                                    </div>
                                    <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="id_clt_advsearch_col9_filter">Контакты</label>
                                                <input type="text" class="form-control input-sm column_filter_r" id="id_clt_advsearch_col9_filter" data-column="9" name="v_clt_advsearch_cnt" >
                                            </div>                                                
                                    </div>
                                    <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="id_clt_advsearch_col8_filter">ip</label>
                                                <input type="text" class="form-control input-sm column_filter_r" id="id_clt_advsearch_col8_filter" data-column="8" name="v_clt_advsearch_ip" >
                                            </div>                                                
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_clt_advsearch_col6_filter">Провайдер</label>
                                            <select class="form-control input-sm column_filter_sel_r" id="id_clt_advsearch_col6_filter" name="v_clt_advsearch_prd">
                                                <option value=""></option>
                                                @foreach ($providers as $provider) 
                                                    <option value="{{  $provider->name }}">{{ $provider->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                                
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_clt_advsearch_col1_filter">Статус</label>
                                            <select class="form-control input-sm column_filter_sel_r" id="id_clt_advsearch_col1_filter" name="v_clt_advsearch_sts">
                                                <option value=""></option>
                                                <option value="Неактивный">Неактивный</option>
                                                <option value="Активный">Активный</option>
                                            </select>
                                        </div>                                                
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_clt_advsearch_col7_filter">Группы</label>
                                            <select class="form-control input-sm column_filter_sel_r" id="id_clt_advsearch_col7_filter" name="v_clt_advsearch_grp">
                                                <option value=""></option>
                                                @foreach ($clt_groups as $clt_group) 
                                                    <option value="{{ $clt_group->name }}">{{ $clt_group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                                
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="id_clt_advsearch_col5_filter">Контракт</label>
                                            <select class="form-control input-sm column_filter_sel_r" id="id_clt_advsearch_col5_filter" name="v_clt_advsearch_ctr">
                                                <option value=""></option>
                                                @foreach ($clt_contracts as $clt_contract) 
                                                    <option value="{{ $clt_contract->title }}">{{ $clt_contract->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>                                                
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="pull-left">
                                        <button type="button" class="btn btn-default" id="id_clt_advsearch_reset_btn"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Сбросить фильтры</button>
                                    </div>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-success" id="id_clt_advsearch_export_btn">Экспорт</button>
                                    </div>
                                </div>
                            </div>
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

                        <table class="display responsiv" id="id_clients_advsearch_td"  cellspacing="0" width="100%">
                            <thead>
                                <tr class="active">
                                    <th>№</th>
                                    <th>Статус</th>
                                    <th style="width: 280px">ФИО/Наименование</th>
                                    <th>Тип</th>
                                    <th>Адрес</th>
                                    <th>Контракт</th>
                                    <th>Провайдер</th>
                                    <th>Группы</th>
                                    <th>ip адрес</th>
                                    <th>Контактные данные</th>
                                    <th>name</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr class="active">
                                    <th>№</th>
                                    <th>Статус</th>
                                    <th style="width: 280px">ФИО/Наименование</th>
                                    <th>Тип</th>
                                    <th>Адрес</th>
                                    <th>Контракт</th>
                                    <th>Провайдер</th>
                                    <th>Группы</th>
                                    <th>ip адрес</th>
                                    <th>Контактные данные</th>
                                    <th>name</th>
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
<script src="{{ asset('js/sheetjs.all.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/excelplus-2.4.1.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">


window.onload = function () {


};

function filterColumn ( i ) {
    $('#id_clients_advsearch_td').DataTable().column( i ).search(
        $('#id_clt_advsearch_col'+i+'_filter').val()
    ).draw();
}

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#id_clt_advsearch_reset_btn').click(function () {
        $('#id_clt_advsearch_col2_filter').val('').keyup();
        $('#id_clt_advsearch_col4_filter').val('').keyup();
        $('#id_clt_advsearch_col8_filter').val('').keyup();
        $('#id_clt_advsearch_col9_filter').val('').keyup();
        
        $('#id_clt_advsearch_col1_filter').val('').change();
        $('#id_clt_advsearch_col5_filter').val('').change();
        $('#id_clt_advsearch_col6_filter').val('').change();
        $('#id_clt_advsearch_col7_filter').val('').change();
    });

    $('#id_clt_advsearch_export_btn').click(function () {
//        alert(table.rows({order: "applied", search: "applied", page: "current"}).data().length);

        var ep = new ExcelPlus();
        ep.createFile("Book1");

        var now = new Date();
        now.setHours(now.getHours() + 9);
        var strd = now.toISOString();
        strd = strd.replace(/T/, ' ');
        
        //ep.write({"sheet": "Book1", "cell": "A1", "content": new Date()});
        ep.writeRow(1, ["Дата: "+strd]);
        ep.writeRow(2, ["Список клиентов"]);
        ep.writeRow(4, ["№","Статус","ФИО","Тип","Адрес","Контракт","Провайдер","Группы","ip","Контакты"]);

        var fData = table.rows({order: "applied", search: "applied"}).data();
        
        var index, len;
        for (index = 0, len = fData.length; index < len; ++index) {
            r = fData[index];
            r[0] = index + 1;
            r[7] = r[7].replace(/<\/li><li>/g, ',');
            r[7] = r[7].replace(/^<li>/, '').replace(/<\/li>$/, '');
            ep.writeRow(index+5, [r[0],r[1],r[10],r[3],r[4],r[5],r[6],r[7],r[8],r[9]]);
            //ep.writeRow(index+5, fData[index]);
            //console.log(fData[index]);
        }
        ep.saveAs("clients_report.xlsx");
        
    });
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    var table = $('#id_clients_advsearch_td').DataTable({
        initComplete: function () {
//            this.api().columns().every( function () {
//                var column = this;

//            var col;
//            for (col = 1; col < 8; col++) {
//                if ( col === 1 || col === 5 || col === 6 || col === 7 ) { // 1,5,6,7  [1,5,6,7].indexof(col) > -1
//                if ( col === 1 ) { // 1,5,6,7  [1,5,6,7].indexof(col) > -1

                    column1 = table.column(1); 
//                    select = $('#id_clt_advsearch_col'+col+'_filter');
                    select1 = $('#id_clt_advsearch_col1_filter');
                    select1.on( 'change', function () {
                            var val1 = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            //alert('"'+val+'"');
                            column1
                                .search( val1 ? '^'+val1+'$' : '', true, false )
                                .draw();
                        } );
                        
///////////////////////////////////////////////////////////////////                        
                    column5 = table.column(5); 
//                    select = $('#id_clt_advsearch_col'+col+'_filter');
                    select5 = $('#id_clt_advsearch_col5_filter');
                    select5.on( 'change', function () {
                            var val5 = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            //alert('"'+val+'"');
                            column5
                                .search( val5 ? '^'+val5+'$' : '', true, false )
                                .draw();
                        } );

///////////////////////////////////////////////////////////////////
                    column6 = table.column(6); 
//                    select = $('#id_clt_advsearch_col'+col+'_filter');
                    select6 = $('#id_clt_advsearch_col6_filter');
                    select6.on( 'change', function () {
                            var val6 = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            //alert('"'+val+'"');
                            column6
                                .search( val6 ? '^'+val6+'$' : '', true, false )
                                .draw();
                        } );

///////////////////////////////////////////////////////////////////
                    column7 = table.column(7); 
//                    select = $('#id_clt_advsearch_col'+col+'_filter');
                    select7 = $('#id_clt_advsearch_col7_filter');
                    select7.on( 'change', function () {
                            var val7 = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
                            //alert('"'+val+'"');
                            column7
                                .search( val7 ? '^'+val7+'$' : '', true, false )
                                .draw();
                        } );

                        
//                    column.data().unique().sort().each( function ( d, j ) {
//                        select.append( '<option value="'+d+'">'+d+'</option>' );
//                    } );                        
//                }
//            }
 
//            } );            
        },
        "columnDefs": [
            {
                "targets": [ 10 ],
                "visible": false,
                "searchable": false
            },
        ],        
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
        //"paging":   false,
        "pageLength": 25,
        "ajax": "/clients/json_advsearch",
        "deferRender": true
    });

    $('input.column_filter_r').on( 'keyup click', function () {
        filterColumn( $(this).attr('data-column') );
    } );



});



</script>

@endsection            