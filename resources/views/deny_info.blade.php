@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="page-header" style="margin-top:-18px">
                <h2>Доступ запрещен</h2>
            </div>            
        </div>
    </div>
<!--    <div class="row">
        <div class="col-md-6">
            Уровень доступа:
        </div>
    </div>-->
    <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">Информация</div>

                <div class="panel-body">
                    <div class="alert alert-warning">
                        <ul>
                            У вас отсутствуют разрешения для доступа на данный ресурс
                        </ul>
                    </div>                
                </div>
                <div class="form-group" style="margin:0 60px 30px 60px">
                    <a class="btn btn-default" href="Javascript:window.history.back()" role="button">  Назад  </a>
                </div>
            </div>
    </div>
</div>
@endsection
