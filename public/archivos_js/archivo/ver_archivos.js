function buscar_descripcion(){
    descripcion = $("#vw_descripcion").val();
    fn_actualizar_grilla('tabla_ver_archivos','ver_archivos/0?grid=ver_archivos&descripcion='+descripcion);
}

function ver_archivos_asignados(id_archivo)
{
    $("#vw_ver_archivos").dialog({
        autoOpen: false, 
        modal: true, width: 1300,height:800, show: {effect: "fade", duration: 300}, resizable: false,
        title: "<div class='widget-header'><h4><span class='widget-icon'> <i class='fa fa-align-justify'></i> </span>VISOR DE ARCHIVOS</h4></div>",
        buttons: [{
                    html: "<i class='fa fa-sign-out'></i>&nbsp; SALIR",
                    "class": "btn btn-lg",
                    "style": "background-color:#CC191C; color:#ffffff",
                    click: function () {
                        $(this).dialog("close");
                    }
                }],
        
    }).dialog('open');
    
    $('#ver_archivo').attr('src','ver_archivos/0?mostrar=ver_archivos&id_archivo='+id_archivo+'#toolbar=0');
}

