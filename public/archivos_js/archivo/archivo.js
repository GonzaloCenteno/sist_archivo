function buscar_descripcion(){
    descripcion = $("#vw_descripcion").val();
    fn_actualizar_grilla('tabla_archivos','archivos/0?grid=archivos&descripcion='+descripcion);
}

function crear_nuevo_archivo() {
    $("#dlg_nuevo_archivo").dialog({
        autoOpen: false, modal: true, width: 900, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 400}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>SUBIDA DE ARCHIVOS</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                click: function () {
                    guardar_archivo();
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

function limpiar_formulario()
{
    $("#file").val('');
    $("#descripcion").val('');
    $('#id_tipo_archivo').val('0');
}

function guardar_archivo()
{
    descripcion = $('#descripcion').val();
    archivo = $('#file').val();
    id_tipo_archivo = $('#id_tipo_archivo').val();
    
    if (descripcion == '') {
        mostraralertasconfoco('* DEBES AGREGAR UNA DESCRIPCION...', '#descripcion');
        return false;
    }
    
    if (id_tipo_archivo == '0') {
        mostraralertasconfoco('* DEBE SELECCIONAR UNA OPCION...', '#id_tipo_archivo');
        return false;
    }

    if (archivo == '') {
        mostraralertasconfoco('* DEBE SUBIR AL MENOS UN ARCHIVO...', '#file');
        return false;
    }
    
    MensajeDialogLoadAjax('dlg_nuevo_archivo', '... .:: Guardando ::. ...');
    var form= new FormData($("#FormularioArchivo")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'archivos?tipo=1',
        type: 'POST',
        dataType: 'json',
        data: form,
        processData: false,
        contentType: false,
        success: function (data) 
        {
            if(data > 0)
            {
                MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE CREADO CORRECTAMENTE...",4000);
                MensajeDialogLoadAjaxFinish('dlg_nuevo_archivo');
                $("#dlg_nuevo_archivo").dialog("close");
                fn_actualizar_grilla('tabla_archivos');
            }
            else
            {
                MensajeDialogLoadAjaxFinish('dlg_nuevo_archivo');
                mostraralertas('* Contactese con el Administrador...');    
            }
        },
        error: function(data) {
            MensajeDialogLoadAjaxFinish('dlg_nuevo_archivo');
            mostraralertas('* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });
  
}

function ver_archivos(id_archivo)
{
    window.open('archivos/0?id_archivo='+id_archivo+'&mostrar=archivo');   
}