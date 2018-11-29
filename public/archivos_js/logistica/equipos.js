
jQuery(document).ready(function($){
	$("#cabecera_logistica").addClass('active');
    $("#detalle_equipos").addClass('active');
    mostrarformulario(false);
	$("#tblEquipos").DataTable({
		"lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todo"]],
		"info": true,
		"ordering": true,
		"destroy":true,
		"searching": true,
		"processing" : true,
		"serverSide" : true,
		"responsive": true,
		"ajax": "equipos/0?tabla=equipos",
		"columns":[
			{data: 'id_equipo'},
            {data: 'codigo',class:'text-center'},
			{data: 'equipo',class:'text-center'},
            {data: 'imagen',class:'text-center',searchable: false},
            {data: 'area',class:'text-center'},
            {data: 'estado',class:'text-center',searchable: false},
		],
        "columnDefs": [
            {
                  "targets": [5], 
                  "data": "estado", 
                  "render": function(data, type, row) {
                    
                    if (data == 0) {
                      return "<center><button type='button' onClick='modificar_estado("+row.id_equipo+","+1+")' class='btn btn-danger btn-lg'><span class='btn-label'><i class='fa fa-close'></i></span> INACTIVO </button></center>";
                    }else if (data == 1) {
                      return "<center><button type='button' onClick='modificar_estado("+row.id_equipo+","+0+")' class='btn btn-success btn-lg'><span class='btn-label'><i class='fa fa-check'></i></span> ACTIVO </button></center>";
                    }
                }
            },
            {
                  "targets": [3], 
                  "data": "imagen", 
                  "render": function(data, type, row) {
                    
                    return "<td><img src='data:image/png;base64,"+data+"' style='max-height:130px; max-width:130px'></td>";
                }
            }
        ],
        "order": [[ 0, "desc" ]],
		"language" : idioma_espanol,
		"select": true
	});

    if(aux1==0)
    {
        autocompletar_areas('form_area');
        aux1=1;
    }
})

var idioma_espanol = {
    "sProcessing":     "Procesando...",
    "sLengthMenu":     "Mostrar _MENU_ registros",
    "sZeroRecords":    "No se encontraron resultados",
    "sEmptyTable":     "Ningún dato disponible en esta tabla",
    "sInfo":           "Registros del _START_ al _END_ de un total de _TOTAL_",
    "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
    "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
    "sInfoPostFix":    "",
    "sSearch":         "Buscar:",
    "sUrl":            "",
    "sInfoThousands":  ",",
    "sLoadingRecords": "Cargando...",
    "oPaginate": {
        "sFirst":    "Primero",
        "sLast":     "Último",
        "sNext":     "Siguiente",
        "sPrevious": "Anterior"
    },
    "oAria": {
        "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sSortDescending": ": Activar para ordenar la columna de manera descendente"
    }
}

function limpiar_datos(){
		$("#form_codigo").val("");
        $("#hidden_form_area").val('0');
        $("#form_area").val("");
        $("#form_foto").val("");
        $("#form_descripcion").val("");
        $("#id_equipo").val('0');
        $("#form_imagen").attr("src","img/product.png");
}

function mostrarformulario(flag)
{
    limpiar_datos();
    if (flag)
    {
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#listadoButtons").hide();
        $("#formularioButtons").show();
        $("#form_codigo").focus();
    }
    else
    {
        $("#listadoRegistros").show();
        $("#formularioRegistros").hide();
        $("#listadoButtons").show();
        $("#formularioButtons").hide();
        $("#formularioButtonsEditar").hide();
    }
}

jQuery(document).on("click", "#btn_vw_equipos_Cancelar", function(){
    limpiar_datos();
    mostrarformulario(false);
})

var aux1=0;
function autocompletar_areas(textbox){
    $.ajax({
        type: 'GET',
        url: 'equipos/0?datos=autocompletar',
        success: function (data) {
            var $datos = data;
            $("#form_area").autocomplete({
                source: $datos,
                focus: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden_" + textbox).val(ui.item.value);
                    $("#" + textbox).attr('maxlength', ui.item.label.length);
                    return false;
                },
                select: function (event, ui) {
                    $("#" + textbox).val(ui.item.label);
                    $("#hidden_" + textbox).val(ui.item.value);
                    return false;
                }
            });
        }
    });
}

jQuery(document).on("click", "#btn_vw_equipos_Guardar", function(){
    var table = $('#tblEquipos').DataTable();
    var datos = table.row('.selected').data();
    codigo = $('#form_codigo').val();
    area = $('#hidden_form_area').val();
    foto = $('#form_foto').val();
    descripcion = $('#form_descripcion').val();

    if (codigo == '') {
        mostraralertasconfoco('* EL CAMPO CODIGO ES OBLIGATORIO...', '#form_codigo');
        return false;
    }
    if (area == '0') {
        mostraralertasconfoco('* EL CAMPO AREA ES OBLIGATORIO...', '#form_area');
        return false;
    }
    if (foto == '') {
        mostraralertasconfoco('* DEBES SUBIR UNA IMAGEN...', '#form_foto');
        return false;
    }
    if (descripcion == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#form_descripcion');
        return false;
    }

    var form= new FormData($("#FormularioEquipo")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'equipos?tipo=1',
        type: 'POST',
        dataType: 'json',
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            if(data.msg === 'si')
            {
                mostraralertasconfoco('El Codigo: '+$('#form_codigo').val()+' Ya fue Registrado en el Sistema', '#form_codigo');
            }
            else
            {
                MensajeConfirmacion('Su Registro Fue Guardado Correctamente...');
                table.ajax.reload();
                mostrarformulario(false);
            }
        },
        error: function(data) {
            MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });
})

function modificar_estado(id_equipo,estado)
{
    var table = $('#tblEquipos').DataTable();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'equipos/'+id_equipo+'/edit',
        type: 'GET',
        data: {
            estado:estado,
            tipo:1
        },
        success: function(data) 
        {
            if (data > 0) 
            {
                MensajeConfirmacion('Se Modifico El estado del Registro...');
                table.ajax.reload();
            }
        },
        error: function(data) {
            mostraralertas("hubo un error, Comunicar al Administrador");
            console.log('error');
            console.log(data);
        }
    });
}

jQuery(document).on("click", "#btn_vw_equipos_Editar", function(){
    var table = $('#tblEquipos').DataTable();
    var datos = table.row('.selected').data();

    if(datos == null){
        mostraralertasconfoco("No hay ningun Registro seleccionado","#tblEquipos");
        return false;
    }else{
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#listadoButtons").hide();
        $("#formularioButtonsEditar").show();
        $.ajax({
                url: 'equipos/'+datos.id_equipo+'?show=datos_equipos',
                type: 'GET',
                beforeSend:function(){            
                    swal({
                      title: 'CARGANDO INFORMACION',
                      onOpen: function () {
                        swal.showLoading()
                      }
                    }).then(
                      function () {},
                      // handling the promise rejection
                      function (dismiss) {
                        if (dismiss === 'timer') {
                          console.log('I was closed by the timer')
                        }
                      }
                    )
                },
                success: function(data) 
                {
                    $("#id_equipo").val(data[0].id_equipo);
                    $("#form_codigo").val(data[0].codigo);
                    $("#hidden_form_area").val(data[0].id_area);
                    $("#form_area").val(data[0].area);
                    $("#form_descripcion").val(data[0].equipo);
                    $("#form_imagen").attr("src","data:image/png;base64,"+data[0].imagen);
                    swal.close();
                },
                error: function(data) {
                    mostraralertas("hubo un error, Comunicar al Administrador");
                    console.log('error');
                    console.log(data);
                }
            });
    }
})

jQuery(document).on("click", "#btn_vw_equipos_Modificar", function(){
    var table = $('#tblEquipos').DataTable();
    var datos = table.row('.selected').data();

    codigo = $('#form_codigo').val();
    area = $('#hidden_form_area').val();
    foto = $('#form_foto').val();
    descripcion = $('#form_descripcion').val();

    if (codigo == '') {
        mostraralertasconfoco('* EL CAMPO CODIGO ES OBLIGATORIO...', '#form_codigo');
        return false;
    }
    if (area == '0') {
        mostraralertasconfoco('* EL CAMPO AREA ES OBLIGATORIO...', '#form_area');
        return false;
    }
    if (descripcion == '') {
        mostraralertasconfoco('* EL CAMPO DESCRIPCION ES OBLIGATORIO...', '#form_descripcion');
        return false;
    }

    var form= new FormData($("#FormularioEquipo")[0]);
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'equipos?tipo=2',
        type: 'POST',
        dataType: 'json',
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            if(data.msg === 'si')
            {
                mostraralertasconfoco('El Codigo: '+$('#form_codigo').val()+' Ya fue Registrado en el Sistema', '#form_codigo');
            }
            else
            {
                MensajeConfirmacion('Su Registro Fue Modificado Correctamente...');
                table.ajax.reload();
                mostrarformulario(false);
            }
        },
        error: function(data) {
            MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
            console.log('error');
            console.log(data);
        }
    });
})

function validarExtensionArchivo(){
    var fileInput = document.getElementById('form_foto');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.png|\.jpg|\.jpeg)$/i;
    if(!allowedExtensions.exec(filePath)){
        let timerInterval
        swal({
          type: 'warning',
          title: 'SOLO SE PUEDEN SUBIR ARCHIVOS DE TIPO .PNG / .JPG / .JPEG',
          timer: 2000,
          showConfirmButton: false,
          onOpen: () => {
            timerInterval = setInterval(() => {
            }, 100)
          },
          onClose: () => {
            clearInterval(timerInterval)
          }
        }).then(
              function () {},
              function (dismiss) {
                if (dismiss === 'timer') {
                  console.log('I was closed by the timer')
                }
              }
            )
        fileInput.value = '';
        $("#form_imagen").attr("src","img/product.png");
        return false;
    }else{
        if (fileInput.files && fileInput.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#form_imagen").attr("src",e.target.result);
            }
            reader.readAsDataURL(fileInput.files[0]);
        }
    }
}

