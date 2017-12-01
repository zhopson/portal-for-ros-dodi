@extends('layouts.app')

@section('content')
<div class="container-fluid" style="margin:0 30px 20px 30px">
<form class="form-horizontal" method="POST" action="{{ route('admin.users.store') }}">
{{ csrf_field() }}

    <div class="row">
        <div class="col-md-4"><h3 style="margin-top:-10px">Новый пользователь</h3></div>
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
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

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
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

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
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Подтверждение пароля</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"  name="admin" id="admin">
                                        Администратор
                                    </label>
                                </div>                                
                            </div>
                        </div>
                    
                        <div class="form-group{{ $errors->has('sip_name') ? ' has-error' : '' }}">
                            <label for="sip_name" class="col-md-4 control-label">Имя SIP</label>

                            <div class="col-md-6">
                                <input id="sip_name" type="text" class="form-control" name="sip_name" value="{{ old('sip_name') }}">

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
                                <input id="sip_password" type="password" class="form-control" name="sip_password">

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
                            <option value="{{ $role->id }}">{{ $role->role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-1 col-md-offset-7">
            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    Добавить
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
