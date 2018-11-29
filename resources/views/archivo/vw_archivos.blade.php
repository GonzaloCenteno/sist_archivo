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
                                                <center><h1>REGISTRO - ARCHIVO</h1></center>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-9">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">DESCRIPCION<i class="icon-append fa fa-male" style="margin-left: 5px;"></i></span>
                                                        <input type="text" id="vw_descripcion" class="form-control text-uppercase">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-success" type="button" onclick="buscar_descripcion();" title="BUSCAR">
                                                                <i class="glyphicon glyphicon-search"></i>&nbsp;Buscar
                                                            </button>
                                                        </span>
                                                    </div>                                            
                                                </div>
                                                <div class="col-xs-3">
                                                    <div class="text-right">
                                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="crear_nuevo_archivo();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>SUBIR ARCHIVOS
                                                        </button>  
                                                    </div>
                                                </div>
                                                <div class="col-xs-12" style="padding: 0px; margin-top: 10px">
                                                    <article class="col-xs-12" style=" padding: 0px !important">
                                                            <table id="tabla_archivos"></table>
                                                            <div id="paginador_tabla_archivos"></div>
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
        $("#li_config_archivos").addClass('cr-active');;
        
        jQuery("#tabla_archivos").jqGrid({
            url: 'archivos/0?grid=archivos',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DESCRIPCION', 'FECHA REGISTRO', 'DESCARGAR ARCHIVO', 'VER ARCHIVO'],
            rowNum: 50, sortname: 'id_archivo', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE ARCHIVOS REGISTRADOS', align: "center",
            colModel: [
                {name: 'id_archivo', index: 'id_archivo', align: 'left',width: 20, hidden: true},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 50},
                {name: 'fecha_registro', index: 'fecha_registro', align: 'center', width: 15},
                {name: 'nuevo', index: 'nuevo', align: 'center', width: 20},
                {name: 'ver', index: 'ver', align: 'center', width: 20}
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
            ondblClickRow: function (Id){}
        });
        
        $("#vw_descripcion").keypress(function (e) {
            if (e.which == 13) {
                buscar_descripcion();
            }
        });
         
    });
</script>

@stop
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/archivo/archivo.js') }}"></script>

<div id="dlg_nuevo_archivo" style="display: none;">
    <div class='cr_content col-xs-12 ' style="margin-bottom: 10px;">
        <div class="col-xs-12 cr-body" >
            <div class="col-xs-12 col-md-12 col-lg-12" style="padding: 0px; margin-top: 0px;">
                <form id="FormularioArchivo" name="FormularioArchivo" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" id="_token1" value="{{ csrf_token() }}" data-token="{{ csrf_token() }}"> 
                <section>
                    <div class="jarviswidget jarviswidget-color-green" style="margin-bottom: 15px;"  >
                        <header>
                                <span class="widget-icon"> <i class="fa fa-info"></i> </span>
                                <h2>SUBIR ARCHIVOS::..</h2>
                        </header>
                    </div>
                </section>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">DESCRIPCION &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <input id="descripcion" name="descripcion" type="text" class="form-control text-center text-uppercase" style="height: 32px;" >
                        </div>
                    </div>
                </div>
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
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
                
                <div class="col-xs-12" style="margin-top: 10px;"></div>
                <div class="col-xs-12" style="padding: 0px;">
                    <div class="input-group input-group-md" style="width: 100%">
                        <span class="input-group-addon" style="width: 30%;">ARCHIVOS &nbsp;<i class="fa fa-cogs"></i></span>
                        <div class="">
                            <input id="file" name="file[]" type="file" multiple="true" class="form-control text-center" style="height: 32px;" >
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div> 

@endsection