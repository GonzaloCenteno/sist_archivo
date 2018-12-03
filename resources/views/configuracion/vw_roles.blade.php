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
                                            <center><h1>MANTENIMIENTO DE ROLES</h1></center>
                                        </div>
                                        
                                        <div class="row">
                                            
                                            <div class="col-xs-12">
                                                <div class="text-center">
                                                    @if( $permisos[0]->btn_new ==1 )
                                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="crear_nuevo_rol();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVO ROL
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVO ROL
                                                        </button>
                                                    @endif
                                                    @if( $permisos[0]->btn_edit ==1 )
                                                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="modificar_rol();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>MODIFICAR ROL
                                                        </button>
                                                    @else
                                                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>MODIFICAR ROL
                                                        </button>
                                                    @endif
                                                       
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
                                <table id="tabla_rol"></table>
                                <div id="paginador_tabla_rol"></div>
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
        $("#li_config_roles").addClass('cr-active');;
        
        jQuery("#tabla_rol").jqGrid({
            url: 'roles/0?grid=roles',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'CODIGO', 'DESCRIPCION'],
            rowNum: 50, sortname: 'id_rol', sortorder: 'asc', viewrecords: true, caption: 'LISTA DE ROLES', align: "center",
            colModel: [
                {name: 'id_rol', index: 'id_rol', align: 'center',width: 10},
                {name: 'codigo', index: 'codigo', align: 'left', width: 30},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 80},
            ],
            pager: '#paginador_tabla_rol',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_rol').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_rol').jqGrid('getDataIDs')[0];
                            $("#tabla_rol").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                perms = {!! json_encode($permisos[0]->btn_edit) !!};
                if(perms == 1)
                {
                    modificar_rol();
                }
                else
                {
                    sin_permiso();
                }
            }
        });
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_rol").jqGrid('setGridWidth', $("#content_2").width());
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
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 420}
                
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
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 320},
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
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion/roles.js') }}"></script>

<div id="dlg_nuevo_rol" style="display: none;">
    <input type="hidden" id="hidden_id_mod" value="0"/>
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header style="background: #154360 !important">
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>LLENADO DE INFORMACION::..</h2>
                        </header>
                    </div>
                </section>
                
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">CODIGO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_codigo" name="dlg_codigo" type="text"  class="form-control text-center text-uppercase" style="height: 32px; ">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">DESCRIPCION &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class=""  >
                            <input id="dlg_descripcion" name="dlg_descripcion" type="text"  class="form-control text-center text-uppercase" style="height: 32px; ">
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div> 

<div id="dialog_editar_rol" style="display: none">
    <div class="col-xs-3">
        <div class="widget-body">
            <div  class="smart-form">
                <div class="panel-group">                
                    <div class="panel panel-success" style="padding-bottom: 20px; ">
                        <div class="panel-heading bg-color-success">.:: Datos del Usuario ::.</div>
                        <div class="panel-body">
                            <div class="col col-12" style="margin-top: 10px;">
                                <label class="label">CODIGO:</label>
                                <label class="input">  
                                    <div class="input-group">
                                        <input id="form_codigo" type="text" placeholder="INGRESAR CODIGO..." style="text-transform: uppercase" class="text-center">
                                        <span class="input-group-addon"><i class="fa fa-text-height"></i></span>
                                    </div>
                                </label>
                            </div>
                            <div class="col col-12" style="margin-top: 10px;">
                                <label class="label">DESCRIPCION:</label>
                                <label class="input">  
                                    <div class="input-group">
                                        <input id="form_descripcion" type="text" placeholder="INGRESAR DESCRIPCION" style="text-transform: uppercase" class="text-center">
                                        <span class="input-group-addon"><i class="fa fa-text-height"></i></span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>                 
            </div>
            <div class="text-center" style="padding-top: 10px;">
                <button type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="modificar_datos()">
                    <span class="cr-btn-label"><i class="glyphicon glyphicon-edit"></i></span> MODIFICAR DATOS
                </button>
            </div>
        </div>
    </div>
    <div class="col-xs-4" style="padding: 0px; margin-top: 0px;">
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
    <div class="col-xs-5" style="padding: 0px; margin-top: 0px; padding-left: 10px;">
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
                        <header style="background: #154360 !important">
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
                        <header style="background: #154360 !important">
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
@endsection