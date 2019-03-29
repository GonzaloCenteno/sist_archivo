<!DOCTYPE html>
<html lang="en">
    <head>        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>SIST. ARCHIVO</title>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

        <link href="{{ asset('css/smartadmin-production-plugins.min.css') }}" rel="stylesheet" type="text/css" media="screen">
        <link href="{{ asset('css/smartadmin-production.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/smartadmin-skins.min.css') }}" rel="stylesheet">
        
        <script src="{{ asset('js/libs/jquery-2.1.1.min.js') }}"></script>

        <link rel="apple-touch-icon" href="{{ asset('img/splash/sptouch-icon-iphone.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/splash/touch-icon-ipad.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('img/splash/touch-icon-iphone-retina.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/splash/touch-icon-ipad-retina.png') }}">

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">

        <link rel="apple-touch-startup-image" href="{{ asset('img/splash/ipad-landscape.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="{{ asset('img/splash/ipad-portrait.png') }}" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="{{ asset('img/splash/iphone.png') }}" media="screen and (max-device-width: 320px)">

        <link rel="icon" type="image/png" href="{{ asset('img/bus-home.png') }}" />
        <link href="{{ asset('css/estilo.css') }}" rel="stylesheet">
        <link href="{{ asset('css/jquery-confirm.css') }}" rel="stylesheet">

    </head>
    <body class="desktop-detected pace-done fixed-header fixed-navigation">
        <header id="header" style="background: #CC191C !important;">
            <div id="logo-group">                
                <span id="logo"> <img src="{{asset('img/logo-cromotex.svg')}}" alt="TRANSPORTES CROMOTEX"> </span> 

            </div>
            @if (Auth::guest())
            <div class="pull-right" style="margin-top: 8px">
                <a href="{{ route('login') }}" class="btn btn-default ">Iniciar Session</a>                
            </div>  
            @else
            <div class="pull-right">
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a style="background:#D48411; color:white;border: 1px solid white;" href="javascript:void(0);" data-action="toggleMenu" title="Colapsar Menu"><i class="fa fa-reorder"></i></a> </span>
                </div> 
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <a style="background: #A90329 !important; color:white;border: 1px solid white;" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Salir" data-action="userLogout" data-logout-msg="You can improve your security further after logging out by closing this opened browser"><i class="fa fa-sign-out"></i> CERRAR SESION</a> 
                        <form id="logout-form" action="{{ route('logout') }}" method="GET" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </span>
                </div>     
                <ul class="header-dropdown-list">
                    <li class="">
                        <a href="#" class="dropdown-toggle userdropdown pull-right" data-toggle="dropdown" style="margin-top: 8px;font-weight:bold;text-transform: uppercase"> 
                            @if (Auth::user()->foto == '-')
                                <img src="{{asset('img/avatars/male.png')}}" style="width: 28px; height: 35px;border: 1px solid #fff; outline: 1px solid #bfbfbf;">                           
                            @else
                                <img src="data:image/png;base64,{{ Auth::user()->foto }}" style="width: 28px; height: 35px;border: 1px solid #fff; outline: 1px solid #bfbfbf;">                           
                            @endif
                            <span style="color: white">BIENVENIDO, {{ Auth::user()->apaterno }} {{ Auth::user()->amaterno }}, {{ Auth::user()->nombres }} | <?php $sql = DB::table('vw_usuarios')->where('id',Auth::user()->id)->first(); echo 'CARGO: '.$sql->cargo;?> </span> <i class="fa fa-angle-down" style="color: black"></i> 
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a onclick="cambiar_foto_usuario();" class="padding-10 padding-top-0 padding-bottom-0" style="cursor: pointer;margin-bottom: 4px;"><i class="fa fa-cog"></i> Cambiar Foto</a>
                            </li>                            
<!--                            <li>
                                <a onclick="cambiar_password();" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Cambiar Password</a>
                            </li>                            -->
                        </ul>
                    </li>
                </ul>
            </div>    
            @endif
        </header>
        <!-- Dialogo de alertas -->
        <div id="alertdialog" style="display: none;" ></div>
        
        @if (!Auth::guest())
        <aside id="left-panel" style="background: #CC191C !important">        
            <div class="login-info" style="background: #CC191C;border-bottom: 3px solid #f2f2f2;">
                <span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
                    <a>
                        @if (Auth::user()->foto == '-')
                            <img src="{{asset('img/avatars/male.png')}}" style="width: 28px; height: 35px;border: 1px solid #fff; outline: 1px solid #bfbfbf;">                           
                        @else
                            <img src="data:image/png;base64,{{ Auth::user()->foto }}" style="width: 28px; height: 35px;border: 1px solid #fff; outline: 1px solid #bfbfbf;">                           
                        @endif
                        
                        <span style="color: white;">
                            Usuario:{{ Auth::user()->usuario }}
                        </span>

                    </a> 
                </span>
            </div>
            <nav >
                <ul>
                    @php $vari=0; @endphp
                    @foreach($menu as $per)
                        @if (@$vari!=$per->id_mod)
                            @if($vari>0)
                                </ul>
                               </li>
                            @endif
                            @php $vari=$per->id_mod; @endphp
                             <li class="">
                                <a href="#" title="{{$per->tit_menu}}"><i class="fa fa-lg fa-fw fa-cog fa-arrow-right"></i> <span class="menu-item-parent">{{$per->descripcion}}</span></a>
                                <ul id="{{$per->id_sis_menu}}">
                        @endif
                        <li id="{{$per->id_sistema}}">
                            <a href="{{$per->ruta_sis}}" title="{{$per->titulo}}"><i class="fa fa-caret-right"></i>{{$per->des_sub_mod}}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>
            <span class="minifyme" data-action="minifyMenu" style="background: #D48411 !important;"> <i class="fa fa-arrow-circle-left hit" style="color: #CC191C !important;"></i> </span>
        </aside>
        @endif

        <div id="main" role="main">            
            <div id="content">
                @yield('content') 
            </div>
        </div>

        <div class="page-footer text-center" style="background: #CC191C !important;">
            <div class="row">
                <div class="col-xs-12 col-sm-12">
                    <span class="txt-color-white">CROMOTEX © Arequipa - Perú &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><a href="https://www.cromotex.com.pe/" target="blank"style="color: white">www.cromotex.com.pe</a>
                </div>
            </div>            
        </div>
        <!--************************                  CAMBIAR FOTO USUARIO         *******************************-->        
        <div id="dialog_Cambiar_Foto_Usuario" style="display: none">
            <div class="widget-body">
                <div  class="smart-form">
                    <div class="panel-group">                
                        <div class="panel panel-success" style="border: 0px !important;">
                            <div class="panel-heading" style="background-color:#D48411">.:: Selecciona Tu Foto ::.</div>
                            <div class="panel-body">
                                <form id="form_cambiar_foto" name="form_cambiar_foto">
                                <div class="text-center col col-12" style="margin-top: 10px;">                            
                                    <img id="vw_usuario_cambiar_foto_img" src="{{asset('img/avatars/male.png')}}" name="vw_usuario_cambiar_foto_img" size="2048" style="width: 233px;height: 230px;border: 1px solid #fff; outline: 1px solid #bfbfbf;margin-bottom: 14px;">
                                    <label class="label">Seleccionar Foto:</label>
                                    <label class="input"> 
                                        <input type="file" id="vw_usuario_cambiar_cargar_foto" name="vw_usuario_cambiar_cargar_foto" accept="image/png, image/jpeg, image/jpg">
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>                 
                </div>        
            </div>
        </div>
        <div id="dialog_Cambiar_password" style="display: none">
            <div class="widget-body">
                <div  class="smart-form">
                    <div class="panel-group">                
                        <div class="panel panel-success" style="border: 0px !important;">
                            <div class="panel-heading" style="background-color:#D48411">.:: Cambiar Password ::.</div>
                            <div class="panel-body">
                                <section style="margin-top: 10px;">                                    
                                    <label class="label">Nuevo Contraseña:</label>
                                    <label class="input"> 
                                        <input type="password" id="vw_usuario_cam_pass_1"></label>                                    
                                </section>
                                <section>                                    
                                    <label class="label">Confirmar Contraseña:</label>
                                    <label class="input"> 
                                        <input type="password" id="vw_usuario_cam_pass_2"></label>                                   
                                </section>                               
                            </div>
                        </div>
                    </div>                 
                </div>        
            </div>
        </div>

        <script src="{{ asset('js/libs/jquery-ui-1.10.3.min.js') }}"></script>

        <script src="{{ asset('archivos_js/global_function.js') }}"></script>
        <script src="{{ asset('js/moment.js') }}"></script>
        <script src="{{ asset('js/app.config.js') }}"></script>
        <script src="{{ asset('js/app.min.js') }}"></script>
        <script src="{{ asset('js/block_ui.js') }}"></script>

        <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>

        <script src="{{ asset('js/plugin/jqgrid/jquery.jqGrid.min.js') }}"></script>
        <script src="{{ asset('js/plugin/jqgrid/grid.locale-es.js') }}"></script>

        <script src="{{ asset('js/plugin/masked-input/jquery.maskedinput.min.js') }}"></script>

        <script src="{{ asset('js/notification/SmartNotification.min.js')}}"></script>
        
        <script src="{{ asset('js/jquery-confirm.js')}}"></script>
        <script src="{{ asset('js/pdf/jspdf.debug.js') }}"></script>
        <script src="{{ asset('js/pdf/html2pdf.js') }}"></script>
        <script src="{{ asset('archivos_js/configuracion.js') }}"></script>
        
        
        <script src="{{ asset('js/plugin/ckeditor/ckeditor.js') }}"></script>

        @if (!Auth::guest()) 

        <script>
            $(document).ready(function () {                
                pageSetUp();
                $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
                    _title: function (title) {
                        if (!this.options.title) {
                            title.html("&#160;");
                        } else {
                            title.html(this.options.title);
                        }
                    }
                }));
                jconfirm.defaults = {
                    closeIcon: true,
                    type: 'green', 

                };
                $("#alertdialog").dialog({
                        autoOpen: false,modal:true,title: "<div class='widget-header'><h4>.: Mensaje del Sistema :.</h4></div>", buttons: [ { html: '<span class="btn-label"><i class="glyphicon glyphicon-check"></i></span>&nbsp; Aceptar',
                        "class": "btn btn-labeled bg-color-blue txt-color-white", click: function() { $( this ).dialog( "close" );  if(focoglobal!=""){ foco(focoglobal);} focoglobal="";} } ]
                });
                
                var n = 0;
                window.setInterval(function(){
                    n++;
                    console.log(n);
                    if (n == 100000) 
                    {
                        cerrar_sesion();
                    }
                },1000);
                
                function cerrar_sesion(){
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'inicio/create',
                        type: 'GET',
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        complete: function (data) 
                        {
                           window.location.href = "{{URL::to('/')}}"
                        }
                    });
                }
                
                document.oncontextmenu = function(){return false;}
                
            });                       
        </script>
        @endif

        @yield('page-js-script')
    </body>
</html>
