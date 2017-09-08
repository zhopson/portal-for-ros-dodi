@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="page-header" style="margin-top:-18px">
                <h2>Личный кабинет</h2>
            </div>            
        </div>
    </div>
<!--    <div class="row">
        <div class="col-md-6">
            Уровень доступа:
        </div>
    </div>-->
    <div class="row">
<!--        <div class="col-md-8 col-md-offset-2">-->
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Вы вошли, как <mark>{{ Auth::user()->name }}</mark>
                </div>
            </div>
<!--        </div>-->
    </div>
</div>
@endsection
