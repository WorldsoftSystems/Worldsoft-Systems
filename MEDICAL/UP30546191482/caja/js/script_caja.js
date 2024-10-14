document.addEventListener('DOMContentLoaded', () => {
    cargarMovimientos();

    document.getElementById('formMovimiento').addEventListener('submit', guardarMovimiento);
});

function formatDate(dateString) {
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}

function cargarMovimientos() {
    fetch('./gets/cargar_movimientos.php')
        .then(response => response.json())
        .then(data => {
            const tabla = document.getElementById('tablaMovimientos');
            tabla.innerHTML = '';
            data.forEach(movimiento => {
                tabla.innerHTML += `
                    <tr>
                        <td>${formatDate(movimiento.fecha)}</td>
                        <td>${movimiento.detalle_full}</td>
                        <td>${movimiento.ingreso}</td>
                        <td>${movimiento.egreso}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarMovimiento(${movimiento.id})">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarMovimiento(${movimiento.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
            });
        });
}

function guardarMovimiento(e) {
    e.preventDefault();

    const id = document.getElementById('movimientoId').value;
    const fecha = document.getElementById('fecha').value;
    const detalle = document.getElementById('detalle').value;
    const ingreso = document.getElementById('ingreso').value || null;
    const egreso = document.getElementById('egreso').value || null;

    const url = id ? './abm/editar_movimiento.php' : './abm/agregar_movimiento.php';
    const formData = new FormData();
    formData.append('id', id);
    formData.append('fecha', fecha);
    formData.append('detalle', detalle);
    formData.append('ingreso', ingreso);
    formData.append('egreso', egreso);

    fetch(url, {
        method: 'POST',
        body: formData
    }).then(response => response.text())
        .then(result => {
            cargarMovimientos();
            document.getElementById('formMovimiento').reset();
            bootstrap.Modal.getInstance(document.getElementById('modalMovimiento')).hide();
        });
}

function editarMovimiento(id) {
    fetch(`./gets/obtener_movimiento.php?id=${id}`)
        .then(response => response.json())
        .then(movimiento => {
            document.getElementById('movimientoId').value = movimiento.id || '';
            document.getElementById('fecha').value = movimiento.fecha || '';
            document.getElementById('detalle').value = movimiento.detalle != null ? movimiento.detalle : '';
            document.getElementById('ingreso').value = movimiento.ingreso != null ? movimiento.ingreso : '';
            document.getElementById('egreso').value = movimiento.egreso != null ? movimiento.egreso : '';
            const modal = new bootstrap.Modal(document.getElementById('modalMovimiento'));

            modal.show();
        });
}

function eliminarMovimiento(id) {
    if (confirm('¿Estás seguro de eliminar este movimiento?')) {
        fetch(`./abm/eliminar_movimiento.php?id=${id}`)
            .then(response => response.text())
            .then(result => {
                cargarMovimientos();
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
    fetch('./gets/cargar_movimientos.php')
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
    fetch(`./gets/reportes_movimientos.php?fechaDesde=${encodeURIComponent(fechaDesde)}&fechaHasta=${encodeURIComponent(fechaHasta)}`)
        .then(response => response.json())
        .then(data => {

            // Agrupar los movimientos por desc_rubro
            const agrupadosPorRubro = data.reduce((acc, movimiento) => {
                if (!acc[movimiento.descripcion]) {
                    acc[movimiento.descripcion] = { ingreso: 0, egreso: 0, detalles: [] };
                }
                acc[movimiento.descripcion].ingreso += parseFloat(movimiento.ingreso) || 0;
                acc[movimiento.descripcion].egreso += parseFloat(movimiento.egreso) || 0;
                acc[movimiento.descripcion].detalles.push({
                    fecha: movimiento.fecha,
                    detalle: movimiento.detalle,
                    ingreso: movimiento.ingreso,
                    egreso: movimiento.egreso
                });
                return acc;
            }, {});

            // Generar el PDF con los movimientos agrupados
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Título centrado
            const title = 'Gastos por Rubro';
            const pageWidth = doc.internal.pageSize.getWidth();
            doc.setFontSize(18);
            const titleWidth = doc.getTextWidth(title);
            const xTitle = (pageWidth - titleWidth) / 2;
            doc.text(title, xTitle, 20);

            // Subtítulo con las fechas seleccionadas
            const subtitle = `Desde: ${fechaDesde} Hasta: ${fechaHasta}`;
            doc.setFontSize(14);
            const subtitleWidth = doc.getTextWidth(subtitle);
            const xSubtitle = (pageWidth - subtitleWidth) / 2;
            doc.text(subtitle, xSubtitle, 30);

            let startY = 40;

            // Variables para el total general
            let totalIngresoGeneral = 0;
            let totalEgresoGeneral = 0;

            // Agregar los datos agrupados por rubro
            for (const [rubro, datos] of Object.entries(agrupadosPorRubro)) {
                // Título del rubro
                doc.setFontSize(16);
                doc.text(`Rubro: ${rubro}`, 10, startY);
                startY += 10;

                // Tabla de detalles
                doc.autoTable({
                    startY: startY,
                    head: [['Fecha', 'Detalle', 'Ingreso', 'Egreso']],
                    body: datos.detalles.map(detalle => [
                        formatDate(detalle.fecha),
                        detalle.detalle,
                        detalle.ingreso,
                        detalle.egreso
                    ]),
                    didDrawPage: function (data) {
                        startY = data.cursor.y;
                    }
                });

                // Totales del rubro
                startY += 10;
                doc.text(`Total Ingreso: ${datos.ingreso.toFixed(2)}`, 10, startY);
                doc.text(`Total Egreso: ${datos.egreso.toFixed(2)}`, 10, startY + 10);

                startY += 20;

                // Sumar los totales al total general
                totalIngresoGeneral += datos.ingreso;
                totalEgresoGeneral += datos.egreso;
            }

            // Total general
            startY += 10;
            doc.setFontSize(16);
            doc.text('Total General', 10, startY);
            startY += 10;
            doc.text(`Total Ingreso General: ${totalIngresoGeneral.toFixed(2)}`, 10, startY);
            doc.text(`Total Egreso General: ${totalEgresoGeneral.toFixed(2)}`, 10, startY + 10);
            doc.text(`Total Neto: ${(totalIngresoGeneral - totalEgresoGeneral).toFixed(2)}`, 10, startY + 20);

            // Cargar la imagen al final de la tabla
            const imgUrl = '../img/logo.png';
            var img = new Image();
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
        .catch(error => console.error('Error al cargar los movimientos:', error));
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