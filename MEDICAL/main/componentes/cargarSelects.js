// --- cargarSelects.js ---

/**
 * Cargar opciones en un <select> de forma asincrónica usando fetch.
 * 
 * @param {string} url - URL del endpoint que devuelve los datos.
 * @param {string} idSelect - ID del select en el DOM.
 * @param {string} placeholder - Texto del primer option.
 * @param {string} valueField - Clave del objeto que será el value de las options.
 * @param {Function|null} formatFunction - Función opcional para formatear el texto visible.
 */
export async function cargarOpcionesSelect(url, idSelect, placeholder = "Seleccionar...", valueField = "id", textField = "nombre", selectedValue = null) {
    try {
       
        const response = await fetch(url);
        const data = await response.json();

        const select = document.getElementById(idSelect);
        if (!select) {
            return;
        }

        select.innerHTML = '';
        const placeholderOption = new Option(placeholder, '');
        select.appendChild(placeholderOption);

        data.forEach(item => {
            const value = item[valueField];
            const text = typeof textField === 'function' ? textField(item) : item[textField];
            const option = new Option(text, value);

            if (selectedValue !== null && selectedValue == value) {
                option.selected = true; // ✅ marcar como seleccionado
            }

            select.appendChild(option);
        });

    } catch (error) {
        console.error(`❌ Error cargando opciones para ${idSelect}:`, error);
    }
}


/**
 * Cargar opciones pasando parámetros dinámicos (ideal para selects dependientes).
 * 
 * @param {string} url - URL del endpoint.
 * @param {string} idSelect - ID del select.
 * @param {Object} dataParams - Parámetros a enviar.
 * @param {string} placeholder - Texto del primer option.
 * @param {Function|null} formatFunction - Función opcional para formatear el texto visible.
 */
export async function cargarOpcionesSelectConParametros(url, idSelect, dataParams = {}, placeholder = "Seleccionar...", formatFunction = null) {
    try {
        const queryString = new URLSearchParams(dataParams).toString();
        const response = await fetch(`${url}?${queryString}`);
        const data = await response.json();

        const select = document.getElementById(idSelect);
        if (!select) {
            
            return;
        }

        select.innerHTML = '';
        select.appendChild(new Option(placeholder, ''));

        data.forEach(item => {
            const text = formatFunction ? formatFunction(item) : item.nombre;
            const value = item.id;
            const option = new Option(text, value);
            select.appendChild(option);
        });

    } catch (error) {
        console.error(`Error cargando opciones dinámicas para ${idSelect}:`, error);
    }
}
