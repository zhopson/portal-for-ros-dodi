@extends('layouts.app')
@section('head')

@endsection
@section('content')

@php
$city_guid = '';
$region_guid = '';
$raion_guid = '';
$np_guid = '';
$st_guid = '';
if ($address_components) {
    foreach ($address_components as $component) { 
        if ($component->taolevel == 1) { $region_guid = $component->taoguid; }
        if ($component->taolevel == 3) { $raion_guid = $component->taoguid; }
        if ($component->taolevel == 4) { $city_guid = $component->taoguid; }
        if ($component->taolevel == 6) { $np_guid = $component->taoguid; }
        if ($component->taolevel > 6) { $st_guid = $component->taoid; }
    }
}

$dom = '';
$korp = '';
$kv = '';
if ($addresses!=='') {
    if ($addresses[0]->address_number) $dom = $addresses[0]->address_number; 
    if ($addresses[0]->address_building) $korp = $addresses[0]->address_building; 
    if ($addresses[0]->address_apartment) $kv = $addresses[0]->address_apartment;
}

//Request::flash();

$users = App\User::orderBy('email','asc')->get();

@endphp

@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

@if (session('status'))
  <div class="alert alert-success">
        {{ session('status') }}
  </div>
@endif

<div class="container-fluid" style="margin:0 60px 0 60px">
    <div class="row">
        <h3 style="margin-top:25px"><div class="header-text">Изменить данные пользователя</div></h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="margin-bottom:55px">
                <div class="panel-body">
                    <form class="form-horizontal"  method="POST" action="{{ route('clients.update', ['id' => $new_clt->id]) }}">
                        {{ csrf_field() }}
                        <input type="hidden" class="form-control" id="id_clt_edit_src" name="v_clt_edit_src" value="{{$src}}">                        
                        <div id='id_clt_user_view'>
                            @if ( $new_clt->clients_type_id!==3 )
                            <div id='id_clt_fio_section'>
                                <div class="page-header" style="margin: 20px 0 0 10px">
                                    <h4 style="margin-bottom:-3px">Личные данные</h4>
                                </div>
                                <div class="form-group" style="margin-top:10px">
                                    <label for="id_clt_edit_surname" class="col-sm-3 control-label">Фамилия</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_surname" name="v_clt_edit_surname" value="{{$new_clt->surname}}" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_clt_edit_name" class="col-sm-3 control-label">Имя</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_name" name="v_clt_edit_name" value="{{$new_clt->name}}" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_clt_edit_otch" class="col-sm-3 control-label">Отчество</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_otch" name="v_clt_edit_otch" value="{{$new_clt->patronymic}}" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Пол</label>
                                    <div class="col-sm-8">
                                        @if ($new_clt->sex==1)
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexm" value="1" required checked> Мужской
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexw" value="0" required> Женский
                                        </label>
                                        @else
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexm" value="1" required> Мужской
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="inlineRadio_clt_sex" id="id_clt_edit_sexw" value="0" required  checked> Женский
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
                                    <label for="id_clt_edit_father" class="col-sm-3 control-label">Отец</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_father" name="v_clt_edit_father" value="{{$new_clt->father}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="id_clt_edit_mother" class="col-sm-3 control-label">Мать</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_mother" name="v_clt_edit_mother" value="{{$new_clt->mother}}">
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
                                    <label for="id_clt_edit_langs" class="col-sm-3 control-label">Языки</label>
                                    <div class="col-sm-8">
                                        <select multiple class="form-control" id="id_clt_edit_langs" name="v_clt_edit_langs[]" size=3>
                                            @foreach (array("russian" => "русский", "english" => "английский", "sakha" => "якутский") as $key => $value)
                                            @if (strpos($new_clt->language, $key)!==false)
                                            <option value='{{$key}}' selected>{{ $value }}</option>
                                            @else
                                            <option value='{{$key}}'>{{ $value }}</option>
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
                                    <label for="id_clt_edit_org" class="col-sm-3 control-label">Наименование</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_org" name="v_clt_edit_org" value="{{$new_clt->name}}">
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
                                <label for="id_clt_edit_diag" class="col-sm-3 control-label">Диагноз</label>
                                <div class="col-sm-8">
                                    <textarea rows="10" cols="50" class="form-control" name="v_clt_edit_diag" id="id_clt_edit_diag">{{$new_clt->diagnose}}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div id='id_clt_address_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Адрес</h4>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_prev" class="col-sm-3 control-label">Предыдущие адреса</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_prev" name="v_clt_edit_adr_prev ">
                                        <option value="0"></option>
                                        @for ($i = 1; $i < count($addresses); $i++)
                                        <option value="{{ $addresses[$i]->address_aoid }}">
                                            {{ $addresses[$i]->date.' ' }}
                                            @foreach (explode(",",$addresses[$i]->adr) as $address)
                                            @if ($address!='""') 
                                            {{ trim($address,'()"') }}
                                            @endif
                                            @endforeach                                    
                                            @if ($addresses[$i]->address_number)
                                            {{ ", д. ".$addresses[$i]->address_number }}
                                            @endif
                                            @if ($addresses[$i]->address_building)
                                            {{ "/".$addresses[$i]->address_building }}
                                            @endif
                                            @if ($addresses[$i]->address_apartment)
                                            {{ ", кв. ".$addresses[$i]->address_apartment }} 
                                            @endif                                            
                                        </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_ind" class="col-sm-3 control-label">Почтовый индекс</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_ind" name="v_clt_edit_adr_ind" value="{{$new_clt->address_postal}}">
                                </div>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_region" class="col-sm-3 control-label">Регион</label>
                                <div class="col-sm-6">
                                    <select class="form-control" id="id_clt_edit_adr_region" name="v_clt_edit_adr_region">
                                        <option value="0" selected>- Выберите -</option>
                                        @foreach ($regions as $region) 
                                        <option value="{{ $region->aoguid }}">{{ $region->shortname.'. '.$region->offname }}</option>
                                        @endforeach
                                    </select>  
                                </div>
                                <div class="col-sm-2">
                                        <span class="input-group-btn">
                                            <button data-toggle="tooltip" title="Очистить адресные поля" class="btn btn-default" type="button" id="id_clt_edit_adr_btnnew"><span class="glyphicon glyphicon-erase" aria-hidden="true"></span></button>
                                        </span>
                                </div>                                
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_raion" class="col-sm-3 control-label">Район</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_raion" name="v_clt_edit_adr_raion">
                                        <option value="0" selected>- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_city" class="col-sm-3 control-label">Город</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_city" name="v_clt_edit_adr_city">
                                        <option value="0">- Выберите -</option>

                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_np" class="col-sm-3 control-label">Населенный пункт</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_np" name="v_clt_edit_adr_np">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_adr_st" class="col-sm-3 control-label">Улица</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_adr_st" name="v_clt_edit_adr_st">
                                        <option value="0">- Выберите -</option>
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="id_clt_edit_adr_dom" class="col-sm-3 control-label">Дом</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_dom" name="v_clt_edit_adr_dom" value="{{ $dom }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_adr_korp" class="col-sm-3 control-label">Корпус</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_korp" name="v_clt_edit_adr_korp" value="{{ $korp }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_adr_kv" class="col-sm-3 control-label">Квартира</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="id_clt_edit_adr_kv" name="v_clt_edit_adr_kv" value="{{ $kv }}">
                                </div>
                            </div>
                        </div>
                        <div id='id_clt_contacts_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Контактные данные</h4>
                            </div>
                            <div id="id_clt_contacts_container" class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_contacts_tel" class="col-sm-3 control-label">Телефон</label>

                                <div class="ContactsBlock" style="margin-bottom: 5px">

                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="id_clt_edit_contacts_tel" name="v_clt_edit_contacts_tel">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="form-control" id="id_clt_edit_contacts_name" name="v_clt_edit_contacts_name">
                                    </div>
                                    <div class="col-sm-1">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="id_clt_edit_contacts_btnadd"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_contacts_mail" class="col-sm-3 control-label">Электронная почта</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="id_clt_edit_contacts_mail" name="v_clt_edit_contacts_mail" value="{{ $new_clt->email }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_contacts_skype" class="col-sm-3 control-label">Skype</label>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" id="id_clt_edit_contacts_skype" name="v_clt_edit_contacts_skype" value="{{ $new_clt->skype }}">
                                </div>
                            </div>
                            <div id='id_clt_user_align_section'>
                                <div class="page-header" style="margin: 30px 0 0 10px">
                                    <h4 style="margin-bottom:-3px">Привязка к учетной записи</h4>
                                </div>
                                @if ( $align_user )
                                <div class="form-group" style="margin-top:10px">
                                    <label for="id_clt_edit_user_aligned" class="col-sm-3 control-label">Привязан логин</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="id_clt_edit_user_aligned" name="v_clt_edit_user_aligned" value="{{ $align_user->email }}" readonly>
                                    </div>
                                </div>
                                @else
                                <div class="form-group" style="margin-top:10px">
                                    <label for="id_clt_edit_user_align" class="col-sm-3 control-label">Выберите логин</label>
                                    <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_user_align" name="v_clt_edit_user_align">
                                        <option value="0"></option>
                                        @foreach ($users as $usr) 
                                            <option value="{{ $usr->id }}">{{ $usr->email .' ---- '. $usr->name }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                                @endif
                                <div class="form-group" style="margin-top:10px">
                                    <div class="col-sm-10 col-sm-offset-1">
                                    <p class="bg-warning">
                                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                        Привязка к логину необходима, чтобы связать личные данные пользователя 
                                        с логином входа в портал, в целях возможности создания заявок в техподдержку 
                                        из личного кабинета (Для ролей <mark>'ученик'</mark> и <mark>'учитель'</mark>)
                                    </p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div id='id_clt_inet_view' style="display:none">
                            <div class="page-header" style="margin: 20px 0 0 10px">
                                <h4 style="margin-bottom:-3px">Интернет</h4>
                            </div>
                            <div class="form-group" style="margin-top:10px">
                                <label for="id_clt_edit_inet_prd" class="col-sm-3 control-label">Провайдер</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_inet_prd" name="v_clt_edit_inet_prd">
                                        <option value="0"></option>
                                        @foreach ($providers as $provider) 
                                        <option value="{{ $provider->id }}">{{ $provider->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <table class="table table-hover" id="id_clt_edit_inet_table" name="v_clt_edit_inet_table">
                                <thead>
                                    <tr class="active">
                                        <th>Адрес</th>
                                        <th>Маска</th>
                                        <th>Шлюз</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="id_clt_edit_inet_table_tbody">
                                    <tr class="info">
                                        <td class="table-text">
                                            <input type="text" class="form-control" id="id_clt_edit_inet_ip" name="v_clt_edit_inet_ip">
                                        </td>
                                        <td class="table-text">
                                            <input type="text" class="form-control" id="id_clt_edit_mask_ip" name="v_clt_edit_mask_ip">
                                        </td>
                                        <td class="table-text">
                                            <input type="text" class="form-control" id="id_clt_edit_gate_ip" name="v_clt_edit_gate_ip">
                                        </td>
                                        <td class="table-text">
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button" id="id_clt_edit_inet_btnadd"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
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
                                <label for="id_clt_edit_dop_active" class="col-sm-3 control-label">Статус</label>
                                <div class="col-sm-2">
                                    <div class="checkbox">
                                        <label>
                                            @if ($new_clt->active == 1)
                                                <input type="checkbox" id="id_clt_edit_dop_active" name="v_clt_edit_dop_active" checked>
                                            @else
                                                <input type="checkbox" id="id_clt_edit_dop_active" name="v_clt_edit_dop_active">
                                            @endif        
                                            Активный
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="id_clt_dop_grps_container">
                                <label for="id_clt_edit_dop_gr" class="col-sm-3 control-label">Группы</label>
                                <div class="MBlock" style="margin-bottom: 5px">
                                    <div class="col-sm-7">
                                        <select class="form-control" id="id_clt_edit_dop_gr" name="v_clt_edit_dop_gr">
                                            <option value="0"></option>
                                            @foreach ($clt_groups as $clt_group) 
                                            <option value="{{ $clt_group->id }}">{{ $clt_group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button" id="id_clt_edit_dop_grps_btnadd"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
                                        </span>                                    
                                    </div>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="id_clt_edit_dop_problem" class="col-sm-3 control-label"></label>
                                <div class="col-sm-2">
                                    <div class="checkbox">
                                        <label>
                                            @if ($new_clt->problematic == 1)
                                                <input type="checkbox" id="id_clt_edit_dop_problem" name="v_clt_edit_dop_problem" checked>
                                            @else    
                                                <input type="checkbox" id="id_clt_edit_dop_problem" name="v_clt_edit_dop_problem">
                                            @endif 
                                            Проблемный
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_clt_edit_dop_contract" class="col-sm-3 control-label">Контракт</label>
                                <div class="col-sm-8">
                                    <select class="form-control" id="id_clt_edit_dop_contract" name="v_clt_edit_dop_contract">
                                        <option value="0"></option>
                                        @foreach ($clt_contracts as $clt_contract) 
                                        <option value="{{ $clt_contract->id }}">{{ $clt_contract->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label for="id_clt_edit_dop_prim" class="col-sm-3 control-label">Примечание</label>
                                <div class="col-sm-8">
                                    <textarea rows="10" cols="50" class="form-control" id="id_clt_edit_dop_prim" name="v_clt_edit_dop_prim">{{ $new_clt->comment }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-9 col-sm-1" style="margin-right:10px">
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
                @if ($new_clt->clients_type_id!=4)
                <li role="presentation" id="id_clt_edit_nav_inet"><a href="#">Интернет</a></li>   
                @endif
                <li role="presentation" id="id_clt_edit_nav_dop"><a href="#">Дополнительно</a></li>   
            </ul>
            <ul class="nav nav-pills nav-stacked" style="margin-top:50px">
                <li role="presentation"><a href="{{ url()->previous() }}">Вернуться назад</a></li>   
            </ul>
        </div>
    </div>
</div>

@endsection

@section('footer')
<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>

<script type="text/javascript">
var FlagClearAddress; 
var nn = 0;

    function addip(ip,mask,gw,id = null) {
        var n = $("#id_clt_edit_inet_table_tbody").children('.table-tr-ips').length+1;
        //alert(n+1);
        var raw = $('<tr/>', {
            'class': 'table-tr-ips'
        }).appendTo($('#id_clt_edit_inet_table_tbody'));
        var td1 = $('<td/>', {
            'class': 'table-text'
        }).appendTo(raw);
        var input_id = $('<input/>', {
            value: id,
            name: 'v_clt_edit_id_ip'+n,
            type: 'hidden',
            'class': 'form-control'
        }).appendTo(td1);        
        var input1 = $('<input/>', {
            value: ip,
            name: 'v_clt_edit_inet_ip'+n,
            type: 'text',
            'class': 'form-control'
        }).appendTo(td1);
        var td2 = $('<td/>', {
            'class': 'table-text'
        }).appendTo(raw);
        var input2 = $('<input/>', {
            value: mask,
            name: 'v_clt_edit_mask_ip'+n,
            type: 'text',
            'class': 'form-control'
        }).appendTo(td2);
        var td3 = $('<td/>', {
            'class': 'table-text'
        }).appendTo(raw);
        var input3 = $('<input/>', {
            value: gw,
            name: 'v_clt_edit_gate_ip'+n,
            type: 'text',
            'class': 'form-control'
        }).appendTo(td3);
        var td4 = $('<td/>', {
            'class': 'table-text',
            align: 'center'
        }).appendTo(raw);

        var div_radio = $('<div/>', {
            'class': 'radio'
        }).appendTo(td4);
        var div_label = $('<label/>', {
        }).appendTo(div_radio);
        var radio_sel = $('<input/>', {
            type: 'radio',
            name: 'clt_edit_inet_table_optionsRadios',
            value: n//$('#id_clt_edit_inet_table_tbody').rows - 1
        }).appendTo(div_label);

//        btn_del.click(function () {
//            $(this).parent().parent().parent().remove();
//        });
    }

    function addcontacts() {
        //var n = $("#id_clt_contacts_container").children('.ContactsBlock').length;
        nn = nn + 1;
        //alert(n);
        var raw = $('<div/>', {
            'class': 'ContactsBlock',
            style: 'margin-top:5px'
        }).appendTo($('#id_clt_contacts_container'));
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
            value: $('#id_clt_edit_contacts_tel').val(),
            type: 'text',
            name: 'v_clt_edit_contacts_tel'+nn,
            'class': 'form-control'
        }).appendTo(div_sm4);

        var div_sm3 = $('<div/>', {
            'class': 'col-sm-3'
        }).appendTo(raw);
        var input2 = $('<input/>', {
            value: $('#id_clt_edit_contacts_name').val(),
            type: 'text',
            name: 'v_clt_edit_contacts_name'+nn,
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

    function addgroups(id=null) {
        //var n = $("#id_clt_dop_grps_container").children('.DopBlock').length;
        var raw = $('<div/>', {
            'class': 'DopBlock',
            style: 'margin-top:5px'
        }).appendTo($('#id_clt_dop_grps_container'));
//                                        var raw = $('<div/>', {
//                                                'class' : 'row',
//                                                style : 'margin:5px 1px 0 0;'
//                                        }).appendTo(div);
        var div_sm3e = $('<div/>', {
            'class': 'col-sm-3'
        }).appendTo(raw);
        var div_sm6 = $('<div/>', {
            'class': 'col-sm-6'
        }).appendTo(raw);
        var label = $('<label/>', {
            text: $('#id_clt_edit_dop_gr option:selected').text(),
            //value: '1',
            //type: 'text',
            'class': 'control-label'
        }).appendTo(div_sm6);
        var gr_id = $('<input/>', {
            value: id,
            name: 'v_clt_edit_dop_grs'+id,
            type: 'hidden',
            'class': 'form-control'
        }).appendTo(div_sm6);        
        var div_sm1 = $('<div/>', {
            'class': 'col-sm-1'
        }).appendTo(raw);
        var div_span_sm1 = $('<span/>', {
            'class': 'input-group-btn'
        }).appendTo(div_sm1);
        var btn_del = $('<button/>', {
            type: 'button',
            'class': 'btn btn-default',
            style: 'margin-left:-6px'
        }).appendTo(div_span_sm1);
        var span_img = $('<span/>', {
            'class': 'glyphicon glyphicon-minus',
            'aria-hidden': 'true'
        }).appendTo(btn_del);
        btn_del.click(function () {
            $(this).parent().parent().parent().remove();
        });
    }


$(document).ready(function () {

    $("li").click(function () {
        //$("p").removeClass("myClass noClass")
        var id = $(this).attr("id");
        if (id === 'id_clt_edit_nav_user') {
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
        if (id === 'id_clt_edit_nav_health') {
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
        if (id === 'id_clt_edit_nav_addr') {
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
        if (id === 'id_clt_edit_nav_contacts') {
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
        if (id === 'id_clt_edit_nav_inet') {
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
        if (id === 'id_clt_edit_nav_dop') {
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

    $('#id_clt_edit_adr_btnnew').click(function () {
        FlagClearAddress = 1;
        
        $('#id_clt_edit_adr_raion').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_dom').val('');
        $('#id_clt_edit_adr_korp').val('');
        $('#id_clt_edit_adr_kv').val('');
        
        $('#id_clt_edit_adr_region').val('0').change();
    });
    ////////////////////////////////////////////////////////////////////////////    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#id_clt_edit_adr_region').change(function () {
//  if ($(this).val().indexOf("Саха")!==-1){
        //alert($("#id_clt_edit_adr_region option:selected").text());
        $('#id_clt_edit_adr_raion').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        console.log('2 region_chg ' + (new Date().toISOString().slice(11, -1)));
        ajax_fill_addr_sel("#id_clt_edit_adr_raion", data, 'raion', '{{$raion_guid}}');
        //$('#id_clt_edit_adr_raion option[value="{{$raion_guid}}"]').prop('selected', true);
        //$('#id_clt_edit_adr_raion option[value="{{$raion_guid}}"]').attr('selected', 'selected');
        //$('#id_clt_edit_adr_raion').val('{{$raion_guid}}').change();
        //$('#id_clt_edit_adr_raion').change();
        //return false;
//  }
    });

    $('#id_clt_edit_adr_raion').change(function () {
        //if ($(this).val()!=="0"){
        $('#id_clt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_city').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        //if (data === null) data = '{{$raion_guid}}';
        console.log('22 raion_chg ' + (new Date().toISOString().slice(11, -1)));
        //if ? 
        ajax_fill_addr_sel("#id_clt_edit_adr_np", data, 'np', '{{$np_guid}}');
        //alert('{{$raion_guid}}');
        //$('#id_clt_edit_adr_raion').val('{{$raion_guid}}').change();
        // }
    });

    $('#id_clt_edit_adr_city').change(function () {
        //if ($(this).val()!=="0"){
        if ($('#id_clt_edit_adr_raion').val() === "0") {
            $('#id_clt_edit_adr_raion').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        }
        $('#id_clt_edit_adr_np').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        console.log('33 city_chg ' + (new Date().toISOString().slice(11, -1)));
        ajax_fill_addr_sel("#id_clt_edit_adr_np", data, 'np_city', '{{$st_guid}}');
        // }
    });

    $('#id_clt_edit_adr_np').change(function () {
        //if ($(this).val()!=="0"){
        $('#id_clt_edit_adr_st').children().remove().end().append('<option selected value="0">- Выберите -</option>');
        var data = $(this).val();
        //var parent;
//                if ($('#id_clt_edit_adr_city').val()!=="0") parent = $('#id_clt_edit_adr_city').val();
//                else if ($('#id_clt_edit_adr_raion').val()!=="0") parent = $('#id_clt_edit_adr_raion').val();
        console.log('6 np_chg ' + (new Date().toISOString().slice(11, -1)));
        ajax_fill_addr_sel("#id_clt_edit_adr_st", data, 'st', '{{$st_guid}}');
        //}
    });

    function ajax_fill_addr_sel(id_sel, pdata, padr_part, adr_id = null) {
        $.ajax({
            url: '/adresses/adr_part_list',
            type: 'POST',
            data: {'adr_part_val': pdata, 'adr_part': padr_part},
            dataType: 'json',
            success: function (result) {
                if (result.status === 1) {
                    var rows = result.adr_arr;
                    console.log('3 ajax_start ' + (new Date().toISOString().slice(11, -1)));
                    for (loop = 0; loop < rows.length; loop++) {
                        if (padr_part === 'st') {
                            if (adr_id !== null && rows[loop].aoguid === adr_id && FlagClearAddress === 0)
                                $(id_sel)
                                    .append($('<option>', {value: rows[loop].aoid, selected: true}) //value : rows[loop].rtf_aoguid
                                    .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                            else
                                $(id_sel)
                                    .append($('<option>', {value: rows[loop].aoid}) //value : rows[loop].rtf_aoguid
                                    .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        }
                        else {
                            if (adr_id !== null && rows[loop].aoguid === adr_id && FlagClearAddress === 0)
                                $(id_sel)
                                    .append($('<option>', {value: rows[loop].aoguid, selected: true}) //value : rows[loop].rtf_aoguid
                                    .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                            else
                                $(id_sel)
                                    .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                    .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        }
                    }
                    if (id_sel === '#id_clt_edit_adr_raion') {
                        rows = result.city_arr;
                        for (loop = 0; loop < rows.length; loop++) {
                            if (adr_id !== null && rows[loop].aoguid === adr_id)
                                $('#id_clt_edit_adr_city')
                                        .append($('<option>', {value: rows[loop].aoguid, selected: true}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                            else
                                $('#id_clt_edit_adr_city')
                                        .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        }

                        rows = result.punkt_arr;
                        for (loop = 0; loop < rows.length; loop++) {
                            if (adr_id !== null && rows[loop].aoguid === adr_id)
                                $('#id_clt_edit_adr_np')
                                        .append($('<option>', {value: rows[loop].aoguid, selected: true}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                            else
                                $('#id_clt_edit_adr_np')
                                        .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        }
                        console.log('4 ajax_raion ' + (new Date().toISOString().slice(11, -1)));
                    } else if (id_sel === '#id_clt_edit_adr_np') {

                        rows = result.city_arr;
                        for (loop = 0; loop < rows.length; loop++) {
                            if (adr_id !== null && rows[loop].aoguid === adr_id)
                                $('#id_clt_edit_adr_city')
                                        .append($('<option>', {value: rows[loop].aoguid, selected: true}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                            else
                                $('#id_clt_edit_adr_city')
                                        .append($('<option>', {value: rows[loop].aoguid}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        }

                        rows = result.st_arr;
                        for (loop = 0; loop < rows.length; loop++) {
                            if (adr_id !== null && rows[loop].aoid === adr_id)
                                $('#id_clt_edit_adr_st')
                                        .append($('<option>', {value: rows[loop].aoid, selected: true}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                            else
                                $('#id_clt_edit_adr_st')
                                        .append($('<option>', {value: rows[loop].aoid}) //value : rows[loop].rtf_aoguid
                                                .text(rows[loop].shortname + '. ' + rows[loop].formalname));
                        }
                    }
                    if ( FlagClearAddress === 0 ) { //$('#id_clt_edit_adr_raion option:selected').val()!=='0'
                    var r = '{{$raion_guid}}';
                    var rc = '{{$city_guid}}';
                    if (padr_part === 'raion' && adr_id !== null && r!=='') {
                        console.log('5 ajax_raion_sel ' + (new Date().toISOString().slice(11, -1)));
                        $('#id_clt_edit_adr_raion').val('{{$raion_guid}}').change();
                    }
                    else if (padr_part === 'raion' && adr_id !== null && rc!=='') {
                        console.log('5 ajax_city_sel ' + (new Date().toISOString().slice(11, -1)));
                        $('#id_clt_edit_adr_city').val('{{$city_guid}}').change();
                    }
                    var r = '{{$city_guid}}';
                    var rn = '{{$np_guid}}';
                    if (padr_part==='np' && adr_id!==null && r!=='') {
                            console.log('6 ajax_np_sity_sel '+(new Date().toISOString().slice(11, -1)));
                            $('#id_clt_edit_adr_city').val('{{$city_guid}}').change();    
                    }
                    else if (padr_part==='np' && adr_id!==null && rn!=='') {
                        console.log('6 ajax_np_sel '+(new Date().toISOString().slice(11, -1)));
                        $('#id_clt_edit_adr_np').val('{{$np_guid}}').change();    
                    }
                    var r = '{{$np_guid}}';
                    if (padr_part==='np_city' && adr_id!==null && r!=='') { 
                        console.log('7 ajax_np_sel '+(new Date().toISOString().slice(11, -1)));
                        $('#id_clt_edit_adr_np').val('{{$np_guid}}').change();    
                    }
                    var r = '{{$st_guid}}';
                    if (padr_part==='st' && adr_id!==null && r!=='') { 
                        console.log('8 ajax_st_sel '+(new Date().toISOString().slice(11, -1)));
                        $('#id_clt_edit_adr_st').val('{{$st_guid}}').change();    
                    }
                    }
                }
            },
            // Что-то пошло не так
            error: function (result) {

            }
        });
    }
    ////////////////////////////////////////////////////////////////////////////
    $('#id_clt_edit_contacts_btnadd').click(function () {
        if ($('#id_clt_edit_contacts_tel').val() !== '') {
            addcontacts();
        }
        return false;
    });
    ////////////////////////////////////////////////////////////////////////////
    $('#id_clt_edit_inet_btnadd').click(function () {
        if ($('#id_clt_edit_inet_ip').val() !== '') {
            addip($('#id_clt_edit_inet_ip').val(),$('#id_clt_edit_mask_ip').val(),$('#id_clt_edit_gate_ip').val());
        }
        return false;
    });

    ////////////////////////////////////////////////////////////////////////////
    $('#id_clt_edit_dop_grps_btnadd').click(function () {
        if ($('#id_clt_edit_dop_gr').val() !== "0") {
            var flag = 0;
            $(".DopBlock").each(function (i) {
                var ltext = $(this).find('.col-sm-6').find('label').text();
                if (ltext === $('#id_clt_edit_dop_gr option:selected').text()) {
                    //alert(ltext);
                    flag = 1;
                    return false;
                }
            });
            if (flag === 0) {
                addgroups($('#id_clt_edit_dop_gr option:selected').val());
            }
        }
        return false;
    });
    
    ////////////////////////////////////////////////////////////////////////////

    $('#id_clt_edit_contacts_mail').on('input keyup', function(e) {
        var finded = false;
        @foreach ($users as $usr) 
            id = '{{$usr->id}}';
            email = '{{$usr->email}}';
            if ( email.indexOf($('#id_clt_edit_contacts_mail').val(),0) !== -1 ) {
                $('#id_clt_edit_user_align').val('{{$usr->id}}');
                finded = true;
            }
            
        @endforeach

            if ( finded !== true ) 
                $('#id_clt_edit_user_align').val('0');
            
            if ( $('#id_clt_edit_contacts_mail').val() === '' ) $('#id_clt_edit_user_align').val('0');

        //alert($('#id_clt_edit_contacts_mail').val());
    });

//    $('#id_clt_edit_contacts_mail').onkeyup(function () {
//    });        


});

window.onload = function () {
    FlagClearAddress = 0;
    console.log('1 onload ' + (new Date().toISOString().slice(11, -1)));
    var r = '{{$new_clt->address_aoid}}';
    if (r!=='') 
        $('#id_clt_edit_adr_region').val('{{$region_guid}}').change();
    ////////////////////////////////////////////////////////////////////////////
    @if ($new_clt->numbers) 
        @foreach (explode("\n",$new_clt->numbers) as $number)
            @php    
                    $name = '';
                    $num_arr = (explode(":",$number));
                    $phone = $num_arr[0];
                    
                    if ( isset($num_arr[1]) &&  $num_arr[1]!='' )
                        { $name = trim($num_arr[1]); }
            @endphp
            $('#id_clt_edit_contacts_tel').val('{{$phone}}');
            $('#id_clt_edit_contacts_name').val('{{$name}}');
            $('#id_clt_edit_contacts_btnadd').click();
            
            $('#id_clt_edit_contacts_tel').val('');
            $('#id_clt_edit_contacts_name').val('');
        @endforeach
    @endif
    ////////////////////////////////////////////////////////////////////////////
    var r = '{{$new_clt->provider_id}}';
    if (r!=='') 
        $('#id_clt_edit_inet_prd').val('{{$new_clt->provider_id}}').change();
    ////////////////////////////////////////////////////////////////////////////
    @foreach ($new_clt->ipadddresses as $ipadddress) 
//            $('#id_clt_edit_inet_ip').val('{{long2ip($ipadddress->address)}}');
//            $('#id_clt_edit_mask_ip').val('{{long2ip($ipadddress->netmask)}}');
//            $('#id_clt_edit_gate_ip').val('{{long2ip($ipadddress->gateway)}}');
            //$('#id_clt_edit_inet_btnadd').click();
            addip('{{long2ip($ipadddress->address)}}','{{long2ip($ipadddress->netmask)}}','{{long2ip($ipadddress->gateway)}}',{{$ipadddress->id}});
            
            var r = '{{ $ipadddress->default }}';
            if (r === '1') 
                $("input[name='clt_edit_inet_table_optionsRadios'][value='{{$loop->iteration}}']").attr("checked", true);
            
            $('#id_clt_edit_inet_ip').val('');
            $('#id_clt_edit_mask_ip').val('');
            $('#id_clt_edit_gate_ip').val('');
    @endforeach    
    ////////////////////////////////////////////////////////////////////////////
    var r = '{{$new_clt->contract_id}}';
    if (r!=='') 
        $('#id_clt_edit_dop_contract').val('{{$new_clt->contract_id}}').change();
    ////////////////////////////////////////////////////////////////////////////
    @foreach ($new_clt->groups as $group)
        $('#id_clt_edit_dop_gr').val('{{$group->id}}').change();
        $('#id_clt_edit_dop_grps_btnadd').click();
        $('#id_clt_edit_dop_gr').val('0').change();
    @endforeach    
    //id_clt_edit_dop_gr
};
</script>
@endsection