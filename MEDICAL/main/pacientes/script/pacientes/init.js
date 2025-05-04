import { actualizarTablaPacientes } from './tablaPacientes.js';
import { limpiarFormulario } from '../../../componentes/cleanForm.js';
import { showToast } from '../../../componentes/toast.js';
import { cargarSelectsBasicos } from './pacienteForm.js';
import { configurarBusquedaPacientes } from './tablaPacientesSearch.js';
import { configurarBusquedaBeneficiario } from './buscarBeneficiario.js';

document.addEventListener('DOMContentLoaded', () => {
    // Cargar la tabla de pacientes al iniciar
    actualizarTablaPacientes();

    // Configurar búsqueda de pacientes al iniciar
    configurarBusquedaPacientes();

    // 🔥 Inicializar búsqueda de beneficiario
    configurarBusquedaBeneficiario();


    // Recargar la tabla completa al hacer click en el botón de recarga
    document.getElementById('reloadButton').addEventListener('click', actualizarTablaPacientes);

    //escuchar boton de completar manualmente
    document.getElementById('btnCompletarManualmente').addEventListener('click', async () => {
        // Habilitar campos como nombre y fecha_nac
        var nombreInput = document.getElementById('nombre');
        nombreInput.removeAttribute('readonly');  // Elimina el atributo readonly

        // Para 'fecha_nac'
        var fechaNacInput = document.getElementById('fecha_nac');
        fechaNacInput.removeAttribute('readonly');  // Elimina el atributo readonly

        const uglSelect = document.getElementById('ugl_paciente');
        if (uglSelect) {
            uglSelect.disabled = false;
            uglSelect.focus();  // Opcional: pone el foco en el select
        }
    });


    // Obtener referencias a elementos del formulario y botones
    const formPaciente = document.getElementById('formPaciente');
    const agregarPacienteButton = document.querySelector('button[data-bs-target="#agregarPacienteModal"]');
    const modalPaciente = document.getElementById('agregarPacienteModal');

    // Verificar que exista el formulario de paciente
    if (!formPaciente) {
        console.error('❌ No se encontró el formPaciente');
        return;
    }

    // Configurar botón "Agregar Paciente"
    if (agregarPacienteButton) {
        agregarPacienteButton.addEventListener('click', async () => {

            // Limpiar formulario antes de agregar nuevo paciente
            limpiarFormulario('formPaciente');
            formPaciente.dataset.mode = 'add';

            // Cargar selects básicos para el formulario
            await cargarSelectsBasicos();

            // Limpiar campos específicos
            document.getElementById('id').value = '';
            document.getElementById('bajaMensaje').innerHTML = '';
        });
    }

    // Interceptar el submit del formulario de paciente
    formPaciente.addEventListener('submit', async (e) => {
        e.preventDefault(); // Evita la recarga de la página

        // Determinar si es agregar o editar paciente
        const mode = formPaciente.dataset.mode?.toLowerCase() || 'add';

        // Definir la URL según el modo
        const url = mode === 'edit' ? './editarPaciente.php' : './agregarPaciente.php';

        // Crear FormData a partir del formulario
        const formData = new FormData(formPaciente);

        // Obtener botón de guardar para deshabilitarlo mientras se envía
        const botonGuardar = formPaciente.querySelector('button[type="submit"]');
        if (botonGuardar) {
            botonGuardar.disabled = true;
            botonGuardar.innerHTML = 'Guardando...';
        }

        try {
            console.log('Enviando formulario...');
            for (let [key, value] of formData.entries()) {
                console.log(key, ':', value);
            }
            // Realizar la petición POST al servidor
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            // Mostrar mensaje de éxito o error según la respuesta
            if (result.success) {
                showToast({ text: result.message || 'Paciente guardado correctamente.', type: 'success' });

                // Actualizar la tabla de pacientes
                actualizarTablaPacientes();
            } else {
                showToast({ text: result.message || 'Error al guardar el paciente.', type: 'error' });
            }
        } catch (error) {
            console.error('Error enviando formulario:', error);
            showToast({ text: 'Error de conexión con el servidor.', type: 'error' });
        } finally {
            // Rehabilitar botón de guardar al finalizar
            if (botonGuardar) {
                botonGuardar.disabled = false;
                botonGuardar.innerHTML = 'Guardar';
            }
        }
    });
});
