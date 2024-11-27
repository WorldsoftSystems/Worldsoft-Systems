document.addEventListener('DOMContentLoaded', () => {
    cargarVencimientos();

    document.getElementById('formVencimiento').addEventListener('submit', guardarVencimiento);
});

function limpiarCamposModal() {
    // Selecciona el formulario dentro del modal
    const form = document.getElementById('formVencimiento');
    
    // Resetea todos los campos del formulario
    form.reset();
    
    // Si usas selects dinámicos, asegúrate de establecer el valor vacío
    form.querySelectorAll('select').forEach(function(select) {
        select.value = '';
    });
}


function formatDate(dateString) {
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}

function cargarVencimientos() {
    fetch('./gets/cargar_vencimientos.php')
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById('tablaVencimientos');
            tabla.innerHTML = '';
            data.forEach(vencimiento => {
                tabla.innerHTML += `
                    <tr>
                        <td>${formatDate(vencimiento.fecha_vencimiento)}</td>
                        <td>${vencimiento.detalle_full}</td>
                        <td>${vencimiento.importe}</td>
                        <td>${vencimiento.periodo}</td>
                        <td>${vencimiento.comprobante}</td>
                        <td>${vencimiento.num_comprobante}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarVencimiento(${vencimiento.id})">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarVencimiento(${vencimiento.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function guardarVencimiento(e) {
    e.preventDefault();

    const id = document.getElementById('vencimientoId').value;
    const fecha = document.getElementById('fecha_vencimiento').value;
    const detalle = document.getElementById('detalle').value;
    const importe = document.getElementById('importe').value || null;
    const periodo = document.getElementById('periodo').value || null;
    const comprobante = document.getElementById('comprobante').value || null;
    const num_comprobante = document.getElementById('num_comprobante').value || null;

    const url = id ? './abm/editar_gasto.php' : './abm/agregar_gasto.php';
    const formData = new FormData();
    formData.append('id', id);
    formData.append('fecha', fecha);
    formData.append('detalle', detalle);
    formData.append('importe', importe);
    formData.append('periodo', periodo);
    formData.append('comprobante', comprobante);
    formData.append('num_comprobante', num_comprobante);

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => response.text())
        .then(result => {
            cargarVencimientos();
            document.getElementById('formVencimiento').reset();
            bootstrap.Modal.getInstance(document.getElementById('modalVencimiento')).hide();
        });
}

function editarVencimiento(id) {
    fetch(`./gets/obtener_vencimiento.php?id=${id}`)
        .then(response => response.json())
        .then(vencimiento => {
            console.log(vencimiento.fecha_vencimiento)
            document.getElementById('vencimientoId').value = vencimiento.id || '';
            document.getElementById('fecha_vencimiento').value = vencimiento.fecha_vencimiento || '';
            document.getElementById('detalle').value = vencimiento.detalle != null ? vencimiento.detalle : '';
            document.getElementById('importe').value = vencimiento.importe != null ? vencimiento.importe : '';
            document.getElementById('periodo').value = vencimiento.periodo != null ? vencimiento.periodo : '';
            document.getElementById('comprobante').value = vencimiento.comprobante != null ? vencimiento.comprobante : '';
            document.getElementById('num_comprobante').value = vencimiento.comprobante != null ? vencimiento.num_comprobante : '';
            const modal = new bootstrap.Modal(document.getElementById('modalVencimiento'));

            modal.show();
        });
}

function eliminarVencimiento(id) {
    if (confirm('¿Estás seguro de eliminar este vencimiento?')) {
        fetch(`./abm/eliminar_gasto.php?id=${id}`)
            .then(response => response.text())
            .then(result => {
                cargarVencimientos();
            });
    }
}

function filtrarYExportarExcel() {
    // Obtener las fechas seleccionadas
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;

    // Validar que las fechas no estén vacías
    if (!fechaDesde || !fechaHasta) {
        alert("Por favor, seleccione ambas fechas.");
        return;
    }

    // Cerrar el modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('fechaModal'));
    modal.hide();

    // Llamar a la función para exportar a Excel con las fechas seleccionadas
    exportToExcel(fechaDesde, fechaHasta);
}

function exportToExcel(fechaDesde, fechaHasta) {
    fetch('./gets/cargar_vencimientos.php')
        .then(response => response.json())
        .then(data => {
            // Filtrar los movimientos entre las fechas seleccionadas
            const movimientosFiltrados = data.filter(movimiento => {
                const fechaMovimiento = new Date(movimiento.fecha);
                const desde = new Date(fechaDesde);
                const hasta = new Date(fechaHasta);
                return fechaMovimiento >= desde && fechaMovimiento <= hasta;
            });

            // Convertir los datos filtrados a formato de hoja de cálculo
            const ws = XLSX.utils.json_to_sheet(movimientosFiltrados.map(movimiento => ({
                Fecha: movimiento.fecha,
                Detalle: movimiento.detalle_full,
                Ingreso: movimiento.ingreso,
                Egreso: movimiento.egreso
            })));

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Movimientos');

            // Guardar el archivo Excel
            XLSX.writeFile(wb, 'movimientos.xlsx');
        })
        .catch(error => console.error('Error al cargar los movimientos:', error));
}



function filtrarYExportarPDF() {
    // Obtener las fechas seleccionadas
    const fechaDesde = document.getElementById('fechaDesde').value;
    const fechaHasta = document.getElementById('fechaHasta').value;

    // Validar que las fechas no estén vacías
    if (!fechaDesde || !fechaHasta) {
        alert("Por favor, seleccione ambas fechas.");
        return;
    }

    // Cerrar el modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('fechaModal'));
    modal.hide();

    // Llamar a la función para exportar el PDF con las fechas seleccionadas
    exportToPDF(fechaDesde, fechaHasta);
}

function exportToPDF(fechaDesde, fechaHasta) {
    fetch(`./gets/reporte_vencimientos.php?fechaDesde=${encodeURIComponent(fechaDesde)}&fechaHasta=${encodeURIComponent(fechaHasta)}`)
        .then(response => response.json())
        .then(data => {

            // Agrupar los vencimientos por fecha de vencimiento
            const agrupadosPorFecha = data.reduce((acc, vencimiento) => {
                const fechaFormateada = formatDate(vencimiento.fecha_vencimiento);
                if (!acc[fechaFormateada]) {
                    acc[fechaFormateada] = { total: 0, detalles: [] };
                }
                acc[fechaFormateada].total += parseFloat(vencimiento.importe) || 0;
                acc[fechaFormateada].detalles.push({
                    detalle: vencimiento.detalle_full,        // Usar el campo con la descripción completa
                    periodo: vencimiento.periodo_full,        // Usar el campo con la descripción del periodo
                    comprobante: vencimiento.comprobante_full,
                    importe: vencimiento.importe
                });
                return acc;
            }, {});

            // Generar el PDF con los vencimientos agrupados
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Título centrado
            const title = 'Listado de Control de Vencimientos';
            const pageWidth = doc.internal.pageSize.getWidth();
            doc.setFontSize(18);
            const titleWidth = doc.getTextWidth(title);
            const xTitle = (pageWidth - titleWidth) / 2;
            doc.text(title, xTitle, 20);

            // Subtítulo con las fechas seleccionadas
            const subtitle = `Desde: ${formatDate(fechaDesde)} Hasta: ${formatDate(fechaHasta)}`;
            doc.setFontSize(14);
            const subtitleWidth = doc.getTextWidth(subtitle);
            const xSubtitle = (pageWidth - subtitleWidth) / 2;
            doc.text(subtitle, xSubtitle, 30);

            let startY = 40;

            // Agregar los datos agrupados por fecha de vencimiento
            for (const [fechaVencimiento, datos] of Object.entries(agrupadosPorFecha)) {
                // Título de la fecha de vencimiento
                doc.setFontSize(16);
                doc.text(`Fecha Vencimiento: ${fechaVencimiento}`, 10, startY);
                startY += 10;

                // Tabla de detalles
                doc.autoTable({
                    startY: startY,
                    head: [['Detalle', 'Periodo', 'Comprobante', 'Importe']],
                    body: datos.detalles.map(detalle => [
                        detalle.detalle,
                        detalle.periodo,
                        detalle.comprobante,
                        detalle.importe
                    ]),
                    didDrawPage: function (data) {
                        startY = data.cursor.y;
                    }
                });

                // Total por fecha de vencimiento
                startY += 10;
                doc.text(`Total: ${datos.total.toFixed(2)}`, 10, startY);
                startY += 20;
            }

            // Cargar la imagen del logo al final del documento
            const imgUrl = '../img/logo.png';
            const img = new Image();
            img.onload = function () {
                const imgWidth = 29;
                const imgHeight = 25;
                const yImg = startY + 30;
                const xImg = (pageWidth - imgWidth) / 2;
                doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);
                window.open(doc.output('bloburl'));
            };
            img.src = imgUrl;
        })
        .catch(error => console.error('Error al cargar los vencimientos:', error));
}










$.ajax({
    url: './gets/gets_cuenta.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            $('#detalle').append(new Option('Rubro: ' + item.descripcion + ' - Cuenta: ' + item.desc_cuenta, item.id));
        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});

$.ajax({
    url: './gets/get_periodo.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            $('#periodo').append(new Option(item.descripcion, item.id));
        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});

$.ajax({
    url: './gets/get_comprobante.php',
    type: 'GET',
    dataType: 'json',
    success: function (data) {
        data.forEach(function (item) {
            $('#comprobante').append(new Option(item.descripcion, item.id));
        });
    },
    error: function (error) {
        console.error("Error fetching data: ", error);
    }
});