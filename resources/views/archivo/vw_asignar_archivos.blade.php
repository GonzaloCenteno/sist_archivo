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
                                                <center><h1>ARCHIVO - ASIGNAR ARCHIVOS</h1></center>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">NOMBRE PERSONA<i class="icon-append fa fa-male" style="margin-left: 5px;"></i></span>
                                                        <input type="text" id="vw_nombre_persona" class="form-control text-uppercase">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-success" type="button" onclick="buscar_persona();" title="BUSCAR">
                                                                <i class="glyphicon glyphicon-search"></i>&nbsp;Buscar
                                                            </button>
                                                        </span>
                                                    </div>                                            
                                                </div>
                                                
                                                <div class="col-xs-6">
                                                    <div class="text-right">
                                                        @if( $permisos[0]->btn_new ==1 )
                                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="crear_nueva_asignacion();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVA ASIGNACION
                                                            </button>  
                                                        @else
                                                            <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVA ASIGNACION
                                                            </button>
                                                        @endif
                                                        @if( $permisos[0]->btn_edit ==1 )
                                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="modificar_asignacion();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>MODIFICAR ASIGNACION
                                                            </button>
                                                        @else
                                                            <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>MODIFICAR ASIGNACION
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
                                                    <article class="col-xs-12" style=" padding: 0px !important">
                                                            <table id="tabla_archivo_persona"></table>
                                                            <div id="paginador_tabla_archivo_persona"></div>
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
        
        $("#menu_archivos").show();
        $("#li_config_asignar_archivos").addClass('cr-active');;
        
        jQuery("#tabla_archivo_persona").jqGrid({
            url: 'asignar_archivos/0?grid=archivo_persona',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DNI', 'PERSONA', 'CARGO', 'USUARIO', 'EMAIL'],
            rowNum: 50, sortname: 'id', sortorder: 'desc', viewrecords: true, caption: 'ASGINACION DE ARCHIVOS A USUARIOS', align: "center",
            colModel: [
                {name: 'id', index: 'id', align: 'left',width: 20, hidden: true},
                {name: 'dni', index: 'dni', align: 'center', width: 10},
                {name: 'persona', index: 'persona', align: 'left', width: 40},
                {name: 'cargo', index: 'cargo', align: 'left', width: 15},
                {name: 'usuario', index: 'usuario', align: 'left', width: 20},
                {name: 'email', index: 'email', align: 'left', width: 25}
            ],
            pager: '#paginador_tabla_archivo_persona',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_archivo_persona').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_archivo_persona').jqGrid('getDataIDs')[0];
                            $("#tabla_archivo_persona").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                perms = {!! json_encode($permisos[0]->btn_edit) !!};
                if(perms == 1)
                {
                    modificar_asignacion();
                }
                else
                {
                    sin_permiso();
                }
            }
        });
        
        $("#vw_nombre_persona").keypress(function (e) {
            if (e.which == 13) {
                buscar_persona();
            }
        });
        
        $("#dlg_nombre_persona").keydown(function (e) {
            if (e.which == 13) {
                buscar_usuario();
            }
        });
  
        jQuery("#tabla_usuario").jqGrid({
            url: 'asignar_archivos/0?grid=usuarios&nombre=0',
            datatype: 'json', mtype: 'GET',
            height: 300, width: 480,
            toolbarfilter: true,
            colNames: ['ID','DNI','PERSONA','CARGO','USUARIO'],
            rowNum: 12,sortname: 'persona', viewrecords: true, caption: 'LISTA DE USUARIOS', align: "center",
            colModel: [
                {name: 'id', index: 'id', align: 'center', hidden:true,width:20},
                {name: 'dni', index: 'dni', align: 'center', width:10}, 
                {name: 'persona', index: 'persona', align: 'left', width:30},
                {name: 'cargo', index: 'cargo', align: 'left', width:10,hidden:true},
                {name: 'usuario', index: 'usuario', align: 'left', width:10,hidden:true}
            ],
            pager: '#paginador_tabla_usuario',
            rowList: [10, 20],
            gridComplete: function () {
                var idarray = jQuery('#tabla_usuario').jqGrid('getDataIDs');
                if (idarray.length > 0) {
                var firstid = jQuery('#tabla_usuario').jqGrid('getDataIDs')[0];
                        $("#tabla_usuario").setSelection(firstid);
                    }
                jQuery('#tabla_usuario').jqGrid('bindKeys', {"onEnter": function (rowid) { fn_traer_datos(rowid);}});
            },
            onSelectRow: function (Id){},
            ondblClickRow: function (rowid){fn_traer_datos(rowid);}
        });
        
        jQuery("#tabla_asignacion_archivos").jqGrid({
            url: '',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DESCRIPCION','ARCHIVO','FECHA REGISTRO','MARCADOR'],
            rowNum: 50, sortname: 'id_archivo', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE ARCHIVOS REGISTRADOS', align: "center",
            colModel: [
                {name: 'id_archivo', index: 'id_archivo', align: 'left',width: 20, hidden: true},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 595},
                {name: 'archivo', index: 'archivo', align: 'left', width: 250},
                {name: 'fecha_registro', index: 'fecha_registro', align: 'center', width: 145},
                {name: 'check', index: 'check', align: 'center', width: 100}
            ],
            pager: '#paginador_tabla_asignacion_archivos',
            rowList: [10, 20, 30, 40, 50]
        });
        
        jQuery("#tabla_nuevas_asignaciones").jqGrid({
            url: '',
            datatype: 'json', mtype: 'GET',
            height: '300px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DESCRIPCION','ARCHIVO','FECHA REGISTRO','MARCADOR'],
            rowNum: 50, sortname: 'id_archivo', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE ARCHIVOS REGISTRADOS', align: "center",
            colModel: [
                {name: 'id_archivo', index: 'id_archivo', align: 'left',width: 20, hidden: true},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 595},
                {name: 'archivo', index: 'archivo', align: 'left', width: 250},
                {name: 'fecha_registro', index: 'fecha_registro', align: 'center', width: 145},
                {name: 'check', index: 'check', align: 'center', width: 100}
            ],
            pager: '#paginador_tabla_nuevas_asignaciones',
            rowList: [10, 20, 30, 40, 50]
        });
         
         
    });
</script>

@stop
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/archivo/asignar_archivos.js') }}"></script>

<div id="dlg_nueva_asignacion_archivo" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header style="background: #154360 !important">
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>..:: INFORMACION PERSONA ::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">NOMBRE PERSONA &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <input id="id_usuario" type="hidden" value="0">
                            <input id="dlg_nombre_persona" name="dlg_nombre_persona" type="text" class="form-control text-center text-uppercase" style="height: 32px;" placeholder="ESCRIBIR EL NOMBRE DE LA PERSONA">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">CARGO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <input id="dlg_cargo" name="dlg_cargo" type="text" class="form-control text-center text-uppercase" style="height: 32px;" placeholder="CARGO..." disabled="">
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-6" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">USUARIO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <input id="dlg_usuario" name="dlg_usuario" type="text" class="form-control text-center text-uppercase" style="height: 32px;" placeholder="USUARIO..." disabled="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header style="background: #154360 !important">
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>..:: INFORMACION DE ARCHIVOS ::..</h2>
                        </header>
                    </div>
                </section>
                
                <div class="col-xs-12" style="padding: 0px;" id="dnuevo">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">TIPO ARCHIVO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <select id="dlg_id_tipo_archivo_dnuevo" onchange="recuperar_archivos(1);" class="form-control text-center text-uppercase" style="height: 32px;">
                                <option value='0' >.:: SELECCIONE UNA OPCION ::.</option>
                                @foreach ($tipo_archivo as $tip)
                                    <option value='{{$tip->id_tipo_archivo}}' >{{$tip->descripcion}}</option>
                                @endforeach
                            </select><i></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-9" style="padding: 0px;" id="dmodificar">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">TIPO ARCHIVO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <select id="dlg_id_tipo_archivo_dmodificar" onchange="recuperar_archivos(2);" class="form-control text-center text-uppercase" style="height: 32px;">
                                <option value='0' >.:: SELECCIONE UNA OPCION ::.</option>
                                @foreach ($tipo_archivo as $tip)
                                    <option value='{{$tip->id_tipo_archivo}}' >{{$tip->descripcion}}</option>
                                @endforeach
                            </select><i></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-3" style="padding: 0px;" id="btn_nuevas_asignaciones">
                    <div class="input-group input-group-md text-center" style="width: 100%">
                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="nuevas_asignaciones();">
                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>AGREGAR NUEVOS ARCHIVOS
                        </button> 
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                
                <div class="col-xs-12" style="padding: 0px;">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
                        <table id="tabla_asignacion_archivos"></table>
                        <div id="paginador_tabla_asignacion_archivos"></div>
                    </article>
                </div>
            </div>
        </div>    
    </div>
</div> 

<div id="dlg_bus_usuario" style="display: none;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
        <table id="tabla_usuario"></table>
        <div id="paginador_tabla_usuario"></div>
    </article>
</div>

<div id="dlg_nuevas_asignaciones" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header style="background: #154360 !important">
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>..:: INFORMACION DE ARCHIVOS ::..</h2>
                        </header>
                    </div>
                </section>
                
                <div class="col-xs-12" style="padding: 0px;">
                    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="margin-top:5px; margin-bottom: 10px; padding: 0px !important">
                        <table id="tabla_nuevas_asignaciones"></table>
                        <div id="paginador_tabla_nuevas_asignaciones"></div>
                    </article>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection