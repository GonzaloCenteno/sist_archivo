
function crear_nueva_asignacion() {
    $("#dlg_nueva_asignacion_archivo").dialog({
        autoOpen: false, modal: true, width: 1200, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 400}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>NUEVa ASIGNACION DE ARCHIVOS</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                click: function () {
                    guardar_editar_tipo_archivo(1);
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            limpiar_formulario();
        },
        open: function () {
            limpiar_formulario();
        }
    }).dialog('open');
}

function buscar_usuario()
{
    if($("#dlg_nombre_persona").val()=="")
    {
        mostraralertasconfoco("INGRESAR INFORMACION DE BUSQUEDA","#dlg_nombre_persona"); 
        return false;
    }
    if($("#dlg_nombre_persona").val().length<5)
    {
        mostraralertasconfoco("INGRESAR AL MENOS 5 CARACTERES DE BUSQUEDA","#dlg_nombre_persona"); 
        return false;
    }

    jQuery("#tabla_usuario").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=usuarios&nombre='+$('#dlg_nombre_persona').val()}).trigger('reloadGrid');

    $("#dlg_bus_usuario").dialog({
        autoOpen: false, modal: true, width: 500, 
        show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4>.:  BUSQUEDA DE USUARIOS :.</h4></div>"       
    }).dialog('open');
}

function fn_traer_datos(id_usuario){
    $("#id_usuario").val(id_usuario);
    
    $("#dlg_cargo").val($('#tabla_usuario').jqGrid('getCell',id_usuario,'cargo'));    
    $("#dlg_usuario").val($('#tabla_usuario').jqGrid('getCell',id_usuario,'usuario'));
    $("#dlg_nombre_persona").val($('#tabla_usuario').jqGrid('getCell',id_usuario,'persona'));
    
    $("#dlg_bus_usuario").dialog("close");    
}

function limpiar_formulario()
{
    $("#dlg_nombre_persona").val('');
    $("#id_usuario").val('0');
    $("#dlg_cargo").val('');
    $("#dlg_usuario").val('');
    $("#dlg_id_tipo_archivo").val('0');
    jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=asignar_archivos&id_tipo_archivo=0' }).trigger('reloadGrid');
}

function recuperar_archivos()
{   
    if ($('#dlg_id_tipo_archivo').val() == 0) 
    {
        jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=asignar_archivos&id_tipo_archivo=0' }).trigger('reloadGrid');
    }
    else
    {
        jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=asignar_archivos&id_tipo_archivo='+$('#dlg_id_tipo_archivo').val() }).trigger('reloadGrid');
    }
}

function guardar_editar_tipo_archivo(valor)
{
    descripcion = $('#dlg_descripcion').val();
    
    if (descripcion == '') {
        mostraralertasconfoco('* DEBES AGREGAR UNA DESCRIPCION...', '#dlg_descripcion');
        return false;
    }
    
    if (valor == 1) 
    {
        MensajeDialogLoadAjax('dlg_nuevo_tipo_archivo', '.:: Cargando ...');
    
        $.ajax({url: 'tipo_archivo/create',
            type: 'GET',
            data:{
                descripcion:descripcion,
            },
            success: function(data) 
            {
                if (data > 0) 
                {
                    fn_actualizar_grilla('tabla_tipo_archivo');
                    MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE CREADO CORRECTAMENTE...",4000)
                    dialog_close('dlg_nuevo_tipo_archivo');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_tipo_archivo');
                }
                else
                {
                     mostraralertas("hubo un error, Comunicar al Administrador");
                }
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
    }
    if (valor == 2) 
    {
        id_tipo_archivo = $('#tabla_tipo_archivo').jqGrid ('getGridParam', 'selrow');
        MensajeDialogLoadAjax('dlg_nuevo_tipo_archivo', '.:: Cargando ...');
    
        $.ajax({url: 'tipo_archivo/'+id_tipo_archivo+'/edit',
            type: 'GET',
            data:{
                descripcion:descripcion,
            },
            success: function(data) 
            {
                if (data > 0) 
                {
                    fn_actualizar_grilla('tabla_tipo_archivo');
                    MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE MODIFICADO CORRECTAMENTE...",4000)
                    dialog_close('dlg_nuevo_tipo_archivo');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_tipo_archivo');
                }
                else
                {
                     mostraralertas("hubo un error, Comunicar al Administrador");
                }
            },
            error: function(data) {
                mostraralertas("hubo un error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
            }
        });
    }
    
}

function modificar_tipo_archivo()
{
    id_tipo_archivo = $('#tabla_tipo_archivo').jqGrid ('getGridParam', 'selrow');
    if(id_tipo_archivo){
        $("#dlg_nuevo_tipo_archivo").dialog({
            autoOpen: false, modal: true, width: 700,
            show:{ effect: "explode", duration: 400},
            hide:{ effect: "explode", duration: 400}, resizable: false,
            title: "<div class='widget-header'><h4>.:  EDITAR TIPO REQUISITO :.</h4></div>",
            buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    guardar_editar_tipo_archivo(2);
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        });
        $("#dlg_nuevo_tipo_archivo").dialog('open');


        MensajeDialogLoadAjax('dlg_nuevo_tipo_archivo', '.:: Cargando ...');

        $.ajax({url: 'tipo_archivo/'+id_tipo_archivo+'?show=tipos_archivo',
            type: 'GET',
            success: function(data)
            {
                $("#dlg_descripcion").val(data[0].descripcion);
                MensajeDialogLoadAjaxFinish('dlg_nuevo_tipo_archivo');
            },
            error: function(data) {
                mostraralertas("Hubo un Error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
                MensajeDialogLoadAjaxFinish('dlg_nuevo_tipo_archivo');
            }
        });
    }else{
        mostraralertasconfoco("No Hay Tipo de Archivo Seleccionados","#tabla_tipo_archivo");
    }
    
}
