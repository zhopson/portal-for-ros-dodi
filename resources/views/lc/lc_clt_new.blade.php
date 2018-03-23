@extends('layouts.app')
@section('head')

@endsection
@section('content')

<div class="container-fluid" style="margin:0 60px 0 60px">
    <div class="row">
        <h3 style="margin-top:25px">Новый клиент</h3>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="margin-bottom:55px">
                <div class="panel-body">
                    <form class="form-horizontal"  method="POST" action="{{ route('lc.personal.store') }}">
                    {{ csrf_field() }}
                    <div id='id_clt_common_section'>
                        <div class="page-header" style="margin: 0 0 0 10px">
                            <h4 style="margin-bottom:-3px">Общее</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_clt_type" class="col-sm-4 control-label">Тип пользователя</label>
                            <div class="col-sm-8">
                                <select class="form-control" id="id_clt_type" name="v_clt_type">
                                    @foreach ($clt_types as $clt_type) 
                                    <option value="{{ $clt_type->id }}">{{ $clt_type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id='id_clt_fio_section'>
                        <div class="page-header" style="margin: 20px 0 0 10px">
                            <h4 style="margin-bottom:-3px">Личные данные</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_clt_surname" class="col-sm-4 control-label">Фамилия</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="id_clt_surname" name="v_clt_surname" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_clt_name" class="col-sm-4 control-label">Имя</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="id_clt_name"  name="v_clt_name" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_clt_otch" class="col-sm-4 control-label">Отчество</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="id_clt_otch" name="v_clt_otch" required >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Пол</label>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input type="radio" id="id_clt_sexm" name="v_clt_sex" value="1" required> Мужской
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="id_clt_sexw" name="v_clt_sex" value="0" required> Женский
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id='id_clt_parents_section'>
                        <div class="page-header" style="margin: 20px 0 0 10px">
                            <h4 style="margin-bottom:-3px">Родители</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_clt_father" class="col-sm-4 control-label">Отец</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="id_clt_father" name="v_clt_father">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="id_clt_mother" class="col-sm-4 control-label">Мать</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="id_clt_mother" name="v_clt_mother">
                            </div>
                        </div>
                    </div>
                    <div id='id_clt_lang_section'>
                        <div class="page-header" style="margin: 20px 0 0 10px">
                            <h4 style="margin-bottom:-3px">Владение языками</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_clt_langs" class="col-sm-4 control-label">Языки</label>
                            <div class="col-sm-8">
                                <select multiple class="form-control" id="id_clt_langs" name="v_clt_langs[]" size=3>
                                    <option value='russian'>русский</option>
                                    <option value='english'>английский</option>
                                    <option value='sakha'>якутский</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id='id_clt_org_section' style="display:none">
                        <div class="page-header" style="margin: 20px 0 0 10px">
                            <h4 style="margin-bottom:-3px">Организация</h4>
                        </div>
                        <div class="form-group" style="margin-top:10px">
                            <label for="id_clt_org" class="col-sm-4 control-label">Наименование</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="id_clt_org" name="v_clt_org">
                            </div>
                        </div>
                    </div>
                        <div class="form-group" >
                            <div class="col-sm-offset-9 col-sm-1" style="margin-right:10px">
                                <button type="submit" class="btn btn-primary">Далее</button>
                            </div>
                            <div class="col-sm-1">
                                <a class="btn btn-default" href="{{ route('lc.personal') }}">Отмена</a>
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
$( document ).ready(function() {    
$('#id_clt_type').change(function(){
  if($(this).val()==1) {
     // alert('Ученик');
    $('#id_clt_common_section').css('display', 'inline');
    $('#id_clt_fio_section').css('display', 'inline');
    $('#id_clt_parents_section').css('display', 'inline');
    $('#id_clt_lang_section').css('display', 'inline');
    $('#id_clt_org_section').css('display', 'none');
    $("#id_clt_surname").prop("required", true);
    $("#id_clt_name").prop("required", true);
    $("#id_clt_otch").prop("required", true);
    $("#id_clt_sexm").prop("required", true);
    $("#id_clt_sexw").prop("required", true);
    $("#id_clt_org").prop("required", false);
  }
  else if($(this).val()==2) {
    // alert('Учитель')
    $('#id_clt_common_section').css('display', 'inline');
    $('#id_clt_fio_section').css('display', 'inline');
    $('#id_clt_parents_section').css('display', 'none');
    $('#id_clt_lang_section').css('display', 'inline');
    $('#id_clt_org_section').css('display', 'none');
    $("#id_clt_surname").prop("required", true);
    $("#id_clt_name").prop("required", true);
    $("#id_clt_otch").prop("required", true);
    $("#id_clt_sexm").prop("required", true);
    $("#id_clt_sexw").prop("required", true);
    $("#id_clt_org").prop("required", false);
  }
  else if($(this).val()==3) {
    //  alert('Школа')
    $('#id_clt_common_section').css('display', 'inline');
    $('#id_clt_fio_section').css('display', 'none');
    $('#id_clt_parents_section').css('display', 'none');
    $('#id_clt_lang_section').css('display', 'none');
    $('#id_clt_org_section').css('display', 'inline');
    $("#id_clt_surname").prop("required", false);
    $("#id_clt_name").prop("required", false);
    $("#id_clt_otch").prop("required", false);
    $("#id_clt_sexm").prop("required", false);
    $("#id_clt_sexw").prop("required", false);
    $("#id_clt_org").prop("required", true);
  }
  else {
    $('#id_clt_common_section').css('display', 'inline');
    $('#id_clt_fio_section').css('display', 'inline');
    $('#id_clt_parents_section').css('display', 'none');
    $('#id_clt_lang_section').css('display', 'inline');
    $('#id_clt_org_section').css('display', 'none');
    $("#id_clt_surname").prop("required", true);
    $("#id_clt_name").prop("required", true);
    $("#id_clt_otch").prop("required", true);
    $("#id_clt_sexm").prop("required", true);
    $("#id_clt_sexw").prop("required", true);
    $("#id_clt_org").prop("required", false);
      
  }
});
});
window.onload = function () {
    $('#id_clt_type').val('4').change();
    //$('#id_clt_type').prop("readonly", true);
}
</script>
@endsection
