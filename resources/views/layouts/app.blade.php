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
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/smartadmin-production-plugins.min.css') }}" rel="stylesheet" type="text/css" media="screen">
    <link href="{{ asset('css/smartadmin-production.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/smartadmin-skins.min.css') }}" rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('img/favicon/favi.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('img/favicon/favi.ico') }}" type="image/x-icon">

    <link rel="shortcut icon" href="img/favicon/favi.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favi.ico" type="image/x-icon">

    <!--<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">-->

    <link rel="apple-touch-icon" href="{{ asset('img/splash/sptouch-icon-iphone.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/splash/touch-icon-ipad.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/splash/touch-icon-iphone-retina.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/splash/touch-icon-ipad-retina.png') }}">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <link rel="apple-touch-startup-image" href="{{ asset('img/splash/ipad-landscape.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="{{ asset('img/splash/ipad-portrait.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="{{ asset('img/splash/iphone.png') }}" media="screen and (max-device-width: 320px)">

    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.2.0/jquery-confirm.min.css">-->

    <link href="{{ asset('css/estilo.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-confirm.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('img/bus-home.png') }}" />
    
</head>
<body class="hold-transition login-page" onload="dontBack();" style="padding-top: 170px;">
    <div class="panel panel-default col-md-6 col-lg-6 col-md-offset-3" >
        
        <div class="panel-body">
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
