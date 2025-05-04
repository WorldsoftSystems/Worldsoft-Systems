import { crearTabla } from '../../../componentes/tableGenerator.js';
import { cargarFormularioPaciente } from './pacienteForm.js';
import { obtenerPacientes, buscarPaciente } from './apiPacientes.js'; // 🔥 buscarPaciente es nuevo

let selectedPacienteId = null;
let selectedPacienteNombre = null;

export function configurarBusquedaPacientes() {
    const searchButton = document.getElementById('searchButton');
    const searchInput = document.getElementById('searchInput');

    if (!searchButton || !searchInput) {
        console.error('❌ Elementos de búsqueda no encontrados');
        return;
    }

    searchButton.addEventListener('click', () => buscarYActualizarTabla());
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') {
            buscarYActualizarTabla();
        }
    });
}

async function buscarYActualizarTabla() {
    const searchValue = document.getElementById('searchInput').value.trim().toLowerCase();
    const container = document.querySelector('#pacientesTable');

    if (!container) {
        console.error('❌ Contenedor de la tabla no encontrado');
        return;
    }

    try {
        let data = [];
        let error = null;

        if (searchValue) {
            ({ data, error } = await buscarPaciente(searchValue)); // 🔥 Búsqueda por input
        } else {
            ({ data, error } = await obtenerPacientes()); // 🔥 Sin input, trae todo
        }

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
            { key: 'acciones', label: 'Acciones' }
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

            const btnInfo = td.querySelector('.btn-info');
            btnInfo.addEventListener('click', () => {
                selectedPacienteId = paciente.id;
                selectedPacienteNombre = paciente.nombre;
                $('#reportModal').modal('show');
            });
        });

    } catch (error) {
        console.error('Error buscando pacientes:', error);
        container.innerHTML = `<div class="alert alert-danger">Error al buscar pacientes.</div>`;
    }
}
