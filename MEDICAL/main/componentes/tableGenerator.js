// --- tableGenerator.js ---

/**
 * Generador de tablas reutilizable con estilos Bootstrap
 * @param {HTMLElement} container - Contenedor donde insertar la tabla
 * @param {Array} columns - Array [{ key: 'nombre', label: 'Nombre' }]
 * @param {Array} data - Array [{ nombre: 'Juan', edad: 25 }]
 * @param {Function} [rowActions] - Función opcional que recibe el <tr> y el objeto de data
 */
export function crearTabla(container, columns, data, rowActions = null) {
    container.innerHTML = ''; // Limpia el contenedor

    // 🔥 Crear estructura tipo Bootstrap como tenías
    const wrapper = document.createElement('div');
    wrapper.className = 'rounded-4 overflow-hidden shadow-sm bg-white p-0';

    const responsiveDiv = document.createElement('div');
    responsiveDiv.className = 'table-responsive';

    const table = document.createElement('table');
    table.className = 'table table-striped table-bordered mb-0';
    table.style.borderCollapse = 'separate';
    table.style.borderSpacing = '0';

    const thead = document.createElement('thead');
    thead.className = 'table-custom';
    const headerRow = document.createElement('tr');

    columns.forEach((col, index) => {
        const th = document.createElement('th');
        th.textContent = col.label || col.key;

        // 🔥 Copiar redondeo de las esquinas como tenías
        if (index === 0) th.classList.add('rounded-top-start');
        if (index === columns.length - 1) th.classList.add('rounded-top-end');

        headerRow.appendChild(th);
    });

    thead.appendChild(headerRow);
    table.appendChild(thead);

    const tbody = document.createElement('tbody');

    if (data.length > 0) {
        data.forEach(item => {
            const row = document.createElement('tr');

            columns.forEach(col => {
                const td = document.createElement('td');
                td.textContent = item[col.key] ?? '';
                row.appendChild(td);
            });

            if (rowActions) {
                rowActions(row, item); // 🔥 Inyecta los botones, etc.
            }

            tbody.appendChild(row);
        });
    } else {
        const row = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = columns.length;
        td.className = 'text-center';
        td.textContent = 'No hay datos disponibles.';
        row.appendChild(td);
        tbody.appendChild(row);
    }

    table.appendChild(tbody);
    responsiveDiv.appendChild(table);
    wrapper.appendChild(responsiveDiv);
    container.appendChild(wrapper);
}
