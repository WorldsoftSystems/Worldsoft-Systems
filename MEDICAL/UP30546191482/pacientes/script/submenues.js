function formatDate(dateString) {
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}


//EGRESO

// Función para cargar el modal de egreso y ocultar el formulario principal
function loadEgresoModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/egreso/egreso.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('egresoModalBody').innerHTML = response;
            $('#egresoModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });

    $.ajax({
        url: './dato/get_modalidad_sin_egreso_paci.php',
        type: 'GET',
        dataType: 'json',
        data: { id_paciente: id }, // Aquí pasas el id_paciente en la solicitud
        success: function (data) {
            $('#egreso_modalidad').empty(); // Limpia las opciones previas si es necesario

            // Agregar opción de placeholder "Seleccionar"
            $('#egreso_modalidad').append(new Option('Seleccionar', ''));

            data.forEach(function (item) {
                var optionText = item.modalidad_full;
                var optionValue = item.modalidad; // Esto toma el valor de 'modalidad'
                $('#egreso_modalidad').append(new Option(optionText, optionValue)); // Agrega la opción
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });




}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#egresoModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#egresoModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaEgresos(idPaciente) {



    $.ajax({
        url: './dato/get_egreso.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var egresos = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha Egreso</th>';
            html += '<th>Modalidad</th>';
            html += '<th>Motivo</th>';
            html += '<th>Diagnóstico</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            egresos.forEach(function (egreso) {
                html += '<tr>';
                html += '<td>' + formatDate(egreso.fecha_egreso) + '</td>';
                html += '<td>' + (egreso.modalidad_full ? egreso.modalidad_full : '') + '</td>';
                html += '<td>' + egreso.egreso_full + '</td>';
                html += '<td>' + egreso.diag_full + '</td>';
                html += '<td>';
                html += '<button id="editEgreso" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + egreso.id + '">Editar</button> ';
                html += '<button id="deleteEgreso" class="btn btn-danger btn-sm btn-delete" data-id="' + egreso.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaEgresos').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#egresoModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaEgresos(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#egresoModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoEgreso').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#egresoFecha').val('');
        $('#egreso_modalidad').val('');
        $('#egreso_diag').val('');
        $('#egreso_motivo').val('');
        $('#egresoIdPaciente').val(id);
        $('#egresoNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarEgreso').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarEgresoModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editEgreso', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_egreso_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var egreso = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#egresoId').val(egreso.id);
                $('#egresoIdPaciente').val(egreso.id_paciente);
                $('#egresoNombreCarga').val(egreso.nombre_paciente);
                $('#egresoFecha').val(egreso.fecha_egreso);
                $('#egreso_modalidad').val(egreso.modalidad);
                $('#egreso_diag').val(egreso.diag);
                $('#egreso_motivo').val(egreso.motivo);

                // Establecer data-action a "edit"
                $('#btnGuardarEgreso').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarEgresoModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar el egreso al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarEgreso').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/egreso/editar_egreso.php' : './submenu/egreso/agregar_egreso.php';
        var formData = $('#formAgregarEgreso').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const result = JSON.parse(response); // Asumiendo que la respuesta es JSON

                // Mostrar mensaje de éxito o error según la respuesta del servidor
                if (result.status === 'success') {
                    alert(result.message); // Mostrar mensaje de éxito
                    const idPaciente = $('#id').val();
                    cargarListaEgresos(idPaciente);
                    $('#agregarEgresoModal').modal('hide');

                    // Verificar si el paciente está egresado
                    $.ajax({
                        url: './dato/verificar_egreso.php',
                        type: 'GET',
                        data: { id_paciente: idPaciente },
                        success: function (response) {
                            const data = JSON.parse(response);
                            if (data.egresado) {
                                $('#bajaMensaje').html('<h1 style="color: red !important;">PACIENTE EGRESADO</h1>');
                            } else {
                                $('#bajaMensaje').html('');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log('Error en verificar egreso:', textStatus, errorThrown);
                        }
                    });

                    $.ajax({
                        url: './dato/get_modalidad_sin_egreso_paci.php',
                        type: 'GET',
                        dataType: 'json',
                        data: { id_paciente: idPaciente }, // Aquí pasas el id_paciente en la solicitud
                        success: function (data) {
                            $('#egreso_modalidad').empty(); // Limpia las opciones previas si es necesario

                            // Agregar opción de placeholder "Seleccionar"
                            $('#egreso_modalidad').append(new Option('Seleccionar', ''));

                            data.forEach(function (item) {
                                var optionText = item.modalidad_full;
                                var optionValue = item.modalidad; // Esto toma el valor de 'modalidad'
                                $('#egreso_modalidad').append(new Option(optionText, optionValue)); // Agrega la opción
                            });
                        },
                        error: function (error) {
                            console.error("Error fetching data: ", error);
                        }
                    });
                } else {
                    alert(result.message); // Mostrar mensaje de error
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar egreso:', textStatus, errorThrown);
                alert('Error al guardar el egreso');
            }
        });
    });


    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteEgreso', function () {
        var pracId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este egreso?')) {
            $.ajax({
                url: './submenu/egreso/borrar_egreso.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Egreso eliminado:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaEgresos(idPaciente);

                    $.ajax({
                        url: './dato/verificar_egreso.php',
                        type: 'GET',
                        data: { id_paciente: idPaciente },
                        success: function (response) {
                            const data = JSON.parse(response);
                            if (data.egresado) {
                                $('#bajaMensaje').html('<h1 style="color: red !important;">PACIENTE EGRESADO</h1>');
                            } else {
                                $('#bajaMensaje').html('');
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log('Error en verificar egreso:', textStatus, errorThrown);
                        }
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar el egreso:', textStatus, errorThrown);
                }
            });
        }
    });

});

//FIN EGRESO

//RESPONSABLES
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadResponsablesModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/respon/respon.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('responModalBody').innerHTML = response;
            $('#responModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#responModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#responModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaResponsables(idPaciente) {



    $.ajax({
        url: './dato/get_respon.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var respons = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Nombre</th>';
            html += '<th>Telefono</th>';
            html += '<th>Parentesco</th>';
            html += '<th>DNI</th>';
            html += '<th>Domicilio</th>';
            html += '<th>Localidad</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            respons.forEach(function (respon) {
                html += '<tr>';
                html += '<td>' + respon.nombreYapellido + '</td>';
                html += '<td>' + respon.tel + '</td>';
                html += '<td>' + respon.familiar_full + '</td>';
                html += '<td>' + respon.dni + '</td>';
                html += '<td>' + respon.dom + '</td>';
                html += '<td>' + respon.localidad + '</td>';
                html += '<td>';
                html += '<button id="editRespon" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + respon.id + '">Editar</button> ';
                html += '<button id="deleteRespon" class="btn btn-danger btn-sm btn-delete" data-id="' + respon.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaRespon').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#responModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaResponsables(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#responModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoRespon').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria

        $('#respon_nombre').val('');
        $('#respon_tel').val('');
        $('#respon_parent').val('');
        $('#respon_dni').val('');
        $('#respon_dom').val('');
        $('#respon_locali').val('');
        $('#responIdPaciente').val(id);
        $('#responNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarRespon').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarResponModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editRespon', function () {
        var pracId = $(this).data('id');



        $.ajax({
            url: './dato/get_respon_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var respon = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#responId').val(respon.id);
                $('#responIdPaciente').val(respon.id_paciente);
                $('#responNombreCarga').val(respon.nombre_paciente);
                $('#respon_nombre').val(respon.nombreYapellido);
                $('#respon_tel').val(respon.tel);
                $('#respon_parent').val(respon.tipo_familiar);
                $('#respon_dni').val(respon.dni);
                $('#respon_dom').val(respon.dom);
                $('#respon_locali').val(respon.localidad)

                // Establecer data-action a "edit"
                $('#btnGuardarRespon').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarResponModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarRespon').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/respon/editar_respon.php' : './submenu/respon/agregar_respon.php';
        var formData = $('#formAgregarRespon').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaResponsables(idPaciente);
                $('#agregarResponModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar responsable:', textStatus, errorThrown);
                alert('Error al guardar el responsable');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteRespon', function () {
        var pracId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este responsable?')) {
            $.ajax({
                url: './submenu/respon/borrar_respon.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaResponsables(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar el egreso:', textStatus, errorThrown);
                }
            });
        }
    });

});


//FIN RESPONSABLES

//JUDICIALES
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadJudicialesModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/judiciales/judiciales.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('judiModalBody').innerHTML = response;
            $('#judiModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#judiModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#judiModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaJudiciales(idPaciente) {

    $.ajax({
        url: './dato/get_judi.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var judis = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem; ">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Juzgado</th>';
            html += '<th>Secretaria</th>';
            html += '<th>Curaduria</th>';
            html += '<th>Tipo Juicio</th>';
            html += '<th>Vencimiento</th>';
            html += '<th>Observaciones</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            judis.forEach(function (judi) {
                html += '<tr>';
                html += '<td>' + judi.juz_full + '</td>';
                html += '<td>' + judi.secre_full + '</td>';
                html += '<td>' + judi.cura_full + '</td>';
                html += '<td>' + judi.t_full + '</td>';
                html += '<td>' + judi.vencimiento + '</td>';
                html += '<td>' + judi.obs + '</td>';
                html += '<td>';
                html += '<button id="editJudi" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + judi.id + '">Editar</button> ';
                html += '<button id="deleteJudi" class="btn btn-danger btn-sm btn-delete" data-id="' + judi.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaJudi').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#judiModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaJudiciales(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#judiModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoJudi').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#juzgado').val('');
        $('#secretaria').val('');
        $('#curaduria').val('');
        $('#t_juicio').val('');
        $('#judiFecha').val('');
        $('#judiObs').val('');
        $('#judiIdPaciente').val(id);
        $('#judiNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarJudi').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarJudiModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editJudi', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_judi_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var judi = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#judiId').val(judi.id);
                $('#judiIdPaciente').val(judi.id_paciente);
                $('#judiNombreCarga').val(judi.nombre_paciente);
                $('#juzgado').val(judi.juzgado);
                $('#secretaria').val(judi.secre);
                $('#curaduria').val(judi.cura);
                $('#t_juicio').val(judi.t_juicio);
                $('#judiFecha').val(judi.vencimiento);
                $('#judiObs').val(judi.obs);

                // Establecer data-action a "edit"
                $('#btnGuardarJudi').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarJudiModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarJudi').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/judiciales/editar_judiciales.php' : './submenu/judiciales/agregar_judiciales.php';
        var formData = $('#formAgregarJudi').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('respuesta judi:', response);
                const idPaciente = $('#id').val();
                cargarListaJudiciales(idPaciente);
                $('#agregarJudiModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteJudi', function () {
        var pracId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar esta práctica?')) {
            $.ajax({
                url: './submenu/judiciales/borrar_judi.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaJudiciales(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

//FIN JUDICIALES

//SALIDAS
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadSalidasModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/salida/salida.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('saliModalBody').innerHTML = response;
            $('#saliModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#saliModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#saliModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaSalidas(idPaciente) {

    $.ajax({
        url: './dato/get_sali.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var salidas = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha Salida</th>';
            html += '<th>Fecha Llegada</th>';
            html += '<th>Oberservaciones</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            salidas.forEach(function (salida) {
                html += '<tr>';
                html += '<td>' + formatDate(salida.fecha_salida) + '</td>';
                html += '<td>' + formatDate(salida.fecha_llegada) + '</td>';
                html += '<td>' + salida.obs + '</td>';
                html += '<td>';
                html += '<button id="editSali" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + salida.id + '">Editar</button> ';
                html += '<button id="deleteSali" class="btn btn-danger btn-sm btn-delete" data-id="' + salida.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaSali').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#saliModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaSalidas(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#saliModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoSali').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#salida_fecha').val('');
        $('#llegada_fecha').val('');
        $('#saliObs').val('');
        $('#saliIdPaciente').val(id);
        $('#saliNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarSali').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarSaliModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editSali', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_salida_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var salida = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#saliId').val(salida.id);
                $('#saliIdPaciente').val(salida.id_paciente);
                $('#saliNombreCarga').val(salida.nombre_paciente);
                $('#salida_fecha').val(salida.fecha_salida);
                $('#llegada_fecha').val(salida.fecha_llegada);
                $('#saliObs').val(salida.obs);

                // Establecer data-action a "edit"
                $('#btnGuardarSali').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarSaliModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarSali').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/salida/editar_salida.php' : './submenu/salida/agregar_salida.php';
        var formData = $('#formAgregarSali').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaSalidas(idPaciente);
                $('#agregarSaliModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteSali', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar esta práctica?')) {
            $.ajax({
                url: './submenu/salida/borrar_salida.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Salida eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaSalidas(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

//FIN SALIDAS

//HABITACIONES
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadHabitacionesModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/habitacion/habitacion.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('habiModalBody').innerHTML = response;
            $('#habiModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#habiModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#habiModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaHabitacion(idPaciente) {

    $.ajax({
        url: './dato/get_habitacion.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var habis = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Habitacion</th>';
            html += '<th>Piso/Sala</th>';
            html += '<th>Fecha Llegada</th>';
            html += '<th>Fecha Salida</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            habis.forEach(function (habi) {
                html += '<tr>';
                html += '<td>' + habi.num_habitacion + '</td>';
                html += '<td>' + habi.piso_habitacion + '</td>';
                html += '<td>' + formatDate(habi.fecha_ingreso) + '</td>';
                html += '<td>' + formatDate(habi.fecha_egreso) + '</td>';
                html += '<td>';
                html += '<button id="editHabi" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + habi.id + '">Editar</button> ';
                html += '<button id="deleteHabi" class="btn btn-danger btn-sm btn-delete" data-id="' + habi.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaHabi').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#habiModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaHabitacion(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#habiModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaHabi').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#habitacion_nro').val('');
        $('#habi_ingreso_fecha').val('');
        $('#habi_egreso_fecha').val('');
        $('#habiIdPaciente').val(id);
        $('#habiNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarHabi').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarHabiModal').modal('show');


    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editHabi', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_habitacion_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var habi = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#habiId').val(habi.id);
                $('#habiIdPaciente').val(habi.id_paciente);
                $('#habiNombreCarga').val(habi.nombre_paciente);
                $('#habitacion_nro').val(habi.habitacion);
                $('#habi_ingreso_fecha').val(habi.fecha_ingreso);
                $('#habi_egreso_fecha').val(habi.fecha_egreso);

                // Establecer data-action a "edit"
                $('#btnGuardarHabi').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarHabiModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarHabi').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/habitacion/editar_habitacion.php' : './submenu/habitacion/agregar_habitacion.php';
        var formData = $('#formAgregarHabi').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaHabitacion(idPaciente);
                $('#agregarHabiModal').modal('hide');
                mostrarHabitacionesDisponibles();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });




    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteHabi', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar esta habitacion?')) {
            $.ajax({
                url: './submenu/habitacion/borrar_habitacion.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaHabitacion(idPaciente);
                    mostrarHabitacionesDisponibles();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }

    });

});

//FIN HABITACIONES


//VISITAS
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadVisitasModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/visita/visita.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('visiModalBody').innerHTML = response;
            $('#visiModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#visiModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#visiModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaVisita(idPaciente) {

    $.ajax({
        url: './dato/get_visita.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var visitas = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Apellido Y Nombre</th>';
            html += '<th>Parentesco</th>';
            html += '<th>Observaciones</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            visitas.forEach(function (visita) {
                html += '<tr>';
                html += '<td>' + formatDate(visita.fecha) + '</td>';
                html += '<td>' + visita.nom + '</td>';
                html += '<td>' + visita.familiar_full + '</td>';
                html += '<td>' + visita.obs + '</td>';
                html += '<td>';
                html += '<button id="editVisi" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + visita.id + '">Editar</button> ';
                html += '<button id="deleteVisi" class="btn btn-danger btn-sm btn-delete" data-id="' + visita.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaVisi').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#visiModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaVisita(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#visiModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaVisi').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#visita_fecha').val('');
        $('#visita_nom').val('');
        $('#visita_parent').val('');
        $('#visita_obs').val('');
        $('#visiIdPaciente').val(id);
        $('#visiNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarVisi').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarVisiModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editVisi', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_visita_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var visita = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#visiId').val(visita.id);
                $('#visiIdPaciente').val(visita.id_paciente);
                $('#visiNombreCarga').val(visita.nombre_paciente);
                $('#visita_fecha').val(visita.fecha);
                $('#visita_nom').val(visita.nom);
                $('#visita_parent').val(visita.tipo_familiar);
                $('#visita_obs').val(visita.obs);

                // Establecer data-action a "edit"
                $('#btnGuardarVisi').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarVisiModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarVisi').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/visita/editar_visita.php' : './submenu/visita/agregar_visita.php';
        var formData = $('#formAgregarVisi').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaVisita(idPaciente);
                $('#agregarVisiModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteVisi', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar esta visita?')) {
            $.ajax({
                url: './submenu/visita/borrar_visita.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Salida eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaVisita(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});


//FIN VISITAS


//TRASLADOS
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadTrasladosModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/traslados/traslados.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('trasModalBody').innerHTML = response;
            $('#trasModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#trasModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#trasModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaTraslados(idPaciente) {

    $.ajax({
        url: './dato/get_traslados.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var traslados = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Hora</th>';
            html += '<th>Importe</th>';
            html += '<th>Observaciones</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            traslados.forEach(function (traslado) {
                html += '<tr>';
                html += '<td>' + formatDate(traslado.fecha) + '</td>';
                html += '<td>' + traslado.hora + '</td>';
                html += '<td>' + traslado.importe + '</td>';
                html += '<td>' + traslado.obs + '</td>';
                html += '<td>';
                html += '<button id="editTras" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + traslado.id + '">Editar</button> ';
                html += '<button id="deleteTras" class="btn btn-danger btn-sm btn-delete" data-id="' + traslado.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaTras').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#trasModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaTraslados(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#trasModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoTras').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#tras_fecha').val('');
        $('#tras_hora').val('');
        $('#tras_importe').val('');
        $('#tras_obs').val('');
        $('#trasIdPaciente').val(id);
        $('#trasNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarTras').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarTrasModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editTras', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_traslado_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var tras = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#trasId').val(tras.id);
                $('#trasIdPaciente').val(tras.id_paciente);
                $('#trasNombreCarga').val(tras.nombre_paciente);
                $('#tras_fecha').val(tras.fecha);
                $('#tras_hora').val(tras.hora);
                $('#tras_importe').val(tras.importe);
                $('#tras_obs').val(tras.obs);

                // Establecer data-action a "edit"
                $('#btnGuardarTras').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarTrasModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarTras').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/traslados/editar_traslados.php' : './submenu/traslados/agregar_traslados.php';
        var formData = $('#formAgregarTras').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaTraslados(idPaciente);
                $('#agregarTrasModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteTras', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/traslados/borrar_traslados.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Traslado eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaTraslados(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

//FIN TRASLADOS

//DIAG
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadDiagnosticoModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/diag/diag.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('diagModalBody').innerHTML = response;
            $('#diagModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#diagModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#diagModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaDiag(idPaciente) {

    $.ajax({
        url: './dato/get_diag.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var diags = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Codigo</th>';
            html += '<th>Descripcion</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            diags.forEach(function (diag) {
                html += '<tr>';
                html += '<td>' + formatDate(diag.fecha) + '</td>';
                html += '<td>' + diag.diag_cod + '</td>';
                html += '<td>' + diag.diag_desc + '</td>';
                html += '<td>';
                html += '<button id="editDiag" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + diag.id + '">Editar</button> ';
                html += '<button id="deleteDiag" class="btn btn-danger btn-sm btn-delete" data-id="' + diag.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaDiag').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#diagModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaDiag(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#diagModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaDiag').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#diagFecha').val('');
        $('#paci_diag').val('');
        $('#diagIdPaciente').val(id);
        $('#diagNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarDiag').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarDiagModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editDiag', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_diag_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var diag = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#diagId').val(diag.id);
                $('#diagIdPaciente').val(diag.id_paciente);
                $('#diagNombreCarga').val(diag.nombre_paciente);
                $('#diagFecha').val(diag.fecha);
                $('#paci_diag').val(diag.codigo);

                // Establecer data-action a "edit"
                $('#btnGuardarDiag').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarDiagModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarDiag').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/diag/editar_diag.php' : './submenu/diag/agregar_diag.php';
        var formData = $('#formAgregarDiag').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('Diagnostico:', response);
                const idPaciente = $('#id').val();
                cargarListaDiag(idPaciente);
                $('#agregarDiagModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar Diagnostico:', textStatus, errorThrown);
                alert('Error al guardar el diag');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteDiag', function () {
        var pracId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este diagnostico?')) {
            $.ajax({
                url: './submenu/diag/borrar_diag.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Diagnostico eliminado:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaDiag(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar el egreso:', textStatus, errorThrown);
                }
            });
        }
    });

});


//FIN DIAG


//MODALIDAD
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadModalidadesModal() {
    const id = document.getElementById('id').value;
    console.log(id)
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/modalidad/modalidad.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('modaModalBody').innerHTML = response;
            $('#modaModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#modaModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#modaModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaModalidad(idPaciente) {

    $.ajax({
        url: './dato/get_modalidad_paci.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var modalidades = JSON.parse(response);

            // Encontrar la fecha más baja
            var fechas = modalidades.map(modalidad => new Date(modalidad.fecha));
            var fechaMasBaja = new Date(Math.min.apply(null, fechas));

            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Modalidad</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            modalidades.forEach(function (modalidad) {
                var fechaActual = new Date(modalidad.fecha);
                var esFechaMasBaja = fechaActual.getTime() === fechaMasBaja.getTime();

                html += '<tr>';
                html += '<td>' + formatDate(modalidad.fecha) + '</td>';
                html += '<td>' + modalidad.modalidad_full + '</td>';
                html += '<td>';

                if (esFechaMasBaja) {
                    // Si es la fecha más baja, no mostrar el botón de editar
                } else {
                    // Mostrar ambos botones
                    html += '<button id="editModali" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + modalidad.id + '">Editar</button> ';
                    html += '<button id="deleteModali" class="btn btn-danger btn-sm btn-delete" data-id="' + modalidad.id + '">Eliminar</button>';
                }

                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaModa').html(html); // Insertar el HTML generado en el contenedor
        }
    });

}

// Evento al mostrar el modal de egresos
$('#modaModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaModalidad(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#modaModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaModali').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#modaliFecha').val('');
        $('#modalidad_paci').val('');
        $('#modaliIdPaciente').val(id);
        $('#modaliNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarModali').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarModaliModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editModali', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_paci_modalidad_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var paci_modalidad = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#modaliId').val(paci_modalidad.id);
                $('#modaliIdPaciente').val(paci_modalidad.id_paciente);
                $('#modaliNombreCarga').val(paci_modalidad.nombre_paciente);
                $('#modaliFecha').val(paci_modalidad.fecha);
                $('#modalidad_paci').val(paci_modalidad.modalidad);

                $.ajax({
                    url: './dato/verificar_egreso.php',
                    type: 'GET',
                    data: { id_paciente: $('#id').val() },
                    success: function (response) {
                        const data = JSON.parse(response);
                        if (data.egresado) {
                            $('#bajaMensaje').html('<h1 style="color: red !important;">PACIENTE EGRESADO</h1>');
                        } else {
                            $('#bajaMensaje').html('');
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Error en verificar egreso:', textStatus, errorThrown);
                    }
                });

                // Establecer data-action a "edit"
                $('#btnGuardarModali').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarModaliModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarModali').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/modalidad/editar_modalidad.php' : './submenu/modalidad/agregar_modalidad.php';
        var formData = $('#formAgregarModali').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                var jsonResponse = JSON.parse(response);

                // Solo mostramos el mensaje de la respuesta
                alert(jsonResponse.message);

                const idPaciente = $('#id').val();
                cargarListaModalidad(idPaciente);
                $('#agregarModaliModal').modal('hide');

                // Actualizar solo el campo de fecha de admisión
                actualizarFechaAdmision(idPaciente);

                $.ajax({
                    url: './dato/verificar_egreso.php',
                    type: 'GET',
                    data: { id_paciente: $('#id').val() },
                    success: function (response) {
                        const data = JSON.parse(response);
                        if (data.egresado) {
                            $('#bajaMensaje').html('<h1 style="color: red !important;">PACIENTE EGRESADO</h1>');
                        } else {
                            $('#bajaMensaje').html('');
                        }

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log('Error en verificar egreso:', textStatus, errorThrown);
                    }
                });

                $.ajax({
                    url: './dato/get_modalidad_paci_id.php',
                    type: 'GET',
                    dataType: 'json',
                    data: { id_paciente: $('#id').val() },
                    success: function (data) {
                        // Limpia las opciones actuales del select
                        $('#modalidad_act').val(data[0]?.id || '').trigger('change');
                    },
                    error: function (xhr, status, error) {
                    }
                });

            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });







    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteModali', function () {
        var pracId = $(this).data('id');
        console.log('Botón Eliminar clickeado');
        if (confirm('¿Estás seguro de que deseas eliminar esta modalidad?')) {
            $.ajax({
                url: './submenu/modalidad/borrar_modalidad.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaModalidad(idPaciente);

                    $.ajax({
                        url: './dato/verificar_egreso.php',
                        type: 'GET',
                        data: { id_paciente: $('#id').val() },
                        success: function (response) {
                            const data = JSON.parse(response);
                            if (data.egresado) {
                                $('#bajaMensaje').html('<h1 style="color: red !important;">PACIENTE EGRESADO</h1>');
                            } else {
                                $('#bajaMensaje').html('');
                            }

                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log('Error en verificar egreso:', textStatus, errorThrown);
                        }
                    });

                    $.ajax({
                        url: './dato/get_modalidad_paci_id.php',
                        type: 'GET',
                        dataType: 'json',
                        data: { id_paciente: $('#id').val() },
                        success: function (data) {
                            // Limpia las opciones actuales del select
                            $('#modalidad_act').val(data[0]?.id || '').trigger('change');
                        },
                        error: function (xhr, status, error) {
                            console.error("Error fetching data: ", error);
                        }
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

// Función para actualizar el campo de fecha de admisión
function actualizarFechaAdmision(idPaciente) {
    $.ajax({
        url: './dato/get_fecha_admision.php', // Archivo PHP para obtener la fecha de admisión
        type: 'GET',
        data: { id: idPaciente },
        dataType: 'json',
        success: function (data) {
            if (data && data.fecha_admision) {
                $('#admision').val(data.fecha_admision);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error en recargar fecha de admisión:', textStatus, errorThrown);
        }
    });
}

//FIN MODALIDAD


//PRACTICA
// Función para cargar el modal de prácticas
function loadPracticasModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/practicas/practicas.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('pracModalBody').innerHTML = response;
            $('#pracModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error en loadPracticasModal:', textStatus, errorThrown);
        }
    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#pracModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#pracModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaPracticas(idPaciente, page = 1, recordsPerPage = 100) {
    $.ajax({
        url: './dato/get_practicas.php',
        type: 'GET',
        data: {
            id_paciente: idPaciente,
            page: page,
            records_per_page: recordsPerPage
        },
        success: function (response) {
            var data = JSON.parse(response);
            var practicas = data.practicas;
            var totalRecords = data.totalRecords;
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Hora</th>';
            html += '<th>Profesional</th>';
            html += '<th>Práctica</th>';
            html += '<th>Cantidad</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            practicas.forEach(function (practica) {
                html += '<tr>';
                html += '<td>' + formatDate(practica.fecha) + '</td>';
                html += '<td>' + practica.hora + '</td>';
                html += '<td>' + practica.prof_full + '</td>';
                html += '<td>' + practica.act_full + '</td>';
                html += '<td>' + practica.cant + '</td>';
                html += '<td>';
                html += '<button id="editPrac" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + practica.id + '">Editar</button> ';
                html += '<button id="deletePrac" class="btn btn-danger btn-sm btn-delete" data-id="' + practica.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaPrac').html(html); // Insertar el HTML generado en el contenedor

            // Paginación
            var totalPages = Math.ceil(totalRecords / recordsPerPage);
            var paginationHtml = '<nav aria-label="Page navigation"><ul class="pagination">';
            for (var i = 1; i <= totalPages; i++) {
                paginationHtml += '<li class="page-item ' + (i === page ? 'active' : '') + '"><a class="page-link" href="#" data-page="' + i + '">' + i + '</a></li>';
            }
            paginationHtml += '</ul></nav>';
            $('#pagination').html(paginationHtml);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error en cargarListaPracticas:', textStatus, errorThrown);
        }
    });
}

// Controlar el cambio en la cantidad de registros por página
$(document).on('change', '#recordsPerPage', function () {
    var recordsPerPage = $(this).val();
    const idPaciente = document.getElementById('id').value;
    cargarListaPracticas(idPaciente, 1, recordsPerPage); // Recargar la tabla con la nueva cantidad de registros por página
});

// Controlar el cambio de página
$(document).on('click', '#pagination .page-link', function (e) {
    e.preventDefault();
    var page = $(this).data('page');
    var recordsPerPage = $('#recordsPerPage').val();
    const idPaciente = document.getElementById('id').value;
    cargarListaPracticas(idPaciente, page, recordsPerPage); // Recargar la tabla con la página seleccionada
});


// Evento al mostrar el modal de egresos
$('#pracModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaPracticas(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#pracModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaPrac').on('click', function () {
        console.log('Botón "Agregar" clickeado');
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#pracFechas').val('');
        $('#pracHora').val('');
        $('#pracProfesional').val('');
        $('#pracActividad').val('');
        $('#pracCantidad').val('');
        $('#pracIdPaciente').val(id);
        $('#pracNombreCarga').val(nombre);

        $('#pracFechas').datepicker('clearDates');

        // Establecer data-action a "add"
        $('#btnGuardarPractica').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarPracModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editPrac', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_practica_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var practica = JSON.parse(response);

                // Asumir que la fecha está en formato YYYY-MM-DD
                var fechaStr = practica.fecha; // Ejemplo: '2024-09-06'

                // Convertir la fecha en formato YYYY-MM-DD a un objeto Date
                var fecha = new Date(fechaStr + 'T00:00:00'); // Añadir una hora para crear un objeto Date válido

                // Establecer la fecha en el datepicker
                $('#pracFechas').datepicker('setDates', [fecha]);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#pracId').val(practica.id);
                $('#pracIdPaciente').val(practica.id_paciente);
                $('#pracNombreCarga').val(practica.nombre_paciente);
                $('#pracHora').val(practica.hora);
                $('#pracProfesional').val(practica.profesional);

                // Establecer el ID de la actividad seleccionada
                $('#pracActividad').data('selected-id', practica.actividad);
                $('#pracCantidad').val(practica.cant);

                // Establecer data-action a "edit"
                $('#btnGuardarPractica').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarPracModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });



    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarPractica').on('click', function () {
        var fechas = $('#pracFechas').datepicker('getDates').map(function (fecha) {
            return fecha.toISOString().slice(0, 10);
        });

        var formData = $('#formAgregarPrac').serializeArray();
        // Primero, eliminamos cualquier entrada anterior de 'fechas'
        formData = formData.filter(function (item) {
            return item.name !== 'fechas';
        });

        // Luego, agregamos la nueva entrada de 'fechas'
        formData.push({ name: 'fechas', value: JSON.stringify(fechas) });


        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/practicas/editar_practica.php' : './submenu/practicas/agregar_practica.php';

        $.ajax({
            url: url,
            type: 'POST',
            data: $.param(formData),
            dataType: 'json', // Asegurarse de que la respuesta se interprete como JSON
            success: function (response) {
                if (response.status === 'success') {
                    const idPaciente = $('#id').val();
                    cargarListaPracticas(idPaciente);
                    $('#agregarPracModal').modal('hide');
                } else {
                    alert(response.message); // Mostrar el mensaje de error
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });

    });



    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deletePrac', function () {
        var pracId = $(this).data('id');
        console.log('Botón Eliminar clickeado');
        if (confirm('¿Estás seguro de que deseas eliminar esta práctica?')) {
            $.ajax({
                url: './submenu/practicas/borrar_practica.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Práctica eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaPracticas(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

//FIN PRACTICA


//EVOLUCIONES
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadEvolucionModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/evoluciones/evoluciones.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('evoModalBody').innerHTML = response;
            $('#evoModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#evoModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#evoModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaEvoluciones(idPaciente) {

    $.ajax({
        url: './dato/get_evoluciones.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var evoluciones = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Motivo</th>';
            html += '<th>Antecedentes</th>';
            html += '<th>Estado Act.</th>';
            html += '<th>Familia</th>';
            html += '<th>Diagnostico</th>';
            html += '<th>Obj.</th>';
            html += '<th>Dur.</th>';
            html += '<th>Frec.</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            evoluciones.forEach(function (evo) {
                html += '<tr>';
                html += '<td>' + formatDate(evo.fecha) + '</td>';
                html += '<td>' + evo.motivo + '</td>';
                html += '<td>' + evo.antecedentes + '</td>';
                html += '<td>' + evo.estado_actual + '</td>';
                html += '<td>' + evo.familia + '</td>';
                html += '<td>' + evo.diag_full + '</td>';
                html += '<td>' + evo.objetivo + '</td>';
                html += '<td>' + evo.duracion + '</td>';
                html += '<td>' + evo.frecuencia + '</td>';
                html += '<td>';
                html += '<button id="editEvo" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + evo.id + '">Editar</button> ';
                html += '<button id="deleteEvo"  class="btn btn-danger btn-sm btn-delete" data-id="' + evo.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-evolucion" data-id="' + evo.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaEvo').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#evoModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaEvoluciones(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#evoModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});


$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaEvo').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#antecedentes').val('');
        $('#estado_actual').val('');
        $('#familia').val('');
        $('#evo_diag').val('');
        $('#objetivo').val('');
        $('#duracion').val('');
        $('#frecuencia').val('');
        $('#evoFecha').val('');
        $('#motivo_evo').val('');
        $('#evoIdPaciente').val(id);
        $('#evoNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarEvo').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarEvoModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editEvo', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_evo_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var evo = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#evoId').val(evo.id);
                $('#evoNombreCarga').val(evo.nombre_paciente);
                $('#antecedentes').val(evo.antecedentes);
                $('#estado_actual').val(evo.estado_actual);
                $('#familia').val(evo.familia);
                $('#evo_diag').val(evo.diag);
                $('#objetivo').val(evo.objetivo);
                $('#duracion').val(evo.duracion);
                $('#frecuencia').val(evo.frecuencia);
                $('#evoFecha').val(evo.fecha);
                $('#motivo_evo').val(evo.motivo);

                // Establecer data-action a "edit"
                $('#btnGuardarEvo').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarEvoModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    $('#btnGuardarEvo').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/evoluciones/editar_evolucion.php' : './submenu/evoluciones/agregar_evolucion.php';
        var formData = $('#formAgregarEvolucion').serialize();

        console.log('Datos del formulario:', formData);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('Respuesta del servidor:', response);
                const idPaciente = $('#id').val();
                cargarListaEvoluciones(idPaciente);
                $('#agregarEvoModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar evolucion:', textStatus, errorThrown);
                console.log('Detalles del error:', jqXHR.responseText);
            }
        });
    });


    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteEvo', function () {
        var pracId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar esta evolucion?')) {
            $.ajax({
                url: './submenu/evoluciones/borrar_evolucion.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaEvoluciones(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

// Evento para generar el PDF
$(document).on('click', '.btn-pdf-evolucion', function () {
    var evoId = $(this).data('id');

    // Petición AJAX para obtener los datos de la evolución
    $.ajax({
        url: './dato/get_evo_con_id.php',
        type: 'GET',
        data: { id: evoId },
        success: function (response) {
            var evo = JSON.parse(response);

            // Crear el documento PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Título del documento
            doc.setFontSize(16);
            doc.text("Evolución Ambulatoria del Paciente", 10, 10);

            // Información básica
            doc.setFontSize(12);
            doc.text("Nombre del Paciente: " + evo.nombre_paciente, 10, 20);
            doc.text("Fecha: " + formatDate(evo.fecha), 10, 30);
            doc.text("Motivo: " + evo.motivo, 10, 40);

            // Tabla de datos
            doc.autoTable({
                startY: 50,
                head: [['Antecedentes', 'Estado Actual', 'Familia', 'Diagnóstico', 'Objetivo', 'Duración', 'Frecuencia']],
                body: [
                    [
                        evo.antecedentes,
                        evo.estado_actual,
                        evo.familia,
                        evo.diag_full,
                        evo.objetivo,
                        evo.duracion,
                        evo.frecuencia
                    ]
                ],
            });

            // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
            const finalY = doc.lastAutoTable.finalY || 60;

            // Agregar el logo centrado después de la tabla
            const imgUrl = '../img/logo.png';
            var img = new Image();
            img.onload = function () {
                const imgWidth = 29; // Ancho de la imagen
                const imgHeight = 25; // Altura de la imagen
                const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                // Agregar la imagen al PDF
                doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                // Abrir el PDF en una nueva pestaña del navegador
                window.open(doc.output('bloburl'));
            };
            img.src = imgUrl; // Establecer la fuente de la imagen
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error al obtener los datos de la evolución:', textStatus, errorThrown);
        }
    });
});


//FIN EVOLUCIONES

//EVOLUCIONES INT
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadEvolucionIntModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/evoluciones_int/evoluciones_int.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('evoIntModalBody').innerHTML = response;
            $('#evoIntModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#evoIntModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#evoIntModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaEvolucionesInt(idPaciente) {

    $.ajax({
        url: './dato/get_evoluciones_int.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var evoluciones_int = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Motivo</th>';
            html += '<th>Antecedentes</th>';
            html += '<th>Estado Act.</th>';
            html += '<th>Familia</th>';
            html += '<th>Diagnostico</th>';
            html += '<th>Obj.</th>';
            html += '<th>Dur.</th>';
            html += '<th>Frec.</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            evoluciones_int.forEach(function (evo) {
                html += '<tr>';
                html += '<td>' + formatDate(evo.fecha) + '</td>';
                html += '<td>' + evo.motivo + '</td>';
                html += '<td>' + evo.antecedentes + '</td>';
                html += '<td>' + evo.estado_actual + '</td>';
                html += '<td>' + evo.familia + '</td>';
                html += '<td>' + evo.diag_full + '</td>';
                html += '<td>' + evo.objetivo + '</td>';
                html += '<td>' + evo.duracion + '</td>';
                html += '<td>' + evo.frecuencia + '</td>';
                html += '<td>';
                html += '<button id="editEvoInt" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + evo.id + '">Editar</button> ';
                html += '<button id="deleteEvoInt"  class="btn btn-danger btn-sm btn-delete" data-id="' + evo.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-evolucion" data-id="' + evo.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaEvoInt').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#evoIntModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaEvolucionesInt(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#evoIntModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});


$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaEvoInt').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#antecedentes_int').val('');
        $('#estado_actual_int').val('');
        $('#familia_int').val('');
        $('#evo_diag_int').val('');
        $('#objetivo_int').val('');
        $('#duracion_int').val('');
        $('#frecuencia_int').val('');
        $('#evoFecha_int').val('');
        $('#motivo_evo_int').val('');
        $('#evoIntIdPaciente').val(id);
        $('#evoIntNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarEvoInt').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarEvoIntModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editEvoInt', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_evo_int_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var evo = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#evoIntId').val(evo.id);
                $('#evoIntNombreCarga').val(evo.nombre_paciente);
                $('#antecedentes_int').val(evo.antecedentes);
                $('#estado_actual_int').val(evo.estado_actual);
                $('#familia_int').val(evo.familia);
                $('#evo_diag_int').val(evo.diag);
                $('#objetivo_int').val(evo.objetivo);
                $('#duracion_int').val(evo.duracion);
                $('#frecuencia_int').val(evo.frecuencia);
                $('#evoFecha_int').val(evo.fecha);
                $('#motivo_evo_int').val(evo.motivo);

                // Establecer data-action a "edit"
                $('#btnGuardarEvoInt').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarEvoIntModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    $('#btnGuardarEvoInt').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/evoluciones_int/editar_evolucion_int.php' : './submenu/evoluciones_int/agregar_evolucion_int.php';
        var formData = $('#formAgregarEvolucionInt').serialize();

        console.log('Datos del formulario:', formData);

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log('Respuesta del servidor:', response);
                const idPaciente = $('#id').val();
                cargarListaEvolucionesInt(idPaciente);
                $('#agregarEvoIntModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar evolucion:', textStatus, errorThrown);
                console.log('Detalles del error:', jqXHR.responseText);
            }
        });
    });


    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteEvoInt', function () {
        var pracId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar esta evolucion?')) {
            $.ajax({
                url: './submenu/evoluciones_int/borrar_evolucion_int.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaEvolucionesInt(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

$(document).on('click', '.btn-pdf-evolucion', function () {
    var evoId = $(this).data('id');

    // Petición AJAX para obtener los datos de la evolución
    $.ajax({
        url: './dato/get_evo_int_con_id.php',
        type: 'GET',
        data: { id: evoId },
        success: function (response) {
            var evo = JSON.parse(response);

            // Crear el documento PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Título del documento
            doc.setFontSize(16);
            doc.text("Evolución Internación del Paciente", 10, 10);

            // Información básica
            doc.setFontSize(12);
            doc.text("Nombre del Paciente: " + evo.nombre_paciente, 10, 20);
            doc.text("Fecha: " + formatDate(evo.fecha), 10, 30);
            doc.text("Motivo: " + evo.motivo, 10, 40);

            // Tabla de datos
            doc.autoTable({
                startY: 50,
                head: [['Antecedentes', 'Estado Actual', 'Familia', 'Diagnóstico', 'Objetivo', 'Duración', 'Frecuencia']],
                body: [
                    [
                        evo.antecedentes,
                        evo.estado_actual,
                        evo.familia,
                        evo.diag_full,
                        evo.objetivo,
                        evo.duracion,
                        evo.frecuencia
                    ]
                ],
            });

            // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
            const finalY = doc.lastAutoTable.finalY || 60;

            // Agregar el logo centrado después de la tabla
            const imgUrl = '../img/logo.png';
            var img = new Image();
            img.onload = function () {
                const imgWidth = 29; // Ancho de la imagen
                const imgHeight = 25; // Altura de la imagen
                const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                // Agregar la imagen al PDF
                doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                // Abrir el PDF en una nueva pestaña del navegador
                window.open(doc.output('bloburl'));
            };
            img.src = imgUrl; // Establecer la fuente de la imagen
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error al obtener los datos de la evolución:', textStatus, errorThrown);
        }
    });
});

//FIN EVOLUCIONES INT

//ADMISION AMB
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadAdmiAmbModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/admision_amb/admision_amb.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('admiAmbModalBody').innerHTML = response;
            $('#admiAmbModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#admiAmbModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#admiAmbModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaAdmisionAmb(idPaciente) {

    $.ajax({
        url: './dato/get_admision_amb.php',
        type: 'GET',
        data: { id: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var historia = JSON.parse(response);

            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Profesional</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            historia.forEach(function (hist) {
                html += '<tr>';
                html += '<td>' + formatDate(hist.hc_fecha) + '</td>';
                html += '<td>' + hist.profesional + '</td>';
                html += '<td>';
                html += '<button id="editAdmiAmb" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + hist.id + '">Editar</button> ';
                html += '<button id="deleteAdmiAmb"  class="btn btn-danger btn-sm btn-delete" data-id="' + hist.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-admision" data-id="' + hist.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaAdmiAmb').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#admiAmbModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaAdmisionAmb(idPaciente);

});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#admiAmbModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaAdmiAmb').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria

        $('#admiAmbIdPaciente').val(id);
        $('#admiAmbNombreCarga').val(nombre);

        // Establecer data-action a "add"
        $('#btnGuardarAdmiAmb').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarAdmiAmbModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editAdmiAmb', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_admision_amb_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var data = JSON.parse(response);
                console.log(data)
                $('#admiAmbId').val(data.id);
                $('#admiAmbNombreCarga').val(data.nombre_paciente);
                $('#admiAmbIdPaciente').val(data.id_paciente);
                $('#hc_fecha').val(data.hc_fecha)
                $('#admi_familiar').val(data.antecedentes);
                $('#hc_prof').val(data.id_prof);

                $('input[name="aspectoPsiquico"][value="' + data.asc_psiquico + '"]').prop('checked', true);
                $('input[name="act_psiquica"][value="' + data.act_psiquica + '"]').prop('checked', true);
                $('input[name="act"][value="' + data.act + '"]').prop('checked', true);
                $('input[name="orientacion"][value="' + data.orientacion + '"]').prop('checked', true);
                $('input[name="conciencia"][value="' + data.conciencia + '"]').prop('checked', true);
                $('input[name="memoria"][value="' + data.memoria + '"]').prop('checked', true);
                $('input[name="atencion"][value="' + data.atencion + '"]').prop('checked', true);
                $('input[name="pensamiento"][value="' + data.pensamiento + '"]').prop('checked', true);
                $('input[name="cont_pensamiento"][value="' + data.cont_pensamiento + '"]').prop('checked', true);
                $('input[name="sensopercepcion"][value="' + data.sensopercepcion + '"]').prop('checked', true);
                $('input[name="afectividad"][value="' + data.afectividad + '"]').prop('checked', true);
                $('input[name="inteligencia"][value="' + data.inteligencia + '"]').prop('checked', true);
                $('input[name="juicio"][value="' + data.juicio + '"]').prop('checked', true);
                $('input[name="esfinteres"][value="' + data.esfinteres + '"]').prop('checked', true);
                $('input[name="tratamiento"][value="' + data.tratamiento + '"]').prop('checked', true);
                $('input[name="evolucion"][value="' + data.evolucion + '"]').prop('checked', true);

                // Ocultar los elementos de diagnóstico y medicación
                $('#hc_diag').hide(); // Ocultar el select de diagnóstico
                $('label[for="hc_diag"]').hide(); // Ocultar la etiqueta de diagnóstico

                $('#hc_medi').hide(); // Ocultar el select de medicación
                $('#searchMedicacionHc').hide(); // Ocultar el campo de búsqueda de medicación
                $('label[for="hc_medi"]').hide(); // Ocultar la etiqueta de medicación
                $('#hc_desc_medi').hide(); // Ocultar el input de descripción de medicación
                $('label[for="hc_desc_medi"]').hide(); // Ocultar la etiqueta de descripción de medicación
                $('#hc_cada_medi').hide(); // Ocultar el input de cada cuánto
                $('label[for="hc_cada_medi"]').hide(); // Ocultar la etiqueta de cada cuánto




                // Establecer data-action a "edit"
                $('#btnGuardarAdmiAmb').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarAdmiAmbModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarAdmiAmb').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/admision_amb/editar_admi_amb.php' : './submenu/admision_amb/agregar_admi_amb.php';

        // Recoger solo el valor del checkbox seleccionado
        var aspectoPsiquico = $('input[name="aspectoPsiquico"]:checked').val();
        var act_psiquica = $('input[name="act_psiquica"]:checked').val();
        var act = $('input[name="act"]:checked').val();
        var orientacion = $('input[name="orientacion"]:checked').val();
        var conciencia = $('input[name="conciencia"]:checked').val();
        var memoria = $('input[name="memoria"]:checked').val();
        var atencion = $('input[name="atencion"]:checked').val();
        var pensamiento = $('input[name="pensamiento"]:checked').val();
        var cont_pensamiento = $('input[name="cont_pensamiento"]:checked').val();
        var sensopercepcion = $('input[name="sensopercepcion"]:checked').val();
        var afectividad = $('input[name="afectividad"]:checked').val();
        var inteligencia = $('input[name="inteligencia"]:checked').val();
        var juicio = $('input[name="juicio"]:checked').val();
        var esfinteres = $('input[name="esfinteres"]:checked').val();
        var tratamiento = $('input[name="tratamiento"]:checked').val();
        var evolucion = $('input[name="evolucion"]:checked').val();

        // Verificar que todos los campos obligatorios están completos
        if (!aspectoPsiquico || !act_psiquica || !act || !orientacion || !conciencia || !memoria ||
            !atencion || !pensamiento || !cont_pensamiento || !sensopercepcion || !afectividad ||
            !inteligencia || !juicio || !esfinteres || !tratamiento || !evolucion) {

            alert('Por favor complete todos los campos obligatorios.');
            return; // Detiene la ejecución si falta algún dato
        }

        formData = {
            id: $('#admiAmbId').val(),
            id_paciente: $('#admiAmbIdPaciente').val(),
            asc_psiquico: aspectoPsiquico,
            act_psiquica: act_psiquica,
            act: act,
            orientacion: orientacion,
            conciencia: conciencia,
            memoria: memoria,
            atencion: atencion,
            pensamiento: pensamiento,
            cont_pensamiento: cont_pensamiento,
            sensopercepcion: sensopercepcion,
            afectividad: afectividad,
            inteligencia: inteligencia,
            juicio: juicio,
            esfinteres: esfinteres,
            tratamiento: tratamiento,
            evolucion: evolucion,
            hc_fecha: $('#hc_fecha').val(),
            id_diag: $('#hc_diag').val() || '',
            id_medicamento: $('#hc_medi').val(),
            id_prof: $('#hc_prof').val(),
            hc_desc_medi: $('#hc_desc_medi').val() || '',
            hc_cada_medi: $('#hc_cada_medi').val() || '',
            admi_familiar: $('#admi_familiar').val()

        };

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response)
                const idPaciente = $('#id').val();
                cargarListaAdmisionAmb(idPaciente);
                $('#agregarAdmiAmbModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteAdmiAmb', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/admision_amb/eliminar_admision_amb.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Op eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaAdmisionAmb(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

// Evento para generar el PDF
$(document).on('click', '.btn-pdf-admision', function () {
    var admi = $(this).data('id');

    // Petición AJAX para obtener los datos de la evolución
    $.ajax({
        url: './dato/get_admision_amb_id.php',
        type: 'GET',
        data: { id: admi },
        success: function (response) {
            var admision = JSON.parse(response);

            // Crear el documento PDF
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Título del documento
            doc.setFontSize(16);
            doc.text("Admisión del Paciente", 10, 10);

            // Información básica
            doc.setFontSize(12);
            doc.text("Nombre del Paciente: " + admision.nombre_paciente, 10, 20);
            doc.text("Fecha: " + formatDate(admision.hc_fecha), 10, 30);
            doc.text("Profesional: " + admision.prof_full, 10, 40);

            // Tabla de datos
            doc.autoTable({
                startY: 50,
                head: [['Campo', 'Valor']],
                body: [
                    ['Antecedentes', admision.antecedentes],
                    ['Aspecto Psíquico', admision.asc_psiquico],
                    ['Actividad Psíquica', admision.act_psiquica],
                    ['Actividad', admision.act],
                    ['Orientación', admision.orientacion],
                    ['Conciencia', admision.conciencia],
                    ['Memoria', admision.memoria],
                    ['Atención', admision.atencion],
                    ['Pensamiento', admision.pensamiento],
                    ['Contenido del Pensamiento', admision.cont_pensamiento],
                    ['Sensopercepción', admision.sensopercepcion],
                    ['Afectividad', admision.afectividad],
                    ['Inteligencia', admision.inteligencia],
                    ['Juicio', admision.juicio],
                    ['Esfínteres', admision.esfinteres],
                    ['Tratamiento', admision.tratamiento],
                    ['Evolución', admision.evolucion],
                    ['Descripción del Medicamento', admision.hc_desc_medi],
                    ['Frecuencia del Medicamento', admision.hc_cada_medi]
                ],
            });

            // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
            const finalY = doc.lastAutoTable.finalY || 60;

            // Agregar el logo centrado después de la tabla
            const imgUrl = '../img/logo.png';
            var img = new Image();
            img.onload = function () {
                const imgWidth = 29; // Ancho de la imagen
                const imgHeight = 25; // Altura de la imagen
                const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                // Agregar la imagen al PDF
                doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                // Abrir el PDF en una nueva pestaña del navegador
                window.open(doc.output('bloburl'));
            };
            img.src = imgUrl; // Establecer la fuente de la imagen
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('Error al obtener los datos de la evolución:', textStatus, errorThrown);
        }
    });
});

//FIN ADMISION AMB

//ORDENES DE PRESTACION

// Función para cargar el modal de egreso y ocultar el formulario principal
function loadOrdenModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/op/op.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('ordenModalBody').innerHTML = response;
            $('#ordenModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#ordenModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#ordenModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaOrdenes(idPaciente) {

    $.ajax({
        url: './dato/get_modalidad_sin_egreso_paci.php',
        type: 'GET',
        dataType: 'json',
        data: { id_paciente: idPaciente }, // Aquí pasas el id_paciente en la solicitud
        success: function (data) {
            $('#modalidad_op').empty(); // Limpia las opciones previas si es necesario

            // Agregar opción de placeholder "Seleccionar"
            $('#modalidad_op').append(new Option('Seleccionar', ''));

            data.forEach(function (item) {
                var optionText = item.modalidad_full;
                var optionValue = item.modalidad; // Esto toma el valor de 'modalidad'
                $('#modalidad_op').append(new Option(optionText, optionValue)); // Agrega la opción
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_paci_orden.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var orden = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Nro. Orden</th>';
            html += '<th>Cant Meses</th>';
            html += '<th>Modalidad</th>';
            html += '<th>Vencimiento</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            orden.forEach(function (orden) {
                html += '<tr>';
                html += '<td>' + formatDate(orden.fecha) + '</td>';
                html += '<td>' + orden.op + '</td>';
                html += '<td>' + orden.cant + '</td>';
                html += '<td>' + orden.modalidad_full + '</td>';
                html += '<td>' + formatDate(orden.fecha_vencimiento) + '</td>';
                html += '<td>';
                html += '<button id="editOrden" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + orden.id + '">Editar</button> ';
                html += '<button id="deleteOrden" class="btn btn-danger btn-sm btn-delete" data-id="' + orden.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaOrden').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#ordenModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaOrdenes(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#ordenModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaOrden').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#orden_fecha').val('');
        $('#op').val('');
        $('#op_cant').val('');
        $('#modalidad_op').val('');
        $('#fecha_vencimiento').val('');
        $('#ordenIdPaciente').val(id);
        $('#ordenNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarOrden').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarOrdenModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editOrden', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_orden_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var orden = JSON.parse(response);

                $('#ordenId').val(orden.id);
                $('#orden_fecha').val(orden.fecha);
                $('#op').val(orden.op);
                $('#op_cant').val(orden.cant);
                $('#modalidad_op').val(orden.modalidad_op);
                $('#fecha_vencimiento').val(formatDate(orden.fecha_vencimiento));
                $('#ordenNombreCarga').val(orden.nombre_paciente);
                $('#ordenIdPaciente').val(orden.id_paciente);




                // Establecer data-action a "edit"
                $('#btnGuardarOrden').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarOrdenModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarOrden').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/op/editar_op.php' : './submenu/op/agregar_op.php';
        var formData = $('#formAgregarOrden').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaOrdenes(idPaciente);
                $('#agregarOrdenModal').modal('hide');

                console.log('Respuesta del servidor:', response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteOrden', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/op/eliminar_op.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Op eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaOrdenes(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

});

//FIN ORDENES DE PRESTACION

//cant habitaciones disponibles 
function mostrarHabitacionesDisponibles() {
    fetch('./dato/get_habitaciones_disponibles.php')
        .then(response => response.json())
        .then(data => {
            var habitacionesDisponibles = document.getElementById('habitacionesDisponibles');
            var htmlContent = '';

            // Procesar los datos y construir el HTML
            data.forEach(function (habitacion) {
                htmlContent += '<div class="habitacion">';
                htmlContent += '<strong>Habitación ' + habitacion.num + ':</strong> ';
                htmlContent += 'Camas Disponibles: ' + habitacion.camas_disponibles;
                htmlContent += ', Piso/Sala: ' + habitacion.piso + '<br>';
                htmlContent += '</div>';
            });

            habitacionesDisponibles.innerHTML = htmlContent;
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });
}

// Llamar a la función para mostrar los datos al cargar la página o al abrir el modal
document.addEventListener('DOMContentLoaded', mostrarHabitacionesDisponibles);

//MEDICACION
function loadMedicacionModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/medicacion/medicacion.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('mediModalBody').innerHTML = response;
            $('#mediModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#mediModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#mediModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaMedi(idPaciente) {

    $.ajax({
        url: './dato/get_medicaciones.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var medicaciones = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Fecha</th>';
            html += '<th>Hora</th>';
            html += '<th>Dosis</th>';
            html += '<th>Medicacion</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            medicaciones.forEach(function (medicaciones) {
                html += '<tr>';
                html += '<td>' + formatDate(medicaciones.fecha) + '</td>';
                html += '<td>' + medicaciones.hora + '</td>';
                html += '<td>' + medicaciones.dosis + '</td>';
                html += '<td>' + medicaciones.desc_medi + '</td>';
                html += '<td>';
                html += '<button id="editMedi" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + medicaciones.id + '">Editar</button> ';
                html += '<button id="deleteMedi" class="btn btn-danger btn-sm btn-delete" data-id="' + medicaciones.id + '">Eliminar</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaMedi').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#mediModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaMedi(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#mediModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaMedi').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#mediDesc').val('');
        $('#medi_fecha').val('');
        $('#medi_hora').val('');
        $('#dosis').val('');
        $('#mediIdPaciente').val(id);
        $('#mediNombreCarga').val(nombre);


        // Establecer data-action a "add"
        $('#btnGuardarMedi').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarMediModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editMedi', function () {
        var pracId = $(this).data('id');
        console.log(pracId)
        $.ajax({
            url: './dato/get_medicamento_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var medi = JSON.parse(response);

                // Llenar los campos del modal de edición con los datos de la práctica
                $('#mediId').val(medi.id);
                $('#medi_fecha').val(medi.fecha);
                $('#medi_hora').val(medi.hora);
                $('#dosis').val(medi.dosis);
                $('#mediIdPaciente').val(medi.id_paciente);
                $('#mediNombreCarga').val(medi.nombre_paciente);
                $('#mediDesc').val(medi.medicamento);

                // Establecer data-action a "edit"
                $('#btnGuardarMedi').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarMediModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarMedi').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/medicacion/editar_medicamento.php' : './submenu/medicacion/agregar_medicamento.php';
        var formData = $('#formAgregarMedi').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                alert(response);
                const idPaciente = $('#id').val();
                cargarListaMedi(idPaciente);
                $('#agregarMediModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar Diagnostico:', textStatus, errorThrown);
                alert('Error al guardar el diag');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteMedi', function () {
        var pracId = $(this).data('id');
        if (confirm('¿Estás seguro de que deseas eliminar este medicamento?')) {
            $.ajax({
                url: './submenu/medicacion/borrar_medicamento.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Medicamento eliminado:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaMedi(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar el egreso:', textStatus, errorThrown);
                }
            });
        }
    });

});

//NUTRICION
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadNutriModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/nutricion/nutricion.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('nutriModalBody').innerHTML = response;
            $('#nutriModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#nutriModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#nutriModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaNutri(idPaciente) {

    $.ajax({
        url: './dato/get_nutrciones.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var nutri = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Patologias</th>';
            html += '<th>Dieta</th>';
            html += '<th>Actitud frente a la comida</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            nutri.forEach(function (nutri) {
                html += '<tr>';
                html += '<td>' + nutri.patologias + '</td>';
                html += '<td>' + nutri.indicacion_dieta + '</td>';
                html += '<td>' + nutri.actitud_comida + '</td>';
                html += '<td>';
                html += '<button id="editNutri" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + nutri.id + '">Editar</button> ';
                html += '<button id="deleteNutri" class="btn btn-danger btn-sm btn-delete" data-id="' + nutri.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-nutricion" data-id="' + nutri.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaNutri').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#nutriModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaNutri(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#nutriModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevanutri').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria

        $('#patologias').val('');
        $('#indicacion_dieta').val('');
        $('#actitud_comida').val('');
        $('#peso').val('');
        $('#talla').val('');
        $('#imc').val('');
        $('#requiere').val('');
        $('#no_requiere').val('');
        $('#especificar').val('');
        $('#nutriIdPaciente').val(id);
        $('#nutriNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarNutricion').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarNutriModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editNutri', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_nutri_con_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var nutri = JSON.parse(response);

                $('#patologias').val(nutri.patologias);
                $('#indicacion_dieta').val(nutri.indicacion_dieta);
                $('#actitud_comida').val(nutri.actitud_comida);
                $('#peso').val(nutri.peso);
                $('#talla').val(nutri.talla);
                $('#imc').val(nutri.imc);
                $('#requiere').val(nutri.requiere);
                $('#no_requiere').val(nutri.no_requiere);
                $('#especificar').val(nutri.especificar);
                $('#nutriId').val(nutri.id)
                $('#nutriIdPaciente').val(nutri.id_paciente);
                $('#nutriNombreCarga').val(nutri.nombre_paciente);


                // Establecer data-action a "edit"
                $('#btnGuardarNutricion').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarNutriModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarNutricion').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/nutricion/editar_nutricion.php' : './submenu/nutricion/agregar_nutricion.php';
        var formData = $('#agregarNutri').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            dataType: 'json',  // Asegurarse de que el servidor responda con JSON
            success: function (response) {
                const idPaciente = $('#id').val();

                if (response.status === "success") {
                    cargarListaNutri(idPaciente);
                    $('#agregarNutriModal').modal('hide');
                    alert(response.message); // Mostrar mensaje de éxito
                } else {
                    alert('Error: ' + response.message); // Mostrar mensaje de error en caso de fallo
                }

                console.log(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                console.log('Respuesta del servidor:', jqXHR.responseText);
                alert('Error al guardar la práctica: ' + textStatus + ' ' + errorThrown);
            }

        });
    });


    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteNutri', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/nutricion/eliminar_nutricion.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('nutricion eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaNutri(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

    // Evento para generar el PDF
    $(document).on('click', '.btn-pdf-nutricion', function () {
        var nutriId = $(this).data('id');

        // Petición AJAX para obtener los datos de la evolución
        $.ajax({
            url: './dato/get_nutri_con_id.php',
            type: 'GET',
            data: { id: nutriId },
            success: function (response) {
                var nutricion = JSON.parse(response);

                // Crear el documento PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Título del documento
                doc.setFontSize(16);
                doc.text("ADMISÍON NUTRICION DEL PACIENTE", 10, 10);

                // Información básica
                doc.setFontSize(12);
                doc.text("Nombre del Paciente: " + nutricion.nombre_paciente, 10, 20);
                doc.text("Peso: " + nutricion.peso, 10, 30);
                doc.text("Talla: " + nutricion.talla, 10, 40);
                doc.text("IMC: " + nutricion.imc, 10, 50);

                // Tabla de datos
                doc.autoTable({
                    startY: 60,
                    head: [['Campo', 'Valor']],
                    body: [
                        ['Patologías', nutricion.patologias],
                        ['Indicación de Dieta', nutricion.indicacion_dieta],
                        ['Actitud hacia la Comida', nutricion.actitud_comida],
                        ['Requiere', nutricion.requiere],
                        ['No Requiere', nutricion.no_requiere],
                        ['Especificar', nutricion.especificar]
                    ],
                });

                // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
                const finalY = doc.lastAutoTable.finalY || 60;

                // Agregar el logo centrado después de la tabla
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29; // Ancho de la imagen
                    const imgHeight = 25; // Altura de la imagen
                    const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                    const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                    const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                    // Agregar la imagen al PDF
                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    // Abrir el PDF en una nueva pestaña del navegador
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl; // Establecer la fuente de la imagen
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la evolución:', textStatus, errorThrown);
            }
        });
    });

});
//FIN NUTRICION

//ADMISICION FISICA

// Función para cargar el modal de egreso y ocultar el formulario principal
function loadFisicaModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/fisica/fisica.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('fisicaModalBody').innerHTML = response;
            $('#fisicaModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#fisicaModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#fisicaModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaFisica(idPaciente) {

    $.ajax({
        url: './dato/get_ad_fisica.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var fisica = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Medico</th>';
            html += '<th>Obs. generales</th>';
            html += '<th>Examen postural y marcha</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            fisica.forEach(function (fisica) {
                html += '<tr>';
                html += '<td>' + fisica.medico + '</td>';
                html += '<td>' + fisica.objetivos_generales + '</td>';
                html += '<td>' + fisica.examen_postural + '</td>';
                html += '<td>';
                html += '<button id="editFisica" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + fisica.id + '">Editar</button> ';
                html += '<button id="deleteFisica" class="btn btn-danger btn-sm btn-delete" data-id="' + fisica.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-fisica-pdf" data-id="' + fisica.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaFisica').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#fisicaModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaFisica(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#fisicaModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaFisica').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#medico_tratante').val('');
        $('#objetivos_generales').val('');
        $('#examen_postural').val('');
        $('#examen_muscular').val('');
        $('#examen_flexibilidad').val('');
        $('#fuerza_miembros_inferiores').val('');
        $('#fuerza_miembros_superiores').val('');
        $('#equilibrio_normal').val('');
        $('#equilibrio_ojos_cerrados').val('');
        $('#equilibrio_base_sustentacion').val('');
        $('#movimiento_ms').val('');
        $('#movimiento_ml').val('');
        $('#movimiento_tronco').val('');
        $('#caminando_giros').val('');
        $('#observaciones_generales').val('');

        $('#fisicaIdPaciente').val(id);
        $('#fisicaNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarFisica').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarFisicaModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editFisica', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_ad_fisica_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var fisica = JSON.parse(response);

                $('#fisicaId').val(fisica.id);
                $('#medico_tratante').val(fisica.medico_tratante);
                $('#objetivos_generales').val(fisica.objetivos_generales);
                $('#examen_postural').val(fisica.examen_postural);
                $('#examen_muscular').val(fisica.examen_muscular);
                $('#examen_flexibilidad').val(fisica.examen_flexibilidad);
                $('#fuerza_miembros_inferiores').val(fisica.fuerza_miembros_inferiores);
                $('#fuerza_miembros_superiores').val(fisica.fuerza_miembros_superiores);
                $('#equilibrio_normal').val(fisica.equilibrio_normal);
                $('#equilibrio_ojos_cerrados').val(fisica.equilibrio_ojos_cerrados);
                $('#equilibrio_base_sustentacion').val(fisica.equilibrio_base_sustentacion);
                $('#movimiento_ms').val(fisica.movimiento_ms);
                $('#movimiento_ml').val(fisica.movimiento_ml);
                $('#movimiento_tronco').val(fisica.movimiento_tronco);
                $('#caminando_giros').val(fisica.caminando_giros);
                $('#observaciones_generales').val(fisica.observaciones_generales);
                $('#fisicaNombreCarga').val(fisica.nombre_paciente);
                $('#fisicaIdPaciente').val(fisica.id_paciente);


                // Establecer data-action a "edit"
                $('#btnGuardarFisica').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarFisicaModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarFisica').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/fisica/editar_fisica.php' : './submenu/fisica/agregar_fisica.php';
        var formData = $('#formAgregarFisica').serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaFisica(idPaciente);
                $('#agregarFisicaModal').modal('hide');
                console.log(response)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteFisica', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/fisica/eliminar_fisica.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('fisica eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaFisica(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

    //BOTON PDF
    $(document).on('click', '.btn-fisica-pdf', function () {
        var fisicaId = $(this).data('id');

        // Petición AJAX para obtener los datos de la evaluación física
        $.ajax({
            url: './dato/get_ad_fisica_id.php', // Cambia esta URL según sea necesario
            type: 'GET',
            data: { id: fisicaId },
            success: function (response) {
                var fisica = JSON.parse(response);
                console.log(fisica)
                // Crear el documento PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Título del documento
                doc.setFontSize(16);
                doc.text("Evaluación Física del Paciente", 10, 10);

                // Información básica
                doc.setFontSize(12);
                doc.text("ID del Paciente: " + fisica.nombre_paciente, 10, 20);
                doc.text("Médico Tratante: " + fisica.prof_full, 10, 30);

                // Tabla de datos
                doc.autoTable({
                    startY: 40,
                    head: [['Campo', 'Valor']],
                    body: [
                        ['Objetivos Generales', fisica.objetivos_generales],
                        ['Examen Postural', fisica.examen_postural],
                        ['Examen Muscular', fisica.examen_muscular],
                        ['Examen de Flexibilidad', fisica.examen_flexibilidad],
                        ['Fuerza Miembros Inferiores', fisica.fuerza_miembros_inferiores],
                        ['Fuerza Miembros Superiores', fisica.fuerza_miembros_superiores],
                        ['Equilibrio Normal', fisica.equilibrio_normal],
                        ['Equilibrio con Ojos Cerrados', fisica.equilibrio_ojos_cerrados],
                        ['Equilibrio en Base de Sustentación', fisica.equilibrio_base_sustentacion],
                        ['Movimiento del Miembro Superior', fisica.movimiento_ms],
                        ['Movimiento del Miembro Inferior', fisica.movimiento_ml],
                        ['Movimiento del Tronco', fisica.movimiento_tronco],
                        ['Caminando y Giros', fisica.caminando_giros],
                        ['Observaciones Generales', fisica.observaciones_generales]
                    ],
                });

                // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
                const finalY = doc.lastAutoTable.finalY || 70;

                // Agregar el logo centrado después de la tabla
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29; // Ancho de la imagen
                    const imgHeight = 25; // Altura de la imagen
                    const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                    const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                    const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                    // Agregar la imagen al PDF
                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    // Abrir el PDF en una nueva pestaña del navegador
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl; // Establecer la fuente de la imagen
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la evaluación física:', textStatus, errorThrown);
            }
        });
    });


});


//FIN ADMISION FISICA


//ADMISION DIAG
// Función para cargar el modal de egreso y ocultar el formulario principal
function loadAdmisionDiagModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/admiDiag/admiDiag.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('admiDiagModalBody').innerHTML = response;
            $('#admiDiagModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#admiDiagModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#admiDiagModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaAdmiDiag(idPaciente) {

    $.ajax({
        url: './dato/get_ad_diag.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var admiDiag = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>La Naturaleza de los problemas...</th>';
            html += '<th>La situación de la entrevista</th>';
            html += '<th>Conciencia de enfermedad</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            admiDiag.forEach(function (admiDiag) {
                html += '<tr>';
                html += '<td>' + admiDiag.impresion_naturaleza + '</td>';
                html += '<td>' + admiDiag.impresion_situacion + '</td>';
                html += '<td>' + admiDiag.impresion_conciencia + '</td>';
                html += '<td>';
                html += '<button id="editAdmiDiag" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + admiDiag.id + '">Editar</button> ';
                html += '<button id="deleteAdmiDiag" class="btn btn-danger btn-sm btn-delete" data-id="' + admiDiag.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-admi-diag" data-id="' + admiDiag.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaAdmiDiag').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#admiDiagModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaAdmiDiag(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#admiDiagModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevaAdmiDiag').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#impresion_naturaleza').val('');
        $('#impresion_situacion').val('');
        $('#impresion_conciencia').val('');
        $('#impresion_expectativas').val('');
        $('#diagnostico_clinico').val('');
        $('#diagnostico_gravedad').val('');
        $('#factores_desencadenantes').val('');
        $('#personalidad_premorbida').val('');
        $('#incapacidad_social').val('');
        $('#indicaciones').val('');
        $('#pronostico').val('');
        $('#admiDiagIdPaciente').val(id);
        $('#admiDiagNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarAdmiDiag').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarImpresionDiagnosticaModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editAdmiDiag', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_ad_diag_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var admiDiag = JSON.parse(response);

                $('#admiDiagId').val(admiDiag.id);

                $('#impresion_naturaleza').val(admiDiag.impresion_naturaleza);
                $('#impresion_situacion').val(admiDiag.impresion_situacion);
                $('#impresion_conciencia').val(admiDiag.impresion_conciencia);
                $('#impresion_expectativas').val(admiDiag.impresion_expectativas);
                $('#diagnostico_clinico').val(admiDiag.diagnostico_clinico);
                $('#diagnostico_gravedad').val(admiDiag.diagnostico_gravedad);
                $('#factores_desencadenantes').val(admiDiag.factores_desencadenantes);
                $('#personalidad_premorbida').val(admiDiag.personalidad_premorbida);
                $('#incapacidad_social').val(admiDiag.incapacidad_social);
                $('#indicaciones').val(admiDiag.indicaciones);
                $('#pronostico').val(admiDiag.pronostico);

                $('#admiDiagNombreCarga').val(admiDiag.nombre_paciente);
                $('#admiDiagIdPaciente').val(admiDiag.id_paciente);


                // Establecer data-action a "edit"
                $('#btnGuardarAdmiDiag').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarImpresionDiagnosticaModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarAdmiDiag').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/admiDiag/editar_admi_diag.php' : './submenu/admiDiag/agregar_admi_diag.php';
        var formData = $('#formImpresionDiagnostica').serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaAdmiDiag(idPaciente);
                $('#agregarImpresionDiagnosticaModal').modal('hide');
                console.log(response)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteAdmiDiag', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/admiDiag/eliminar_admi_diag.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('admi diag eliminada:', response);
                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaAdmiDiag(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

    // Generar PDF
    $(document).on('click', '.btn-pdf-admi-diag', function () {
        var diagId = $(this).data('id');

        // Petición AJAX para obtener los datos de la impresión diagnóstica
        $.ajax({
            url: './dato/get_ad_diag_id.php', // Cambia esta URL según sea necesario
            type: 'GET',
            data: { id: diagId },
            success: function (response) {
                var diagnostico = JSON.parse(response);

                // Crear el documento PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Título del documento
                doc.setFontSize(16);
                doc.text("Impresión Diagnóstica del Paciente", 10, 10);

                // Información básica
                doc.setFontSize(12);
                doc.text("Paciente: " + diagnostico.nombre_paciente, 10, 20);

                // Tabla de datos
                doc.autoTable({
                    startY: 30,
                    head: [['Campo', 'Valor']],
                    body: [
                        ['Naturaleza de la Impresión', diagnostico.impresion_naturaleza],
                        ['Situación de la Impresión', diagnostico.impresion_situacion],
                        ['Conciencia de la Impresión', diagnostico.impresion_conciencia],
                        ['Expectativas', diagnostico.impresion_expectativas],
                        ['Diagnóstico Clínico', diagnostico.diagnostico_clinico],
                        ['Gravedad del Diagnóstico', diagnostico.diagnostico_gravedad],
                        ['Factores Desencadenantes', diagnostico.factores_desencadenantes],
                        ['Personalidad Premorbida', diagnostico.personalidad_premorbida],
                        ['Incapacidad Social', diagnostico.incapacidad_social],
                        ['Indicaciones', diagnostico.indicaciones],
                        ['Pronóstico', diagnostico.pronostico]
                    ],
                    columnStyles: {
                        0: { cellWidth: 60 }, // Ancho fijo para la columna "Campo"
                        1: { cellWidth: 130 } // Ancho fijo para la columna "Valor"
                    },
                    
                    styles: {
                        overflow: 'linebreak', // Manejo de líneas largas
                        cellPadding: 5, // Espaciado dentro de las celdas
                        fontSize: 12 // Tamaño de fuente
                    },
                });

                // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
                const finalY = doc.lastAutoTable.finalY || 70;

                // Agregar el logo centrado después de la tabla
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29; // Ancho de la imagen
                    const imgHeight = 25; // Altura de la imagen
                    const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                    const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                    const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                    // Agregar la imagen al PDF
                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    // Abrir el PDF en una nueva pestaña del navegador
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl; // Establecer la fuente de la imagen
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la impresión diagnóstica:', textStatus, errorThrown);
            }
        });
    });




});

//FIN ADMISION DIAG

// EXAMEN PSQUIATRICO


// Función para cargar el modal de egreso y ocultar el formulario principal
function loadExPsiquiatricoModal() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/ex_psiquiatrico/ex_psiquiatrico.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('exPsiquiatricoModalBody').innerHTML = response;
            $('#exPsiquiatricoModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#exPsiquiatricoModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#exPsiquiatricoModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaExamen(idPaciente) {

    $.ajax({
        url: './dato/get_ex_psiquiatrico.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var ex = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Forma de presentarse</th>';
            html += '<th>Vestimenta</th>';
            html += '<th>Peso</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            ex.forEach(function (ex) {
                html += '<tr>';
                html += '<td>' + ex.forma_presentarse + '</td>';
                html += '<td>' + ex.vestimenta + '</td>';
                html += '<td>' + ex.peso + '</td>';
                html += '<td>';
                html += '<button id="editEx" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + ex.id + '">Editar</button> ';
                html += '<button id="deleteEx" class="btn btn-danger btn-sm btn-delete" data-id="' + ex.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-ex" data-id="' + ex.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaExPsiquiatrico').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#exPsiquiatricoModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaExamen(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#exPsiquiatricoModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoExPsiquiatrico').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#forma_presentarse').val('');
        $('#vestimenta').val('');
        $('#peso_psiquiatrico').val('');
        $('#grado_actividad').val('');
        $('#cualidad_formal').val('');
        $('#pertinente').val('');
        $('#signos_ansiedad').val('');
        $('#bradilalia').val('');
        $('#cooperativo').val('');
        $('#comunicativo').val('');
        $('#escala_actitudes').val('');

        $('#exPsiquiatricoIdPaciente').val(id);
        $('#exPsiquiatricoNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarExPsiquiatrico').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarExamenPsiquiatricoModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editEx', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_ex_psiquiatrico_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var ex = JSON.parse(response);

                $('#exPsiquiatricoId').val(ex.id);

                $('#forma_presentarse').val(ex.forma_presentarse);
                $('#vestimenta').val(ex.vestimenta);
                $('#peso_psiquiatrico').val(ex.peso);
                $('#grado_actividad').val(ex.grado_actividad);
                $('#cualidad_formal').val(ex.cualidad_formal);
                $('#pertinente').val(ex.pertinente);
                $('#signos_ansiedad').val(ex.signos_ansiedad);
                $('#bradilalia').val(ex.bradilalia);
                $('#cooperativo').val(ex.cooperativo);
                $('#comunicativo').val(ex.comunicativo);
                $('#escala_actitudes').val(ex.escala_actitudes);

                $('#exPsiquiatricoNombreCarga').val(ex.nombre_paciente);
                $('#exPsiquiatricoIdPaciente').val(ex.id_paciente);


                // Establecer data-action a "edit"
                $('#btnGuardarExPsiquiatrico').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarExamenPsiquiatricoModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarExPsiquiatrico').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/ex_psiquiatrico/editar_ex.php' : './submenu/ex_psiquiatrico/agregar_ex.php';
        var formData = $('#formExPsiquiatrico').serialize();
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                const idPaciente = $('#id').val();
                cargarListaExamen(idPaciente);
                $('#agregarExamenPsiquiatricoModal').modal('hide');
                console.log(response)
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteEx', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/ex_psiquiatrico/eliminar_ex.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('fisica eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaExamen(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

    //PDF
    $(document).on('click', '.btn-pdf-ex', function () {
        var exId = $(this).data('id');

        // Petición AJAX para obtener los datos del examen psiquiátrico
        $.ajax({
            url: './dato/get_ex_psiquiatrico_id.php', // Cambia esta URL según sea necesario
            type: 'GET',
            data: { id: exId },
            success: function (response) {
                var examen = JSON.parse(response);

                // Crear el documento PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Título del documento
                doc.setFontSize(16);
                doc.text("Examen Psiquiátrico del Paciente", 10, 10);

                // Información básica
                doc.setFontSize(12);
                doc.text("Paciente: " + examen.nombre_paciente, 10, 20);

                // Tabla de datos
                doc.autoTable({
                    startY: 30,
                    head: [['Campo', 'Valor']],
                    body: [
                        ['Forma de Presentarse', examen.forma_presentarse],
                        ['Vestimenta', examen.vestimenta],
                        ['Peso', examen.peso],
                        ['Grado de Actividad', examen.grado_actividad],
                        ['Cualidad Formal', examen.cualidad_formal],
                        ['Pertinente', examen.pertinente],
                        ['Signos de Ansiedad', examen.signos_ansiedad],
                        ['Bradilalia', examen.bradilalia],
                        ['Cooperativo', examen.cooperativo],
                        ['Comunicativo', examen.comunicativo],
                        ['Escala de Actitudes', examen.escala_actitudes]
                    ],
                });

                // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
                const finalY = doc.lastAutoTable.finalY || 70;

                // Agregar el logo centrado después de la tabla
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29; // Ancho de la imagen
                    const imgHeight = 25; // Altura de la imagen
                    const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                    const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                    const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                    // Agregar la imagen al PDF
                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    // Abrir el PDF en una nueva pestaña del navegador
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl; // Establecer la fuente de la imagen
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos del examen psiquiátrico:', textStatus, errorThrown);
            }
        });
    });


});

// FIN EXAMEN PSIQUIATRICO

//ANTECENDES FAMILIARES

// Función para cargar el modal de egreso y ocultar el formulario principal
function loadAntecendentesFamiliares() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/antecedenteFamiliar/antecedenteFamiliar.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('antecedentesFamiliaresModalBody').innerHTML = response;
            $('#antecedentesFamiliaresModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#antecedentesFamiliaresModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#antecedentesFamiliaresModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaAntecedentes(idPaciente) {

    $.ajax({
        url: './dato/get_paci_antecendes_familiares.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var antecedente = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Grupo familiar de origen</th>';
            html += '<th>Interrelación ambiente psicológico del hogar</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            antecedente.forEach(function (antecedente) {
                html += '<tr>';
                html += '<td>' + antecedente.antecedentes_familiar_1 + '</td>';
                html += '<td>' + antecedente.antecedentes_familiar_2 + '</td>';
                html += '<td>';
                html += '<button id="editAntecedente" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + antecedente.id + '">Editar</button> ';
                html += '<button id="deleteAntecedente" class="btn btn-danger btn-sm btn-delete" data-id="' + antecedente.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-antecedente-f" data-id="' + antecedente.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaAntecendesFamiliares').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#antecedentesFamiliaresModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaAntecedentes(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#antecedentesFamiliaresModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoAntecendesFamiliares').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#antecedentesFamiliar1').val('');
        $('#antecedentesFamiliar2').val('');
        $('#antecedentesFamiliar3').val('');
        $('#antecedentesFamiliar4').val('');
        $('#antecedentesFamiliar5').val('');
        $('#antecedentesFamiliar6').val('');
        $('#antecedentesFamiliaresIdPaciente').val(id);
        $('#antecedentesFamiliaresNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarAntecedentes').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregarAntecedentesFamiliaresModal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editAntecedente', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_paci_antecendes_familiares_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var antecedente = JSON.parse(response);

                $('#antecedentesFamiliaresId').val(antecedente.id);
                $('#antecedentesFamiliar1').val(antecedente.antecedentes_familiar_1);
                $('#antecedentesFamiliar2').val(antecedente.antecedentes_familiar_2);
                $('#antecedentesFamiliar3').val(antecedente.antecedentes_familiar_3);
                $('#antecedentesFamiliar4').val(antecedente.antecedentes_familiar_4);
                $('#antecedentesFamiliar5').val(antecedente.antecedentes_familiar_5);
                $('#antecedentesFamiliar6').val(antecedente.antecedentes_familiar_6);
                $('#antecedentesFamiliaresNombreCarga').val(antecedente.nombre_paciente);
                $('#antecedentesFamiliaresIdPaciente').val(antecedente.id_paciente);


                // Establecer data-action a "edit"
                $('#btnGuardarAntecedentes').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregarAntecedentesFamiliaresModal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarAntecedentes').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/antecedenteFamiliar/editar_antecedente_familiar.php' : './submenu/antecedenteFamiliar/agregar_antecedente_familiar.php';
        var formData = $('#formAntecedenteFamiliar').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                // Si el servidor envía un JSON, lo parseamos
                var jsonResponse = JSON.parse(response);

                // Solo mostramos el mensaje de la respuesta
                alert(jsonResponse.message);
                const idPaciente = $('#id').val();
                cargarListaAntecedentes(idPaciente);
                $('#agregarAntecedentesFamiliaresModal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Verifica el error en detalle
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                console.log('Detalles del error:', jqXHR.responseText);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteAntecedente', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/antecedenteFamiliar/eliminar_antecedente_familiar.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    // Si el servidor envía un JSON, lo parseamos
                    var jsonResponse = JSON.parse(response);

                    // Solo mostramos el mensaje de la respuesta
                    alert(jsonResponse.message);
                    const idPaciente = $('#id').val();
                    cargarListaAntecedentes(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

    //GENERAR PDF
    $(document).on('click', '.btn-pdf-antecedente-f', function () {
        var pacienteId = $(this).data('id');

        // Petición AJAX para obtener los datos de los antecedentes familiares
        $.ajax({
            url: './dato/get_paci_antecendes_familiares_id.php', // Asegúrate de que esta URL es correcta
            type: 'GET',
            data: { id: pacienteId },
            success: function (response) {
                var antecedentes = JSON.parse(response);

                // Crear el documento PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Título del documento
                doc.setFontSize(16);
                doc.text("Antecedentes Familiares del Paciente", 10, 10);

                // Información básica
                doc.setFontSize(12);
                doc.text("Paciente: " + antecedentes.nombre_paciente, 10, 20);

                // Tabla de datos
                doc.autoTable({
                    startY: 30,
                    head: [['Campo', 'Valor']],
                    body: [
                        ['Grupo Familiar', antecedentes.antecedentes_familiar_1],
                        ['Interrelacion', antecedentes.antecedentes_familiar_2],
                        ['Estado cultural, social y económico', antecedentes.antecedentes_familiar_3],
                        ['Cambios y eventos', antecedentes.antecedentes_familiar_4],
                        ['Antecedentes psiquiátricos, psicosomáticos y neurológicos', antecedentes.antecedentes_familiar_5],
                        ['Conclusiones del entrevistador sobre el tipo de familia', antecedentes.antecedentes_familiar_6],
                    ],
                    // Definir estilos de columnas
                    columnStyles: {
                        0: { cellWidth: 60 }, // Ancho de la primera columna (Campo)
                        1: { cellWidth: 120 }, // Ancho de la segunda columna (Valor), ajustar para textos largos
                    },
                    styles: {
                        cellPadding: 3, // Margen interno de las celdas
                        fontSize: 10, // Tamaño de la fuente
                        overflow: 'linebreak', // Permitir salto de línea en celdas con contenido largo
                    },
                });

                // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
                const finalY = doc.lastAutoTable.finalY || 70;

                // Agregar el logo centrado después de la tabla
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29; // Ancho de la imagen
                    const imgHeight = 25; // Altura de la imagen
                    const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                    const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                    const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                    // Agregar la imagen al PDF
                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    // Abrir el PDF en una nueva pestaña del navegador
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl; // Establecer la fuente de la imagen
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de los antecedentes familiares:', textStatus, errorThrown);
            }
        });
    });



});

//FIN ANTECENDES FAMILIARES

//ANTEDENTES PERSONALES

// Función para cargar el modal de egreso y ocultar el formulario principal
function loadAntecendentesPersonales() {
    const id = document.getElementById('id').value;
    const nombre = document.getElementById('nombre').value;
    const benef = document.getElementById('benef').value;
    const parentesco = document.getElementById('parentesco').value;

    $.ajax({
        url: './submenu/antecedentePersonales/antecedentePersonales.php',
        type: 'GET',
        data: {
            id: id,
            nombre: nombre,
            benef: benef,
            parentesco: parentesco
        },
        success: function (response) {
            document.getElementById('antecedentesPersonalesModalBody').innerHTML = response;
            $('#antecedentesPersonalesModal').modal('show'); // Mostrar el modal de egreso
            $('#formPaciente').hide(); // Ocultar el formulario principal usando jQuery al cargar el modal
        }

    });
}

// Función para mostrar el modal de agregar/editar paciente al hacer clic en "Volver" dentro del modal de egreso
$('#antecedentesPersonalesModal').on('click', '.btn-volver', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al hacer clic en "Volver"
    $('#antecedentesPersonalesModal').modal('hide'); // Ocultar el modal de egreso
    $('#agregarPacienteModal').modal('show'); // Mostrar el modal de agregar/editar paciente
});

// Función para cargar la lista de egresos desde la base de datos
function cargarListaAntecendesPersonales(idPaciente) {

    $.ajax({
        url: './dato/get_antecendetes_personales.php',
        type: 'GET',
        data: { id_paciente: idPaciente },
        success: function (response) {
            // Parsear la respuesta JSON si es necesario
            var antecedentesPersonales = JSON.parse(response);
            var html = '<table class="table table-striped table-bordered" style="margin-left: 1rem;">';
            html += '<thead class="table-custom">';
            html += '<tr>';
            html += '<th>Complicaciones en el nacimiento</th>';
            html += '<th>Desarrollo y enfermedades de la niñez</th>';
            html += '<th>Acciones</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            antecedentesPersonales.forEach(function (antecedentesPersonales) {
                html += '<tr>';
                html += '<td>' + antecedentesPersonales.complicaciones_nacimiento + '</td>';
                html += '<td>' + antecedentesPersonales.desarrollo_ninez + '</td>';
                html += '<td>';
                html += '<button id="editAntecentePersonal" class="btn btn-primary btn-custom-save btn-sm btn-edit" data-id="' + antecedentesPersonales.id + '">Editar</button> ';
                html += '<button id="deleteAntecentePersonal" class="btn btn-danger btn-sm btn-delete" data-id="' + antecedentesPersonales.id + '">Eliminar</button>';
                html += '<button class="btn btn-success btn-sm btn-pdf-personales" data-id="' + antecedentesPersonales.id + '">Generar PDF</button>';
                html += '</td>';
                html += '</tr>';
            });

            html += '</tbody>';
            html += '</table>';

            $('#listaAntecendesPersonales').html(html); // Insertar el HTML generado en el contenedor
        }
    });
}

// Evento al mostrar el modal de egresos
$('#antecedentesPersonalesModal').on('shown.bs.modal', function () {
    const idPaciente = document.getElementById('id').value; // Obtener el ID del paciente desde un campo oculto en el formulario
    cargarListaAntecendesPersonales(idPaciente); // Cargar la lista de egresos al mostrar el modal
});

// Función para mostrar nuevamente el formulario principal al cerrar el modal de egreso
$('#antecedentesPersonalesModal').on('hidden.bs.modal', function () {
    $('#formPaciente').show(); // Mostrar el formulario principal al cerrar el modal de egreso
});

$(document).ready(function () {
    // Mostrar el modal de agregar práctica al hacer clic en el botón "Agregar"
    $('#nuevoAntecendesPersonales').on('click', function () {
        const id = $('#id').val();
        const nombre = $('#nombre').val();

        // Llenar los campos del nuevo modal con la información necesaria
        $('#complicaciones_nacimiento').val('');
        $('#desarrollo_ninez').val('');
        $('#enfermedades_principales').val('');
        $('#sistema_nervioso').val('');
        $('#estudios').val('');
        $('#actividad_sexual').val('');
        $('#historial_marital').val('');
        $('#embarazos_hijos').val('');
        $('#interrelacion_familiar').val('');
        $('#actividades_laborales').val('');
        $('#habitos').val('');
        $('#intereses').val('');
        $('#actividad_social').val('');
        $('#creencias_religiosas').val('');
        $('#toxicomanias').val('');
        $('#rasgos_personalidad').val('');
        $('#antecedentesPersonalesIdPaciente').val(id);
        $('#antecedentesPersonalesNombreCarga').val(nombre);



        // Establecer data-action a "add"
        $('#btnGuardarAntecedentesPersonales').attr('data-action', 'add');

        // Mostrar el modal de agregar práctica
        $('#agregar_antecedentes_modal').modal('show');
    });

    // Mostrar el modal de editar práctica al hacer clic en el botón "Editar"
    $(document).on('click', '#editAntecentePersonal', function () {
        var pracId = $(this).data('id');

        $.ajax({
            url: './dato/get_antecendes_personales_id.php',
            type: 'GET',
            data: { id: pracId },
            success: function (response) {
                var antecedentesPersonales = JSON.parse(response);

                $('#antecedentePersonalesId').val(antecedentesPersonales.id);
                $('#complicaciones_nacimiento').val(antecedentesPersonales.complicaciones_nacimiento);
                $('#desarrollo_ninez').val(antecedentesPersonales.desarrollo_ninez);
                $('#enfermedades_principales').val(antecedentesPersonales.enfermedades_principales);
                $('#sistema_nervioso').val(antecedentesPersonales.sistema_nervioso);
                $('#estudios').val(antecedentesPersonales.estudios);
                $('#actividad_sexual').val(antecedentesPersonales.actividad_sexual);
                $('#historial_marital').val(antecedentesPersonales.historial_marital);
                $('#embarazos_hijos').val(antecedentesPersonales.embarazos_hijos);
                $('#interrelacion_familiar').val(antecedentesPersonales.interrelacion_familiar);
                $('#actividades_laborales').val(antecedentesPersonales.actividades_laborales);
                $('#habitos').val(antecedentesPersonales.habitos);
                $('#intereses').val(antecedentesPersonales.intereses);
                $('#actividad_social').val(antecedentesPersonales.actividad_social);
                $('#creencias_religiosas').val(antecedentesPersonales.creencias_religiosas);
                $('#toxicomanias').val(antecedentesPersonales.toxicomanias);
                $('#rasgos_personalidad').val(antecedentesPersonales.rasgos_personalidad);
                $('#antecedentesPersonalesNombreCarga').val(antecedentesPersonales.nombre_paciente);
                $('#antecedentesPersonalesIdPaciente').val(antecedentesPersonales.id_paciente);


                // Establecer data-action a "edit"
                $('#btnGuardarAntecedentesPersonales').attr('data-action', 'edit');

                // Mostrar el modal de agregar práctica (se reutiliza para editar)
                $('#agregar_antecedentes_modal').modal('show');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de la práctica:', textStatus, errorThrown);
            }
        });
    });

    // Guardar la práctica al hacer clic en "Guardar" dentro del modal
    $('#btnGuardarAntecedentesPersonales').on('click', function () {
        var action = $(this).attr('data-action');
        var url = action === 'edit' ? './submenu/antecedentePersonales/editar_antecedentes_personales.php' : './submenu/antecedentePersonales/agregar_antecedentes_personales.php';
        var formData = $('#formAntecedentesPersonales').serialize();

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function (response) {
                var jsonResponse = JSON.parse(response);

                // Solo mostramos el mensaje de la respuesta
                alert(jsonResponse.message);
                const idPaciente = $('#id').val();
                cargarListaAntecendesPersonales(idPaciente);
                $('#agregar_antecedentes_modal').modal('hide');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error en guardar práctica:', textStatus, errorThrown);
                alert('Error al guardar la práctica');
            }
        });


    });

    // Eliminar práctica al hacer clic en "Eliminar"
    $(document).on('click', '#deleteAntecentePersonal', function () {
        var pracId = $(this).data('id');;
        if (confirm('¿Estás seguro de que deseas eliminar este traslado?')) {
            $.ajax({
                url: './submenu/antecedentePersonales/eliminar_antecedentes_personales.php',
                type: 'POST',
                data: { id: pracId },
                success: function (response) {
                    console.log('Op eliminada:', response);

                    // Recargar la lista de prácticas después de eliminar
                    const idPaciente = $('#id').val();
                    cargarListaAntecendesPersonales(idPaciente);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('Error al eliminar la práctica:', textStatus, errorThrown);
                }
            });
        }
    });

    //GENERAR PDF
    $(document).on('click', '.btn-pdf-personales', function () {
        var pacienteId = $(this).data('id');

        // Petición AJAX para obtener los datos de los antecedentes personales
        $.ajax({
            url: './dato/get_antecendes_personales_id.php', // Asegúrate de que esta URL es correcta
            type: 'GET',
            data: { id: pacienteId },
            success: function (response) {
                var antecedentes = JSON.parse(response);

                // Crear el documento PDF
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Título del documento
                doc.setFontSize(16);
                doc.text("Antecedentes Personales del Paciente", 10, 10);

                // Información básica
                doc.setFontSize(12);
                doc.text("Paciente: " + antecedentes.nombre_paciente, 10, 20);

                // Tabla de datos con ajuste de texto largo
                doc.autoTable({
                    startY: 30,
                    head: [['Campo', 'Valor']],
                    body: [
                        ['Complicaciones al Nacimiento', antecedentes.complicaciones_nacimiento],
                        ['Desarrollo en la Niñez', antecedentes.desarrollo_ninez],
                        ['Enfermedades Principales', antecedentes.enfermedades_principales],
                        ['Sistema Nervioso', antecedentes.sistema_nervioso],
                        ['Estudios', antecedentes.estudios],
                        ['Actividad Sexual', antecedentes.actividad_sexual],
                        ['Historial Marital', antecedentes.historial_marital],
                        ['Embarazos e Hijos', antecedentes.embarazos_hijos],
                        ['Interrelación Familiar', antecedentes.interrelacion_familiar],
                        ['Actividades Laborales', antecedentes.actividades_laborales],
                        ['Hábitos', antecedentes.habitos],
                        ['Intereses', antecedentes.intereses],
                        ['Actividad Social', antecedentes.actividad_social],
                        ['Creencias Religiosas', antecedentes.creencias_religiosas],
                        ['Toxicomanías', antecedentes.toxicomanias],
                        ['Rasgos de Personalidad', antecedentes.rasgos_personalidad],
                    ],
                    styles: {
                        overflow: 'linebreak',  // Permitir saltos de línea en contenido largo
                        cellWidth: 'auto',      // Ajustar el ancho de las celdas según el contenido
                        fontSize: 10,           // Ajustar el tamaño de fuente para caber mejor
                    },
                    columnStyles: {
                        0: { cellWidth: 50 },   // Ancho fijo para la columna "Campo"
                        1: { cellWidth: 120 },  // Ancho fijo para la columna "Valor"
                    },
                    bodyStyles: {
                        valign: 'top',  // Alinear el contenido al inicio de la celda
                    },
                    margin: { top: 30 },  // Establecer margen superior
                });

                // Obtener la altura de la tabla generada para colocar la imagen debajo de la tabla
                const finalY = doc.lastAutoTable.finalY || 70;

                // Agregar el logo centrado después de la tabla
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29; // Ancho de la imagen
                    const imgHeight = 25; // Altura de la imagen
                    const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
                    const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
                    const yImg = finalY + 15; // La posición Y justo debajo de la tabla

                    // Agregar la imagen al PDF
                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    // Abrir el PDF en una nueva pestaña del navegador
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl; // Establecer la fuente de la imagen
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log('Error al obtener los datos de los antecedentes personales:', textStatus, errorThrown);
            }
        });
    });





});

//FIN ANTEDENTES PERSONALES