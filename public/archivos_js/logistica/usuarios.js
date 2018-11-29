
jQuery(document).ready(function($){
	$("#cabecera_logistica").addClass('active');
    $("#detalle_usuarios").addClass('active');
    mostrarformulario(false);
	$("#tblUsuarios").DataTable({
		"lengthMenu": [[5, 10, 15, -1], [5, 10, 15, "Todo"]],
		"info": true,
		"ordering": true,
		"destroy":true,
		"searching": true,
		"processing" : true,
		"serverSide" : true,
		"responsive": true,
		"ajax": "usuarios/0?tabla=usuarios",
		"columns":[
			{data: 'id'},
            {data: 'dni',class:'text-center'},
			{data: 'persona',class:'text-center'},
            {data: 'foto',class:'text-center',searchable: false},
            {data: 'email',class:'text-center'},
            {data: 'cargo',class:'text-center'},
            {data: 'usuario',class:'text-center'},
            {data: 'estado',class:'text-center'},
		],
        "columnDefs": [
            {
                  "targets": [7], 
                  "data": "estado", 
                  "render": function(data, type, row) {
                    
                    if (data == 0) {
                      return "<center><button type='button' onClick='cambiar_estado("+row.id+","+1+")' class='btn btn-danger btn-lg'><span class='btn-label'><i class='fa fa-close'></i></span> INACTIVO </button></center>";
                    }else if (data == 1) {
                      return "<center><button type='button' onClick='cambiar_estado("+row.id+","+0+")' class='btn btn-success btn-lg'><span class='btn-label'><i class='fa fa-check'></i></span> ACTIVO </button></center>";
                    }
                }
            },
            {
                  "targets": [3], 
                  "data": "foto", 
                  "render": function(data, type, row) {
                    
                    return "<td><img src='data:image/png;base64,"+data+"' style='max-height:130px; max-width:130px'></td>";
                }
            }
        ],
        "order": [[ 0, "desc" ]],
		"language" : idioma_espanol,
		"select": true
	});
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
    $('#id_usuario').val('0');
    $('#form_dni').val("");
    $('#form_nombres').val("");
    $('#form_apellidos').val("");
    $('#form_usuario').val("");
    $('#form_email').val("");
    $('#form_cargo').val('USUARIO');
    $('#form_password').val("");
    $("#form_foto").attr("src","img/male.png");
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
        $("#form_dni").focus();
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

jQuery(document).on("click", "#btn_vw_usuarios_Cancelar", function(){
    limpiar_datos();
    mostrarformulario(false);
})

jQuery(document).on("click", "#btn_buscar", function(){
    consultar_dni($("#form_dni").val(),'form_nombres','form_apellidos');
})

$("#form_dni").keypress(function (e) {
    if (e.which == 13) {
       consultar_dni($("#form_dni").val(),'form_nombres','form_apellidos');
    }
});

function consultar_dni(dni,nombres,apellidos)
{
    if (dni == '') {
        mostraralertasconfoco('* EL CAMPO DNI ES OBLIGATORIO...', '#form_dni');
        return false;
    }
    if (dni.length <= 7) 
    {
        mostraralertasconfoco('* EL CAMPO DNI DEBE CONTENER 8 NUMEROS...', '#form_dni');
        return false;
    }

    $.ajax({
        url: 'usuarios/'+dni+'?show=recuperar_dni',
        type: 'GET',
        beforeSend:function(){            
            swal({
              title: 'BUSCANDO DNI',
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
        success: function (data) {
            if (data) {
                $("#"+nombres).val(data.nombres);
                $("#"+apellidos).val(data.apellidos);
                swal.close();
            } else {
                buscar_datos_reniec(dni);
            }
        },
        error: function (data) {
            MensajeAdvertencia('* Error de Red...<br>* Contactese con el Administrador...');
        }
    });
}

function buscar_datos_reniec(dni)
{   
    $.ajax({
        type: 'GET',
        url: 'usuarios/0?datos=buscar_datos_reniec&nro_doc='+dni, 
        beforeSend:function(){            
            swal({
              title: 'BUSCANDO DNI',
              title: 'EFECTUANDO BUSQUEDA EN RENIEC',
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
        success: function (data) {
            $("#form_nombres").val(data.nombres);
            $("#form_apellidos").val(data.ape_pat + ' ' +data.ape_mat);
            $("#form_foto").attr("src",data.foto);
            swal.close();
        },
        error: function (data){            
            MensajeAdvertencia('* No se Encontró el DNI<br>* Porfavor Ingrese los Datos Manualmente...');            
        }
    });
}

jQuery(document).on("click", "#btn_vw_usuarios_Guardar", function(){

    var table = $('#tblUsuarios').DataTable();
    dni = $('#form_dni').val();
    nombres = $('#form_nombres').val();
    apellidos = $('#form_apellidos').val();
    usuario = $('#form_usuario').val();
    email = $('#form_email').val();
    cargo = $('#form_cargo').val();
    password = $('#form_password').val();

    if (dni == '') {
        mostraralertasconfoco('* EL CAMPO DNI ES OBLIGATORIO...', '#form_dni');
        return false;
    }
    if (nombres == '') {
        mostraralertasconfoco('* EL CAMPO NOMBRES ES OBLIGATORIO...', '#form_nombres');
        return false;
    }
    if (apellidos == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDOS ES OBLIGATORIO...', '#form_apellidos');
        return false;
    }
    if (usuario == '') {
        mostraralertasconfoco('* EL CAMPO USUARIO ES OBLIGATORIO...', '#form_usuario');
        return false;
    }
    if (password == '') {
        mostraralertasconfoco('* EL CAMPO PASSWORD ES OBLIGATORIO...', '#form_password');
        return false;
    }

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'usuarios',
        type: 'POST',
        data: {
            nombres: $('#form_nombres').val() || '-',
            apellidos: $('#form_apellidos').val() || '-',
            email: $('#form_email').val() || '-',
            password: $('#form_password').val(),
            cargo: $('#form_cargo').val() || '-',
            usuario: $('#form_usuario').val() || '-',
            dni: $('#form_dni').val() || '-',
            foto: $("#form_foto").attr("src"),
            tipo:1
        },
        success: function (data) {
            if(data.msg === 'si')
            {
                mostraralertasconfoco('El DNI: '+$('#form_dni').val()+' Ya fue Registrado en el Sistema', '#form_dni');
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

jQuery(document).on("click", "#btn_vw_usuarios_Editar", function(){
    var table = $('#tblUsuarios').DataTable();
    var datos = table.row('.selected').data();

    if(datos == null){
        mostraralertasconfoco("No hay ningun Registro seleccionado","#tblUsuarios");
        return false;
    }else{
        $("#listadoRegistros").hide();
        $("#formularioRegistros").show();
        $("#listadoButtons").hide();
        $("#formularioButtonsEditar").show();

        $.ajax({
            url: 'usuarios/'+datos.id+'?show=datos_usuarios',
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
                $("#id_usuario").val(data[0].id);
                $("#form_dni").val(data[0].dni);
                $("#form_nombres").val(data[0].nombres);
                $("#form_apellidos").val(data[0].apellidos);
                $("#form_usuario").val(data[0].usuario);
                $("#form_cargo").val(data[0].cargo);
                $("#form_email").val(data[0].email);
                $("#form_foto").attr("src","data:image/png;base64,"+data[0].foto);
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

jQuery(document).on("click", "#btn_vw_usuarios_Modificar", function(){
    var table = $('#tblUsuarios').DataTable();
    var datos = table.row('.selected').data();

    dni = $('#form_dni').val();
    nombres = $('#form_nombres').val();
    apellidos = $('#form_apellidos').val();
    usuario = $('#form_usuario').val();
    email = $('#form_email').val();
    cargo = $('#form_cargo').val();
    password = $('#form_password').val();

    if (dni == '') {
        mostraralertasconfoco('* EL CAMPO DNI ES OBLIGATORIO...', '#form_dni');
        return false;
    }
    if (nombres == '') {
        mostraralertasconfoco('* EL CAMPO NOMBRES ES OBLIGATORIO...', '#form_nombres');
        return false;
    }
    if (apellidos == '') {
        mostraralertasconfoco('* EL CAMPO APELLIDOS ES OBLIGATORIO...', '#form_apellidos');
        return false;
    }
    if (usuario == '') {
        mostraralertasconfoco('* EL CAMPO USUARIO ES OBLIGATORIO...', '#form_usuario');
        return false;
    }

    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'usuarios',
        type: 'POST',
        data: {
            id_usuario: $('#id_usuario').val(),
            nombres: $('#form_nombres').val() || '-',
            apellidos: $('#form_apellidos').val() || '-',
            email: $('#form_email').val() || '-',
            password: $('#form_password').val(),
            cargo: $('#form_cargo').val() || '-',
            usuario: $('#form_usuario').val() || '-',
            dni: $('#form_dni').val(),
            foto: $("#form_foto").attr("src"),
            tipo:2
        },
        success: function (data) {
            if(data.msg === 'si')
            {
                mostraralertasconfoco('El DNI: '+$('#form_dni').val()+' Ya fue Registrado en el Sistema', '#form_dni');
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

function cambiar_estado(id_usuario,estado)
{
    var table = $('#tblUsuarios').DataTable();
    $.ajax({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        url: 'usuarios/'+id_usuario+'/edit',
        type: 'GET',
        data: {
            estado:estado,
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