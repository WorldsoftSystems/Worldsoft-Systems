import { getModalidades, getModalidadPaciente, verificarEgreso } from './apiPacientes.js';
import { cargarOpcionesSelect } from '../../../componentes/cargarSelects.js';
import { fetchUGLs } from './apiPacientes.js'; // 🔥

export async function cargarFormularioPaciente(paciente) {
    const form = document.getElementById('formPaciente');
    if (!form) {
        console.error('Formulario no encontrado');
        return;
    }

    form.action = './editarPaciente.php';
    form.dataset.mode = 'edit'; // Modo edición

    // 🔵 Primero, cargar todos los SELECTS importantes
    await Promise.all([
        cargarOpcionesSelect('./dato/get_obras_sociales.php', 'obra_social', 'Seleccionar obra social...', 'id', item => `${item.siglas} - ${item.razon_social}`, paciente.obra_social),
        cargarOpcionesSelect('./dato/get_bocas_atencion.php', 'boca_atencion', 'Seleccionar boca...', 'id', item => item.boca, paciente.boca_atencion),
        cargarOpcionesSelect('./dato/get_tipo_afiliado.php', 'tipo_afiliado', 'Seleccionar tipo afiliado...', 'id', item => `${item.codigo} - ${item.descripcion}`, paciente.tipo_afiliado),
        cargarOpcionesSelect('./dato/get_modalidad.php', 'modalidad_paci', 'Seleccionar modalidad...', 'id', item => `${item.codigo} - ${item.descripcion}`, paciente.modalidad_paci),
        cargarOpcionesSelect('./dato/get_profesional.php', 'id_prof', 'Seleccionar profesional...', 'id_prof', item => item.nombreYapellido, paciente.id_prof),
        cargarOpcionesSelect('./dato/obtener_ugl.php', 'ugl_paciente', 'Seleccionar UGL...', 'ugl_paciente', item => `${item.descripcion}`, paciente.ugl),
    ]);

    // Convertimos el input a select acá
    await reemplazarInputUGLporSelect(paciente.ugl);


    // 🟢 Ahora sí, cargar los demás inputs del formulario
    completarCamposPaciente(paciente);

    // 🟢 También cargar modalidad actual (otro select diferente)
    await cargarModalidadPaciente(paciente.id);

    // 🟢 También verificar si está egresado
    await mostrarEgresoPaciente(paciente.id);

    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('agregarPacienteModal'));
    modal.show();
}

function completarCamposPaciente(paciente) {
    for (const key in paciente) {
        const input = document.getElementById(key);
        if (input) {
            input.value = paciente[key] ?? '';
        }
    }
}

async function cargarModalidadPaciente(idPaciente) {
    try {
        const modalidades = await getModalidades();
        const select = document.getElementById('modalidad_act');
        if (!select) return;

        select.innerHTML = '<option value="">Seleccionar...</option>';

        modalidades.forEach(item => {
            const option = new Option(`${item.codigo} - ${item.descripcion}`, item.id);
            select.appendChild(option);
        });

        const modalidadActual = await getModalidadPaciente(idPaciente);
        if (modalidadActual.length > 0) {
            select.value = modalidadActual[0].id;
        }
    } catch (error) {
        console.error('Error cargando modalidades:', error);
    }
}

async function mostrarEgresoPaciente(idPaciente) {
    try {
        const egreso = await verificarEgreso(idPaciente);
        const mensaje = document.getElementById('bajaMensaje');
        if (mensaje) {
            mensaje.innerHTML = egreso.egresado
                ? '<h1 style="color: red;">PACIENTE EGRESADO</h1>'
                : '';
        }
    } catch (error) {
        console.error('Error verificando egreso:', error);
    }
}

// 🔵 Cargar SELECTs sin paciente (nuevo paciente)
export async function cargarSelectsBasicos() {
    await Promise.all([
        cargarOpcionesSelect('./dato/get_obras_sociales.php', 'obra_social', 'Seleccionar obra social...', 'id', item => `${item.siglas} - ${item.razon_social}`),
        cargarOpcionesSelect('./dato/get_bocas_atencion.php', 'boca_atencion', 'Seleccionar boca...', 'id', item => item.boca),
        cargarOpcionesSelect('./dato/get_tipo_afiliado.php', 'tipo_afiliado', 'Seleccionar tipo afiliado...', 'id', item => `${item.codigo} - ${item.descripcion}`),
        cargarOpcionesSelect('./dato/get_modalidad.php', 'modalidad_act', 'Seleccionar modalidad...', 'id', item => `${item.codigo} - ${item.descripcion}`),
        cargarOpcionesSelect('./dato/get_profesional.php', 'id_prof', 'Seleccionar profesional...', 'id_prof', item => item.nombreYapellido),
        cargarOpcionesSelect('./dato/obtener_ugl.php', 'ugl_paciente', 'Seleccionar UGL...', 'ugl_paciente', item => `${item.descripcion}`), // 🔥 UGL en nuevo paciente
    ]);

    // Convertimos el input a select acá
    await reemplazarInputUGLporSelect();
}

async function reemplazarInputUGLporSelect(valorActual = '') {
    const uglInput = document.getElementById('ugl_paciente');
    if (!uglInput) {
        console.error('❌ Input ugl_paciente no encontrado.');
        return;
    }

    // Crear el nuevo SELECT
    const select = document.createElement('select');
    select.className = 'form-control input-rounded';
    select.id = 'ugl_paciente';
    select.name = 'ugl_paciente';
    select.required = true;

    // Agregar opción por defecto
    const defaultOption = document.createElement('option');
    defaultOption.value = '';
    defaultOption.textContent = 'Seleccionar UGL...';
    select.appendChild(defaultOption);

    try {
        const ugls = await fetchUGLs();
        ugls.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = `${item.descripcion}`;
            select.appendChild(option);
        });

        // Asignar valor actual si existe
        select.value = valorActual || '';

    } catch (error) {
        console.error('Error cargando UGLs:', error);
    }

    // Reemplazar input por select en el DOM
    uglInput.parentNode.replaceChild(select, uglInput);

    // 🔒 Bloquear el select por defecto
    select.disabled = true;
}