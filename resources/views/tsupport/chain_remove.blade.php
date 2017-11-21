@extends('layouts.app')
@section('head')

@endsection
@section('content')

<div class="container-fluid" style="margin:0 60px 0 60px">
<form class="form-horizontal" name="frm_ch_remove" method="POST" action="{{ route('chains.removing', ['id' => $id]) }}">
{{ csrf_field() }}

    <div class="row">
        <h3 style="margin-top:-10px">Удаление протокола #{{$id}}</h3>
    </div>

@if (count($errors) > 0)

<div class="row">
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
    <ul class="nav nav-pills nav-stacked" id="id_ch_close_nav_buttons">
        <li role="presentation"><a href="{{ url()->previous() }}">Вернуться</a></li>       
    </ul>
</div>

@else

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default" style="margin-bottom:55px">
                <div class="panel-body">
                    <div class="form-group" style="margin-top:10px">
                        <div class="col-sm-offset-1 col-sm-10">
                        <div class="alert alert-danger" role="alert">
                                Вы действительно хотите удалить протокол #{{$id}} ?
                        </div>
                        </div>    
                    </div>
                    <div class="form-group" style="margin-top:10px">
                        <label for="id_ch_remove_clt" class="col-sm-3 control-label">Пользователь</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="id_ch_remove_clt" name="v_ch_remove_clt" value="{{$fio}}"  disabled>
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:10px">
                        <label for="id_ch_remove_msg" class="col-sm-3 control-label">Причина</label>
                        <div class="col-sm-8">
                            <textarea rows="10" cols="50" class="form-control" name="v_ch_remove_msg" id="id_ch_remove_msg" required></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>                    
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" id="id_ch_remove_nav_buttons">
                <input type="submit" id="id_ch_remove_submit" style="display:none">
                <li role="presentation" id="id_ch_remove_nav_save"><a href="#" onclick="$('#id_ch_remove_submit').click()">Удалить</a></li>
<!--                <li role="presentation" id="id_ch_remove_nav_save"><a href="#" onclick="document.frm_ch_remove.submit();">Удалить</a></li>-->
                <li role="presentation" id="id_ch_remove_nav_cancel"><a href="{{ url()->previous() }}">Отмена</a></li>   
            </ul>
        </div>
        
    </div>
@endif
</form>                    
                
</div>
@endsection

@section('footer')
<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
@endsection
