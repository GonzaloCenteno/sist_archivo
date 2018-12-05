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
                                            <center><h1>MANTENIMIENTO TIPO ARCHIVO</h1></center>
                                        </div>
                                        
                                        <div class="row">
                                            
                                            <div class="col-xs-12">
                                                <div class="text-center">
                                                    @if( $permisos[0]->btn_new ==1 )
                                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="crear_nuevo_tipo_archivo();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVO TIPO
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-labeled bg-color-greenLight txt-color-white" onclick="sin_permiso();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-plus-sign"></i></span>NUEVO TIPO
                                                        </button>
                                                    @endif
                                                    @if( $permisos[0]->btn_edit ==1 )
                                                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="modificar_tipo_archivo();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>MODIFICAR TIPO
                                                        </button>
                                                    @else
                                                        <button  type="button" class="btn btn-labeled bg-color-blue txt-color-white" onclick="sin_permiso();">
                                                            <span class="btn-label"><i class="glyphicon glyphicon-pencil"></i></span>MODIFICAR TIPO
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
                                <table id="tabla_tipo_archivo"></table>
                                <div id="paginador_tabla_tipo_archivo"></div>
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
        $("#li_config_tipo_archivo").addClass('cr-active');
        
        jQuery("#tabla_tipo_archivo").jqGrid({
            url: 'tipo_archivo/0?grid=tipo_archivo',
            datatype: 'json', mtype: 'GET',
            height: '550px', autowidth: true,
            toolbarfilter: true,
            colNames: ['ID', 'DESCRIPCION - TIPO ARCHIVO'],
            rowNum: 50, sortname: 'id_tipo_archivo', sortorder: 'desc', viewrecords: true, caption: 'LISTA DE TIPOS DE ARCHIVOS', align: "center",
            colModel: [
                {name: 'id_tipo_archivo', index: 'id_tipo_archivo', align: 'center',width: 20},
                {name: 'descripcion', index: 'descripcion', align: 'left', width: 80}
            ],
            pager: '#paginador_tabla_tipo_archivo',
            rowList: [10, 20, 30, 40, 50],
            gridComplete: function () {
                    var idarray = jQuery('#tabla_tipo_archivo').jqGrid('getDataIDs');
                    if (idarray.length > 0) {
                    var firstid = jQuery('#tabla_tipo_archivo').jqGrid('getDataIDs')[0];
                            $("#tabla_tipo_archivo").setSelection(firstid);    
                        }
                },
            onSelectRow: function (Id){},
            ondblClickRow: function (Id)
            {
                perms = {!! json_encode($permisos[0]->btn_edit) !!};
                if(perms == 1)
                {
                    modificar_tipo_archivo();
                }
                else
                {
                    sin_permiso();
                }
            }
        });
        
        $(window).on('resize.jqGrid', function () {
            $("#tabla_tipo_archivo").jqGrid('setGridWidth', $("#content_2").width());
        });
         
    });
</script>

@stop
<script language="JavaScript" type="text/javascript" src="{{ asset('archivos_js/configuracion/tipo_archivo.js') }}"></script>

<div id="dlg_nuevo_tipo_archivo" style="display: none;">
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
@endsection