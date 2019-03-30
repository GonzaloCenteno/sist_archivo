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
                                                <center><h1 style="color:#CC191C"><b>REGISTRO - SUBIR ARCHIVO</b></h1></center>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-7">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">DESCRIPCION<i class="icon-append fa fa-male" style="margin-left: 5px;"></i></span>
                                                        <input type="text" id="vw_descripcion" class="form-control text-uppercase input-lg">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-success btn-lg" style="background-color:#D48411" type="button" onclick="buscar_descripcion();" title="BUSCAR">
                                                                <i class="glyphicon glyphicon-search"></i>&nbsp;Buscar
                                                            </button>
                                                        </span>
                                                    </div>                                            
                                                </div>
                                                
                                                <div class="col-xs-5">
                                                    <div class="text-right">
                                                        @if( $permisos[0]->btn_new ==1 )
                                                            <button type="button" class="btn btn-labeled btn-lg txt-color-white" style="background-color:#D48411" onclick="crear_nuevo_archivo();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>SUBIR ARCHIVOS
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-labeled btn-lg txt-color-white" style="background-color:#D48411" onclick="sin_permiso();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>SUBIR ARCHIVOS
                                                            </button>
                                                        @endif
                                                        @if( $permisos[0]->btn_del ==1 )
                                                            <button type="button" class="btn btn-labeled btn-lg txt-color-white" style="background-color:#D48411" onclick="eliminar_archivo();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>ELIMINAR ARCHIVO
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-labeled btn-lg txt-color-white" style="background-color:#D48411" onclick="sin_permiso();">
                                                                <span class="btn-label"><i class="glyphicon glyphicon-trash"></i></span>ELIMINAR ARCHIVO
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
                                <table id="tabla_archivos"></table>
                                <div id="paginador_tabla_archivos"></div>
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
    
    Dropzone.autoDiscover = false;
    var myDropzone;
    
    $(document).ready(function (){
        
        $("#menu_archivos").show();
        $("#li_config_archivos").addClass('cr-active');
        
        myDropzone = new Dropzone("#FormularioArchivo", {
            url: "archivos?tipo=1",                        
            autoProcessQueue: false,
            paramName: "file",
            maxFiles: 50,
            maxFileSize: 1000000,
            uploadMultiple: true,
            addRemoveLinks: true,
            parallelUploads: 50,
            init: function () {
                this.on("complete", function (file) {
                    if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) 
                    {
                        MensajeDialogLoadAjaxFinish('dlg_nuevo_archivo');
                        MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE CREADO CORRECTAMENTE...",4000);
                        $("#dlg_nuevo_archivo").dialog("close");
                        fn_actualizar_grilla('tabla_archivos');
                    }
                });
            },
            sending: function(file, xhr, formData){
                MensajeDialogLoadAjax('dlg_nuevo_archivo', '... .:: Guardando ::. ...');
                formData.append('id_tipo_archivo', $("#id_tipo_archivo").val());
            },
            success: function (file, response) {
                if(response == 1)
                { 
                    myDropzone.removeAllFiles();
                }
                else
                {
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_archivo');
                    mostraralertas('* Contactese con el Administrador...');    
                }
            },
            error: function(file, response)
            {
               return console.log(response);
            }
        });
        
        jQuery("#tabla_archivos").jqGrid({
            url: 'archivos/0?grid=archivos',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DESCRIPCION ARCHIVO', 'TIPO ARCHIVO', 'FECHA REGISTRO', 'DESCARGAR ARCHIVO', 'VER ARCHIVO'],
            rowNum: 50, sortname: 'id_archivo', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE ARCHIVOS REGISTRADOS', align: "center",
            colModel: [
                {name: 'id_archivo', index: 'id_archivo', align: 'left',width: 20, hidden: true},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 45},
                {name: 'tipo_archivo', index: 'tipo_archivo', align: 'left', width: 25},
                {name: 'fecha_registro', index: 'fecha_registro', align: 'center', width: 25},
                {name: 'nuevo', index: 'nuevo', align: 'center', width: 30},
                {name: 'ver', index: 'ver', align: 'center', width: 30}
            ],
            pager: '#paginador_tabla_archivos',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_archivos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_archivos').jqGrid('getDataIDs')[0];
                            $("#tabla_archivos").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                perms = {!! json_encode($permisos[0]->btn_del) !!};
                if(perms == 1)
                {
                    eliminar_archivo();
                }
                else
                {
                    sin_permiso();
                }
            }
        });
        
        $("#vw_descripcion").keypress(function (e) {
            if (e.which == 13) {
                buscar_descripcion();
            }
        });
        
        $("#descripcion").keypress(function(e) {
            if (e.which == 13) {
                return false;
            }
        });
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_archivos").jqGrid('setGridWidth', $("#content_2").width());
        });
         
    });
    
    function guardar_archivo()
    {
        if ($('#id_tipo_archivo').val() == '0') {
            mostraralertasconfoco('* DEBE SELECCIONAR UNA OPCION...', '#id_tipo_archivo');
            return false;
        }
        myDropzone.processQueue();
    }
</script>

@stop
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/archivo/archivo.js') }}"></script>

<div id="dlg_nuevo_archivo" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <form id="FormularioArchivo" name="FormularioArchivo" method="post" enctype="multipart/form-data" class="dropzone">
                <input type="hidden" name="_token" id="_token1" value="{{ csrf_token() }}" data-token="{{ csrf_token() }}"> 
                </form>
                
                <div class="col-xs-12" style="padding-top: 20px;"></div>
                
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">TIPO ARCHIVO &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <select id="id_tipo_archivo" name="id_tipo_archivo" class="form-control text-center text-uppercase" style="height: 32px;">
                                <option value='0' >.:: SELECCIONE UNA OPCION ::.</option>
                                @foreach ($tipo_archivo as $tip)
                                    <option value='{{$tip->id_tipo_archivo}}' >{{$tip->descripcion}}</option>
                                @endforeach
                            </select><i></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 

@endsection