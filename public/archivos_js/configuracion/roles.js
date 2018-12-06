
function crear_nuevo_rol() {
    $("#dlg_nuevo_rol").dialog({
        autoOpen: false, modal: true, width: 700, 
        show:{ effect: "explode", duration: 400},
        hide:{ effect: "explode", duration: 400}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>NUEVO ROL</h4></div>",
        buttons: [{
                html: "<i class='fa fa-save'></i>&nbsp; Guardar",
                "class": "btn btn-success",
                click: function () {
                    guardar_rol();
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
    $('#dlg_codigo').val('');
    $("#dlg_descripcion").val('');
}

function guardar_rol()
{
    codigo = $('#dlg_codigo').val();
    descripcion = $('#dlg_descripcion').val();
    
    if (codigo == '') {
        mostraralertasconfoco('* DEBES AGREGAR UN CODIGO...', '#dlg_codigo');
        return false;
    }
    if (descripcion == '') {
        mostraralertasconfoco('* DEBES AGREGAR UNA DESCRIPCION...', '#dlg_descripcion');
        return false;
    }
    
    MensajeDialogLoadAjax('dlg_nuevo_rol', '.:: Cargando ...');

    $.ajax({url: 'roles/create',
        type: 'GET',
        data:{
            codigo:codigo,
            descripcion:descripcion,
            tipo:1
        },
        success: function(data) 
        {
            if (data > 0) 
            {
                fn_actualizar_grilla('tabla_rol');
                MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE CREADO CORRECTAMENTE...",4000)
                dialog_close('dlg_nuevo_rol');
                MensajeDialogLoadAjaxFinish('dlg_nuevo_rol');
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

function modificar_rol()
{
    $("#dialog_editar_rol").dialog({
        autoOpen: false, modal: true, width: 1500, 
        show:{ effect: "fide", duration: 700}, resizable: false,
        title: "<div class='widget-header'><h4>&nbsp&nbsp.: EDITAR ROL :.</h4></div>",
        buttons: [ {
                html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
                "class": "btn btn-danger btn-round",
                click: function () {
                    $(this).dialog("close");
                }
            }],
        close: function (event, ui) {
            //limpiar_form_usuario();
        }
    }).dialog('open');

    id_rol = $('#tabla_rol').jqGrid('getGridParam', 'selrow');
    
    $.ajax({
        type: 'GET',
        url: 'roles/'+id_rol+'?show=datos_roles',
        success: function (data) {
            llamar_sub_modulo();
            $("#form_codigo").val(data[0].codigo);
            $("#form_descripcion").val(data[0].descripcion);
        }, error: function (data) {
            mostraralertas('* Error base de datos... <br> * Contactese con el administrador..');
            dialog_close('dialog_editar_rol');
        }
    });
}

function llamar_sub_modulo()
{
    
    modulo= $('#table_modulos').jqGrid('getGridParam', 'selrow');
    id_rol = $('#tabla_rol').jqGrid('getGridParam', 'selrow');
    if(id_rol==null){
        return false;
    }
    jQuery("#table_sub_modulos").jqGrid('setGridParam', {url: 'sub_modulos?identifi='+modulo+'&id_rol='+id_rol}).trigger('reloadGrid');
            
}

//CREACION DE TIPOS DE ARCHIVO POR ROL

function agregar_tip_archivos()
{
    $("#dlg_nuevo_tipo_rol").dialog({
        autoOpen: false, modal: true, width: 700,
        show:{ effect: "fide", duration: 500}, resizable: false,
        title: "<div class='widget-header'><h4>.: TIPOS DE ARCHIVOS :.</h4></div>",
        buttons: [{
            html: "<i class='fa fa-sign-out'></i>&nbsp; Salir",
            "class": "btn btn-danger",
            click: function () {
                $(this).dialog("close");
            }
        }]
    });
    $("#dlg_nuevo_tipo_rol").dialog('open');

    id_rol = $('#inp_id_rol').val();
    if(id_rol == 0){
        return false;
    }
    jQuery("#tabla_roles_tipo_archivo").jqGrid('setGridParam', {url: 'roles/0?grid=tipo_archivos&id_rol='+id_rol}).trigger('reloadGrid');
}

function cambiar_estado(id_tipo_archivo,tip)
{
    if( $('#ck'+tip+'_'+id_tipo_archivo).is(':checked') ) {
        nu=1;
    }
    else
    {
        nu=0;
    }
    id_rol = $('#inp_id_rol').val();
    MensajeDialogLoadAjax('dlg_nuevo_tipo_rol', '.:: Guardando ...');
    $.ajax({
        url: 'roles/create',
        type: 'GET',
        data: {
            id_tipo_archivo:id_tipo_archivo,
            tip:tip,
            val:nu,
            id_rol:id_rol,
            tipo:2
        },
        success: function(r) 
        {
            MensajeExito("MENSAJE DE EXTIO","SE AGREGO CORRECTAMENTE EL TIPO DE ARCHIVO",4000);
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tipo_rol');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dlg_nuevo_tipo_rol');
            console.log('error');
            console.log(data);
        }
    });
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
    id_rol = $('#tabla_rol').jqGrid('getGridParam', 'selrow');
    MensajeDialogLoadAjax('dialog_editar_rol', '.:: Guardando ...');
    $.ajax({
        url: 'permisos/create',
        type: 'GET',
        data: {submod:id,tipo:tip,val:nu,id_rol:id_rol},
        success: function(r) 
        {
            MensajeExito("SE CREO CORRECTAMENTE","SU PERMISO FUE AGREGADO CORRECTAMENTE",4000);
            MensajeDialogLoadAjaxFinish('dialog_editar_rol');
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            MensajeDialogLoadAjaxFinish('dialog_editar_rol');
            console.log('error');
            console.log(data);
        }
    });
    
}

function modificar_datos()
{
    codigo = $('#form_codigo').val();
    descripcion = $('#form_descripcion').val();

    if (codigo == '') {
        mostraralertasconfoco('* EL CAMPO CODIGO ES OBLIGATORIO...', '#form_codigo');
        return false;
    }
    if (descripcion == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#form_descripcion');
        return false;
    }
    
    id_rol = $('#tabla_rol').jqGrid ('getGridParam', 'selrow');
    
    MensajeDialogLoadAjax('dialog_editar_rol', '.:: Cargando ...');
    
    $.ajax({url: 'roles/'+id_rol+'/edit',
        type: 'GET',
        data:{
            codigo:codigo,
            descripcion:descripcion
        },
        success: function(data) 
        {
            if (data > 0) 
            {
                MensajeExito("MENSAJE DE EXITO","EL REGISTRO FUE EDITADO CORRECTAMENTE...",4000)
                MensajeDialogLoadAjaxFinish('dialog_editar_rol');
                fn_actualizar_grilla('tabla_rol');
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
