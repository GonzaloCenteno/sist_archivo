function buscar_descripcion(){
    descripcion = $("#vw_descripcion").val();
    fn_actualizar_grilla('tabla_archivos','archivos/0?grid=archivos&descripcion='+descripcion);
}

function crear_nuevo_archivo() {
    $("#dlg_nuevo_archivo").dialog({
        autoOpen: false, modal: true, width: 900, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 500}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>SUBIDA DE ARCHIVOS</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; GUARDAR DATOS",
                "class": "btn btn-lg",
                "style": "background-color:#D48411; color:#ffffff",
                click: function () {
                    guardar_archivo();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; SALIR",
                "class": "btn btn-lg",
                "style": "background-color:#CC191C; color:#ffffff",
                click: function () {
                    myDropzone.removeAllFiles();
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            myDropzone.removeAllFiles();
            limpiar_formulario();
        },
        open: function () {
            myDropzone.removeAllFiles();
            limpiar_formulario();
        }
    }).dialog('open');
}

function limpiar_formulario()
{
    $("#file").val('');
    $("#descripcion").val('');
    $('#id_tipo_archivo').val('0');
}

function ver_archivos(id_archivo)
{
    window.open('archivos/0?id_archivo='+id_archivo+'&mostrar=archivo');   
}

function eliminar_archivo()
{
    id_archivo = $('#tabla_archivos').jqGrid ('getGridParam', 'selrow');
    if(id_archivo)
    {
        archivo = $('#tabla_archivos').jqGrid ('getCell', id_archivo, 'descripcion');
        MensajeDialogLoadAjax('tabla_archivos', '.:: Cargando ...');
        $.confirm({
            title: '.: CUIDADO :.!',
            content: '¿ESTA SEGURO DE ELIMINAR EL SIGUIENTE ARCHIVO?&nbsp;&nbsp;<b>'+archivo+'</b>',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        url: 'archivos/destroy',
                        type: 'POST',
                        data: {_method: 'delete', id_archivo: id_archivo},
                        success: function (data) 
                        {
                           if (data > 0) 
                            {
                                fn_actualizar_grilla('tabla_archivos');
                                MensajeExito("MENSAJE DE EXITO","EL ARCHIVO FUE ELIMINADO...",4000)
                                MensajeDialogLoadAjaxFinish('tabla_archivos');
                            }
                            else
                            {
                                MensajeDialogLoadAjaxFinish('tabla_archivos');
                                 mostraralertas("hubo un error, Comunicar al Administrador");
                            }
                        },
                        error: function (data) {
                            MensajeDialogLoadAjaxFinish('tabla_archivos');
                            MensajeAlerta('Eliminar Archivo', id_archivo + ' - No se pudo Eliminar.');
                        }
                    });
                },
                Cancelar: function () {
                    MensajeDialogLoadAjaxFinish('tabla_archivos');
                    MensajeAlerta('ELIMINAR ARCHIVO','OPERACION CANCELADA');
                }

            }
        });
    }
    else
    {
        mostraralertasconfoco("NO HAY REGISTROS SELECCIONADOS","#tabla_archivos");
    }
}

function permisos_archivos(id_archivo,est)
{
    MensajeDialogLoadAjax('tabla_archivos', '.:: Cargando ...');
    
    $.ajax({url: 'archivos/'+id_archivo+'/edit',
        type: 'GET',
        data:{
            est:est,
        },
        success: function(data) 
        {
            if (data > 0) 
            {
                fn_actualizar_grilla('tabla_archivos');
                MensajeExito("MENSAJE DE EXITO","SE AGREGO UN PERMISO AL ARCHIVO",4000);
                MensajeDialogLoadAjaxFinish('tabla_archivos');
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