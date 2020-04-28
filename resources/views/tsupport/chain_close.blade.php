@extends('layouts.app')
@section('head')

@endsection
@section('content')

<div class="container-fluid" style="margin:0 60px 0 60px">
<form class="form-horizontal" name="frm_ch_close" method="POST" action="{{ route('chains.closing', ['id' => $id]) }}">
{{ csrf_field() }}

    <div class="row">
        <h3 style="margin-top:25px"><div class="header-text">Закрытие протокола #{{$id}}</div></h3>
    </div>

@if ($errors != null)

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
                                <label for="id_ch_close_msg" class="col-sm-3 control-label">Сообщение</label>
                                <div class="col-sm-8">
                                    <textarea rows="10" cols="50" class="form-control" name="v_ch_close_msg" id="id_ch_close_msg"></textarea>
                                </div>
                            </div>
                </div>
            </div>
        </div>
        <div class="col-md-1"></div>                    
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked" id="id_ch_close_nav_buttons">
                <li role="presentation" id="id_ch_close_nav_save"><a href="#" onclick="document.frm_ch_close.submit();">Сохранить</a></li>
                <li role="presentation" id="id_ch_close_nav_cancel"><a href="{{ url()->previous() }}">Отмена</a></li>   
            </ul>
        </div>
        
    </div>
@endif
</form>                    
                
</div>
@endsection

@section('footer')
<!--<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
<script type="text/javascript">

function HideMsg() {
  $(".alert").css('display', 'none');  
}

window.onload = function () {

@if ($errors != null)
    setTimeout(HideMsg,5000);
@endif    
        
};

</script>
@endsection
