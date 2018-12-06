
function crear_nuevo_tipo_archivo() {
    $("#dlg_nuevo_tipo_archivo").dialog({
        autoOpen: false, modal: true, width: 700, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 400}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>NUEVO TIPO ARCHIVO</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; GUARDAR DATOS",
                "class": "btn btn-lg",
                "style": "background-color:#D48411; color:#ffffff",
                click: function () {
                    guardar_editar_tipo_archivo(1);
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; SALIR",       
                "class": "btn btn-lg",
                "style": "background-color:#CC191C; color:#ffffff",
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

function limpiar_formulario()
{
    $("#dlg_descripcion").val('');;
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
                html: "<i class='fa fa-save'></i>&nbsp; GUARDAR DATOS",
                "class": "btn btn-lg",
                "style": "background-color:#D48411; color:#ffffff",
                click: function () {
                    guardar_editar_tipo_archivo(2);
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; SALIR",
                "class": "btn btn-lg",
                "style": "background-color:#CC191C; color:#ffffff",
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
