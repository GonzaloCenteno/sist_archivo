function fn_editar_usuario() {
    $("#dialog_editar_usuario").dialog({
        autoOpen: false, modal: true, width: 1500, 
        show:{ effect: "explode", duration: 600},
        hide:{ effect: "explode", duration: 700}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: EDITAR USUARIO :.</h4></div>",
        buttons: [ {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger btn-round",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            limpiar_form_usuario();
        }
    }).dialog('open');

    id_usuario = $('#tabla_usuarios').jqGrid('getGridParam', 'selrow');
    
    $.ajax({
        type: 'GET',
        url: 'usuarios/'+id_usuario+'?show=datos_usuario',
        success: function (data) {
            llamar_sub_modulo();
            $("#dlg_usuario_nombres").val(data[0].nombres);
            $("#dlg_usuario_apaterno").val(data[0].apaterno);
            $("#dlg_usuario_amaterno").val(data[0].amaterno);
            $("#dlg_usuario_cargo").val(data[0].cargo);
            $("#dlg_usuario_dni").val(data[0].dni);
            $("#dlg_usuario_ingreso").val(data[0].usuario);
        }, error: function (data) {
            mostraralertas('* Error base de datos... <br> * Contactese con el administrador..');
            dialog_close('dialog_editar_usuario');
        }
    });
   
}

function llamar_sub_modulo()
{
    
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    id_usuario = $('#tabla_usuarios').jqGrid('getGridParam', 'selrow');
    if(id_usuario==null){
        return false;
    }
    jQuery("#table_sub_modulos").jqGrid('setGridParam', {url: 'sub_modulos?identifi='+modulo+'&id_usuario='+id_usuario}).trigger('reloadGrid');
            
}

function limpiar_form_usuario() {
    $("#dlg_usuario_nombre").val('');
    $("#dlg_usu_usuario").val('');
    $("#dlg_usuario_dni").val('');
}

//CREAR MODULOS
function fn_new_mod()
{
    
    fn_crea_mod();
    $("#btn_edit_mod").hide();
    $("#btn_save_mod").show();

}

function fn_crea_mod()
{
    $("#hidden_id_mod").val(0);
    $("#dlg_des_mod,#dlg_title_mod,#dlg_idsis_mod").val("");
    $("#dlg_modulos").dialog({
        autoOpen: false, modal: true, width: 900, 
        show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>Generar Modulos Sistema</h4></div>",
        buttons: [{
                html: "<i class='fa fa-sign-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                id:'btn_save_mod',
                click: function () {
                    fn_save_mod();
                }
            },
            {
                html: "<i class='fa fa-sign-save'></i>&nbsp; Modificar",
                "class": "btn btn-primary",
                id:'btn_edit_mod',
                click: function () {
                    fn_save_mod();
                }
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        }).dialog('open');
}

function fn_save_mod()
{
    if($("#dlg_des_mod").val()==0||$("#dlg_des_mod").val()=="")
    {
        mostraralertasconfoco("Ingresar Nombre de Módulo","#dlg_des_mod");
        return false;
    }
    if($("#dlg_title_mod").val()==0||$("#dlg_title_mod").val()=="")
    {
        mostraralertasconfoco("Ingresar Título de Módulo","#dlg_title_mod");
        return false;
    }
    if($("#dlg_idsis_mod").val()==0||$("#dlg_idsis_mod").val()=="")
    {
        mostraralertasconfoco("Ingresar id_sistema","#dlg_idsis_mod");
        return false;
    }
    if($("#hidden_id_mod").val()==0)
    {
        url='modulos/create'; titulo="Insertó";
    }
    else
    {
        url='modulos/'+$("#hidden_id_mod").val()+'/edit'; titulo="Modificó";
    }
    MensajeDialogLoadAjax('dlg_modulos', '.:: Guardando ...');
    $.ajax({
        url: url,
        type: 'GET',
        data: {des:$("#dlg_des_mod").val(),tit:$("#dlg_title_mod").val(),sis:$("#dlg_idsis_mod").val()},
        success: function(r) 
        {
            jQuery("#table_modulos").jqGrid('setGridParam', {url: 'modulos'}).trigger('reloadGrid');
            MensajeExito("Se "+titulo+" Correctamente","Su Registro Fue Insertado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dlg_modulos');
            $("#dlg_modulos").dialog("close");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_modulos');
            console.log('error');
            console.log(data);
        }
    });
}

function fn_edit_mod()
{
    fn_crea_mod();
    $("#btn_edit_mod").show();
    $("#btn_save_mod").hide();
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    if(modulo==null)
    {
        mostraralertasconfoco("No hay Modulo seleccionado","#table_modulos");
        return false;
    }
    MensajeDialogLoadAjax('dlg_modulos', '.:: Cargando ...');
    $.ajax({
        url: 'modulos/'+modulo,
        type: 'GET',
        success: function(r) 
        {
            $("#hidden_id_mod").val(modulo);
            $("#dlg_des_mod").val(r[0].descripcion);
            $("#dlg_title_mod").val(r[0].titulo);
            $("#dlg_idsis_mod").val(r[0].id_sistema);
            MensajeDialogLoadAjaxFinish('dlg_modulos');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_modulos');
            console.log('error');
            console.log(data);
        }
    });

}

//CREAR SUBMODULOS

function fn_new_submod()
{
    
    fn_crea_submod();
    $("#btn_edit_submod").hide();
    $("#btn_save_submod").show();

}

function fn_crea_submod()
{
    $("#hidden_id_submod").val(0);
    $("#dlg_des_submod,#dlg_title_submod,#dlg_idsis_submod,#dlg_ruta_submod").val("");
    $("#dlg_submodulos").dialog({
        autoOpen: false, modal: true, width: 900, 
        show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>Generar Modulos Sistema</h4></div>",
        buttons: [{
                html: "<i class='fa fa-sign-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                id:'btn_save_submod',
                click: function () {
                    fn_save_submod();
                }
            },
            {
                html: "<i class='fa fa-sign-save'></i>&nbsp; Modificar",
                "class": "btn btn-primary",
                id:'btn_edit_submod',
                click: function () {
                    fn_save_submod();
                }
            },
            {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        }).dialog('open');
}

function fn_save_submod()
{
    if($("#dlg_des_submod").val()==0||$("#dlg_des_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar Nombre de sub Módulo","#dlg_des_mod");
        return false;
    }
    if($("#dlg_title_submod").val()==0||$("#dlg_title_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar Título de Módulo","#dlg_title_mod");
        return false;
    }
    if($("#dlg_idsis_submod").val()==0||$("#dlg_idsis_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar id_sistema","#dlg_idsis_mod");
        return false;
    }
    if($("#dlg_ruta_submod").val()==0||$("#dlg_ruta_submod").val()=="")
    {
        mostraralertasconfoco("Ingresar Ruta","#dlg_ruta_submod");
        return false;
    }
    if($("#dlg_orden_submod").val()=='')
    {
       $("#dlg_orden_submod").val(0);
    }
    if($("#hidden_id_submod").val()==0)
    {
        url='sub_modulos/create'; titulo="Insertó";
    }
    else
    {
        url='sub_modulos/'+$("#hidden_id_submod").val()+'/edit'; titulo="Modificó";
    }
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dlg_submodulos', '.:: Guardando ...');
    $.ajax({
        url: url,
        type: 'GET',
        data: {des:$("#dlg_des_submod").val(),tit:$("#dlg_title_submod").val(),sis:$("#dlg_idsis_submod").val(),ruta:$("#dlg_ruta_submod").val(),mod:modulo,orden:$("#dlg_orden_submod").val()},
        success: function(r) 
        {
            llamar_sub_modulo() 
            MensajeExito("Se "+titulo+" Correctamente","Su Registro Fue Insertado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
            $("#dlg_submodulos").dialog("close");
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
            console.log('error');
            console.log(data);
        }
    });
}

function fn_edit_submod()
{
    fn_crea_submod();
    $("#btn_edit_submod").show();
    $("#btn_save_submod").hide();
    submodulo= $('#table_sub_modulos').jqGrid('getGridParam', 'selrow');
    if(submodulo==null)
    {
        mostraralertasconfoco("No hay Sub Modulo seleccionado","#table_sub_modulos");
        return false;
    }
    MensajeDialogLoadAjax('dlg_submodulos', '.:: Cargando ...');
    $.ajax({
        url: 'sub_modulos/'+submodulo,
        type: 'GET',
        success: function(r) 
        {
            $("#hidden_id_submod").val(submodulo);
            $("#dlg_des_submod").val(r[0].des_sub_mod);
            $("#dlg_title_submod").val(r[0].titulo);
            $("#dlg_idsis_submod").val(r[0].id_sistema);
            $("#dlg_ruta_submod").val(r[0].ruta_sis);
            $("#dlg_orden_submod").val(r[0].orden);
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_submodulos');
            console.log('error');
            console.log(data);
        }
    });

}

//ACTIVAR PERMISOS

function actbtn(id,tip)
{
    if( $('#ck'+tip+'_'+id).is(':checked') ) {
        nu=1;
    }
    else
    {
        nu=0;
    }
    id_user = $('#tabla_usuarios').jqGrid('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dialog_editar_usuario', '.:: Guardando ...');
    $.ajax({
        url: 'permisos/create',
        type: 'GET',
        data: {submod:id,tipo:tip,val:nu,usu:id_user},
        success: function(r) 
        {
            MensajeExito("Se Creo Correctamente","Su Permiso Fue Insertado Correctamente...",4000)
            MensajeDialogLoadAjaxFinish('dialog_editar_usuario');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dialog_editar_usuario');
            console.log('error');
            console.log(data);
        }
    });
    
}

// CREAR USUARIOS

function crear_nuevo_usuario() {
    $("#dlg_nuevo_usuario").dialog({
        autoOpen: false, modal: true, width: 700, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 400}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>CREAR NUEVO USUARIO</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                click: function () {
                    guardar_editar_usuario(1);
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
    $("#form_dni").val('');
    $("#form_nombres").val('');
    $("#form_apaterno").val('');
    $("#form_amaterno").val('');
    $("#form_email").val('');
    $("#form_password").val('');
    $("#form_usuario").val('');
    $("#form_foto").val('');
}

function guardar_editar_usuario(valor)
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
        mostraralertasconfoco('* EL CAMPO PASSWORD MATERNO ES OBLIGATORIO...', '#form_password');
        return false;
    }
    if (cargo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#form_cargo');
        return false;
    }
    if (usuario == '') {
        mostraralertasconfoco('* EL CAMPO USUARIO MATERNO ES OBLIGATORIO...', '#form_usuario');
        return false;
    }
    
    if (valor == 1) 
    {
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
                    mostraralertasconfoco('El DNI: '+$('#form_dni').val()+' Ya fue Registrado en el Sistema', '#form_dni');
                }
                else
                {
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
    nombres = $('#dlg_usuario_nombres').val();
    apaterno = $('#dlg_usuario_apaterno').val();
    amaterno = $('#dlg_usuario_amaterno').val();
    cargo = $('#dlg_usuario_cargo').val();
    dni = $('#dlg_usuario_dni').val();
    usuario = $('#dlg_usuario_ingreso').val();

    if (nombres == '') {
        mostraralertasconfoco('* EL CAMPO NOMBRES ES OBLIGATORIO...', '#dlg_usuario_nombres');
        return false;
    }
    if (apaterno == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDO PATERNO ES OBLIGATORIO...', '#dlg_usuario_apaterno');
        return false;
    }
    if (amaterno == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDO MATERNO ES OBLIGATORIO...', '#dlg_usuario_amaterno');
        return false;
    }
    if (cargo == '0') {
        mostraralertasconfoco('* DEBES SELECCIONAR UNA OPCION...', '#dlg_usuario_cargo');
        return false;
    }
    if (dni == '') {
        mostraralertasconfoco('* EL CAMPO DNI ES OBLIGATORIO...', '#dlg_usuario_dni');
        return false;
    }
    if (usuario == '') {
        mostraralertasconfoco('* EL CAMPO USUARIO ES OBLIGATORIO...', '#dlg_usuario_ingreso');
        return false;
    }
    
    id_usuario = $('#tabla_usuarios').jqGrid ('getGridParam', 'selrow');
    
    MensajeDialogLoadAjax('dialog_editar_usuario', '.:: Cargando ...');
    
    $.ajax({url: 'usuarios/'+id_usuario+'/edit',
        type: 'GET',
        data:{
            nombres:nombres,
            apaterno:apaterno,
            amaterno:amaterno,
            cargo:cargo,
            dni:dni,
            usuario:usuario
        },
        success: function(data) 
        {
            if (data > 0) 
            {
                MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE EDITADO CORRECTAMENTE...",4000)
                MensajeDialogLoadAjaxFinish('dialog_editar_usuario');
                fn_actualizar_grilla('tabla_usuarios');
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