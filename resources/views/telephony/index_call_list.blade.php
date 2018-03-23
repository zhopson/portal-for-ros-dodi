@extends('layouts.app')
@section('head')
<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"> </script>
@endsection

@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
    <div class="row">
        <h3 style="margin-top:25px"><div class="header-text">Голосовой портал</div></h3>
    </div>
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Списки обзвона</h3>
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
                <div class="col-md-10">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <ul class="nav nav-tabs">
                                <li role="presentation" class="active"><a href="#">Вкладка</a></li>
<!--                                <li role="presentation"><a href="#">Profile</a></li>
                                <li role="presentation"><a href="#">Messages</a></li>-->
                            </ul>
                            <div class="row">
                            </div>
                        </div>
                    </div>
                    <!--                    <form class="form-inline">-->
                    <!--                    </form>-->

                    <!--                    <audio id='audio_remote' autoplay='autoplay'></audio>
                                        <audio id='ringbacktone' loop src='sounds/ringbacktone.wav'></audio>-->

                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer')
        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('js/sip.js?svn=3') }}" type="text/javascript"> </script>
        
        <script type="text/javascript">

        $(document).ready(function () {
        //on page load do init
            
        });

        window.onload = function () {

        };


        </script>
        
@endsection