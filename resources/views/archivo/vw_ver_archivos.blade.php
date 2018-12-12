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
                                                <center><h1 style="color:#CC191C"><b>REGISTRO - VER ARCHIVOS</b></h1></center>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
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
                                <table id="tabla_ver_archivos"></table>
                                <div id="paginador_tabla_ver_archivos"></div>
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
        
        $("#menu_archivos").show();
        $("#li_config_ver_archivos").addClass('cr-active');;
        
        jQuery("#tabla_ver_archivos").jqGrid({
            url: 'ver_archivos/0?grid=ver_archivos',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DESCRIPCION', 'ARCHIVO', 'TIPO ARCHIVO', 'FECHA REGISTRO', 'VER ARCHIVO'],
            rowNum: 50, sortname: 'descripcion_archivo', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE ARCHIVOS ASIGNADOS', align: "center",
            colModel: [
                {name: 'id_archivo', index: 'id_archivo', align: 'left',width: 20, hidden: true},
                {name: 'descripcion_archivo', index: 'descripcion_archivo', align: 'left', width: 50},
                {name: 'archivo', index: 'archivo', align: 'left', width: 40},
                {name: 'tipo_archivo', index: 'tipo_archivo', align: 'left', width: 25},
                {name: 'fecha_registro', index: 'fecha_registro', align: 'center', width: 15},
                {name: 'ver', index: 'ver', align: 'center', width: 20}
            ],
            pager: '#paginador_tabla_ver_archivos',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_ver_archivos').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_ver_archivos').jqGrid('getDataIDs')[0];
                            $("#tabla_ver_archivos").setSelection(firstid);    
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
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_ver_archivos").jqGrid('setGridWidth', $("#content_2").width());
        });
         
    });
</script>

@stop
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/archivo/ver_archivos.js') }}"></script>

<div id="vw_ver_archivos" style="display: none">
    <div class="widget-body">
        <div  class="smart-form">
            <div class="panel-group text-center">
                <iframe id="ver_archivo" style="width:1200px; height:670px;" frameborder="0" allowfullscreen></iframe> 
            </div>
        </div>
    </div>
</div>
@endsection