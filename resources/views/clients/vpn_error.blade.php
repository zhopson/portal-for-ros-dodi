@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="page-header" style="margin-top:-18px">
                <h2>Ошибка при операции в БД VPN</h2>
            </div>            
        </div>
    </div>
    <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Предупреждение</div>

                <div class="panel-body">
                    <div class="alert alert-warning">
                        <ul>
                            Клиент id: {{ $id }}
                        </ul>
                        <ul>
                            Сообщение: {{ $msg }}
                        </ul>
                    </div>                
                </div>
                <div class="form-group" style="margin:0 60px 30px 60px">
                     Сейчас работа будет продолжена
                </div>
            </div>
    </div>
</div>
@endsection
@section('footer')

<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>

<script type="text/javascript">
    
    function RedirectDelay() {
        window.location = '/clients/edit/'+'{{$id}}';
    }    

    window.onload = function () {
        setTimeout(RedirectDelay,5000);
    };
    
</script>

@endsection