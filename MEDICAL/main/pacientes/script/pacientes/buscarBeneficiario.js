import { buscarBeneficiario } from '../../../componentes/apiBeneficiarios.js';

function seleccionarOptionPorTexto(selectElement, textoBuscado) {
    if (!selectElement) return;

    const opciones = [...selectElement.options];

    const opcionEncontrada = opciones.find(opt => opt.textContent.trim().toLowerCase() === textoBuscado.trim().toLowerCase());

    if (opcionEncontrada) {
        selectElement.value = opcionEncontrada.value;
        return true; // encontrado
    } else {
        console.warn(`⚠️ No se encontró opción para: ${textoBuscado}`);
        return false; // no encontrado
    }
}

export function configurarBusquedaBeneficiario() {
    const btnBuscar = document.getElementById('btnBuscar');
    const inputBenef = document.getElementById('benef');
    const inputParentesco = document.getElementById('parentesco');

    if (!btnBuscar || !inputBenef || !inputParentesco) {
        console.error('❌ Elementos de búsqueda de beneficiario no encontrados.');
        return;
    }

    btnBuscar.addEventListener('click', async () => {
        const beneficio = inputBenef.value.trim();
        const parentesco = inputParentesco.value.trim();

        if (!beneficio || !parentesco) {
            alert('Completa ambos campos para buscar.');
            return;
        }

        // 🔥 Cambiamos ícono a spinner durante la búsqueda
        const originalIcon = btnBuscar.querySelector('i');
        originalIcon.classList.remove('fa-magnifying-glass');
        originalIcon.classList.add('fa-spinner', 'fa-spin');

        try {
            const data = await buscarBeneficiario(beneficio, parentesco);

            if (data.resultado) {
                function convertDateFormat(dateStr) {
                    const parts = dateStr.split('/');
                    if (parts.length === 3) {
                        return `${parts[2]}-${parts[1]}-${parts[0]}`;
                    }
                    return dateStr;
                }

                // Asignar los valores obtenidos correctamente
                const uglSelect = document.getElementById('ugl_paciente');
                if (uglSelect) {
                    seleccionarOptionPorTexto(uglSelect, data.resultado.ugl);
                }


                const nombreInput = document.getElementById('nombre');
                if (nombreInput) {
                    nombreInput.value = data.resultado.nombreApellido || '';
                }

                const fechaNacInput = document.getElementById('fecha_nac');
                if (fechaNacInput) {
                    const fechaNac = convertDateFormat(data.resultado.fecha_nac);
                    fechaNacInput.value = fechaNac || '';
                }
            } else {
                alert('No se encontró ningún beneficiario con los datos proporcionados.');
            }
        } catch (error) {
            alert('Error al buscar el nombre y apellido.');
        } finally {
            // 🔥 Restaurar ícono original
            originalIcon.classList.remove('fa-spinner', 'fa-spin');
            originalIcon.classList.add('fa-magnifying-glass');
        }
    });
}
