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
                                            
                                            <div class="col-xs-12">
                                                <div class="text-center">
                                                    <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="crear_nuevo_usuario();">
                                                        <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVO USUARIO
                                                    </button>
                                                       
                                                </div>
                                            </div>
                                            
                                            <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
                                                <article class="col-xs-12" style=" padding: 0px !important">
                                                        <table id="tabla_usuarios"></table>
                                                        <div id="paginador_tabla_usuarios"></div>
                                                </article>
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
        </div>       
    </div>
</section>
@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function (){
        
        $("#menu_configuracion").show();
        $("#li_config_usuarios").addClass('cr-active');;
        
        jQuery("#tabla_usuarios").jqGrid({
            url: 'usuarios/0?grid=usuarios',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DNI', 'PERSONA', 'EMAIL', 'CARGO', 'USUARIO', 'ESTADO'],
            rowNum: 50, sortname: 'id', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE USUARIOS REGISTRADOS', align: "center",
            colModel: [
                {name: 'id', index: 'id', align: 'left',width: 20, hidden: true},
                {name: 'dni', index: 'dni', align: 'center', width: 10},
                {name: 'persona', index: 'persona', align: 'left', width: 50},
                {name: 'email', index: 'email', align: 'left', width: 15},
                {name: 'cargo', index: 'cargo', align: 'left', width: 12},
                {name: 'usuario', index: 'usuario', align: 'left', width: 10},
                {name: 'estado', index: 'estado', align: 'center', width: 10},
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
            ondblClickRow: function (Id){fn_editar_usuario();}
        });
        
        jQuery("#table_modulos").jqGrid({
            url: 'modulos',
            datatype: 'json', mtype: 'GET',
            height: '320px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id', 'Descripcion'],
            rowNum: 50, sortname: 'id_mod', sortorder: 'asc', viewrecords: true, caption: 'Lista de Módulos', align: "center",
            colModel: [
                {name: 'id_mod', index: 'id_mod',align: 'center', width: 60},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 330}
                
            ],
            pager: '#pager_table_modulos',
            rowList: [50, 100],
            gridComplete: function () {
                    var idarray = jQuery('#table_modulos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_modulos').jqGrid('getDataIDs')[0];
                            $("#table_modulos").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){llamar_sub_modulo()},
            ondblClickRow: function (Id){fn_edit_mod()}
        });

        jQuery("#table_sub_modulos").jqGrid({
            url: 'sub_modulos?identifi=0&usu=0',
            datatype: 'json', mtype: 'GET',
            height: '320px', autowidth: true,
            toolbarfilter: true,
            colNames: ['id', 'Descripcion','Grabar','Editar','Eliminar'],
            rowNum: 50, sortname: 'id_mod', sortorder: 'asc', viewrecords: true, caption: 'Lista de Sub Módulos', align: "center",
            colModel: [
                {name: 'id_mod', index: 'id_mod',align: 'center', width: 50},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 300},
                {name: 'new', index: 'new', align: 'center', width: 60},
                {name: 'upd', index: 'upd', align: 'center', width: 60},
                {name: 'del', index: 'del', align: 'center', width: 60}
            ],
            pager: '#pager_table_sub_modulos',
            rowList: [50, 100],
            gridComplete: function () {
                    var idarray = jQuery('#table_sub_modulos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#table_sub_modulos').jqGrid('getDataIDs')[0];
                            $("#table_sub_modulos").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id){fn_edit_submod()}
        });
         
    });
</script>

@stop
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion/usuarios.js') }}"></script>

<div id="dialog_editar_usuario" style="display: none">
    <div class="col-xs-4">
        <div class="widget-body">
            <div  class="smart-form">
                <div class="panel-group">                
                    <div class="panel panel-success" style="padding-bottom: 20px; ">
                        <div class="panel-heading bg-color-success">.:: Datos del Usuario ::.</div>
                        <div class="panel-body">
                            <div class="col col-12" style="margin-top: 10px;">
                                <label class="label">NOMBRES Y APELLIDOS:</label>
                                <label class="input">  
                                    <div class="input-group">
                                        <input id="dlg_usuario_nombre" type="text" placeholder="Nombres y Apellidos" style="text-transform: uppercase">
                                        <span class="input-group-addon"><i class="fa fa-text-height"></i></span>
                                    </div>
                                </label>
                            </div>
                            <section>
                                <div class="col col-6">
                                    <label class="label">USUARIO:</label>
                                    <label class="input">
                                        <div class="input-group">
                                            <input id="dlg_usu_usuario" type="text" placeholder="de 3 a mas caracteres" style="text-transform: uppercase">
                                        </div>
                                    </label>
                                </div>
                                <div class="col col-6">
                                    <label class="label">DNI:</label>
                                    <label class="input">  
                                        <div class="input-group">
                                            <input id="dlg_usuario_dni" type="text" placeholder="00000000" onkeypress="return soloDNI(event);" maxlength="8" disabled="">                                
                                        </div>
                                    </label>                                
                                </div>
                            </section>
                        </div>
                    </div>
                </div>                 
            </div>
            <div class="text-right" style="padding-top: 10px;">
                <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="update_user()">
                    <span class="cr-btn-label"><i class="glyphicon glyphicon-save"></i></span> GUARDAR DATOS
                </button>
            </div>
        </div>
    </div>
    <div class="col-xs-3" style="padding: 0px; margin-top: 0px;">
        <article class="col-xs-12" style=" padding-left: 0px !important">
            <table id="table_modulos"></table>
            <div id="pager_table_modulos"></div>
        </article>
        <div class="col-xs-12" style=" margin-bottom: 10px; padding: 0px;">
            <ul class="text-center" style="margin-top: 5px !important; margin-bottom: 0px !important; padding: 0px;">                                        
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="fn_new_mod()">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-plus"></i></span> Nuevo
                    </button>
                    <button type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="fn_edit_mod()">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-edit"></i></span> Editar
                    </button>
                    <button id="btn_delmod" data-token="{{ csrf_token() }}" type="button" class="btn btn-labeled bg-color-red txt-color-white" onclick="fn_borrar_Modulo()">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-edit"></i></span> Borrar
                    </button>
                    
            </ul>
        </div>
    </div>
    <div class="col-xs-5" style="padding: 0px; margin-top: 0px; padding-left: 50px;">
        <article class="col-xs-12" style=" padding-left: 0px !important">
            <table id="table_sub_modulos"></table>
            <div id="pager_table_sub_modulos"></div>
        </article>
        <div class="col-xs-12" style=" margin-bottom: 10px; padding: 0px;">
            <ul class="text-center" style="margin-top: 5px !important; margin-bottom: 0px !important; padding: 0px;">                                        
                    <button type="button" class="btn btn-labeled bg-color-green txt-color-white" onclick="fn_new_submod()">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-plus"></i></span> Nuevo
                    </button>
                    <button type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="fn_edit_submod()">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-edit"></i></span> Editar
                    </button>
                    <button id="btn_delsubmod" data-token="{{ csrf_token() }}" type="button" class="btn btn-labeled bg-color-red txt-color-white" onclick="fn_borrar_subModulo()">
                        <span class="cr-btn-label"><i class="glyphicon glyphicon-edit"></i></span> Borrar
                    </button>
            </ul>
        </div>
    </div>
</div>

<div id="dlg_modulos" style="display: none;">
    <input type="hidden" id="hidden_id_mod" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>LLenado de Información::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon ">Nombre del Módulo (Será Visible desde el Menú) &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_des_mod" type="text"  class="form-control" style="height: 32px; " maxlength="25"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Título Módulo(Se verá cuando pase el mouse sobre la Descrip.) &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_title_mod" type="text"  class="form-control" style="height: 32px; " maxlength="50"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Id Sistema &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_idsis_mod" type="text"  class="form-control" style="height: 32px; " maxlength="50"  >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

<div id="dlg_submodulos" style="display: none;">
    <input type="hidden" id="hidden_id_submod" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>LLenado de Información::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon ">Nombre del Sub Módulo (Será Visible desde el Menú) &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_des_submod" type="text"  class="form-control" style="height: 32px; " maxlength="25"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Título Sub Módulo(Se verá cuando pase el mouse sobre la Descrip.) &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_title_submod" type="text"  class="form-control" style="height: 32px; " maxlength="50"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">id_sistena del sub modulo &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_idsis_submod" type="text"  class="form-control" style="height: 32px; " maxlength="50"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">ruta sub modulo &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_ruta_submod" type="text"  class="form-control" style="height: 32px; " maxlength="50"  >
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md">
                        <span class="input-group-addon">Orden del menú &nbsp;<i class="fa fa-list"></i></span>
                        <div class=""  >
                            <input id="dlg_orden_submod" type="text"  class="form-control" style="height: 32px; "  >
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="dlg_nuevo_usuario" style="display: none;">
    <input type="hidden" id="hidden_id_mod" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <form id="FormularioUsuario" name="FormularioUsuario" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token1" value="{{ csrf_token() }}" data-token="{{ csrf_token() }}"> 
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>LLENADO DE INFORMACION::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">DNI &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="form_dni" name="form_dni" type="text"  class="form-control text-center" style="height: 32px; " maxlength="8"  >
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
                                <option value="0">--SELECCIONE UNA PRIORIDAD--</option>
                                <option value="ADMIN">--ADMINISTRADOR--</option>
                                <option value="USUARIO">--USUARIO--</option>
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
@endsection