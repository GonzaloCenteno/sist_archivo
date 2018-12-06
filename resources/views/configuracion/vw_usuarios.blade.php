@extends('layouts.admin')

@section('content')
<style>
    .smart-form fieldset {    
        padding: 5px 8px 0px;   
    }
    .smart-form section {
        margin-bottom: 5px;    
    }
    .smart-form .label {  
        margin-bottom: 0px;   
    }
    .smart-form .col {
        padding-right: 8px;
        padding-left: 8px;       
    }
</style>
<section id="widget-grid" class="content">    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-bottom: -12px">
            <div class="well well-sm well-light">
                <div class="row">                    
                    
                    <section class="col col-lg-12">
                        <section class="col col-lg-12">
                        <div class="col-xs-12" class="box box-primary">               
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <section style="padding-right: 0px">
                                        <div class="col-xs-12">
                                            
                                        <div class="box-header with-border">
                                            <center><h1>MANTENIMIENTO USUARIOS</h1></center>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-xs-7">
                                                <div class="input-group">
                                                    <span class="input-group-addon">NOMBRE PERSONA<i class="icon-append fa fa-male" style="margin-left: 5px;"></i></span>
                                                    <input type="text" id="vw_nombre_persona" class="form-control text-uppercase">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-success" style="background-color:#CC191C" type="button" onclick="buscar_persona();" title="BUSCAR">
                                                            <i class="glyphicon glyphicon-search"></i>&nbsp;Buscar
                                                        </button>
                                                    </span>
                                                </div>                                            
                                            </div>
                                            <div class="col-xs-5">
                                                <div class="text-right">
                                                    <button type="button" class="btn btn-labeled txt-color-white" style="background-color:#D48411" onclick="crear_nuevo_usuario();">
                                                        <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVO USUARIO
                                                    </button>
                                                    <button  type="button" class="btn btn-labeled txt-color-white" style="background-color:#CC191C" onclick="modificar_usuario();">
                                                        <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>MODIFICAR USUARIO
                                                    </button>   
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                           </div>
                        </section>
                    </section>
                </div>
            </div>

            <div class="well well-sm well-light" style="margin-top:-20px;">                
                <div class="row">
                    <div class="col-xs-12"> 
                        <div class="row">
                            <section id="content_2" class="col-lg-12">
                                <table id="tabla_usuarios"></table>
                                <div id="paginador_tabla_usuarios"></div>
                            </section>                            
                        </div>                                             
                    </div>
                </div> 
            </div>
        </div>       
    </div>
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function (){
        
        $("#menu_configuracion").show();
        $("#li_config_usuarios").addClass('cr-active');
        
        jQuery("#tabla_usuarios").jqGrid({
            url: 'usuarios/0?grid=usuarios',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DNI', 'PERSONA', 'EMAIL', 'CARGO', 'USUARIO', 'ESTADO','ROL'],
            rowNum: 50, sortname: 'id', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE USUARIOS REGISTRADOS', align: "center",
            colModel: [
                {name: 'id', index: 'id', align: 'left',width: 20, hidden: true},
                {name: 'dni', index: 'dni', align: 'center', width: 10},
                {name: 'persona', index: 'persona', align: 'left', width: 50},
                {name: 'email', index: 'email', align: 'left', width: 15},
                {name: 'cargo', index: 'cargo', align: 'left', width: 12},
                {name: 'usuario', index: 'usuario', align: 'left', width: 10},
                {name: 'estado', index: 'estado', align: 'center', width: 10},
                {name: 'id_rol', index: 'id_rol', align: 'center', width: 10, hidden:true}
            ],
            pager: '#paginador_tabla_usuarios',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_usuarios').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_usuarios').jqGrid('getDataIDs')[0];
                            $("#tabla_usuarios").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){modificar_usuario();}
        });
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_usuarios").jqGrid('setGridWidth', $("#content_2").width());
        });
        
        $("#vw_nombre_persona").keypress(function (e) {
            if (e.which == 13) {
                buscar_persona();
            }
        });
         
    });
</script>

@stop
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion/usuarios.js') }}"></script>

<div id="dlg_nuevo_usuario" style="display: none;">
    <input type="hidden" id="hidden_id_mod" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <form id="FormularioUsuario" name="FormularioUsuario" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token1" value="{{ csrf_token() }}" data-token="{{ csrf_token() }}"> 

                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">DNI &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_dni" name="form_dni" type="text"  class="form-control text-center" style="height: 32px; " maxlength="8" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">NOMBRES &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_nombres" name='form_nombres' type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">APELLIDO PATERNO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_apaterno" name="form_apaterno" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">APELLIDO MATERNO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_amaterno" name="form_amaterno" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">EMAIL &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_email" name="form_email" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">CONTRASEÑA &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_password" name="form_password" type="password"  class="form-control" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">CARGO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <select id='form_cargo' name="form_cargo" class="form-control text-uppercase text-center">
                                <option value="0">--SELECCIONE UN ROL--</option>
                                @foreach($roles as $rol)
                                <option value="{{$rol->id_rol}}">--{{$rol->descripcion}}--</option>
                                @endforeach
                            </select> 
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">USUARIO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_usuario" name="form_usuario" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">FOTO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_foto" name="form_foto" type="file"  class="form-control" style="height: 32px;" >
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div> 

<div id="dlg_editar_usuario" style="display: none;">
    <input type="hidden" id="hidden_id_mod" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <form id="FormularioUsuarioEdit" name="FormularioUsuarioEdit" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token2" value="{{ csrf_token() }}" data-token="{{ csrf_token() }}"> 
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;">
                        <header style="background: #154360 !important">
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>LLENADO DE INFORMACION::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">DNI &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_dni_edit" name="form_dni_edit" type="text"  class="form-control text-center" style="height: 32px; " maxlength="8" onkeypress="return soloNumeroTab(event);">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">NOMBRES &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_nombres_edit" name='form_nombres_edit' type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">APELLIDO PATERNO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_apaterno_edit" name="form_apaterno_edit" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">APELLIDO MATERNO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_amaterno_edit" name="form_amaterno_edit" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">EMAIL &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_email_edit" name="form_email_edit" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">CARGO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <select id='form_cargo_edit' name="form_cargo_edit" class="form-control text-uppercase text-center">
                                <option value="0">--SELECCIONE UN ROL--</option>
                                @foreach($roles as $rol)
                                <option value="{{$rol->id_rol}}">--{{$rol->descripcion}}--</option>
                                @endforeach
                            </select> 
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">USUARIO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_usuario_edit" name="form_usuario_edit" type="text"  class="form-control text-center text-uppercase" style="height: 32px; " maxlength="255"  >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">FOTO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_foto_edit" name="form_foto_edit" type="file"  class="form-control" style="height: 32px;" >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;" id="btn_resetear_clave">
                    <div class="input-group input-group-md text-center" style="width: 100%">
                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="resetear_clave();">
                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>RESETEAR CONTRASEÑA
                        </button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div> 
@endsection