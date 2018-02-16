@extends('layouts.app')
@section('head')

@endsection
@section('content')

@php
$city_guid = ''; $city_name = '';
$region_guid = ''; $region_name = '';
$raion_guid = ''; $raion_name = '';
$np_guid = ''; $np_name = '';
$st_guid = ''; $st_name = '';
if ($address_components) {
    foreach ($address_components as $component) { 
        if ($component->taolevel == 1) { $region_guid = $component->taoguid; }
        if ($component->taolevel == 3) { $raion_guid = $component->taoguid; $raion_name = $component->tshortname.'. '.$component->toffname; }
        if ($component->taolevel == 4) { $city_guid = $component->taoguid;  $city_name = $component->tshortname.'. '.$component->toffname;}
        if ($component->taolevel == 6) { $np_guid = $component->taoguid;  $np_name = $component->tshortname.'. '.$component->toffname;}
        if ($component->taolevel > 6) { $st_guid = $component->taoid;  $st_name = $component->tshortname.'. '.$component->toffname;}
    }
}

@endphp

<div class="container-fluid" style="margin:0 60px 0 60px">
    <div class="row">
        <h3 style="margin-top:-10px">Изменить контакт</h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="margin-bottom:55px">
                <div class="panel-body">
                    <form class="form-horizontal"  method="POST" action="{{ route('contacts.update', ['id' => $new_cnt->id]) }}">
                    {{ csrf_field() }}
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                            <a class="btn btn-default" href="{{ url('/contacts') }}">Отмена</a>
                        </div>
                    <div class="form-group" style="margin-top:10px"></div>
                    @if ( $new_cnt->type!=='ORGANIZATION' )
                    <div id='id_cnt_fio_section'>
                        <div class="page-header" style="margin: 20px 0 0 10px">
                            <h4 style="margin-bottom:-3px">Личные данные</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_cnt_edit_surname" class="col-sm-4 control-label">Фамилия</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="id_cnt_edit_surname" name="v_cnt_edit_surname" value="{{$new_cnt->surname}}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_cnt_edit_name" class="col-sm-4 control-label">Имя</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="id_cnt_edit_name"  name="v_cnt_edit_name" value="{{$new_cnt->name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_cnt_edit_otch" class="col-sm-4 control-label">Отчество</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="id_cnt_edit_otch" name="v_cnt_edit_otch" value="{{$new_cnt->patronymic}}">
                            </div>
                        </div>
                    </div>
                    @else
                    <div id='id_cnt_edit_org_section'>
                        <div class="page-header" style="margin: 20px 0 0 10px">
                            <h4 style="margin-bottom:-3px">Организация</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_cnt_edit_org" class="col-sm-4 control-label">Наименование</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="id_cnt_edit_org" name="v_cnt_edit_org" value="{{$new_cnt->name}}">
                            </div>
                        </div>
                    </div>
                    @endif
                        <div id='id_cnt_address_view'>
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Адрес</h4>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_cnt_edit_adr_ind" class="col-sm-3 control-label">Почтовый индекс</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="id_cnt_edit_adr_ind" name="v_cnt_edit_adr_ind" value="{{$new_cnt->address_postal}}">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_cnt_edit_adr_region" class="col-sm-3 control-label">Регион</label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="id_cnt_edit_adr_region" name="v_cnt_edit_adr_region">
                                        <option value="0" selected>- Выберите -</option>
                                        @foreach ($regions as $region) 
                                        <option value="{{ $region->aoguid }}">{{ $region->shortname.'. '.$region->offname }}</option>
                                        @endforeach
                                    </select>  
                                </div>
                                <div class="col-sm-2">
                                        <span class="input-group-btn">
                                            <button data-toggle="tooltip" title="Очистить адресные поля" class="btn btn-default" type="button" id="id_cnt_edit_adr_btnnew"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></button>
                                        </span>
                                </div>                                
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_cnt_edit_adr_raion" class="col-sm-3 control-label">Район</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_cnt_edit_adr_raion" name="v_cnt_edit_adr_raion">
                                        <option value="0" selected>- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_cnt_edit_adr_city" class="col-sm-3 control-label">Город</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_cnt_edit_adr_city" name="v_cnt_edit_adr_city">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_cnt_edit_adr_np" class="col-sm-3 control-label">Населенный пункт</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_cnt_edit_adr_np" name="v_cnt_edit_adr_np">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_cnt_edit_adr_st" class="col-sm-3 control-label">Улица</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_cnt_edit_adr_st" name="v_cnt_edit_adr_st">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="id_cnt_edit_adr_dom" class="col-sm-3 control-label">Дом</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_cnt_edit_adr_dom" name="v_cnt_edit_adr_dom" value="{{$new_cnt->address_number}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_cnt_edit_adr_korp" class="col-sm-3 control-label">Корпус</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_cnt_edit_adr_korp" name="v_cnt_edit_adr_korp" value="{{$new_cnt->address_building}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_cnt_edit_adr_kv" class="col-sm-3 control-label">Квартира</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_cnt_edit_adr_kv" name="v_cnt_edit_adr_kv" value="{{$new_cnt->address_apartment}}">
                                </div>
                            </div>
                        </div>
                    @if ( $new_cnt->type!=='ORGANIZATION' )
                    <div id='id_cnt_edit_job_section'>
                        <div class="page-header" style="margin: 20px 0 0 10px">
                            <h4 style="margin-bottom:-3px">Работа</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_cnt_edit_placejob" class="col-sm-4 control-label">Место работы</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="id_cnt_edit_placejob" name="v_cnt_edit_placejob"  value="{{$new_cnt->job}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_cnt_edit_postjob" class="col-sm-4 control-label">Должность</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="id_cnt_edit_postjob"  name="v_cnt_edit_postjob"  value="{{$new_cnt->post}}" >
                            </div>
                        </div>
                    </div>
                    @endif
                        <div id='id_cnt_edit_contacts_view'>
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Контактные данные</h4>
                            </div>
                            <div id="id_cnt_edit_contacts_container" class="form-group" style="margin-top:10px">
                                <label for="id_cnt_edit_contacts_tel" class="col-sm-3 control-label">Телефон</label>

                                <div class="ContactsBlock" style="margin-bottom: 5px">

                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="id_cnt_edit_contacts_tel" name="v_cnt_edit_contacts_tel">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="id_cnt_edit_contacts_name" name="v_cnt_edit_contacts_name">
                                    </div>
                                    <div class="col-sm-1">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="id_cnt_edit_contacts_btnadd"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_cnt_edit_contacts_mail" class="col-sm-3 control-label">Электронная почта</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="id_cnt_edit_contacts_mail" name="v_cnt_edit_contacts_mail"  value="{{$new_cnt->email}}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_cnt_edit_contacts_skype" class="col-sm-3 control-label">Skype</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="id_cnt_edit_contacts_skype" name="v_cnt_edit_contacts_skype"  value="{{$new_cnt->skype}}">
                                </div>
                            </div>
                        </div>                    
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
<script type="text/javascript">
    var FlagClearAddress; 
    //var StartAddress; 
    var n = 0;
    function addcontacts() {
        //var n = $("#id_cnt_contacts_container").children('.ContactsBlock').length;
        //if ($("input[name='v_cnt_edit_contacts_name"+n+"']" ).length) n = n + 1;
//        for (var i = 1; i < 20; i++) {
//            if ($('v_cnt_edit_contacts_name'+i)!=null) { n = n + 1;  break; }
//        }
        
        n = n + 1;
        var raw = $('<div/>', {
            'class': 'ContactsBlock',
            style: 'margin-top:5px'
        }).appendTo($('#id_cnt_edit_contacts_container'));
//                                        var raw = $('<div/>', {
//                                                'class' : 'row',
//                                                style : 'margin:5px 1px 0 0;'
//                                        }).appendTo(div);
        var div_sm3e = $('<div/>', {
            'class': 'col-sm-3'
        }).appendTo(raw);
        var div_sm4 = $('<div/>', {
            'class': 'col-sm-4'
        }).appendTo(raw);
        var input1 = $('<input/>', {
            value: $('#id_cnt_edit_contacts_tel').val(),
            type: 'text',
            name: 'v_cnt_edit_contacts_tel'+n,
            'class': 'form-control'
        }).appendTo(div_sm4);

        var div_sm3 = $('<div/>', {
            'class': 'col-sm-3'
        }).appendTo(raw);
        var input2 = $('<input/>', {
            value: $('#id_cnt_edit_contacts_name').val(),
            type: 'text',
            name: 'v_cnt_edit_contacts_name'+n,
            'class': 'form-control'
        }).appendTo(div_sm3);

        var div_sm1 = $('<div/>', {
            'class': 'col-sm-1'
        }).appendTo(raw);
        var div_span_sm1 = $('<span/>', {
            'class': 'input-group-btn'
        }).appendTo(div_sm1);
        var btn_del = $('<button/>', {
            //value : '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span>',
            type: 'button',
            'class': 'btn btn-default'
        }).appendTo(div_span_sm1);
        var span_img = $('<span/>', {
            'class': 'glyphicon glyphicon-minus',
            'aria-hidden': 'true'
        }).appendTo(btn_del);
        btn_del.click(function () {
            $(this).parent().parent().parent().remove();
        });
    }
    
$( document ).ready(function() {  
    
$('#id_cnt_edit_type').change(function(){
  if($(this).val()=='PERSON') {
    $('#id_cnt_edit_fio_section').css('display', 'inline');
    $('#id_cnt_edit_org_section').css('display', 'none');
    $('#id_cnt_edit_job_section').css('display', 'inline');
    $("#id_cnt_edit_surname").prop("required", true);
    $("#id_cnt_edit_name").prop("required", true);
    $("#id_cnt_edit_otch").prop("required", true);
    $("#id_cnt_edit_org").prop("required", false);
  }
  else if($(this).val()=='ORGANIZATION') {
    $('#id_cnt_edit_fio_section').css('display', 'none');
    $('#id_cnt_edit_org_section').css('display', 'inline');
    $('#id_cnt_edit_job_section').css('display', 'none');
    $("#id_cnt_edit_surname").prop("required", false);
    $("#id_cnt_edit_name").prop("required", false);
    $("#id_cnt_edit_otch").prop("required", false);
    $("#id_cnt_edit_org").prop("required", true);  }
});

    $('#id_cnt_edit_adr_btnnew').click(function () {
        FlagClearAddress = 1;
        
        $('#id_cnt_edit_adr_raion').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_dom').val('');
        $('#id_cnt_edit_adr_korp').val('');
        $('#id_cnt_edit_adr_kv').val('');
        
        $('#id_cnt_edit_adr_region').val('0').change();
    });
    
    ////////////////////////////////////////////////////////////////////////////
    $('#id_cnt_edit_contacts_btnadd').click(function () {
        if ($('#id_cnt_edit_contacts_tel').val() !== '') {
            addcontacts();
        }
        return false;
    });
    
    ////////////////////////////////////////////////////////////////////////////    

    $('#id_cnt_edit_adr_region').change(function () {
//  if ($(this).val().indexOf("Саха")!==-1){
        //alert($("#id_clt_edit_adr_region option:selected").text());
        $('#id_cnt_edit_adr_raion').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        console.log('1 region_chg ' + (new Date().toISOString().slice(11, -1)) + '; value:' + data);

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '/adresses/chg_adr_components',
            type: 'POST',
            data: {'parent_aoguid': data},
            dataType: 'json',
            success: function (result) {
                console.log('2 ajax_start region_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    var rows = result.common;
                    for (loop = 0; loop < rows.length; loop++) {
                        if (rows[loop].aolevel === 3 ) 
                            $('#id_cnt_edit_adr_raion')
                                .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        else if (rows[loop].aolevel === 4 ) 
                            $('#id_cnt_edit_adr_city')
                                .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        else if (rows[loop].aolevel === 6 ) 
                            $('#id_cnt_edit_adr_np')
                                .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                    }
                    if ( FlagClearAddress === 0 ) { //$('#id_clt_edit_adr_raion option:selected').val()!=='0'

                    }
                }
            },
            // Что-то пошло не так
            error: function (result) {

            }
        });
//  }
    });


    $('#id_cnt_edit_adr_raion').change(function () {
//  if ($(this).val().indexOf("Саха")!==-1){
        //alert($("#id_clt_edit_adr_region option:selected").text());
        $('#id_cnt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        console.log('3 raion_chg ' + (new Date().toISOString().slice(11, -1)) + '; value:' + data);

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '/adresses/chg_adr_components',
            type: 'POST',
            data: {'parent_aoguid': data},
            dataType: 'json',
            success: function (result) {
                console.log('4 ajax_start raion_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    var rows = result.common;
                    for (loop = 0; loop < rows.length; loop++) {
                        if ( rows[loop].aolevel === 4 ) 
                            $('#id_cnt_edit_adr_city')
                                .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        else if ( rows[loop].aolevel === 6 ) 
                            $('#id_cnt_edit_adr_np')
                                .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                    }
                    if ( FlagClearAddress === 0 ) { //$('#id_clt_edit_adr_raion option:selected').val()!=='0'

                    }
                }
            },
            // Что-то пошло не так
            error: function (result) {

            }
        });
//  }
    });


    $('#id_cnt_edit_adr_city').change(function () {
//  if ($(this).val().indexOf("Саха")!==-1){
        //alert($("#id_clt_edit_adr_region option:selected").text());
        $('#id_cnt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_cnt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        console.log('5 city_chg ' + (new Date().toISOString().slice(11, -1)) + '; value:' + data);

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '/adresses/chg_adr_components',
            type: 'POST',
            data: {'parent_aoguid': data},
            dataType: 'json',
            success: function (result) {
                console.log('6 ajax_start city_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    var rows = result.common;
                    for (loop = 0; loop < rows.length; loop++) {
                        if ( rows[loop].aolevel === 6 ) 
                            $('#id_cnt_edit_adr_np')
                                .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        else if ( rows[loop].aolevel > 6 ) 
                            $('#id_cnt_edit_adr_st')
                                .append($('<option>', {value: rows[loop].aoid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                    }
                    if ( FlagClearAddress === 0 ) { //$('#id_clt_edit_adr_raion option:selected').val()!=='0'

                    }
                }
            },
            // Что-то пошло не так
            error: function (result) {

            }
        });
//  }
    });


    $('#id_cnt_edit_adr_np').change(function () {
//  if ($(this).val().indexOf("Саха")!==-1){
        //alert($("#id_clt_edit_adr_region option:selected").text());
        $('#id_cnt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        console.log('7 np_chg ' + (new Date().toISOString().slice(11, -1)) + '; value:' + data);

        $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url: '/adresses/chg_adr_components',
            type: 'POST',
            data: {'parent_aoguid': data},
            dataType: 'json',
            success: function (result) {
                console.log('8 ajax_start np_chg ' + (new Date().toISOString().slice(11, -1)));
                if (result.status === 1) {
                    var rows = result.common;
                    for (loop = 0; loop < rows.length; loop++) {
                        if ( rows[loop].aolevel > 6 ) 
                            $('#id_cnt_edit_adr_st')
                                .append($('<option>', {value: rows[loop].aoid}) //value : rows[loop].rtf_aoguid
                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                    }
                    if ( FlagClearAddress === 0 ) { //$('#id_clt_edit_adr_raion option:selected').val()!=='0'

                    }
                }
            },
            // Что-то пошло не так
            error: function (result) {

            }
        });
//  }
    });


});

window.onload = function () {
    @if ($new_cnt->phones) 
        @foreach (explode("\n",$new_cnt->phones) as $number)
            @php    
                    $name = '';
                    $num_arr = (explode(":",$number));
                    $phone = $num_arr[0];
                    
                    if ( isset($num_arr[1]) &&  $num_arr[1]!='' )
                        { $name = trim($num_arr[1]); }
            @endphp
            $('#id_cnt_edit_contacts_tel').val('{{$phone}}');
            $('#id_cnt_edit_contacts_name').val('{{$name}}');
            $('#id_cnt_edit_contacts_btnadd').click();
            
            $('#id_cnt_edit_contacts_tel').val('');
            $('#id_cnt_edit_contacts_name').val('');
        @endforeach
    @endif
    
    var r = '{{$region_guid}}';
    if (r!=='') 
        $('#id_cnt_edit_adr_region').val('{{$region_guid}}');
    
    var r = '{{$raion_guid}}';
    if (r!=='') 
            $('#id_cnt_edit_adr_raion').append($('<option>', {value: '{{$raion_guid}}', selected: true})
                                       .text('{{$raion_name}}'));
      
    var r = '{{$city_guid}}';
    if (r!=='') 
            $('#id_cnt_edit_adr_city').append($('<option>', {value: '{{$city_guid}}', selected: true})
                                       .text('{{$city_name}}'));
    
    var r = '{{$np_guid}}';
    if (r!=='') 
            $('#id_cnt_edit_adr_np').append($('<option>', {value: '{{$np_guid}}', selected: true})
                                       .text('{{$np_name}}'));
    
    var r = '{{$st_guid}}';
    if (r!=='') 
            $('#id_cnt_edit_adr_st').append($('<option>', {value: '{{$st_guid}}', selected: true})
                                       .text('{{$st_name}}'));
        
};

</script>
@endsection
