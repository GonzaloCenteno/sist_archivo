<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>S.A.</title>

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">
</head>
<body class="hold-transition login-page" onload="dontBack();">
    <div id="app" class="login-box" style="width: 500px; height: 550px;">
        <div class="login-logo">
            <b>SISTEMA</b> ARCHIVO
        </div>
        <div class="login-box-body">
        @yield('content')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/libs/jquery-2.1.1.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        function dontBack(){
        window.location.hash="";
        window.location.hash="Again-No-back-button" //chrome
        window.onhashchange=function(){window.location.hash="";}
      };
    </script>
</body>
</html>
