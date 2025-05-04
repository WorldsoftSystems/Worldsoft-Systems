// --- generarPDF.js ---
import { formatDate } from './formatDate.js'; // Reutilizar tu formateador

export async function generarPDF({ 
    titulo = 'Reporte', 
    subtitulo = '', 
    fechas = {}, 
    parametros = {}, 
    headers = [], 
    rows = [], 
    orientacion = 'l' // 'l' (landscape) o 'p' (portrait)
}) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF(orientacion, 'mm', 'a4');
    const pageWidth = doc.internal.pageSize.getWidth();
    let startY = 10;

    // --- Título ---
    doc.setFontSize(16);
    const titleWidth = doc.getTextWidth(titulo);
    doc.text(titulo, (pageWidth - titleWidth) / 2, startY);
    startY += 10;

    // --- Rango de fechas ---
    if (fechas.desde && fechas.hasta) {
        const rangoFechas = `Desde: ${formatDate(fechas.desde)} Hasta: ${formatDate(fechas.hasta)}`;
        doc.setFontSize(12);
        const rangeWidth = doc.getTextWidth(rangoFechas);
        doc.text(rangoFechas, (pageWidth - rangeWidth) / 2, startY);
        startY += 10;
    }

    // --- Parámetros adicionales ---
    if (parametros.inst || parametros.localidad || parametros.tel) {
        doc.setFontSize(10);
        if (parametros.inst) {
            doc.text(`Institución: ${parametros.inst}`, (pageWidth - doc.getTextWidth(`Institución: ${parametros.inst}`)) / 2, startY);
            startY += 6;
        }
        if (parametros.localidad) {
            doc.text(`Localidad: ${parametros.localidad}`, (pageWidth - doc.getTextWidth(`Localidad: ${parametros.localidad}`)) / 2, startY);
            startY += 6;
        }
        if (parametros.tel) {
            doc.text(`Teléfono: ${parametros.tel}`, (pageWidth - doc.getTextWidth(`Teléfono: ${parametros.tel}`)) / 2, startY);
            startY += 6;
        }
    }

    // --- Subtítulo ---
    if (subtitulo) {
        doc.setFontSize(12);
        const subtitleWidth = doc.getTextWidth(subtitulo);
        doc.text(subtitulo, (pageWidth - subtitleWidth) / 2, startY);
        startY += 10;
    }

    // --- Tabla de datos ---
    let tableY = startY;
    if (headers.length && rows.length) {
        doc.autoTable({
            head: [headers],
            body: rows,
            startY: startY,
            theme: 'striped',
            margin: { top: startY },
            styles: { fontSize: 10, overflow: 'linebreak' },
            didDrawPage: (data) => {
                tableY = data.cursor.y; // Actualizar hasta donde llegó la tabla
            }
        });
    }

    // --- Agregar logo fijo ---
    const imgUrl = '../img/logo.png';
    const img = new Image();
    img.onload = () => {
        const imgWidth = 30;
        const imgHeight = 25;
        const xImg = (pageWidth - imgWidth) / 2;
        const yImg = tableY + 10;

        doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);
        window.open(doc.output('bloburl'));
    };
    img.src = imgUrl;
}
