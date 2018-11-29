@extends('layouts.app')

@section('content')
<h3 class="login-box-msg">INGRESAR CREDENCIALES PARA INICIAR SESION</h3>

<form method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('usuario') ? ' has-error' : '' }} has-feedback">
        <input type="text" name="usuario" class="form-control input-lg text-center" placeholder="INGRESAR NOMBRE DE USUARIO" autofocus="true">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>

        @if ($errors->has('usuario'))
            <span class="help-block">
                <strong>{{ $errors->first('usuario') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
        <input type="password" name="password" class="form-control input-lg text-center" placeholder="INGRESAR CONTRASEÑA">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif
    </div>

    <div class="row">
        <div class="col-xs-12">
            <button type="submit" class="btn btn-primary btn-block btn-flat btn-lg">INGRESAR</button>
        </div>
    </div>

</form>
@endsection
