document.addEventListener("DOMContentLoaded", function () {
    // Solo mostrar la alerta si el parámetro 'success' está presente al cargar la página
    const urlParams = new URLSearchParams(window.location.search);
    const successAlertShown = sessionStorage.getItem("successAlertShown");

    if (urlParams.has('success') && urlParams.get('success') === 'true' && !successAlertShown) {
        // Muestra la alerta y marca que ya se ha mostrado
        alert("El paciente se ha editado correctamente.");
        sessionStorage.setItem("successAlertShown", "true"); // Evita mostrar la alerta nuevamente
        urlParams.delete('success');
        window.history.replaceState({}, document.title, window.location.pathname);  // Elimina el parámetro de la URL
    }
});

document.getElementById('btnGenerarPDF').addEventListener('click', function () {
    const pacienteId = document.getElementById('id').value;

    if (!pacienteId) {
        return; // No mostrar alerta si no se ha seleccionado un paciente
    }

    // Realizar una solicitud AJAX para obtener los datos del paciente
    fetch(`./dato/obtenerPaciente.php?id=${pacienteId}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Genera el PDF con un ligero retraso para evitar conflictos con la alerta

                setTimeout(() => generarPDF(data), 100);
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error);
        });
});

// Función para generar el PDF
function generarPDF(paciente) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Estilo del PDF
    doc.setFont('helvetica');
    doc.setFontSize(16);

    // Título
    doc.setTextColor(40, 45, 52);
    doc.text(`Ficha de Paciente - ${paciente.nombre}`, 14, 20);

    // Tabla de datos adicionales
    const tableHeaders = ['', '  DATOS PERSONALES'];
    const tableData = [
        ['Nombres y Apellido', paciente.nombre || 'No disponible'],
        ['Nº Afiliado', paciente.benef + '/' + paciente.parentesco || 'No disponible'],
        ['T. Doc y DNI', paciente.tipo_doc + ' ' + paciente.nro_doc || 'No disponible'],
        ['Fecha de Nacimiento', formatDate(paciente.fecha_nac) || 'No disponible'],
        ['Obra Social', paciente.obra],
        ['Sexo', paciente.sexo || 'No disponible'],
        ['Domicilio', paciente.domicilio || 'No disponible'],
        ['Localidad', paciente.localidad || 'No disponible'],
        ['Teléfono', paciente.telefono || 'No disponible'],
        ['Fecha de admision', formatDate(paciente.admision) || 'No disponible'],
        ['Diagnostico', paciente.diag_full || 'No disponible'],
        ['Profesional', paciente.profesional || 'No disponible'],
        ['Modalidad', paciente.modalidad || 'No disponible'],
        ['Egreso', paciente.tipo_egreso || 'Sin egreso']
    ];

    doc.autoTable({
        head: [tableHeaders],
        body: tableData,
        startY: 30, // Inicia la tabla después de los textos
        theme: 'grid', // Estilo de la tabla con líneas
        headStyles: {
            fillColor: [41, 128, 185], // Color azul para el encabezado
            textColor: 255, // Texto blanco
            fontSize: 12
        },
        bodyStyles: {
            fontSize: 10
        },
        margin: { top: 150 }, // Margen superior
        styles: {
            cellWidth: 'auto',
            valign: 'middle'
        }
    });

    const imgUrl = '../img/logo.png';
    var img = new Image();
    img.onload = function () {
        const imgWidth = 29; // Ancho de la imagen
        const imgHeight = 25; // Altura de la imagen
        const pageWidth = doc.internal.pageSize.getWidth(); // Ancho de la página
        const xImg = (pageWidth - imgWidth) / 2; // Calcular la posición X para centrar
        const yImg = 160; // La posición Y justo debajo de la tabla

        // Agregar la imagen al PDF
        doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

        // Abrir el PDF en una nueva pestaña del navegador
        window.open(doc.output('bloburl'));
    };
    img.src = imgUrl;
}



function formatDate(dateString) {
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}


//BARRA DE BUSQUEDA 
document.getElementById('searchButton').addEventListener('click', realizarBusqueda);

// Agregar evento para detectar "Enter" en el campo de entrada
document.getElementById('searchInput').addEventListener('keypress', function (event) {
    if (event.key === 'Enter') { // Detecta la tecla Enter
        realizarBusqueda();
    }
});

function realizarBusqueda() {
    var searchValue = document.getElementById('searchInput').value.toLowerCase();

    if (searchValue) {
        $.ajax({
            url: './dato/buscarPaciente.php',
            type: 'GET',
            data: { search: searchValue },
            dataType: 'json',
            success: function (response) {
                const tableBody = $('#pacientesTable tbody');
                tableBody.empty();

                if (response.error) {
                    console.error(response.error);
                    tableBody.append('<tr><td colspan="5">' + response.error + '</td></tr>');
                    return;
                }

                const data = response.data;

                if (data.length > 0) {
                    data.forEach(function (paciente) {
                        const row = `<tr>
                            <td>${paciente.id}</td>
                            <td>${paciente.nombre}</td>
                            <td>${paciente.benef}</td>
                             <td>${paciente.modalidad_actual}</td>
                            <td>
                                <button class="btn btn-custom-editar" onclick='editarPaciente(${JSON.stringify(paciente)})'>
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <a href="?eliminar=${paciente.id}" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este paciente?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <button class="btn btn-info" onclick="openReportModal(${paciente.id})">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            </td>
                        </tr>`;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append('<tr><td colspan="5">No se encontraron resultados</td></tr>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("Error: " + textStatus + " - " + errorThrown);
                tableBody.append('<tr><td colspan="5">Error al buscar los datos</td></tr>');
            }
        });
    } else {
        alert('Por favor, ingresa un valor de búsqueda.');
    }
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
        document.getElementById('hijos').value = paciente.hijos;
        document.getElementById('ocupacion').value = paciente.ocupacion;
        document.getElementById('tipo_afiliado').value = paciente.tipo_afiliado;
        document.getElementById('boca_atencion').value = paciente.boca_atencion;
        document.getElementById('nro_hist_amb').value = paciente.nro_hist_amb;
        document.getElementById('nro_hist_int').value = paciente.nro_hist_int;
        document.getElementById('hora_admision').value = paciente.hora_admision;
        document.getElementById('nro_de_tramite').value = paciente.nro_de_tramite || '';
        
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
    }

    window.editarPaciente = editarPaciente;

    var btnAgregarPacienteModal = document.querySelector('button[data-bs-target="#agregarPacienteModal"]');
    if (btnAgregarPacienteModal) {
        btnAgregarPacienteModal.addEventListener('click', limpiarFormulario);
    } else {
        console.warn('Botón de agregar paciente modal no encontrado.');
    }

    function actualizarTablaPacientes() {
        const tableBody = $('#pacientesTable tbody');
        const loader = $('#loader');

        // Mostrar el loader
        loader.show();

        $.ajax({
            url: './dato/obtenerPacientes.php',
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                loader.hide();  // Esconder el loader cuando se carguen los datos
                tableBody.empty();

                if (response.error) {
                    console.error(response.error);
                    tableBody.append('<tr><td colspan="5">' + response.error + '</td></tr>');
                    return;
                }

                const data = response.data;

                if (data.length > 0) {
                    data.forEach(function (paciente) {
                        const row = `<tr>
                            <td>${paciente.id}</td>
                            <td>${paciente.nombre}</td>
                            <td>${paciente.benef}</td>
                            <td>${paciente.modalidad_actual}</td>
                            <td>
                                <button class="btn btn-custom-editar" onclick='editarPaciente(${JSON.stringify(paciente)})'>
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <a href="?eliminar=${paciente.id}" class="btn btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este paciente?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <button class="btn btn-info" onclick="openReportModal(${paciente.id})">
                                    <i class="fas fa-file-alt"></i>
                                </button>
                            </td>
                        </tr>`;
                        tableBody.append(row);
                    });
                } else {
                    tableBody.append('<tr><td colspan="5">No se encontraron resultados</td></tr>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                loader.hide();  // Esconder el loader en caso de error
                console.error("Error: " + textStatus + " - " + errorThrown);
                tableBody.append('<tr><td colspan="5">Error al cargar los datos</td></tr>');
            }
        });
    }


    // Evento para el botón de recarga
    document.getElementById('reloadButton').addEventListener('click', function () {
        actualizarTablaPacientes(); // Llama a la función para recargar los 300 pacientes
    });








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
                $('#pagoProfesional').append(new Option(item.nombreYapellido, item.id_prof));
                $('#hc_prof').append(new Option(item.nombreYapellido, item.id_prof));
                $('#medico_tratante').append(new Option(item.nombreYapellido, item.id_prof));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: './dato/get_actividades_pago.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                $('#pagoActividad').append(new Option(item.descripcion, item.id));
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
                    // Concatenar descripcion y potencia
                    const optionText = `${item.descripcion} - ${item.potencia}`;

                    $('#hc_medi').append(new Option(optionText, item.id));
                    $('#mediDesc').append(new Option(optionText, item.id));
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
    const fechaDesde = document.getElementById('fechaDesde_paci_turno').value;
    const fechaHasta = document.getElementById('fechaHasta_paci_turno').value;

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




// Limitar a 12 dígitos en el campo "benef"
document.getElementById('benef').addEventListener('input', function () {
    var benefInput = this;
    if (benefInput.value.length > 12) {
        benefInput.value = benefInput.value.slice(0, 12); // Recorta a 12 dígitos
    }
});
