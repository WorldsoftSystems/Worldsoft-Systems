document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success') && urlParams.get('success') === 'true') {
        alert("El paciente se ha editado correctamente.");
        urlParams.delete('success');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

function formatDate(dateString) {
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}

document.addEventListener("DOMContentLoaded", function () {
    function editarPaciente(paciente) {
        // Configura el formulario para la edición
        var form = document.getElementById('formPaciente');
        form.action = './editarPaciente.php';
        form.dataset.mode = 'edit'; // Agrega un atributo de datos para identificar el modo
        document.getElementById('id').value = paciente.id;
        document.getElementById('nombre').value = paciente.nombre;
        document.getElementById('obra_social').value = paciente.obra_social;
        document.getElementById('fecha_nac').value = paciente.fecha_nac;
        document.getElementById('sexo').value = paciente.sexo;
        document.getElementById('domicilio').value = paciente.domicilio;
        document.getElementById('localidad').value = paciente.localidad;
        document.getElementById('partido').value = paciente.partido;
        document.getElementById('c_postal').value = paciente.c_postal;
        document.getElementById('telefono').value = paciente.telefono;
        document.getElementById('tipo_doc').value = paciente.tipo_doc;
        document.getElementById('nro_doc').value = paciente.nro_doc;
        document.getElementById('admision').value = paciente.admision;
        document.getElementById('id_prof').value = paciente.id_prof;
        document.getElementById('benef').value = paciente.benef;
        document.getElementById('parentesco').value = paciente.parentesco;
        document.getElementById('hijos').value = paciente.hijos;
        document.getElementById('ocupacion').value = paciente.ocupacion;
        document.getElementById('tipo_afiliado').value = paciente.tipo_afiliado;
        document.getElementById('boca_atencion').value = paciente.boca_atencion;
        document.getElementById('nro_hist_amb').value = paciente.nro_hist_amb;
        document.getElementById('nro_hist_int').value = paciente.nro_hist_int;
        document.getElementById('hora_admision').value = paciente.hora_admision;
        document.getElementById('ugl_paciente').value = paciente.ugl_descripcion;


        // Primero, carga las modalidades
        $.ajax({
            url: './dato/get_modalidad.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                // Carga todas las opciones disponibles
                $('#modalidad_act').empty();
                data.forEach(function (item) {
                    var optionText = item.codigo + ' - ' + item.descripcion;
                    $('#modalidad_act').append(new Option(optionText, item.id));
                });

                // Luego, selecciona la modalidad actual del paciente
                $.ajax({
                    url: './dato/get_modalidad_paci_id.php',
                    type: 'GET',
                    dataType: 'json',
                    data: { id_paciente: paciente.id },
                    success: function (data) {
                        // Limpia las opciones actuales del select
                        $('#modalidad_act').val(data[0]?.id || '').trigger('change');
                    },
                    error: function (xhr, status, error) {
                        console.error("Error fetching modalidad: ", error);
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching modalidades: ", error);
            }
        });



        $.ajax({
            url: './dato/verificar_egreso.php',
            type: 'GET',
            data: { id_paciente: paciente.id },
            success: function (response) {
                try {
                    const data = JSON.parse(response);
                    if (data.egresado) {
                        $('#bajaMensaje').html('<h1 style="color: red !important;">PACIENTE EGRESADO</h1>');
                    } else {
                        $('#bajaMensaje').html('');
                    }
                } catch (e) {
                    console.error("Error parsing response from verificar_egreso.php: ", e);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error('Error en verificar egreso:', textStatus, errorThrown);
            }
        });

        var modal = new bootstrap.Modal(document.getElementById('agregarPacienteModal'));
        modal.show();
    }

    function limpiarFormulario() {
        var form = document.getElementById('formPaciente');
        form.reset();
        form.action = './agregarPaciente.php'; // Restablece la acción del formulario
        form.dataset.mode = 'add'; // Restablece el modo
        // Limpiar o vaciar el div de bajaMensaje
        var bajaMensaje = document.getElementById('bajaMensaje');
        bajaMensaje.innerHTML = ''; // Vacía el contenido del div

        // Aquí llamamos al backend para obtener los parámetros
        fetch('./dato/obtener_parametros.php')
            .then(response => response.json()) // Convertimos la respuesta en JSON
            .then(data => {
                console.log(data)
                document.getElementById('nro_hist_amb').value = data[0].num_hist_amb;
                document.getElementById('nro_hist_int').value = data[0].num_hist_int;
            })
            .catch(error => {
                console.error('Error al obtener los datos del backend:', error);
            });
    }

    window.editarPaciente = editarPaciente;

    var btnAgregarPacienteModal = document.querySelector('button[data-bs-target="#agregarPacienteModal"]');
    if (btnAgregarPacienteModal) {
        btnAgregarPacienteModal.addEventListener('click', limpiarFormulario);
    } else {
        console.warn('Botón de agregar paciente modal no encontrado.');
    }

    function actualizarTablaPacientes() {
        $.ajax({
            url: './dato/obtenerPacientes.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                var tableBody = $('#pacientesTable tbody');
                tableBody.empty(); // Limpia el cuerpo de la tabla

                if (data.length > 0) {
                    data.forEach(function (paciente) {
                        var row = '<tr>' +
                            '<td>' + paciente.id + '</td>' +
                            '<td>' + paciente.nombre + '</td>' +
                            '<td>' + paciente.benef + '</td>' +
                            '<td>' + paciente.parentesco + '</td>' +
                            '<td>' +
                            '<button class="btn btn-custom-editar" onclick=\'editarPaciente(' + JSON.stringify(paciente) + ')\'><i class="fas fa-pencil-alt"></i></button>' +
                            '<a href="?eliminar=' + paciente.id + '" class="btn btn-danger" onclick="return confirm(\'¿Estás seguro de que deseas eliminar este paciente?\');"><i class="fas fa-trash-alt"></i></a>' +
                            '<button class="btn btn-info" onclick="openReportModal(' + paciente.id + ')"><i class="fas fa-file-alt"></i></button>' +
                            '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append('<tr><td colspan="5">No se encontraron resultados</td></tr>');
                }
            },
            error: function (error) {
                console.error("Error fetching data: ", error);
            }
        });
    }

    document.getElementById('formPaciente').addEventListener('submit', function (event) {
        event.preventDefault(); // Previene el envío del formulario de la manera tradicional
        var formData = $(this).serialize(); // Serializa los datos del formulario
        var formAction = $(this).attr('action'); // Obtiene la acción del formulario

        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            dataType: 'json', // Asegúrate de que jQuery espera JSON
            success: function (response) {
                console.log("Respuesta del servidor:", response);
                if (response.success) {
                    alert(response.message);
                    if (response.id) {
                        $('#id').val(response.id); // Actualiza el campo oculto con el ID del nuevo paciente
                    }
                    actualizarTablaPacientes(); // Actualiza la tabla con los nuevos datos
                } else {
                    alert(response.message);
                }
            },
            error: function (error) {
                console.error("Error al enviar datos: ", error);
            }
        });
    });

    // Carga inicial de la tabla
    actualizarTablaPacientes();
});


document.getElementById('btnBuscar').addEventListener('click', function () {
    // Obtener los valores de los campos de "Beneficio" y "Parentesco"
    var beneficio = $('#benef').val();
    var parentesco = $('#parentesco').val();

    // Realizar la solicitud al backend
    fetch(`https://worldsoftsystems.com.ar/buscar?beneficio=${beneficio}&parentesco=${parentesco}`, {
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
        .then(response => response.json())
        .then(data => {
            // Verificar si se encontró el nombre y apellido
            if (data.resultado) {
                function convertDateFormat(dateStr) {
                    const parts = dateStr.split('/');
                    // Asegúrate de que tienes el formato esperado
                    if (parts.length === 3) {
                        return `${parts[2]}-${parts[1]}-${parts[0]}`; // Devuelve en formato "yyyy-MM-dd"
                    }
                    return dateStr; // Devuelve original si no es un formato esperado
                }

                // Actualizar el campo de nombre y apellido con el resultado
                $('#ugl_paciente').val(data.resultado.ugl);
                $('#nombre').val(data.resultado.nombreApellido); // Asigna nombre y apellido
                // Convertir la fecha y asignarla
                const fechaNac = convertDateFormat(data.resultado.fecha_nac);
                $('#fecha_nac').val(fechaNac); // Asigna fecha de nacimiento

            } else {
                // Mostrar una alerta si no se encuentra el resultado
                alert("No se encontró ningún beneficiario con los datos proporcionados.");
            }
        })
        .catch(error => {
            console.error(error);
            // Muestra un mensaje de error si ocurre un error durante la solicitud
            alert("Error al buscar el nombre y apellido.");
        });
});


$(document).ready(function () {
    $.ajax({
        url: './dato/get_obras_sociales.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.siglas + ' - ' + item.razon_social;
                $('#obra_social').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_bocas_atencion.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.boca;
                $('#boca_atencion').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_parentescos.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#respon_parent').append(new Option(optionText, item.id));
                $('#visita_parent').append(new Option(optionText, item.id));
                $('#hc_familiar').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $('#agregarPracModal').on('shown.bs.modal', function () {
        var pacienteId = $('#id').val();

        // Limpiar el select antes de agregar nuevas opciones
        var $pracActividad = $('#pracActividad');
        $pracActividad.empty(); // Elimina todas las opciones actuales

        // Añadir la opción predeterminada
        $pracActividad.append(new Option('Seleccionar...', ''));

        // Hacer la llamada AJAX para llenar el select de actividades
        $.ajax({
            url: './dato/get_todas_las_practicas.php',
            type: 'GET',
            dataType: 'json',
            data: { paciente_id: pacienteId },
            success: function (data) {
                data.forEach(function (item) {
                    var optionText = item.codigo + ' - ' + item.descripcion;
                    $pracActividad.append(new Option(optionText, item.id));
                });

                // Si se está editando una práctica, seleccionar la opción correcta
                var pracActividadId = $pracActividad.data('selected-id');
                if (pracActividadId) {
                    $pracActividad.val(pracActividadId);
                }
            },
            error: function (error) {
                console.error("Error fetching data: ", error);
            }
        });
    });




    $.ajax({
        url: './dato/get_tipo_afiliado.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#tipo_afiliado').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_tipo_egreso.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#egreso_motivo').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_diags.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#egreso_diag').append(new Option(optionText, item.id));
                $('#evo_diag').append(new Option(optionText, item.id));
                $('#evo_diag_int').append(new Option(optionText, item.id));
                $('#paci_diag').append(new Option(optionText, item.id));
                $('#hc_diag').append(new Option(optionText, item.id));

            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });


    $.ajax({
        url: './dato/get_modalidad.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.codigo + ' - ' + item.descripcion;
                $('#modalidad').append(new Option(optionText, item.id));
                $('#modalidad_paci').append(new Option(optionText, item.id));
                $('#modalidad_act').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_profesional.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $('#id_prof').append(new Option(item.nombreYapellido, item.id_prof));
                $('#pracProfesional').append(new Option(item.nombreYapellido, item.id_prof));
                $('#hc_prof').append(new Option(item.nombreYapellido, item.id_prof));
                $('#medico_tratante').append(new Option(item.nombreYapellido, item.id_prof));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    let currentPage = 1;
    const resultsPerPage = 100;

    // Función para cargar medicamentos en el select
    function cargarMedicacion(page, search = '') {
        $.ajax({
            url: './dato/get_medicacion.php',
            type: 'GET',
            data: {
                page: page,
                per_page: resultsPerPage,
                search: search
            },
            dataType: 'json',
            success: function (data) {
                // Limpiar ambos selects antes de cargar nuevos registros
                $('#hc_medi').empty().append(new Option("Seleccionar...", ""));
                $('#mediDesc').empty().append(new Option("Seleccionar...", ""));

                // Agregar las opciones recibidas
                data.forEach(function (item) {
                    $('#hc_medi').append(new Option(item.descripcion, item.id));
                    $('#mediDesc').append(new Option(item.descripcion, item.id));
                });
            },
            error: function (error) {
                console.error("Error fetching data: ", error);
            }
        });
    }

    // Llamar a la función cuando el documento está listo
    $(document).ready(function () {
        cargarMedicacion(currentPage); // Cargar medicamentos al iniciar

        // Manejar la búsqueda en el input
        $('#searchMedicacion').on('input', function () {
            currentPage = 1; // Resetear a la primera página al buscar
            const search = $(this).val();
            cargarMedicacion(currentPage, search);
        });

        // Botón de siguiente página
        $('#nextPage').on('click', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario
            currentPage++;
            const search = $('#searchMedicacion').val();
            cargarMedicacion(currentPage, search);
        });

        // Botón de página anterior
        $('#prevPage').on('click', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario
            if (currentPage > 1) {
                currentPage--;
                const search = $('#searchMedicacion').val();
                cargarMedicacion(currentPage, search);
            }
        });

        $('#searchMedicacionHc').on('input', function () {
            currentPage = 1; // Resetear a la primera página al buscar
            const search = $(this).val();
            cargarMedicacion(currentPage, search);
        });

        // Botón de siguiente página
        $('#nextPageHc').on('click', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario
            currentPage++;
            const search = $('#searchMedicacion').val();
            cargarMedicacion(currentPage, search);
        });

        // Botón de página anterior
        $('#prevPageHc').on('click', function (event) {
            event.preventDefault(); // Prevenir el envío del formulario
            if (currentPage > 1) {
                currentPage--;
                const search = $('#searchMedicacion').val();
                cargarMedicacion(currentPage, search);
            }
        });
    });






    $.ajax({
        url: './dato/get_secretaria.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $('#secretaria').append(new Option(item.descripcion, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_curaduria.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $('#curaduria').append(new Option(item.descripcion, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_juzgado.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $('#juzgado').append(new Option(item.descripcion, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });



    $.ajax({
        url: './dato/get_t_juicio.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $('#t_juicio').append(new Option(item.descripcion, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_habitaciones.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                // Concatenar item.num y item.piso
                $('#habitacion_nro').append(new Option('Nro: ' + item.num + ' - Piso: ' + item.piso, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });




});

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

//REPORTE
let selectedPacienteId;

function openReportModal(pacienteId) {
    selectedPacienteId = pacienteId; // Guardar el ID del paciente seleccionado
    // Abrir el modal
    const myModal = new bootstrap.Modal(document.getElementById('reportModal'));
    myModal.show();
}

function generatePdf() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4');

    const pacienteId = selectedPacienteId;
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;

    function fetchDataPaci(pacienteId, fechaDesde, fechaHasta) {
        return new Promise((resolve, reject) => {
            fetch(`./dato/get_turno_de_paciente.php?id_paci=${pacienteId}&fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchParametros() {
        return new Promise((resolve, reject) => {
            fetch('../turnos/gets/get_parametros.php')
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    Promise.all([fetchDataPaci(pacienteId, fechaDesde, fechaHasta), fetchParametros()])
        .then(([dataTurnos, dataParametros]) => {
            const rows = dataTurnos.map(turnos => [
                (`${formatDate(turnos.fecha)} ${turnos.hora}`),
                turnos.nom_prof,
                turnos.motivo_full,
                turnos.llego,
                turnos.atendido,
                turnos.observaciones
            ]);

            const nombrePaciente = dataTurnos.length > 0 ? dataTurnos[0].nombre_paciente : 'Desconocido';
            const formattedFechaDesde = formatDate(fechaDesde);
            const formattedFechaHasta = formatDate(fechaHasta);

            const parametros = dataParametros[0] || {};
            const param1 = parametros.inst || 'No disponible';
            const param2 = parametros.localidad || 'No disponible';
            const param3 = parametros.tel || 'No disponible';

            // Título centrado
            const title = 'Historial de Paciente';
            const pageWidth = doc.internal.pageSize.getWidth();
            const titleWidth = doc.getTextWidth(title);
            const xTitle = (pageWidth - titleWidth) / 2;
            doc.setFontSize(16);
            doc.text(title, xTitle, 10);

            // Fechas centradas
            const dateRange = `Desde: ${formattedFechaDesde} Hasta: ${formattedFechaHasta}`;
            const dateRangeWidth = doc.getTextWidth(dateRange);
            const xDateRange = (pageWidth - dateRangeWidth) / 2;
            doc.setFontSize(14);
            doc.text(dateRange, xDateRange, 20);

            // Parámetros centrados
            doc.setFontSize(12);
            const startY = 30;

            const param1Text = `Institución: ${param1}`;
            const param2Text = `Localidad: ${param2}`;
            const param3Text = `Teléfono: ${param3}`;

            const param1Width = doc.getTextWidth(param1Text);
            const param1X = (pageWidth - param1Width) / 2;
            const param2Width = doc.getTextWidth(param2Text);
            const param2X = (pageWidth - param2Width) / 2;
            const param3Width = doc.getTextWidth(param3Text);
            const param3X = (pageWidth - param3Width) / 2;

            doc.text(param1Text, param1X, startY);
            doc.text(param2Text, param2X, startY + 10);
            doc.text(param3Text, param3X, startY + 20);

            // Subtítulo centrado
            const subtitle = `Paciente: ${nombrePaciente}`;
            const subtitleWidth = doc.getTextWidth(subtitle);
            const xSubtitle = (pageWidth - subtitleWidth) / 2;
            doc.setFontSize(12);
            doc.text(subtitle, xSubtitle, startY + 35);

            // Tabla
            const tableWidth = 200;
            let marginLeft = (pageWidth - tableWidth) / 2;
            marginLeft -= 15;

            const headers = ['Fecha y Hora', 'Profesional', 'Motivo', 'Llegó', 'Atendido', 'Observaciones'];

            let tableY;

            doc.autoTable({
                head: [headers],
                body: rows,
                startY: startY + 50,
                margin: { left: marginLeft, top: startY + 50 },
                theme: 'striped',
                styles: {
                    fontSize: 10, // Fuente más pequeña
                    cellPadding: 2,
                    overflow: 'linebreak'
                },
                columnStyles: {
                    0: { cellWidth: 30 },
                    1: { cellWidth: 70 },
                    2: { cellWidth: 40 },
                    3: { cellWidth: 20 },
                    4: { cellWidth: 20 },
                    5: { cellWidth: 50 }
                },
                didDrawPage: function (data) {
                    tableY = data.cursor.y;
                },
                didDrawCell: function (data) {
                    if (data.column.index === 0) {
                        tableY = data.cursor.y;
                    }
                }
            });

            const imgUrl = '../img/logo.png';
            var img = new Image();
            img.onload = function () {
                const imgWidth = 29;
                const imgHeight = 25;
                const xImg = (pageWidth - imgWidth) / 2;
                const yImg = tableY + 10;

                doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);
                window.open(doc.output('bloburl'))
            };
            img.src = imgUrl;

        }).catch(error => {
            console.error('Error:', error);
        });
}


//MULTI FECHAS
$(document).ready(function () {
    $.fn.datepicker.dates['es'] = {
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        clear: "Limpiar",
        format: "dd/mm/yyyy",
        titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
        weekStart: 1
    };

    $('#pracFechas').datepicker({
        format: "dd/mm/yyyy",
        language: "es", // Esto utilizará la configuración que acabamos de definir
        multidate: true,
        todayHighlight: true
    });
});



document.getElementById('btnCompletarManualmente').addEventListener('click', function () {
    var nombreInput = document.getElementById('nombre');
    nombreInput.removeAttribute('readonly');  // Elimina el atributo readonly
    nombreInput.focus();  // Opcional: pone el foco en el campo para que el usuario pueda escribir

    // Para 'fecha_nac'
    var fechaNacInput = document.getElementById('fecha_nac');
    fechaNacInput.removeAttribute('readonly');  // Elimina el atributo readonly
    fechaNacInput.focus();  // Opcional: pone el foco en el campo para que el usuario pueda escribir

    // Para 'ugl_paciente'
    convertirInputEnSelect();  // Llama a la función para convertir el input en select

    // Enfoca el nuevo select si es necesario
    var uglPacienteSelect = document.getElementById('ugl_paciente');
    uglPacienteSelect.focus();  // Opcional: pone el foco en el select
});

// Función para convertir el input en un select
function convertirInputEnSelect() {
    var uglInput = document.getElementById('ugl_paciente');
    var uglValue = uglInput.value;  // Guardar el valor actual

    // Crear el nuevo elemento select
    var selectUGL = document.createElement('select');
    selectUGL.className = 'form-control';
    selectUGL.id = 'ugl_paciente';
    selectUGL.name = 'ugl_paciente';
    selectUGL.required = true;

    // Agregar la opción predeterminada
    var defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Seleccione UGL';
    selectUGL.appendChild(defaultOption);

    // Obtener UGLs mediante AJAX
    fetch('./dato/obtener_ugl.php')
        .then(response => response.json())
        .then(data => {
            data.forEach(item => {
                var option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.descripcion;
                selectUGL.appendChild(option);
            });

            // Establecer el valor actual si está disponible
            selectUGL.value = uglValue;

            // Reemplazar el input por el select
            uglInput.parentNode.replaceChild(selectUGL, uglInput);
        })
        .catch(error => console.error('Error al cargar UGLs:', error));
}


// Limitar a 12 dígitos en el campo "benef"
document.getElementById('benef').addEventListener('input', function () {
    var benefInput = this;
    if (benefInput.value.length > 12) {
        benefInput.value = benefInput.value.slice(0, 12); // Recorta a 12 dígitos
    }
});
