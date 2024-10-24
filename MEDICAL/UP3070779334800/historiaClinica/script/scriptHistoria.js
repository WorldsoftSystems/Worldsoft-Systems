function formatDate(dateString) {
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}

//CERRAR SESION
function confirmLogout(event) {
    // Evitar la acción predeterminada del botón
    event.preventDefault();

    // Mostrar una ventana de confirmación
    var userConfirmed = confirm("¿Estás seguro de que deseas cerrar sesión?");

    // Si el usuario confirma, redirigir al script de cierre de sesión
    if (userConfirmed) {
        window.location.href = './loginHC/logout.php';
    }
    // Si el usuario cancela, no hacer nada
}

//BARRA DE BUSQUEDA 
document.getElementById('searchInput').addEventListener('input', function () {
    var searchValue = this.value.toLowerCase();
    var rows = document.querySelectorAll('#pacientesTable tbody tr');

    rows.forEach(row => {
        var nombre = row.cells[1].textContent.toLowerCase();
        var benef = row.cells[2].textContent.toLowerCase();
        if (nombre.includes(searchValue) || benef.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Inicializar tooltips de Bootstrap
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

//modals
$(document).ready(function () {
    $('.btn-hc').on('click', function () {
        var pacienteId = $(this).data('id');
        var modalType = $(this).data('modal');
        // Almacenar el pacienteId en el modal como atributo de datos
        $('#hcAmbModal').data('paciente-id', pacienteId);
        $.ajax({
            url: './gets/get_paciente_con_id.php',
            type: 'GET',
            data: { id: pacienteId },
            success: function (response) {
                var data = JSON.parse(response);

                // Rellenar encabezados y datos en el modal H.C AMB
                if (modalType === 'amb') {
                    $('#nombre-amb, #nombre-amb-tab2, #nombre-amb-tab3').text(data.nombre);
                    $('#benef-amb, #benef-amb-tab2, #benef-amb-tab3').text(data.benef + " / " + data.parentesco);
                    $('#obra-social-amb, #obra-social-amb-tab2, #obra-social-amb-tab3').text(data.obra);

                    $('#edad-amb, #edad-amb-tab2, #edad-amb-tab3').text(data.edad);
                    $('#dni-amb, #dni-amb-tab2, #dni-amb-tab3').text(data.nro_doc);

                    cargarParentesco();

                    $('#hcAmbModal').modal('show');
                }
                // Rellenar encabezados y datos en el modal H.C INT
                else if (modalType === 'int') {
                    $('#nombre-int, #nombre-int-tab2, #nombre-int-tab3').text(data.nombre);
                    $('#benef-int, #benef-int-tab2, #benef-int-tab3').text(data.benef + " / " + data.parentesco);
                    $('#obra-social-int, #obra-social-int-tab2, #obra-social-int-tab3').text(data.obra);

                    $('#hcIntModal').modal('show');
                }
            }
        });

        function cargarParentesco() {
            $.ajax({
                url: '../pacientes/dato/get_respon.php',
                type: 'GET',
                data: { id_paciente: pacienteId },
                dataType: 'json',
                success: function (data) {
                    // Limpiar las opciones existentes excepto la primera
                    $('#hc_familiar').find('option:not(:first)').remove();

                    // Añadir las nuevas opciones
                    data.forEach(function (item) {
                        $('#hc_familiar').append(new Option(item.nombreYapellido, item.id));
                    });

                    // Añadir la opción "Nueva Responsable"
                    $('#hc_familiar').append(new Option("Nueva Responsable", "nueva_responsable"));
                },
                error: function (error) {
                    console.error("Error fetching data: ", error);
                }
            });
        }

        // Manejar el cambio en el dropdown
        $('#hc_familiar').change(function () {
            if ($(this).val() === 'nueva_responsable') {
                // Mostrar el modal para agregar un nuevo responsable
                $('#agregarResponModal').modal('show');
            }
        });

        $('#btnGuardarRespon').click(function () {
            // Recoger los datos del formulario
            var responData = {
                id_paciente: pacienteId,  // Cambia a 'id_paciente'
                respon_nombre: $('#respon_nombre').val(),  // Cambia a 'respon_nombre'
                respon_tel: $('#respon_tel').val(),  // Cambia a 'respon_tel'
                respon_parent: $('#respon_parent').val(),  // Cambia a 'respon_parent'
                respon_dni: $('#respon_dni').val(),  // Cambia a 'respon_dni'
                respon_dom: $('#respon_dom').val(),  // Cambia a 'respon_dom'
                respon_locali: $('#respon_locali').val()  // Cambia a 'respon_locali'
            };

            // Enviar los datos al backend usando AJAX
            $.ajax({
                url: '../pacientes/submenu/respon/agregar_respon.php',
                type: 'POST',
                data: responData,
                success: function (response) {

                    cargarParentesco();
                    $('#agregarResponModal').modal('hide');

                },
                error: function (error) {
                    console.error("Error guardando el responsable:", error);
                }
            });
        });

        document.getElementById('ver-admisiones-btn').addEventListener('click', function () {
            // Cerrar el modal actual
            $('#admission').modal('hide');

            console.log(pacienteId)
            $.ajax({
                url: './gets/get_hist_con_id_paci.php',
                type: 'GET',
                data: { id: pacienteId },
                success: function (response) {
                    // Parsear la respuesta y llenar los datos en el nuevo modal
                    var data = JSON.parse(response);
                    // Llenar los campos del modal
                    $('#id-admision').text(data.id);
                    $('#id-paciente').text(data.nombre);
                    $('#id-responsable').text(data.responsable);
                    $('#id-prof').text(data.profesional);
                    $('#asc-psiquico').text(data.asc_psiquico);
                    $('#act-psiquica').text(data.act_psiquica);
                    $('#act').text(data.act);
                    $('#orientacion').text(data.orientacion);
                    $('#conciencia').text(data.conciencia);
                    $('#memoria').text(data.memoria);
                    $('#atencion').text(data.atencion);
                    $('#pensamiento').text(data.pensamiento);
                    $('#cont-pensamiento').text(data.cont_pensamiento);
                    $('#sensopercepcion').text(data.sensopercepcion);
                    $('#afectividad').text(data.afectividad);
                    $('#inteligencia').text(data.inteligencia);
                    $('#juicio').text(data.juicio);
                    $('#esfinteres').text(data.esfinteres);
                    $('#tratamiento').text(data.tratamiento);
                    $('#evolucion').text(data.evolucion);
                    $('#hc-cada-medi').text(data.hc_cada_medi);
                    $('#hc-desc-medi').text(data.hc_desc_medi);
                    $('#hc-fecha').text(formatDate(data.hc_fecha));

                    // Llenar más campos según sea necesario

                    // Generar el código QR con todos los campos, cada uno en una línea separada
                    var qrContent = `
                    ID Admisión: ${data.id}
                    Paciente: ${data.nombre}
                    Edad: ${data.edad}
                    Diag: ${data.full_diag}
                    Responsable: ${data.responsable}
                    Profesional: ${data.profesional}
                    Asc. Psíquico: ${data.asc_psiquico}
                    Act. Psíquica: ${data.act_psiquica}
                    Actitud: ${data.act}
                    Orientación: ${data.orientacion}
                    Conciencia: ${data.conciencia}
                    Memoria: ${data.memoria}
                    Atención: ${data.atencion}
                    Pensamiento: ${data.pensamiento}
                    Cont. Pensamiento: ${data.cont_pensamiento}
                    Sensopercepción: ${data.sensopercepcion}
                    Afectividad: ${data.afectividad}
                    Inteligencia: ${data.inteligencia}
                    Juicio: ${data.juicio}
                    Esfínteres: ${data.esfinteres}
                    Tratamiento: ${data.tratamiento}
                    Evolución: ${data.evolucion}
                    CADA Medi: ${data.hc_cada_medi}
                    Desc. Medi: ${data.hc_desc_medi}
                    Fecha: ${formatDate(data.hc_fecha)}
                    `.trim(); // Eliminar espacios en blanco adicionales

                    var qr = new QRious({
                        element: document.getElementById('qr-code'),
                        size: 400, // Tamaño del QR
                        value: qrContent, // Contenido del QR con saltos de línea
                        level: 'H' // Nivel de corrección de errores alto
                    });


                    // Abrir el nuevo modal
                    $('#admissionsModal').modal('show');
                },
                error: function (error) {
                    console.error("Error al obtener los datos:", error);
                }
            });
        });




    });





});

//COMPLETAR DIAG
$.ajax({
    url: '../pacientes/dato/get_diags.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            var optionText = item.codigo + ' - ' + item.descripcion;
            $('#hc_diag').append(new Option(optionText, item.id));

        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});

$.ajax({
    url: '../pacientes/dato/get_parentescos.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            var optionText = item.codigo + ' - ' + item.descripcion;
            $('#respon_parent').append(new Option(optionText, item.id));
        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});

$.ajax({
    url: '../pacientes/dato/get_medicacion.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            $('#hc_medi').append(new Option(item.descripcion, item.id));
        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});

$.ajax({
    url: '../pacientes/dato/get_profesional.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            $('#hc_prof').append(new Option(item.nombreYapellido, item.id_prof));
        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});

//MANEJO DE BACK 
$(document).ready(function () {
    // Detectar el clic en el botón "Guardar"
    $('#guardarBtn').on('click', function () {
        // Obtener la pestaña activa
        var activeTab = $('.tab-pane.active').attr('id');
        var pacienteId = $('#hcAmbModal').data('paciente-id');

        // Crear un objeto de datos para enviar al PHP
        var formData = {};

        if (activeTab === 'admission') {
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
                !inteligencia || !juicio || !esfinteres || !tratamiento || !evolucion ||
                !$('#hc_diag').val() || !$('#hc_medi').val()) {

                alert('Por favor complete todos los campos obligatorios.');
                return; // Detiene la ejecución si falta algún dato
            }

            formData = {
                id_paciente: pacienteId,
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
                id_responsable: $('#hc_familiar').val()

                // Añade más datos según sea necesario
            };

        }

        // Enviar los datos al archivo PHP mediante AJAX
        $.ajax({
            url: './schedule/agregar_admision.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                console.log(response)
                alert('Datos guardados correctamente');
            },
            error: function (xhr, status, error) {
                console.log(error)
                alert('Ocurrió un error al guardar los datos');
            }
        });

    });
});

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("hc_amb").addEventListener("click", function() {
        window.location.href = "../pacientes/paciente.php";
    });

    document.getElementById("hc_int").addEventListener("click", function() {
        window.location.href = "../pacientes/paciente.php";
    });
});





