// --- cleanForm.js ---

export function limpiarFormulario(form) {
    // Si viene como string, buscá el elemento
    if (typeof form === 'string') {
        form = document.getElementById(form);
    }

    // Ahora validar si es un FORM
    if (form instanceof HTMLFormElement) {
        form.reset();
    } else {
        console.error(`❌ El elemento proporcionado no es un formulario o no existe.`);
    }
}
