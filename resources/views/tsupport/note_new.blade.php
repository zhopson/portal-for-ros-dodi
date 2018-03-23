@extends('layouts.app')
@section('head')

@endsection
@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
    <form method="POST" action="{{ route('notes.store', ['id' => $client_id]) }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-md-4"><h3 style="margin-top:25px"><div class="header-text">Новая заметка к протоколу <mark>#{{ $ch_id }}</mark></div></h3></div>
    </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-body">
                        <table class="table table-hover">
                            <tr>
                                <td class="table-text">
                                    <div>Пользователь</div>
                                    <h4>
                                    @php
                                        $client = App\Client::find($client_id);
                                    @endphp
                                    {{ $client->surname." ".$client->name." ".$client->patronymic }}
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <td class="table-text">
                                <div class="form-group">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div>Заметка</div>
                                    </div>
                                    <div class="col-md-10"> 
                                        <textarea rows="8" cols="50" class="form-control" id="id_note_new_body" name="v_note_new_body" required ></textarea>
                                    </div>
                                </div>
                                </div>
                                </td>
                            </tr>
                            <input type="hidden" class="form-control" id="id_note_new_chain" name="v_note_new_chain" value="{{$ch_id}}">

                            
                            
                        </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-2 col-md-offset-8">
                    <button type="submit" class="btn btn-primary" id="id_note_new_btn_save">Сохранить</button>
                </div>
                <div class="col-md-2">
                    <a class="btn btn-default" href="{{ url()->previous() }}" role="button" id="id_note_new_btn_cancel">Отмена</a>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('footer')
<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<script type="text/javascript">

$(document).ready(function () {

});

window.onload = function () {
   
};
</script>
@endsection
