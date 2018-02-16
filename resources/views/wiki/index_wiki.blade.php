@extends('layouts.app')
@section('head')
<script src="{{ asset('SIPml-api.js?svn=251') }}" type="text/javascript"> </script>
@endsection

@section('content')

<div class="panel panel-default" style="margin: -15px 5px 25px 5px">
    <div class="panel-heading">
        <h3 class="panel-title">Раздел документации </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
<!--                <div style="margin-top:-18px" >-->
                    
                    

                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="{{ url('/documents') }}">WiKi</a>
                            </div>

                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                
                                <ul class="nav navbar-nav">
                                    <li class="active"><a href="{{ route('documents.new') }}"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить документ <span class="sr-only">(current)</span></a></li>
<!--                                    <li><a href="#">Link</a></li>-->
                                </ul>
                                
                                <form class="navbar-form navbar-left">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Поиск по ключевым словам, разделенных пробелами"  style="width: 500px">
                                    </div>
<!--                                    <button type="submit" class="btn btn-default">Submit</button>-->
                                </form>
                                <ul class="nav navbar-nav">
<!--                                    <li><a href="#">Link</a></li>-->
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Поиск <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="#">Поиск в заголовке</a></li>
                                            <li><a href="#">Поиск в описании</a></li>
                                            <li role="separator" class="divider"></li>
                                            <li><a href="#">Поиск в заголовке и описании</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                    </nav>                    

<!--                    <div class="col-md-2">
                        <a href="{{ route('documents.new') }}"><h6><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить</h6></a>
                    </div>-->
                </div>
<!--            </div>-->
            <div class="row">
<!--                <table class="table table-hover table-bordered">-->
                <div class="table-responsive">
                <table class="display" id="id_documents_td"  cellspacing="0" width="100%">
                    <thead>
                        <tr class="active">
                            <th>Номер</th>
                            <th>Категория</th>
                            <th style="width: 480px">Заголовок</th>
                            <th>Дата изменения</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="active">
                            <th>Номер</th>
                            <th>Категория</th>
                            <th style="width: 480px">Заголовок</th>
                            <th>Дата изменения</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
                </div>
            </div>        
        </div>
    </div>
</div>
@endsection

@section('footer')
<!--        <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>-->
        <script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<!--<script src="{{ asset('js/bootstrap.min.js') }}"></script>-->
<!--        <script src="{{ asset('js/modal.js') }}"></script>-->
        <script type="text/javascript">

        window.onload = function () {
            
        };

    $(document).ready(function () {
        
//    $.ajaxSetup({
//        headers: {
//            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//        }
//    });    
    
        $('#id_documents_td').DataTable({
            "language": {
                //"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json",
                "processing": "Подождите...",
                "search": "Поиск:",
                "lengthMenu": "Показать _MENU_ записей",
                "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
                "infoEmpty": "Записи с 0 до 0 из 0 записей",
                "infoFiltered": "(отфильтровано из _MAX_ записей)",
                "infoPostFix": "",
                "loadingRecords": "Загрузка записей...",
                "zeroRecords": "Записи отсутствуют.",
                "emptyTable": "В таблице отсутствуют данные",
                "paginate": {
                    "first": "Первая",
                    "previous": "Предыдущая",
                    "next": "Следующая",
                    "last": "Последняя"
                },
                "aria": {
                    "sortAscending": ": активировать для сортировки столбца по возрастанию",
                    "sortDescending": ": активировать для сортировки столбца по убыванию"
                }                
            },            
            "pageLength": 25,
            "ajax": "/documents/json", 
            "deferRender": true            
        });

    });



        </script>
        
@endsection            