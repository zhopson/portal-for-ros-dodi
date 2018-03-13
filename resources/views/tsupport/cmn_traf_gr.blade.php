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
                            <a class="btn btn btn-info" href="javascript:window.location.reload(true)" role="button">Обновить</a>
                        </div>
                        <div class="pull-left" id="container_period">
                            <h4>Период</h4>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadiosPeriod" id="id_optionsRadio_hour" value="3600">
                                    1 час
                                </label>
                            </div>                  
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadiosPeriod" id="id_optionsRadio_6hour" value="21600" checked>
                                    6 часов
                                </label>
                            </div>                  
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadiosPeriod" id="id_optionsRadio_12hour" value="43200">
                                    12 часов
                                </label>
                            </div>                  
                            <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadiosPeriod" id="id_optionsRadio_24hour" value="86400">
                                    24 часа
                                </label>
                            </div>                  
                        </div>
                        <div id="container_img">
                            <img src="{{ asset('images/zabbix/zabbix_graph_1388_21600.png').'?ver='.date("YmdHis") }}"  style="max-width: 100%" class="img-responsive center-block" alt="Responsive image">
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

$('input[type=radio][name=optionsRadiosPeriod]').on('change', function() {
     switch($(this).val()) {
         case '3600':
             //alert("1 hour");
             $("img").attr('src', "{{ asset('images/zabbix/zabbix_graph_1388_3600.png') }}");
             break;
         case '21600':
             $("img").attr('src', "{{ asset('images/zabbix/zabbix_graph_1388_21600.png') }}");
             //alert("6 hours");
             break;
         case '43200':
             $("img").attr('src', "{{ asset('images/zabbix/zabbix_graph_1388_43200.png') }}");
             //alert("12 hours");
             break;
         case '86400':
             $("img").attr('src', "{{ asset('images/zabbix/zabbix_graph_1388_86400.png') }}");
             //alert("24 hours");
             break;
     }
});

});
window.onload = function () {
            
};

</script>
@endsection