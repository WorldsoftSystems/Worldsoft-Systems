import { obtenerPacientes, fetchTurnos } from './apiPacientes.js';
import { cargarFormularioPaciente } from './pacienteForm.js';
import { crearTabla } from '../../../componentes/tableGenerator.js';
import { generarPDF } from '../../../componentes/generarPDF.js';
import { formatDate } from '../../../componentes/formatDate.js';

let selectedPacienteId = null; // Variable global temporal
let selectedPacienteNombre = null; // Variable global temporal

export async function actualizarTablaPacientes() {
    const container = document.querySelector('#pacientesTable');

    try {
        const { data, error } = await obtenerPacientes();

        if (error) {
            container.innerHTML = `<div class="alert alert-danger">${error}</div>`;
            return;
        }

        const columns = [
            { key: 'id', label: 'ID' },
            { key: 'nombre', label: 'Nombre' },
            { key: 'benef', label: 'Beneficio' },
            { key: 'parentesco', label: 'Parentesco' },
            { key: 'modalidad_actual', label: 'Modalidad Actual' },
            { key: 'acciones', label: 'Acciones' } // 🔥 Agregar este
        ];

        crearTabla(container, columns, data, (row, paciente) => {
            const td = row.querySelector('td:last-child');
            td.innerHTML = `
                <button class="button-form btn-custom-editar">
                    <i class="fas fa-pencil-alt"></i>
                </button>
                <a href="?eliminar=${paciente.id}" class="button-form-delete btn-danger" onclick="return confirm('¿Seguro que deseas eliminar?');">
                    <i class="fas fa-trash-alt"></i>
                </a>
                <button class="button-form-info btn-info">
                    <i class="fas fa-file-alt"></i>
                </button>
            `;

            const btnEditar = td.querySelector('.btn-custom-editar');
            btnEditar.addEventListener('click', () => {
                cargarFormularioPaciente(paciente);
            });

            // Botón "Info"
            const btnInfo = td.querySelector('.btn-info');
            btnInfo.addEventListener('click', () => {
                selectedPacienteId = paciente.id;
                selectedPacienteNombre = paciente.nombre; // 🔥 Nuevo
                $('#reportModal').modal('show');
            });

        });


    } catch (error) {
        console.error('Error cargando pacientes:', error);
        container.innerHTML = `<div class="alert alert-danger">Error al cargar pacientes.</div>`;
    }
}


// Botón "Generar PDF"
$(document).on('click', '#btnGenerarPDF', async function () {
    console.log(selectedPacienteId, "id paciente")

    const fechaDesde = document.getElementById('fechaDesde_paci_turno').value;
    const fechaHasta = document.getElementById('fechaHasta_paci_turno').value;

    if (!selectedPacienteId) {
        alert('Selecciona un paciente primero');
        return;
    }

    const turnos = await fetchTurnos(selectedPacienteId, fechaDesde, fechaHasta);

    const headers = ['Fecha y Hora', 'Profesional', 'Motivo', 'Llegó', 'Atendido', 'Observaciones'];
    const rows = turnos.map(t => [
        `${formatDate(t.fecha)} ${t.hora}`,
        t.nom_prof,
        t.motivo_full,
        t.llego,
        t.atendido,
        t.observaciones
    ]);

    await generarPDF({
        titulo: 'Historial de Paciente',
        subtitulo: `Paciente: ${selectedPacienteNombre || 'Desconocido'}`,
        fechas: { desde: fechaDesde, hasta: fechaHasta },
        parametros: {
            inst: 'Institución X',
            localidad: 'Ciudad Y',
            tel: '1234-5678'
        },
        headers,
        rows
    });

    $('#reportModal').modal('hide');
});
