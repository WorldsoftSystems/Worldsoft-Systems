// --- practicas.js ---
import { showToast } from '../../../componentes/toast.js'; // Importar la función showToast
import { cargarOpcionesSelect, cargarOpcionesSelectConParametros } from '../../../componentes/cargarSelects.js';
import { crearTabla } from '../../../componentes/tableGenerator.js';
import { formatDate } from '../../../componentes/formatDate.js'; // Importar la función formatDate

function toMysqlDateFormat(dateStr) {
    if (!dateStr) return '';

    const parts = dateStr.split('/');
    if (parts.length !== 3) return dateStr; // Ya podría estar en yyyy-mm-dd

    const [day, month, year] = parts;
    return `${year}-${month}-${day}`; // yyyy-mm-dd
}

// Función para abrir el modal de prácticas
function loadPracticasModal() {
    const id = document.getElementById('id')?.value;
    const nombre = document.getElementById('nombre')?.value;
    const benef = document.getElementById('benef')?.value;
    const parentesco = document.getElementById('parentesco')?.value;

    if (!id || !nombre || !benef || !parentesco) {
        return;
    }

    $('#pracModal').modal('show');     // Mostrás el modal que ya estaba generado
    $('#pracListadoNombreCarga').val(nombre);
    $('#pracListadoBenef').val(benef);
    $('#pracListadoParentesco').val(parentesco);

    checkPacienteStatus(benef, parentesco);   // Verificás el paciente
    cargarListaPracticas(id);                  // Cargás la lista de prácticas
}

// Verificar estado del paciente (si está dado de baja)
async function checkPacienteStatus(benef, parentesco) {
    const avisoPaciente = document.getElementById('noResultadosTitulo') || document.getElementById('avisoPaciente');
    if (!avisoPaciente) return;

    try {
        const response = await fetch(`https://worldsoftsystems.com.ar/buscar?beneficio=${benef}&parentesco=${parentesco}`);

        if (!response.ok) {
            avisoPaciente.classList.remove('d-none');
            avisoPaciente.innerText = 'No se pudo verificar afiliación en PAMI';
            return;
        }

        const data = await response.json();
        if (!data.resultado || Object.keys(data.resultado).length === 0) {
            avisoPaciente.classList.remove('d-none');
            avisoPaciente.innerText = 'Paciente sin afiliación en PAMI';
        } else {
            avisoPaciente.classList.add('d-none');
        }
    } catch (error) {
        avisoPaciente.classList.remove('d-none');
        avisoPaciente.innerText = 'No se pudo verificar afiliación en PAMI';
    }
}


// Cargar lista de prácticas en la tabla
// Función para cargar la lista de prácticas en la tabla
function cargarListaPracticas(idPaciente, page = 1, recordsPerPage = 100) {
    $.ajax({
        url: './dato/get_practicas.php',
        type: 'GET',
        data: { id_paciente: idPaciente, page, records_per_page: recordsPerPage },
        success: function (response) {
            let data;

            try {
                data = JSON.parse(response);
            } catch (error) {
                $('#listaPrac').html('<div class="text-danger">Error cargando prácticas</div>');
                return;
            }

            if (!data || !Array.isArray(data.practicas)) {
                $('#listaPrac').html('<div class="text-warning">No se encontraron prácticas registradas.</div>');
                return;
            }

            const practicas = data.practicas;

            // Formatear las fechas usando formatDate
            practicas.forEach(p => {
                if (p.fecha) {
                    p.fecha = formatDate(p.fecha);
                }
            });

            const columns = [
                { key: 'fecha', label: 'Fecha' },
                { key: 'hora', label: 'Hora' },
                { key: 'prof_full', label: 'Profesional' },
                { key: 'act_full', label: 'Práctica' },
                { key: 'cant', label: 'Cantidad' },
                { key: 'acciones', label: 'Acciones' } // Acciones visibles
            ];

            const container = document.getElementById('listaPrac');

            // Crear la tabla
            crearTabla(container, columns, practicas, (row, item) => {
                const td = row.querySelector('td:last-child'); // Usar el último td (acciones)

                td.innerHTML = `
                    <button class="button-form btn-edit">Editar</button>
                    <button class="button-form-delete btn-delete">Eliminar</button>
                `;

                // Asignar eventos a los botones
                td.querySelector('.btn-edit').addEventListener('click', () => editarPractica(item.id));
                td.querySelector('.btn-delete').addEventListener('click', () => eliminarPractica(item.id));
            });

            // Generar la paginación
            const totalRecords = data.totalRecords || 0;
            const totalPages = Math.ceil(totalRecords / recordsPerPage);

            let pagHtml = `<nav><ul class="pagination">`;
            for (let i = 1; i <= totalPages; i++) {
                pagHtml += `<li class="page-item ${i === page ? 'active' : ''}">
                                <a class="page-link" href="#" data-page="${i}">${i}</a>
                            </li>`;
            }
            pagHtml += `</ul></nav>`;

            $('#pagination').html(pagHtml);
        },
        error: function (xhr, status, error) {
            console.error('Error cargando lista prácticas:', status, error);
        }
    });
}

function editarPractica(id) {

    abrirFormularioEdicion(id);
}



function eliminarPractica(id) {
    if (confirm('¿Eliminar práctica?')) {
        $.post('./submenu/practicas/borrar_practica.php', { id })
            .done(function () {
                const idPaciente = $('#id').val();
                cargarListaPracticas(idPaciente);

                showToast({ text: "Práctica eliminada correctamente.", type: "success" });
            })
            .fail(function (xhr, status, error) {
                showToast({ text: "Error al eliminar la práctica.", type: "error" });
            });
    }
}


// Eventos

// Cambiar de página
$(document).on('click', '#pagination .page-link', function (e) {
    e.preventDefault();
    const page = $(this).data('page');
    const idPaciente = $('#id').val();
    cargarListaPracticas(idPaciente, page);
});

// Volver al formulario principal desde prácticas
$(document).on('click', '#pracModal .btn-volver', function () {
    $('#pracModal').modal('hide');
    $('#agregarPacienteModal').modal('show');

});

// Botón "Agregar" para abrir el form de nueva práctica
// Cuando abras el formulario de "Agregar"
$(document).on('click', '#nuevaPrac', function () {
    $('#formAgregarPrac')[0].reset();

    // 🔥 Re-inicializar el Datepicker para múltiples fechas
    $('#pracFechas').datepicker('destroy'); // Eliminar si ya había uno
    $('#pracFechas').datepicker({
        format: 'dd/mm/yyyy',
        multidate: true,          // ✅ Permitir múltiples fechas
        multidateSeparator: ', ', // ✅ Separarlas por coma
        language: 'es'
    });

    $('#pracFechas').datepicker('clearDates');

    $('#guardarPrac').attr('data-action', 'add');

    // Llenar los campos adicionales
    $('#pracFormNombreCarga').val($('#pracListadoNombreCarga').val());
    $('#pracFormBenef').val($('#pracListadoBenef').val());
    $('#pracFormParentesco').val($('#pracListadoParentesco').val());

    cargarOpcionesSelect('./dato/get_profesional.php', 'pracProfesional', 'Seleccionar profesional...', 'id_prof', 'nombreYapellido');

    const pacienteId = $('#id').val();
    cargarOpcionesSelectConParametros(
        './dato/get_todas_las_practicas.php',
        'pracActividad',
        { paciente_id: pacienteId },
        'Seleccionar actividad...',
        item => `${item.codigo} - ${item.descripcion}`
    );

    $('#agregarPracModal').modal('show');
});


// Botón "Editar" práctica
function abrirFormularioEdicion(id) {
    const idPaciente = document.getElementById('id')?.value;

    cargarOpcionesSelect('./dato/get_profesional.php', 'pracProfesional', 'Seleccionar profesional...', 'id_prof', 'nombreYapellido');
    cargarOpcionesSelectConParametros(
        './dato/get_todas_las_practicas.php',
        'pracActividad',
        { paciente_id: idPaciente },
        'Seleccionar actividad...',
        item => `${item.codigo} - ${item.descripcion}`
    );

    $.ajax({
        url: './dato/get_practica_con_id.php',
        type: 'GET',
        data: { id },
        success: function (response) {
            const p = JSON.parse(response);

            $('#pracId').val(p.id);
            $('#pracNombreCarga').val(p.nombre_paciente);
            $('#pracHora').val(p.hora);
            $('#pracProfesional').val(p.profesional);
            $('#pracActividad').val(p.actividad);
            $('#pracCantidad').val(p.cant);

            // 🔥 Re-inicializar el Datepicker solo para una fecha
            $('#pracFechas').datepicker('destroy');
            $('#pracFechas').datepicker({
                format: 'dd/mm/yyyy',
                multidate: false,   // ❌ SOLO UNA FECHA
                language: 'es'
            });

            // Cargar la fecha existente
            $('#pracFechas').datepicker('setDate', formatDate(p.fecha));

            $('#guardarPrac').attr('data-action', 'edit');

            if (!$('#pracFormNombreCarga').val() || !$('#pracFormBenef').val() || !$('#pracFormParentesco').val()) {
                $('#pracFormNombreCarga').val($('#pracListadoNombreCarga').val());
                $('#pracFormBenef').val($('#pracListadoBenef').val());
                $('#pracFormParentesco').val($('#pracListadoParentesco').val());
            }

            $('#agregarPracModal').modal('show');
        }
    });
}

// Guardar práctica (nuevo o editar)
$(document).on('click', '#guardarPrac', function (e) {
    e.preventDefault();

    const action = $(this).attr('data-action') || 'add';
    const url = action === 'edit' ? './submenu/practicas/editar_practica.php' : './submenu/practicas/agregar_practica.php';

    let fechas = $('#pracFechas').datepicker('getDates'); // 🔥 Obtener como array de objetos Date

    if (!fechas.length) {
        showToast({ text: "Debes seleccionar al menos una fecha.", type: "warning" });
        return;
    }

    if (action === 'add') {
        fechas = fechas.map(f => f.toISOString().slice(0, 10));
        fechas = JSON.stringify(fechas);
    } else {
        fechas = JSON.stringify([fechas[0].toISOString().slice(0, 10)]);
    }


    const formData = $('#formAgregarPrac').serializeArray().filter(f => f.name !== 'fechas');
    formData.push({ name: 'id_paciente', value: document.getElementById('id')?.value });
    formData.push({ name: 'fechas', value: fechas });



    $.ajax({
        type: "POST",
        url: url,
        data: $.param(formData),
        dataType: "json",
    })
        .done(function (response) {
            if (response.status === 'success') {
                $('#agregarPracModal').modal('hide');
                cargarListaPracticas($('#id').val());

                showToast({ text: "Práctica guardada correctamente.", type: "success" });
            } else {
                showToast({ text: response.message || "Error al guardar la práctica.", type: "warning" });
            }
        })
        .fail(function (xhr, status, error) {
            console.error("Error en la comunicación con el servidor:", status, error, xhr.responseText);
            showToast({ text: "Error enviando los datos al servidor.", type: "error" });
        });
});



// Exponer función global
window.loadPracticasModal = loadPracticasModal;