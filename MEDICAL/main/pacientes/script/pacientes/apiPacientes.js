//todos los pacientes
export async function obtenerPacientes() {
    const response = await fetch('./dato/obtenerPacientes.php');
    return await response.json();
}

//pacientes por id
export async function buscarPaciente(search) {
    const response = await fetch(`./dato/buscarPaciente.php?search=${encodeURIComponent(search)}`);
    return await response.json();
}

//modalidades
export async function getModalidades() {
    const response = await fetch('./dato/get_modalidad.php');
    return await response.json();
}

//modalidad de paciente
export async function getModalidadPaciente(idPaciente) {
    const response = await fetch(`./dato/get_modalidad_paci_id.php?id_paciente=${idPaciente}`);
    return await response.json();
}

//egreso de paciente
export async function verificarEgreso(idPaciente) {
    const response = await fetch(`./dato/verificar_egreso.php?id_paciente=${idPaciente}`);
    return await response.json();
}

//turnos de paciente
export async function fetchTurnos(idPaciente, fechaDesde, fechaHasta) {
    try {
        const response = await fetch(`./dato/get_turno_de_paciente.php?id_paci=${idPaciente}&fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching turnos:', error);
        return [];
    }
}

//parametros de clinica
export async function fetchParametros(){
    try {
        const response = await fetch('../turnos/gets/get_parametros.php');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching parametros:', error);
        return null;
    }
}

// todas las ugls
export async function fetchUGLs() {
    try {
        const response = await fetch('./dato/obtener_ugl.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching UGLs:', error);
        return [];
    }
}

