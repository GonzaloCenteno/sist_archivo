
function buscar_persona()
{
    nombre = $("#vw_nombre_persona").val();
    fn_actualizar_grilla('tabla_archivo_persona','asignar_archivos/0?grid=archivo_persona&nombre='+nombre);
}

function crear_nueva_asignacion() {
    $('#dmodificar').hide();
    $('#btn_nuevas_asignaciones').hide();
    $('#dnuevo').show();
    $('#dlg_nombre_persona').removeAttr('disabled');
    $("#dlg_nueva_asignacion_archivo").dialog({
        autoOpen: false, modal: true, width: 1200, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 400}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>NUEVA ASIGNACION DE ARCHIVOS</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    guardar_asignacion_archivos();
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
    
    MensajeDialogLoadAjax('dlg_nombre_persona', '....:: Cargando ::...');
    $.ajax({
        url: 'asignar_archivos/'+id_usuario+'?show=verificar_usuario',
        type: 'GET',
        success: function (data) {
            if (data > 0) 
            {
                MensajeDialogLoadAjaxFinish('dlg_nombre_persona');
                $("#id_usuario").val(id_usuario);
                $("#dlg_cargo").val($('#tabla_usuario').jqGrid('getCell',id_usuario,'cargo'));    
                $("#dlg_usuario").val($('#tabla_usuario').jqGrid('getCell',id_usuario,'usuario'));
                $("#dlg_nombre_persona").val($('#tabla_usuario').jqGrid('getCell',id_usuario,'persona'));
                $("#dlg_bus_usuario").dialog("close");
            }
            else
            {
                MensajeDialogLoadAjaxFinish('dlg_nombre_persona');
                $("#dlg_bus_usuario").dialog("close");
                mostraralertas('* LA PERSONA, YA TIENE ARCHIVOS SELECCIONADOS...BUSCAR EN LA LISTA DE ASIGNACIONES Y CON DOBLE CLICK SELECCIONAR USUARIO PARA AGREGAR NUEVOS ARCHIVOS');
                return false;
            }
        },
        error: function (data) {
            MensajeDialogLoadAjaxFinish('dlg_nombre_persona');
            mostraralertas('* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });         
}

function limpiar_formulario()
{
    $("#dlg_nombre_persona").val('');
    $("#id_usuario").val('0');
    $("#dlg_cargo").val('');
    $("#dlg_usuario").val('');
    $("#dlg_id_tipo_archivo_dnuevo").val('0');
    $("#dlg_id_tipo_archivo_dmodificar").val('0');
    jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=asignar_archivos&id_tipo_archivo=0' }).trigger('reloadGrid');
}

function recuperar_archivos(valor)
{
    if (valor == 1) 
    {
        if ($('#dlg_id_tipo_archivo_dnuevo').val() == 0) 
        {
            jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=asignar_archivos&id_tipo_archivo=0' }).trigger('reloadGrid');
        }
        else
        {
            jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=asignar_archivos&id_tipo_archivo='+$('#dlg_id_tipo_archivo_dnuevo').val() }).trigger('reloadGrid');
        }
    }
    
    if (valor == 2) 
    {
        if ($('#dlg_id_tipo_archivo_dmodificar').val() == 0) 
        {
            jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=archivos_asignados&id_tipo_archivo=0&id_usuario=0' }).trigger('reloadGrid');
        }
        else
        {
            jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=archivos_asignados&id_tipo_archivo='+$('#dlg_id_tipo_archivo_dmodificar').val()+'&id_usuario='+$('#id_usuario').val() }).trigger('reloadGrid');
        }
    }
}

function guardar_asignacion_archivos(){
    id_usuario = $("#id_usuario").val();
    id_tipo_archivo = $('#dlg_id_tipo_archivo_dnuevo').val();
    
    if (id_usuario == '0') {
        mostraralertasconfoco('* EL CAMPO NOMBRE ES OBLIGATORIO...', '#dlg_nombre_persona');
        return false;
    }
    
    if (id_tipo_archivo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#dlg_id_tipo_archivo_dnuevo');
        return false;
    }
    
    $('input[type=checkbox][name=id_archivo_check]').each(function() {
        insertar_datos($(this).attr('id_archivo'),id_usuario,$(this).is(':checked')?1:0);
    });  
}

function insertar_datos(id_archivo,id_usuario,flag) 
{
    MensajeDialogLoadAjax('dlg_nueva_asignacion_archivo', '....:: Cargando ::...');
    $.ajax({
        url: 'asignar_archivos/create',
        type: 'GET',
        data: 
        {
            id_archivo :id_archivo,
            id_usuario :id_usuario,
            flag       :flag,
            tipo       :1
        },
        success: function (data) {
            if (data > 0) 
            {
                MensajeDialogLoadAjaxFinish('dlg_nueva_asignacion_archivo');
                fn_actualizar_grilla('tabla_archivo_persona');
                $("#dlg_nombre_persona").val('');
                $("#id_usuario").val('0');
                $("#dlg_cargo").val('');
                $("#dlg_usuario").val('');
            }
            else
            {
                MensajeDialogLoadAjaxFinish('dlg_nueva_asignacion_archivo');
                mostraralertas('* Contactese con el Administrador...');
                console.log('error');
                console.log(data);
            }
        },
        error: function (data) {
            MensajeDialogLoadAjaxFinish('dlg_nueva_asignacion_archivo');
            mostraralertas('* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });     
}

function modificar_asignacion()
{
    id_usuario = $('#tabla_archivo_persona').jqGrid ('getGridParam', 'selrow');
    if(id_usuario){
        $('#dnuevo').hide();
        $('#dmodificar').show();
        $('#btn_nuevas_asignaciones').show();
        $('#dlg_nombre_persona').attr('disabled',true);
        $("#dlg_nueva_asignacion_archivo").dialog({
            autoOpen: false, modal: true, width: 1200,
            show:{ effect: "fide", duration: 300}, resizable: false,
            title: "<div class='widget-header'><h4>.:  EDITAR ASIGNACION DE ARCHIVOS :.</h4></div>",
            buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    modificar_asignacion_archivos();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        });
        $("#dlg_nueva_asignacion_archivo").dialog('open');


        MensajeDialogLoadAjax('dlg_nueva_asignacion_archivo', '...:: Cargando ::...');

        $.ajax({url: 'asignar_archivos/'+id_usuario+'?show=archivo_persona',
            type: 'GET',
            success: function(data)
            {
                $("#id_usuario").val(data[0].id);
                $("#dlg_nombre_persona").val(data[0].persona);
                $("#dlg_cargo").val(data[0].cargo);
                $("#dlg_usuario").val(data[0].usuario);
                
                id_tipo_archivo = $('#dlg_id_tipo_archivo_dmodificar').val('0');
                jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=archivos_asignados&id_tipo_archivo=0&id_usuario=0' }).trigger('reloadGrid');
                MensajeDialogLoadAjaxFinish('dlg_nueva_asignacion_archivo');
            },
            error: function(data) {
                mostraralertas("Hubo un Error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
                MensajeDialogLoadAjaxFinish('dlg_nueva_asignacion_archivo');
            }
        });
    }else{
        mostraralertasconfoco("No Hay Una Persona Seleccionada","#tabla_archivo_persona");
    }
}

function modificar_asignacion_archivos()
{   
    id_tipo_archivo = $('#dlg_id_tipo_archivo_dmodificar').val();
    id_usuario = $("#id_usuario").val();
    
    if (id_usuario == '0') {
        mostraralertasconfoco('* EL CAMPO NOMBRE ES OBLIGATORIO...', '#dlg_nombre_persona');
        return false;
    }
    
    if (id_tipo_archivo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#dlg_id_tipo_archivo_dmodificar');
        return false;
    }
    
    $('input[type=checkbox][name=estado]').each(function() {
        actualizar_datos($(this).attr('id_arch_pers'),$(this).attr('id_archivo'),id_usuario,$(this).is(':checked')?1:0);
    });
}

function actualizar_datos(id_arch_pers,id_archivo,id_usuario,flag)
{
    MensajeDialogLoadAjax('dlg_nueva_asignacion_archivo', '...:: Cargando ::...');
    
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'asignar_archivos/'+id_usuario+'/edit',
        type: 'GET',
        data: {
            id_arch_pers:id_arch_pers,
            id_archivo :id_archivo,
            id_usuario :id_usuario,
            flag       :flag
        },
        success: function (data) {
            MensajeDialogLoadAjaxFinish('dlg_nueva_asignacion_archivo');
            dialog_close('dlg_nueva_asignacion_archivo');
        },
        error: function (data) {
            MensajeDialogLoadAjaxFinish('dlg_nueva_asignacion_archivo');
            mostraralertas('* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });
}

function nuevas_asignaciones()
{
    if ($('#dlg_id_tipo_archivo_dmodificar').val() == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#dlg_id_tipo_archivo_dmodificar');
        return false;
    }
    
    if($('#id_usuario').val() != 0)
    {    
        $("#dlg_nuevas_asignaciones").dialog({
            autoOpen: false, modal: true, width: 1200,
            show:{ effect: "fide", duration: 400}, resizable: false,
            title: "<div class='widget-header'><h4>.: AGREGAR NUEVAS ASIGNACIONES DE ARCHIVOS :.</h4></div>",
            buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success bg-color-green",
                click: function () {
                    agregar_nuevas_asignaciones();
                }
            }, {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        });
        $("#dlg_nuevas_asignaciones").dialog('open');

        jQuery("#tabla_nuevas_asignaciones").jqGrid(
                'setGridParam', 
        {url: 'asignar_archivos/0?grid=nuevas_asignaciones&id_tipo_archivo='+$('#dlg_id_tipo_archivo_dmodificar').val()+'&id_usuario='+$('#id_usuario').val() 
        }).trigger('reloadGrid');
    }
    else
    {
        mostraralertasconfoco("NO HAY UNA PERSONA SELECCIONADA","#id_usuario");
    }
}

function agregar_nuevas_asignaciones(){
    id_usuario = $("#id_usuario").val();
    id_tipo_archivo = $('#dlg_id_tipo_archivo_dmodificar').val();
    
    if (id_usuario == '0') {
        mostraralertasconfoco('* EL CAMPO NOMBRE ES OBLIGATORIO...', '#dlg_nombre_persona');
        return false;
    }
    
    if (id_tipo_archivo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#dlg_id_tipo_archivo_dmodificar');
        return false;
    }
    
    $('input[type=checkbox][name=asignados]').each(function() {
        insertar_datos_asignacion($(this).attr('id_archivo'),id_usuario,$(this).is(':checked')?1:0);
    });  
}

function insertar_datos_asignacion(id_archivo,id_usuario,flag) 
{
    MensajeDialogLoadAjax('dlg_nuevas_asignaciones', '....:: Cargando ::...');
    $.ajax({
        url: 'asignar_archivos/create',
        type: 'GET',
        data: 
        {
            id_archivo :id_archivo,
            id_usuario :id_usuario,
            flag       :flag,
            tipo       :2
        },
        success: function (data) {
            if (data > 0) 
            {
                MensajeDialogLoadAjaxFinish('dlg_nuevas_asignaciones');
                dialog_close('dlg_nuevas_asignaciones');
                jQuery("#tabla_asignacion_archivos").jqGrid('setGridParam', {url: 'asignar_archivos/0?grid=archivos_asignados&id_tipo_archivo='+$('#dlg_id_tipo_archivo_dmodificar').val()+'&id_usuario='+$('#id_usuario').val() }).trigger('reloadGrid');
            }
            else
            {
                MensajeDialogLoadAjaxFinish('dlg_nuevas_asignaciones');
                mostraralertas('* Contactese con el Administrador...');
                return false;
            }
        },
        error: function (data) {
            MensajeDialogLoadAjaxFinish('dlg_nuevas_asignaciones');
            mostraralertas('* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });     
}