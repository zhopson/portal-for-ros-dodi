@extends('layouts.app')

@section('head')

@endsection

@section('content')

@if (session('status'))
  <div class="alert alert-success">
        {{ session('status') }}
  </div>
@endif

<div class="container-fluid" style="margin:0 30px 45px 30px">
    <div class="row">
        <h3 style="margin-top:-10px">Трафик общий (с Zabbix) </h3>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12"  style="margin: 5px 5px 0 5px">
                        <div class="pull-right" id="container_button">
                            <a class="btn btn btn-info" href="javascript:window.location.reload()" role="button">Обновить</a>
                        </div>
                        <div id="container_img">
                            <img src="{{ asset('images/zabbix/zabbix_graph_1388.png') }}"  style="max-width: 100%" class="img-responsive center-block" alt="Responsive image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footer')
<!--<script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
        <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>

<script type="text/javascript">

$(document).ready(function () {

});
window.onload = function () {
            
};

</script>
@endsection