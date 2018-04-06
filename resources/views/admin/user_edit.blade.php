@extends('layouts.app')

@section('content')

@php
    $clt = ''; $clt_name = '';
    if ( $user->client_id ) {
        $clt = App\Client::find($user->client_id);
        if ($clt) $clt_name = $clt->surname.' '.$clt->name.' '.$clt->patronymic;
        $clt_name = trim($clt_name);
    }
@endphp

<div class="container-fluid" style="margin:0 30px 0 30px">
<form class="form-horizontal" method="POST" action="{{ route('admin.users.update', ['id' => $user->id]) }}">
{{ csrf_field() }}

    <div class="row">
        <div class="col-md-4"><h3 style="margin-top:25px"><div class="header-text">Изменить данные пользователя</div></h3></div>
    </div> 
@if (count($errors) > 0)
  <div class="alert alert-danger">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif 
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Учетные данные</div>

                <div class="panel-body">

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Имя пользователя</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Адрес E-Mail</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $user->email }}" required readonly="true">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Пароль</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" value="{{ $user->password }}" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                        <input type="hidden" class="form-control" id="password_old" name="password_old" value="{{ $user->password }}">                    

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Подтверждение пароля</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" value="{{ $user->password }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        @if ($user->is_admin === true)
                                            <input type="checkbox"  name="admin" id="admin" checked>
                                        @else
                                            <input type="checkbox"  name="admin" id="admin">
                                        @endif
                                        Администратор
                                    </label>
                                </div>                                
                            </div>
                        </div>
                    
                        <div class="form-group{{ $errors->has('sip_name') ? ' has-error' : '' }}">
                            <label for="sip_name" class="col-md-4 control-label">Имя SIP</label>

                            <div class="col-md-6">
                                <input id="sip_name" type="text" class="form-control" name="sip_name" value="{{ $user->sip_number }}">

                                @if ($errors->has('sip_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sip_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('sip_password') ? ' has-error' : '' }}">
                            <label for="sip_password" class="col-md-4 control-label">Пароль SIP</label>

                            <div class="col-md-6">
                                <input id="sip_password" type="password" class="form-control" name="sip_password" value="{{ $user->sip_secret }}">

                                @if ($errors->has('sip_password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('sip_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Группы</div>
                <div class="panel-body">

                    <select size="11" multiple class="form-control" name="v_usr_roles[]"  required>
                        @foreach ($roles as $role)
                            @if ($user->roles->find($role->id))
                                <option value="{{ $role->id }}" selected>{{ $role->role }}</option>
                            @else
                                <option value="{{ $role->id }}">{{ $role->role }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">Персональные данные</div>
                <div class="panel-body">
                    @if ( $user->client_id )
                        <div class="row">
                            <div class="pull-left"><h3 style="margin:20px 0 20px 30px"> {{ $clt_name }} </h3></div>
                            <div class="pull-right"><a style="margin:20px 20px 60px 30px"  href="{{ route('clients.view', ['id' => $user->client_id]) }}" role="button" class="btn btn-info"><span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>  Смотреть информацию</a></div>
                        </div>
                    @else
                    <div class="form-group" style="margin-top:10px">
                        <div class="row">
                            <div class="pull-left"><h3 style="margin:20px 0 20px 30px">Персональные данные отсутствуют</h3></div>
                            <div class="pull-right"><a style="margin:20px 20px 60px 30px"  href="{{  route('clients.new',['id' => $user->id]) }}" role="button" class="btn btn-info"><span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span>  Добавить информацию</a></div>
                        </div>
                        <div class="col-sm-10 col-sm-offset-1">
                            <p class="bg-warning">
                                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
                                Также персональные данные пользователя можно добавить в Разделе 
                                <mark><Техподдержка></mark> => <mark><Пользователи></mark>
                            </p>
                        </div>
                        
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
    </div>
    <div class="row">
        <div class="col-md-1 col-md-offset-7">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Сохранить
                </button>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.users') }}" role="button">Отмена</a>
            </div>
        </div>
    </div>
</form>
</div>
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

@if (count($errors) > 0)
    setTimeout(HideMsg,5000);
@endif    
        
};

</script>
@endsection
