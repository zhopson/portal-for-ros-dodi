@extends('layouts.app')
@section('head')
<script type="text/javascript" src="http://code.jquery.com/jquery.min.js?2"></script>
@endsection
@section('content')

<div class="container-fluid" style="margin:0 60px 0 60px">
    <div class="row">
        <h3 style="margin-top:-10px">Изменить данные пользователя</h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="margin-bottom:55px">
                <div class="panel-body">
                    <form class="form-horizontal"  method="POST" action="{{ url('clients.update') }}">
                        {{ csrf_field() }}
                        <div id='id_clt_user_view'>
                            @if ($new_clt->clients_type_id==1 || $new_clt->clients_type_id==2 )
                            <div id='id_clt_fio_section'>
                                <div class="page-header" style="margin: 20px 0 0 10px">
                                    <h4 style="margin-bottom:-3px">Личные данные</h4>
                                </div>
                                <div class="form-group" style="margin-top:10px">
                                    <label for="id_clt_edit_surname" class="col-sm-4 control-label">Фамилия</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_surname" value="{{$new_clt->surname}}" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_clt_edit_name" class="col-sm-4 control-label">Имя</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_name" value="{{$new_clt->name}}" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_clt_edit_otch" class="col-sm-4 control-label">Отчество</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_otch" value="{{$new_clt->patronymic}}" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Пол</label>
                                    <div class="col-sm-8">
                                        @if ($new_clt->sex==1)
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexm" value="id_clt_sexm" required checked> Мужской
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexw" value="id_clt_sexw" required> Женский
                                        </label>
                                        @else
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexm" value="id_clt_sexm" required> Мужской
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexw" value="id_clt_sexw" required  checked> Женский
                                        </label>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if ($new_clt->clients_type_id==1)
                            <div id='id_clt_parents_section'>
                                <div class="page-header" style="margin: 20px 0 0 10px">
                                    <h4 style="margin-bottom:-3px">Родители</h4>
                                </div>
                                <div class="form-group" style="margin-top:10px">
                                    <label for="id_clt_edit_father" class="col-sm-4 control-label">Отец</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_father" value="{{$new_clt->father}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_clt_edit_mother" class="col-sm-4 control-label">Мать</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_cltedit__mother" value="{{$new_clt->mother}}">
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if ($new_clt->clients_type_id!=3)
                            <div id='id_clt_lang_section'>
                                <div class="page-header" style="margin: 20px 0 0 10px">
                                    <h4 style="margin-bottom:-3px">Владение языками</h4>
                                </div>
                                <div class="form-group" style="margin-top:10px">
                                    <label for="id_clt_edit_langs" class="col-sm-4 control-label">Языки</label>
                                    <div class="col-sm-8">
                                        <select multiple class="form-control" id="id_clt_edit_langs">
                                            @foreach (array("russian" => "русский", "english" => "английский", "sakha" => "якутский") as $key => $value)
                                            @if (strpos($new_clt->language, $key)!==false)
                                                <option selected>{{ $value }}</option>
                                            @else
                                                <option>{{ $value }}</option>
                                            @endif
                                            @endforeach  
<!--                                            <option selected>русский</option>
                                            <option>английский</option>
                                            <option>якутский</option>-->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if ($new_clt->clients_type_id==3)
                            <div id='id_clt_org_section'>
                                <div class="page-header" style="margin: 20px 0 0 10px">
                                    <h4 style="margin-bottom:-3px">Организация</h4>
                                </div>
                                <div class="form-group" style="margin-top:10px">
                                    <label for="id_clt_edit_org" class="col-sm-4 control-label">Наименование</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_org" value="{{$new_clt->name}}">
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @if ($new_clt->clients_type_id==1)
                        <div id='id_clt_health_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Здоровье</h4>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_diag" class="col-sm-4 control-label">Диагноз</label>
                                <div class="col-sm-8">
                                    <textarea rows="10" cols="50" class="form-control" id="id_clt_edit_diag" value="{{$new_clt->diagnose}}"></textarea>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div id='id_clt_address_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Адрес</h4>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_ind" class="col-sm-4 control-label">Почтовый индекс</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_ind">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_region" class="col-sm-4 control-label">Регион</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_region" name="v_clt_adr_region">
                                        <option value="0">- Выберите -</option>
                                        @foreach ($regions as $region) 
                                            <option value="{{ $region->aoguid }}">{{ $region->shortname.'. '.$region->offname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_raion" class="col-sm-4 control-label">Район</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_raion" name="v_clt_adr_raion">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_city" class="col-sm-4 control-label">Город</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_city" name="v_clt_adr_city">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_np" class="col-sm-4 control-label">Населенный пункт</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_np" name="v_clt_adr_np">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_st" class="col-sm-4 control-label">Улица</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_st" name="v_clt_adr_st">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="id_clt_edit_adr_dom" class="col-sm-4 control-label">Дом</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_dom">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_adr_korp" class="col-sm-4 control-label">Корпус</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_korp">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_adr_kv" class="col-sm-4 control-label">Квартира</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_kv">
                                </div>
                            </div>
                        </div>
                        <div id='id_clt_contacts_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Контактные данные</h4>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_contacts_tel" class="col-sm-4 control-label">Телефон</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="id_clt_edit_contacts_tel">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" id="id_clt_edit_contacts_name">
                                </div>
                                <div class="col-sm-1">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_contacts_mail" class="col-sm-4 control-label">Электронная почта</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="id_clt_edit_contacts_mail">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_contacts_skype" class="col-sm-4 control-label">Skype</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="id_clt_edit_contacts_skype">
                                </div>
                            </div>
                        </div>
                        <div id='id_clt_inet_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Интернет</h4>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_inet_prd" class="col-sm-4 control-label">Провайдер</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_inet_prd" name="v_clt_inet_prd">
                                        <option value="0"></option>
                                        @foreach ($providers as $provider) 
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <table class="table table-hover">
                                <thead>
                                    <tr class="active">
                                        <th>Адрес</th>
                                        <th>Маска</th>
                                        <th>Шлюз</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="table-text">
                                            <input type="text" class="form-control" id="id_clt_edit_inet_ip">
                                        </td>
                                        <td class="table-text">
                                            <input type="text" class="form-control" id="id_clt_edit_mask_ip">
                                        </td>
                                        <td class="table-text">
                                            <input type="text" class="form-control" id="id_clt_edit_gate_ip">
                                        </td>
                                        <td class="table-text">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>                                
                            </table>
                        </div>
                        <div id='id_clt_dop_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Дополнительно</h4>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_dop_active" class="col-sm-4 control-label">Статус</label>
                                <div class="col-sm-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="" id="id_clt_edit_dop_active" checked>
                                            Активный
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_dop_gr" class="col-sm-4 control-label">Группы</label>
                                <div class="col-sm-7">
                                    <select class="form-control" id="id_clt_edit_dop_gr" name="v_clt_dop_gr">
                                        <option value="0"></option>
                                        @foreach ($clt_groups as $clt_group) 
                                        <option value="{{ $clt_group->id }}">{{ $clt_group->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-1">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                    </span>                                    
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="id_clt_edit_dop_problem" class="col-sm-4 control-label"></label>
                                <div class="col-sm-2">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" value="" id="id_clt_edit_dop_problem">
                                            Проблемный
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_dop_contract" class="col-sm-4 control-label">Контракт</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_dop_contract" name="v_clt_dop_contract">
                                        <option value="0"></option>
                                        @foreach ($clt_contracts as $clt_contract) 
                                        <option value="{{ $clt_contract->id }}">{{ $clt_contract->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="id_clt_edit_dop_prim" class="col-sm-4 control-label">Примечание</label>
                                <div class="col-sm-8">
                                    <textarea rows="10" cols="50" class="form-control" id="id_clt_edit_dop_prim"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-10 col-sm-1" style="margin-right:10px">
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" id="id_clt_edit_nav_buttons">
                <li role="presentation" id="id_clt_edit_nav_user" class="active"><a href="#">Пользователь</a></li>
                @if ($new_clt->clients_type_id==1)
                <li role="presentation" id="id_clt_edit_nav_health"><a href="#">Здоровье</a></li>
                @endif
                <li role="presentation" id="id_clt_edit_nav_addr"><a href="#">Адрес</a></li>   
                <li role="presentation" id="id_clt_edit_nav_contacts"><a href="#">Контактные данные</a></li>   
                <li role="presentation" id="id_clt_edit_nav_inet"><a href="#">Интернет</a></li>   
                <li role="presentation" id="id_clt_edit_nav_dop"><a href="#">Дополнительно</a></li>   
            </ul>
            <ul class="nav nav-pills nav-stacked" style="margin-top:50px">
                <li role="presentation"><a href="{{ url('/clients') }}">Вернуться назад</a></li>   
            </ul>
        </div>
    </div>
</div>

<script type="text/javascript">
$( document ).ready(function() {  
    
$("li").click(function(){
    //$("p").removeClass("myClass noClass")
    var id = $(this).attr("id");
    if (id==='id_clt_edit_nav_user') {
        $("#id_clt_edit_nav_buttons").find("li").removeClass("active");
        $("#id_clt_edit_nav_user").addClass("active");
        
        $('#id_clt_user_view').css('display', 'inline');
        $('#id_clt_health_view').css('display', 'none');
        $('#id_clt_address_view').css('display', 'none');
        $('#id_clt_contacts_view').css('display', 'none');
        $('#id_clt_inet_view').css('display', 'none');
        $('#id_clt_dop_view').css('display', 'none');
        //alert('user');
    }
    if (id==='id_clt_edit_nav_health') {
        $("#id_clt_edit_nav_buttons").find("li").removeClass("active");
        $("#id_clt_edit_nav_health").addClass("active");
        
        $('#id_clt_user_view').css('display', 'none');
        $('#id_clt_health_view').css('display', 'inline');
        $('#id_clt_address_view').css('display', 'none');
        $('#id_clt_contacts_view').css('display', 'none');
        $('#id_clt_inet_view').css('display', 'none');
        $('#id_clt_dop_view').css('display', 'none');
        //alert('health');
    }
    if (id==='id_clt_edit_nav_addr') {
        $("#id_clt_edit_nav_buttons").find("li").removeClass("active");
        $("#id_clt_edit_nav_addr").addClass("active");
        
        $('#id_clt_user_view').css('display', 'none');
        $('#id_clt_health_view').css('display', 'none');
        $('#id_clt_address_view').css('display', 'inline');
        $('#id_clt_contacts_view').css('display', 'none');
        $('#id_clt_inet_view').css('display', 'none');
        $('#id_clt_dop_view').css('display', 'none');
        //alert('addr');
    }
    if (id==='id_clt_edit_nav_contacts') {
        $("#id_clt_edit_nav_buttons").find("li").removeClass("active");
        $("#id_clt_edit_nav_contacts").addClass("active");

        $('#id_clt_user_view').css('display', 'none');
        $('#id_clt_health_view').css('display', 'none');
        $('#id_clt_address_view').css('display', 'none');
        $('#id_clt_contacts_view').css('display', 'inline');
        $('#id_clt_inet_view').css('display', 'none');
        $('#id_clt_dop_view').css('display', 'none');
        //alert('contacts');
    }
    if (id==='id_clt_edit_nav_inet') {
        $("#id_clt_edit_nav_buttons").find("li").removeClass("active");
        $("#id_clt_edit_nav_inet").addClass("active");
        
        $('#id_clt_user_view').css('display', 'none');
        $('#id_clt_health_view').css('display', 'none');
        $('#id_clt_address_view').css('display', 'none');
        $('#id_clt_contacts_view').css('display', 'none');
        $('#id_clt_inet_view').css('display', 'inline');
        $('#id_clt_dop_view').css('display', 'none');       
        //alert('inet');
    }
    if (id==='id_clt_edit_nav_dop') {
        $("#id_clt_edit_nav_buttons").find("li").removeClass("active");
        $("#id_clt_edit_nav_dop").addClass("active");
        
        $('#id_clt_user_view').css('display', 'none');
        $('#id_clt_health_view').css('display', 'none');
        $('#id_clt_address_view').css('display', 'none');
        $('#id_clt_contacts_view').css('display', 'none');
        $('#id_clt_inet_view').css('display', 'none');
        $('#id_clt_dop_view').css('display', 'inline');         
        //alert('dop');
    }
});

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$('#id_clt_edit_adr_region').change(function(){
//  if ($(this).val().indexOf("Саха")!==-1){
      //alert($("#id_clt_edit_adr_region option:selected").text());
                $('#id_clt_edit_adr_raion').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                $('#id_clt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                $('#id_clt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                var data = $(this).val();
                ajax_fill_addr_sel("#id_clt_edit_adr_raion",data,'raion');
//  }
});

$('#id_clt_edit_adr_raion').change(function(){
  //if ($(this).val()!=="0"){
                $('#id_clt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                $('#id_clt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                var data = $(this).val();
                ajax_fill_addr_sel("#id_clt_edit_adr_np",data,'np');
 // }
});

$('#id_clt_edit_adr_city').change(function(){
  //if ($(this).val()!=="0"){
                if ($('#id_clt_edit_adr_raion').val()==="0") {
                    $('#id_clt_edit_adr_raion').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;}
                $('#id_clt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                var data = $(this).val();
                ajax_fill_addr_sel("#id_clt_edit_adr_np",data,'np_city');
 // }
});

$('#id_clt_edit_adr_np').change(function(){
  //if ($(this).val()!=="0"){
                $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>') ;
                var data = $(this).val();
                //var parent;
//                if ($('#id_clt_edit_adr_city').val()!=="0") parent = $('#id_clt_edit_adr_city').val();
//                else if ($('#id_clt_edit_adr_raion').val()!=="0") parent = $('#id_clt_edit_adr_raion').val();
                ajax_fill_addr_sel("#id_clt_edit_adr_st",data,'st');
  //}
});

function ajax_fill_addr_sel(id_sel,pdata,padr_part) {
//            var formData = {
//                'adr_part_val': pdata,
//                'adr_part': padr_part
//            };
                
		$.ajax({
			// На какой URL будет послан запрос
			url: '/adresses/adr_part_list',
			// Тип запроса
			type: 'POST',
			// Какие данные нужно передать
			data: {'adr_part_val': pdata, 'adr_part': padr_part},
                        //data: formData,
			// !!! не использовать, параметры на передаются в контроллер. Эта опция не разрешает jQuery изменять данные
			//processData: false,		
			// !!! не использовать, параметры на передаются в контроллер. Эта опция не разрешает jQuery изменять типы данных
			//contentType: false,		
			// Формат данных ответа с сервера
			dataType: 'json',
                        //dataType: 'text',
                        //headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			// Функция удачного ответа с сервера
			success: function(result) { 	
				// Получили ответ с сервера (ответ содержится в переменной result)
				// Если в ответе есть объект 
				if (result.status===1) {
                                    
                                    var rows = result.adr_arr;
                                    for (loop = 0; loop < rows.length; loop++) {
                                        $(id_sel)
                                            .append($('<option>', { value : rows[loop].aoguid }) //value : rows[loop].rtf_aoguid
                                            .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                                    }
                                    
                                    if (id_sel==='#id_clt_edit_adr_raion') {
                                        rows = result.city_arr;
                                        for (loop = 0; loop < rows.length; loop++) {
                                            $('#id_clt_edit_adr_city')
                                                .append($('<option>', { value : rows[loop].aoguid }) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                                        }

                                        rows = result.punkt_arr;
                                        for (loop = 0; loop < rows.length; loop++) {
                                            $('#id_clt_edit_adr_np')
                                                .append($('<option>', { value : rows[loop].aoguid }) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                                        }
                                    }
                                    else if (id_sel==='#id_clt_edit_adr_np') {
                                        
                                        rows = result.city_arr;
                                        for (loop = 0; loop < rows.length; loop++) {
                                            $('#id_clt_edit_adr_city')
                                                .append($('<option>', { value : rows[loop].aoguid }) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                                        }
                                        
                                        rows = result.st_arr;
                                        for (loop = 0; loop < rows.length; loop++) {
                                            $('#id_clt_edit_adr_st')
                                                .append($('<option>', { value : rows[loop].aoid }) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                                        }
                                    }
				} 
			},
			// Что-то пошло не так
			error: function (result) {

			}
		});     
}
});
</script>
@endsection