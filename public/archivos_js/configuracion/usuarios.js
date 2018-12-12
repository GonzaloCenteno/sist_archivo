
// CREAR USUARIOS
function buscar_persona(){
    persona = $("#vw_nombre_persona").val();
    fn_actualizar_grilla('tabla_usuarios','usuarios/0?grid=usuarios&persona='+persona);
}

function crear_nuevo_usuario() {
    $("#dlg_nuevo_usuario").dialog({
        autoOpen: false, modal: true, width: 700, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 400}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>CREAR NUEVO USUARIO</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; GUARDAR DATOS",
                "class": "btn btn-lg",
                "style": "background-color:#D48411; color:#ffffff",
                click: function () {
                    guardar_usuario(1);
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
    $("#form_dni").val('');
    $("#form_nombres").val('');
    $("#form_apaterno").val('');
    $("#form_amaterno").val('');
    $("#form_email").val('');
    $("#form_password").val('');
    $("#form_usuario").val('');
    $("#form_cargo").val('0')
    $("#form_foto").val('');
}

function guardar_usuario(valor)
{
    dni = $('#form_dni').val();
    nombres = $('#form_nombres').val();
    apaterno = $('#form_apaterno').val();
    amaterno = $('#form_amaterno').val();
    email = $('#form_email').val();
    password = $('#form_password').val();
    cargo = $('#form_cargo').val();
    usuario = $('#form_usuario').val();
    foto = $('#form_foto').val();

    if (dni == '') {
        mostraralertasconfoco('* EL CAMPO DNI ES OBLIGATORIO...', '#form_dni');
        return false;
    }
    if (nombres == '') {
        mostraralertasconfoco('* EL CAMPO NOMBRES ES OBLIGATORIO...', '#form_nombres');
        return false;
    }
    if (apaterno == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDO PATERNO ES OBLIGATORIO...', '#form_apaterno');
        return false;
    }
    if (amaterno == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDO MATERNO ES OBLIGATORIO...', '#form_amaterno');
        return false;
    }
    if (password == '') {
        mostraralertasconfoco('* EL CAMPO PASSWORD ES OBLIGATORIO...', '#form_password');
        return false;
    }
    if (cargo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#form_cargo');
        return false;
    }
    if (usuario == '') {
        mostraralertasconfoco('* EL CAMPO USUARIO ES OBLIGATORIO...', '#form_usuario');
        return false;
    }
    
    if (valor == 1) 
    {
        MensajeDialogLoadAjax('dlg_nuevo_usuario', '.:: Cargando ...');
        var form= new FormData($("#FormularioUsuario")[0]);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'usuarios?tipo=1',
            type: 'POST',
            dataType: 'json',
            data: form,
            processData: false,
            contentType: false,
            success: function (data) {
                if(data.msg === 'si')
                {
                    mostraralertasconfoco('El DNI: '+$('#form_dni').val()+' YA FUE REGISTRADO EN EL SISTEMA', '#form_dni');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_usuario');
                }
                else if(data.msg === 'usuario_ok')
                {
                    mostraralertasconfoco('El NOMBRE DE USUARIO: '+$('#form_usuario').val()+' YA FUE REGISTRADO EN EL SISTEMA', '#form_usuario');
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_usuario');
                }
                else
                {
                    MensajeDialogLoadAjaxFinish('dlg_nuevo_usuario');
                    MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE CREADO CORRECTAMENTE...",4000)
                    $("#dlg_nuevo_usuario").dialog("close");
                    fn_actualizar_grilla('tabla_usuarios');
                }
            },
            error: function(data) {
                MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
                console.log('error');
                console.log(data);
            }
        });
    }
}

function modificar_usuario()
{
    id_usuario = $('#tabla_usuarios').jqGrid ('getGridParam', 'selrow');
    if(id_usuario){
        $("#dlg_editar_usuario").dialog({
            autoOpen: false, modal: true, width: 700,
            show:{ effect: "explode", duration: 400},
            hide:{ effect: "explode", duration: 400}, resizable: false,
            title: "<div class='widget-header'><h4>.:  EDITAR USUARIO :.</h4></div>",
            buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; GUARDAR DATOS",
                "class": "btn btn-lg",
                "style": "background-color:#D48411; color:#ffffff",

                click: function () {
                    editar_usuario(1);
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
                $('#form_foto_edit').val('');
            }
        });
        $("#dlg_editar_usuario").dialog('open');

        MensajeDialogLoadAjax('dlg_editar_usuario', '.:: Cargando ...');

        $.ajax({url: 'usuarios/'+id_usuario+'?show=datos_usuario',
            type: 'GET',
            success: function(data)
            {
                $("#form_dni_edit").val(data[0].dni);
                $("#form_nombres_edit").val(data[0].nombres);
                $("#form_apaterno_edit").val(data[0].apaterno);
                $("#form_amaterno_edit").val(data[0].amaterno);
                $("#form_email_edit").val(data[0].email);
                $("#form_cargo_edit").val(data[0].id_rol);
                $("#form_usuario_edit").val(data[0].usuario);
                
                MensajeDialogLoadAjaxFinish('dlg_editar_usuario');
            },
            error: function(data) {
                mostraralertas("Hubo un Error, Comunicar al Administrador");
                console.log('error');
                console.log(data);
                MensajeDialogLoadAjaxFinish('dlg_editar_usuario');
            }
        });
    }else{
        mostraralertasconfoco("No Hay Tipo de Archivo Seleccionados","#tabla_usuarios");
    }
    
}

function editar_usuario(valor)
{
    dni = $('#form_dni_edit').val();
    nombres = $('#form_nombres_edit').val();
    apaterno = $('#form_apaterno_edit').val();
    amaterno = $('#form_amaterno_edit').val();
    email = $('#form_email_edit').val();
    password = $('#form_password').val();
    cargo = $('#form_cargo_edit').val();
    usuario = $('#form_usuario_edit').val();
    foto = $('#form_foto_edit').val();

    if (dni == '') {
        mostraralertasconfoco('* EL CAMPO DNI ES OBLIGATORIO...', '#form_dni_edit');
        return false;
    }
    if (nombres == '') {
        mostraralertasconfoco('* EL CAMPO NOMBRES ES OBLIGATORIO...', '#form_nombres_edit');
        return false;
    }
    if (apaterno == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDO PATERNO ES OBLIGATORIO...', '#form_apaterno_edit');
        return false;
    }
    if (amaterno == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDO MATERNO ES OBLIGATORIO...', '#form_amaterno_edit');
        return false;
    }
    if (cargo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#form_cargo_edit');
        return false;
    }
    if (usuario == '') {
        mostraralertasconfoco('* EL CAMPO USUARIO ES OBLIGATORIO...', '#form_usuario_edit');
        return false;
    }
    
    if (valor == 1) 
    {
        MensajeDialogLoadAjax('dlg_editar_usuario', '.:: Cargando ...');
        var form= new FormData($("#FormularioUsuarioEdit")[0]);
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: 'usuarios?tipo=4&id_usuario='+$('#tabla_usuarios').jqGrid ('getGridParam', 'selrow'),
            type: 'POST',
            dataType: 'json',
            data: form,
            processData: false,
            contentType: false,
            success: function (data) {
                if(data.msg === 'si')
                {
                    mostraralertasconfoco('El DNI: '+$('#form_dni_edit').val()+' YA FUE REGISTRADO EN EL SISTEMA', '#form_dni_edit');
                    MensajeDialogLoadAjaxFinish('dlg_editar_usuario');
                }
                else if(data.msg === 'usuario_ok')
                {
                    mostraralertasconfoco('El NOMBRE DE USUARIO: '+$('#form_usuario_edit').val()+' YA FUE REGISTRADO EN EL SISTEMA', '#form_usuario_edit');
                    MensajeDialogLoadAjaxFinish('dlg_editar_usuario');
                }
                else
                {
                    MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE CREADO CORRECTAMENTE...",4000);
                    MensajeDialogLoadAjaxFinish('dlg_editar_usuario');
                    $("#dlg_editar_usuario").dialog("close");
                    fn_actualizar_grilla('tabla_usuarios');
                }
            },
            error: function(data) {
                MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
                console.log('error');
                console.log(data);
            }
        });
    }
}

function resetear_clave()
{
    id_usuario = $('#tabla_usuarios').jqGrid ('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dialog_editar_usuario', '.:: Cargando ...');

    $.ajax({url: 'usuarios/'+id_usuario+'?show=resetear_clave',
        type: 'GET',
        success: function(data) 
        {
            if (data > 0) 
            {
                fn_actualizar_grilla('tabla_usuarios');
                MensajeExito("MENSAJE DE EXITO","LA CONTRASEÑA FUE CAMBIADA CORRECTAMENTE...",4000)
                MensajeDialogLoadAjaxFinish('dialog_editar_usuario');
            }
            else
            {
                MensajeDialogLoadAjaxFinish('dialog_editar_usuario');
                 mostraralertas("hubo un error, Comunicar al Administrador");
            }
        },
        error: function(data) {
            MensajeDialogLoadAjaxFinish('dialog_editar_usuario');
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

function cambiar_estado_usuario(id_usuario,estado)
{
    MensajeDialogLoadAjax('tabla_usuarios', '.:: Cargando ...');

    $.ajax({url: 'usuarios/0?datos=cambiar_estado&id_usuario='+id_usuario+'&estado='+estado,
        type: 'GET',
        success: function(data) 
        {
            if (data > 0) 
            {
                fn_actualizar_grilla('tabla_usuarios');
                MensajeExito("MENSAJE DE EXITO","EL ESTADO DEL USUARIO FUE CAMBIADO...",4000)
                MensajeDialogLoadAjaxFinish('tabla_usuarios');
            }
            else
            {
                MensajeDialogLoadAjaxFinish('tabla_usuarios');
                 mostraralertas("hubo un error, Comunicar al Administrador");
            }
        },
        error: function(data) {
            MensajeDialogLoadAjaxFinish('tabla_usuarios');
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}