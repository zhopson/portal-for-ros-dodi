@extends('layouts.app')
@section('head')

@endsection
@section('content')
<div class="container-fluid" style="margin:0 30px 0 30px">
@if ($errors != null)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif    
    <div class="row">
        <div class="col-md-4"><h3 style="margin-top:25px"><div class="header-text">Пользователи и группы</div></h3></div>
    </div>
    <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Пользователи</div>
                <div class="panel-body">
                    <div class="row" style="margin:10px 0 10px 0">
                        <div style="margin-top:-18px" >
                            <div class="col-md-2">
                                <a href="{{ route('admin.users.new') }}"><h6><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить</h6></a>
                            </div>
                        </div>
                    </div>    
                    <div class="table-responsive">
                    <table class="display responsive nowrap" id="id_usr_td" cellspacing="0" width="100%" style="font-size:11px">
                        <thead>
                            <tr class="active">
                                <th>ID</th>
                                <th>Имя</th>
                                <th>e-mail</th>
                                <th>Дата создания</th>
                                <th>Админ</th>
                                <th>SIP логин</th>
                            </tr>
                        </thead>                            
                    </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Группы</div>
                <div class="panel-body">
                    <div class="table-responsive">
                    <table class="table-hover table-bordered table-condensed" id="id_gr_td" cellspacing="0" width="100%">
                        <thead>
                            <tr class="success">
                                <th>ID</th>
                                <th>Название</th>
                                <th>Дата создания</th>
                                <th>Число юзеров</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table-text">
                                    <div>1</div>
                                </td>
                                <td class="table-text">
                                    <div><a href="Javascript:GetUsersbyGr(1)">Все пользователи</a></div>
                                </td>
                                <td class="table-text">
                                    <div></div>
                                </td>                                
                                <td class="table-text">
                                    <div>{{ App\User::all()->count() }}</div>
                                </td>
                            </tr>
                          @foreach ($roles as $role)
                            <tr>
                                <td class="table-text">
                                    <div>{{ $role->id }}</div>
                                </td>
                                <td class="table-text">
                                    <div><a href="Javascript:GetUsersbyGr({{ $role->id }})">{{ $role->role }}</a></div>
                                </td>
                                <td class="table-text">
                                    <div>{{ $role->created_at }}</div>
                                </td>                                
                                <td class="table-text">
                                    <div>{{ count($role->users) }}</div>
                                </td>
                            </tr> 
                          @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            
<!--            <div class="row">
                <div class="col-md-2 col-md-offset-8">
                    <button type="submit" class="btn btn-primary" id="id_note_new_btn_save">Сохранить</button>
                </div>
            </div>-->
        </div>
</div>

@endsection

@section('footer')
<script src="{{ asset('js/jquery-3.2.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript">
var gr_id;
var table;
$(document).ready(function () {

        table = $('#id_usr_td').DataTable({
            
            "columnDefs": [
                {
                    "targets": [ 0 ],
                    "visible": false,
                    "searchable": false
                }
            ],
            
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
            "ajax": "/users/json", 
            "deferRender": true            
        });
});

function GetUsersbyGr(pgr_id){
            gr_id = pgr_id;
            
            //if (pgr_id!==1) table.ajax.reload();
            table.ajax.url( '/users/json/'+gr_id ).load();
}

window.onload = function () {
   gr_id = 1;
};
</script>
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