@extends('layouts.app')
@section('head')
<script src="{{ asset('SIPml-api.js?svn=252') }}" type="text/javascript"> </script>
@endsection

@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
    <div class="row">
        <h3 style="margin-top:-10px">Голосовой портал</h3>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Позвонить</h3>
            </div>            
            <div class="panel-body">
                <div class="col-md-2">
                    <ul class="nav nav-pills nav-stacked">
                        <li role="presentation"><a href="{{ url('/telephony') }}">Позвонить</a></li>                        
                        <li role="presentation"><a href="{{ url('/telephony/stat') }}">Статистика и Отчеты</a></li>
<!--                        <li role="presentation"><a href="{{-- url('/telephony/rep') --}}">Отчеты</a></li>-->
                        <li role="presentation"><a href="{{ url('/telephony/call_list') }}">Обзвон</a></li>                        
                    </ul>
                </div>
                <div class="col-md-2 col-md-offset-1">
                    <!--                    <form class="form-inline">-->
                    <div class="form-group">
                        <label for="id_tel_phone">Номер телефона</label>
                        <input type="text" class="form-control" id="id_tel_phone" placeholder="81112223333">
                    </div>
                    <button id="id_tel_call" class="btn btn-default">Позвонить</button>
                    <button id="id_tel_hangup" class="btn btn-default">Положить</button>
                    <div class="form-group"></div>
                    <div class="form-group has-warning">
                        <label class="control-label" for="inputWarning1">Статус SIP</label>
                        <input type="text" class="form-control" id="id_call_anytel_sip_log">
                    </div>                

                    <!--                    </form>-->

<!--                    <audio id='audio_remote' autoplay='autoplay'></audio>
                    <audio id='ringbacktone' loop src='sounds/ringbacktone.wav'></audio>-->

                </div>
                <div class="pull-right">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
        <script src="{{ asset('js/sip.js?svn=3') }}" type="text/javascript"> </script>
        
        <script type="text/javascript">
        $(document).ready(function () {
        //on page load do init
            $('#id_tel_call').click(function () {
                //alert('call');
                makeCall($('#id_tel_phone').val());
            });
            $('#id_tel_hangup').click(function () {
                sipHangUp();
            });
        });

        window.onload = function () {
            //
            //
            SetVar1('{{ base64_encode ( Auth::user()->sip_number ) }}');
            SetVar2('{{ base64_encode ( Auth::user()->sip_secret ) }}');
            
        //init sip stack
            SIPml.init(readyCallback, errorCallback);

        //start stip stack
            sipStack.start();

        //do login
            login();

        };


        </script>
        
@endsection