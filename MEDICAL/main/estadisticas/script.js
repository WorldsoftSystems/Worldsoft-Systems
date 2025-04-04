function formatDate(dateString) {
    if (!dateString) return '';
    var parts = dateString.split('-');
    var year = parts[0];
    var month = parts[1];
    var day = parts[2];
    return day + "/" + month + "/" + year;
}


document.addEventListener('DOMContentLoaded', function () {
    const openModalLink = document.getElementById('openModalLink');
    const generatePdfBtn = document.getElementById('generatePdfBtn');
    const generateExcelBtn = document.getElementById('generateExcelBtn');

    const openPatientModalLink = document.getElementById('openPatientModalLink');
    const generatePatientPdfBtn = document.getElementById('generatePatientPdfBtn');
    const generatePatientExcelBtn = document.getElementById('generatePatientExcelBtn');

    const openOmesLink = document.getElementById('openOmesModalLink');
    const generateOmesBtn = document.getElementById('generateOmesBtn');
    const generateOmesExcelBtn = document.getElementById('generateOmesExcelBtn');

    const openProfLink = document.getElementById('openProfModalLink');
    const generateProfBtn = document.getElementById('generateProfBtn');
    const generateProfExcelBtn = document.getElementById('generateProfExcelBtn');


    const openPracLink = document.getElementById('openPracModalLink');
    const generatePracBtn = document.getElementById('generatePracBtn');
    const generatePracExcelBtn = document.getElementById('generatePracExcelBtn');

    const openEgresoLink = document.getElementById('openEgresoModalLink');
    const generateEgresoBtn = document.getElementById('generateEgresoBtn');
    const generateEgresoExcelBtn = document.getElementById('generateEgresoExcelBtn');

    const openIngresoLink = document.getElementById('openIngresoModalLink');
    const generateIngresoBtn = document.getElementById('generateIngresoBtn');
    const generateIngresoExcelBtn = document.getElementById('generateIngresoExcelBtn');

    const openDiagModalLink = document.getElementById('openDiagModalLink');
    const generateDiagBtn = document.getElementById('generateDiagBtn');
    const generateDiagExcelBtn = document.getElementById('generateDiagExcelBtn');

    const openPlanModalLink = document.getElementById('openPlanModalLink');
    const generatePlanBtn = document.getElementById('generatePlanBtn');
    const generatePlanExcelBtn = document.getElementById('generatePlanExcelBtn');

    const openPacientesBocaModalLink = document.getElementById('openPacientesBocaModalLink');
    const generatePacientesBocaBtn = document.getElementById('generatePacientesBocaBtn');
    const generatePacientesBocaExcelBtn = document.getElementById('generatePacientesBocaExcelBtn');

    const openPaciUnicosModalLink = document.getElementById('openPaciUnicosModalLink');
    const generatePaciUnicosBtn = document.getElementById('generatePaciUnicosBtn');
    const generatePaciUnicosExcelBtn = document.getElementById('generatePaciUnicosExcelBtn');

    const openOpModalLink = document.getElementById('openOrdenModalLink');
    const generateOpBtn = document.getElementById('generateOpBtn');
    const generateOpExcelBtn = document.getElementById('generateOpExcelBtn');

    const openOpMesModalLink = document.getElementById('openOrdenMesModalLink');
    const generateOpMesBtn = document.getElementById('generateOpMesBtn');
    const generateOpMesExcelBtn = document.getElementById('generateOpMesExcelBtn');

    const openPacientesPrestacionesModalLink = document.getElementById('openPacientesPrestacionesModalLink');
    const generatePacientesPrestacionesBtn = document.getElementById('generatePacientesPrestacionesBtn');
    const generatePacientesPrestacionesExcelBtn = document.getElementById('generatePacientesPrestacionesExcelBtn');

    const openPaciSinDiagModalLink = document.getElementById('openPaciSinDiagModalLink');
    const generatePaciSinDiagBtn = document.getElementById('generatePaciSinDiagBtn');
    const generatePaciSinDiagExcelBtn = document.getElementById('generatePaciSinDiagExcelBtn');

    if (openPaciSinDiagModalLink) {
        openPaciSinDiagModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openPaciSinDiagnosticoModal'));
            modal.show();
        });
    }

    if (generatePaciSinDiagBtn) {
        generatePaciSinDiagBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePaciSinDiag').value;
            const fechaHasta = document.getElementById('fechaHastaPaciSinDiag').value;
            const obraSocialId = $('#obra_social_paci_sin_diag').val();
            generatePaciSinDiagPDF(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePaciSinDiagExcelBtn) {
        generatePaciSinDiagExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePaciSinDiag').value;
            const fechaHasta = document.getElementById('fechaHastaPaciSinDiag').value;
            const obraSocialId = $('#obra_social_paci_sin_diag').val();
            generatePaciSinDiagExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (openPacientesPrestacionesModalLink) {
        openPacientesPrestacionesModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openPacientesPrestacionesModal'));
            modal.show();
        });
    }

    if (generatePacientesPrestacionesBtn) {
        generatePacientesPrestacionesBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePacientesPrestaciones').value;
            const fechaHasta = document.getElementById('fechaHastaPacientesPrestaciones').value;
            const obraSocialId = $('#obra_social_paci_prestaciones').val();
            generatePacientesPrestacionesPDF(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePacientesPrestacionesExcelBtn) {
        generatePacientesPrestacionesExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePacientesPrestaciones').value;
            const fechaHasta = document.getElementById('fechaHastaPacientesPrestaciones').value;
            const obraSocialId = $('#obra_social_paci_prestaciones').val();
            generatePacientesPrestacionesExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (openPacientesBocaModalLink) {
        openPacientesBocaModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openPacientesBocaModal'));
            modal.show();
        });
    }

    if (generatePacientesBocaBtn) {
        generatePacientesBocaBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePacientesBoca').value;
            const fechaHasta = document.getElementById('fechaHastaPacientesBoca').value;
            const obraSocialId = $('#obra_social_paci_boca').val();
            generatePacientesBocaPDF(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePacientesBocaExcelBtn) {
        generatePacientesBocaExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePacientesBoca').value;
            const fechaHasta = document.getElementById('fechaHastaPacientesBoca').value;
            const obraSocialId = $('#obra_social_paci_boca').val();
            generatePacientesBocaExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (openPaciUnicosModalLink) {
        openPaciUnicosModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openPaciUnicosModal'));
            modal.show();
        });
    }

    if (generatePaciUnicosBtn) {
        generatePaciUnicosBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePaciUnicos').value;
            const fechaHasta = document.getElementById('fechaHastaPaciUnicos').value;
            const obraSocialId = $('#obra_social_paci_unicos').val();
            generatePaciUnicosPDF(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePaciUnicosExcelBtn) {
        generatePaciUnicosExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePaciUnicos').value;
            const fechaHasta = document.getElementById('fechaHastaPaciUnicos').value;
            const obraSocialId = $('#obra_social_paci_unicos').val();
            generatePaciUnicosExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }


    if (openOpModalLink) {
        openOpModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openOrdenModal'));
            modal.show();
        });
    }

    if (generateOpBtn) {
        generateOpBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeOp').value;
            const fechaHasta = document.getElementById('fechaHastaOp').value;
            generateOpPdf(fechaDesde, fechaHasta);
        });
    }

    if (generateOpExcelBtn) {
        generateOpExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeOp').value;
            const fechaHasta = document.getElementById('fechaHastaOp').value;
            generateOpExcel(fechaDesde, fechaHasta);
        });
    }

    if (openOpMesModalLink) {
        openOpMesModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openOrdenMesModal'));
            modal.show();
        });
    }

    if (generateOpMesBtn) {
        generateOpMesBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeOpMes').value;
            const fechaHasta = document.getElementById('fechaHastaOpMes').value;
            generateOpMesPdf(fechaDesde, fechaHasta);
        });
    }

    if (generateOpMesExcelBtn) {
        generateOpMesExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeOpMes').value;
            const fechaHasta = document.getElementById('fechaHastaOpMes').value;
            generateOpMesExcel(fechaDesde, fechaHasta);
        });
    }

    if (openPlanModalLink) {
        openPlanModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openPlanModal'));
            modal.show();
        });
    }

    if (openDiagModalLink) {
        openDiagModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openDiagModal'));
            modal.show();
        });
    }

    if (openIngresoLink) {
        openIngresoLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openIngresoModal'));
            modal.show();
        });
    }

    if (openModalLink) {
        openModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('resumenModal'));
            modal.show();
        });
    }

    if (openPatientModalLink) {
        openPatientModalLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('patientResumenModal'));
            modal.show();
        });
    }

    if (openOmesLink) {
        openOmesLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openOmesModal'));
            modal.show();
        });
    }

    if (openPracLink) {
        openPracLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openPracModal'));
            modal.show();
        });
    }

    if (openProfLink) {
        openProfLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openProfModal'));
            modal.show();
        });
    }

    if (openEgresoLink) {
        openEgresoLink.addEventListener('click', function (e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('openEgresoModal'));
            modal.show();
        });
    }

    if (generatePlanBtn) {
        generatePlanBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePlan').value;
            const fechaHasta = document.getElementById('fechaHastaPlan').value;
            const obraSocialId = $('#obra_social_plan').val();
            generatePlanPdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePlanExcelBtn) {
        generatePlanExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePlan').value;
            const fechaHasta = document.getElementById('fechaHastaPlan').value;
            const obraSocialId = $('#obra_social_plan').val();
            generatePlanExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateDiagBtn) {
        generateDiagBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeDiag').value;
            const fechaHasta = document.getElementById('fechaHastaDiag').value;
            const obraSocialId = $('#obra_social_diag').val();
            generateDiagPdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateDiagExcelBtn) {
        generateDiagExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeDiag').value;
            const fechaHasta = document.getElementById('fechaHastaDiag').value;
            const obraSocialId = $('#obra_social_diag').val();
            generateDiagExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateIngresoBtn) {
        generateIngresoBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeIngreso').value;
            const fechaHasta = document.getElementById('fechaHastaIngreso').value;
            const obraSocialId = $('#obra_social_ingreso').val();
            generateIngresoPdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateIngresoExcelBtn) {
        generateIngresoExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeIngreso').value;
            const fechaHasta = document.getElementById('fechaHastaIngreso').value;
            const obraSocialId = $('#obra_social_ingreso').val();
            generateIngresoExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateEgresoBtn) {
        generateEgresoBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeEgreso').value;
            const fechaHasta = document.getElementById('fechaHastaEgreso').value;
            const obraSocialId = $('#obra_social_egreso').val();
            generateEgresoPdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateEgresoExcelBtn) {
        generateEgresoExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeEgreso').value;
            const fechaHasta = document.getElementById('fechaHastaEgreso').value;
            const obraSocialId = $('#obra_social_egreso').val();
            generateEgresoExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePracBtn) {
        generatePracBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePrac').value;
            const fechaHasta = document.getElementById('fechaHastaPrac').value;
            const obraSocialId = $('#obra_social_prac').val();
            generatePracPdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePracExcelBtn) {
        generatePracExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePrac').value;
            const fechaHasta = document.getElementById('fechaHastaPrac').value;
            const obraSocialId = $('#obra_social_prac').val();
            generatePracExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateProfBtn) {
        generateProfBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeProf').value;
            const fechaHasta = document.getElementById('fechaHastaProf').value;
            const obraSocialId = $('#obra_social_prof').val();
            const profesional = $('#profesional').val();
            generateProfPdf(fechaDesde, fechaHasta, obraSocialId, profesional);
        });
    }

    if (generateProfExcelBtn) {
        generateProfExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeProf').value;
            const fechaHasta = document.getElementById('fechaHastaProf').value;
            const obraSocialId = $('#obra_social_prof').val();
            const profesional = $('#profesional').val();
            generateProfExcel(fechaDesde, fechaHasta, obraSocialId, profesional);
        });
    }

    //
    if (generateOmesBtn) {
        generateOmesBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeOmes').value;
            const fechaHasta = document.getElementById('fechaHastaOmes').value;
            const obraSocialId = $('#obra_social_ome').val();
            generateOmesPdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateOmesExcelBtn) {
        generateOmesExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdeOmes').value;
            const fechaHasta = document.getElementById('fechaHastaOmes').value;
            const obraSocialId = $('#obra_social_ome').val();
            generateOmesExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePdfBtn) {
        generatePdfBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesde').value;
            const fechaHasta = document.getElementById('fechaHasta').value;
            const obraSocialId = $('#obra_social').val();
            console.log(obraSocialId)
            generatePdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generateExcelBtn) {
        generateExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesde').value;
            const fechaHasta = document.getElementById('fechaHasta').value;
            const obraSocialId = $('#obra_social').val();
            console.log(obraSocialId)
            generateExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePatientPdfBtn) {
        generatePatientPdfBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePaciente').value;
            const fechaHasta = document.getElementById('fechaHastaPaciente').value;
            const obraSocialId = $('#obra_social_paciente').val();
            generatePatientPdf(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    if (generatePatientExcelBtn) {
        generatePatientExcelBtn.addEventListener('click', function () {
            const fechaDesde = document.getElementById('fechaDesdePaciente').value;
            const fechaHasta = document.getElementById('fechaHastaPaciente').value;
            const obraSocialId = $('#obra_social_paciente').val();
            generatePatientExcel(fechaDesde, fechaHasta, obraSocialId);
        });
    }

    function fetchData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_estadisticas.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchPaciBoca(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_paciente_boca_atencion.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }



    function fetchPaciUnicos(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_prestacion_unicas.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchOpData(fechaDesde, fechaHasta) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_op_vencimiento.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchPatientData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_estadisticas_paciente.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchOmesData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_turnos_omes.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchProfData(fechaDesde, fechaHasta, obraSocialId, profesional) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_pract_prof.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}&profesional=${profesional}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });

    }

    function fetchPracData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_paci_prestacion.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchEgresoData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_paci_egreso.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchIngresoData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_paci_ingreso.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchDiagData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_paci_diag.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchPlanData(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_paci_medi.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    function fetchPrestacionesPorPaci(fechaDesde, fechaHasta, obraSocialId) {
        return new Promise((resolve, reject) => {
            fetch(`./gets/get_prestaciones_paciente.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
                .then(response => response.json())
                .then(data => resolve(data))
                .catch(error => reject(error));
        });
    }

    //PRESTACIONES POR PACIENTES 
    function generatePacientesPrestacionesExcel(fechaDesde, fechaHasta, obraSocialId) {
        fetchPrestacionesPorPaci(fechaDesde, fechaHasta, obraSocialId)
            .then(resumen => {
                const workbook = XLSX.utils.book_new();
                const sheetData = [];

                // Agregar encabezado general
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);
                sheetData.push([`LISTADO DE PRESTACIONES POR PACIENTE`]);
                sheetData.push([`DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`]);
                sheetData.push([]); // Fila en blanco

                let grandTotal = 0;

                resumen.forEach(paciente => {
                    const { nombre, prestaciones, totalCantidad } = paciente;

                    // Agregar el encabezado de cada paciente
                    sheetData.push([`PACIENTE: ${nombre.toUpperCase()}`]);
                    sheetData.push(['Prestación', 'Fecha', 'Cantidad']);

                    // Agregar cada prestación del paciente
                    prestaciones.forEach(prest => {
                        sheetData.push([
                            prest.pract_full,
                            formatDate(prest.fecha_pract),
                            prest.cantidad
                        ]);
                    });

                    // Total por paciente
                    sheetData.push([`Total Cantidad de ${nombre}:`, '', totalCantidad]);
                    sheetData.push([]); // Fila en blanco para separar pacientes

                    grandTotal += totalCantidad;
                });

                // Total general al final
                sheetData.push([`Total General:`, '', grandTotal]);

                // Crear la hoja de Excel y agregar los datos
                const worksheet = XLSX.utils.aoa_to_sheet(sheetData);
                XLSX.utils.book_append_sheet(workbook, worksheet, 'Prestaciones');

                // Generar el archivo Excel
                XLSX.writeFile(workbook, `Prestaciones_Pacientes_${formattedFechaDesde}_${formattedFechaHasta}.xlsx`);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generatePacientesPrestacionesPDF(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 };
            const rowHeight = 10;
            const headerHeight = 10;

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            return Math.floor(availableHeight / rowHeight);
        }

        Promise.all([fetchPrestacionesPorPaci(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'LISTADO DE PRESTACIONES POR PACIENTE';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                let grandTotal = 0;

                resumen.forEach(paciente => {
                    const { nombre, prestaciones, totalCantidad } = paciente;
                    const pacienteSubtitle = `PACIENTE: ${nombre.toUpperCase()}`;

                    doc.setFontSize(12);
                    doc.text(pacienteSubtitle, pageWidth / 2, startY, { align: 'center' });

                    startY += 5;

                    const headers = ['PRESTACIÓN', 'FECHA', 'CANTIDAD'];
                    const maxRowsPerPage = getMaxRowsPerPage(doc, headers, prestaciones);

                    for (let i = 0; i < prestaciones.length; i += maxRowsPerPage) {
                        const chunk = prestaciones.slice(i, i + maxRowsPerPage);

                        doc.autoTable({
                            head: [headers],
                            body: chunk.map(prest => [
                                prest.pract_full,
                                formatDate(prest.fecha_pract),
                                prest.cantidad
                            ]),
                            startY: startY,
                            margin: margin,
                            theme: 'striped',
                            styles: { fontSize: 10, cellPadding: 2, overflow: 'linebreak' },
                            columnStyles: { 0: { cellWidth: 70 }, 1: { cellWidth: 45 }, 2: { cellWidth: 25 } },
                            didDrawPage: data => { startY = data.cursor.y; },
                            pageBreak: 'auto'
                        });

                        if (i + maxRowsPerPage < prestaciones.length) {
                            doc.addPage();
                            startY = 30;
                        }
                    }

                    doc.setFontSize(12);
                    doc.text(`Total Cantidad de ${nombre}: ${totalCantidad}`, pageWidth / 2, startY + 10, { align: 'center' });

                    grandTotal += totalCantidad;
                    startY += 20;
                });

                doc.setFontSize(14);
                doc.text(`Total General: ${grandTotal}`, pageWidth / 2, startY + 10, { align: 'center' });

                const imgUrl = '../img/logo.png';
                const img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = startY + 15;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }



    // Función para generar el PDF de OPS

    function generateOpExcel(fechaDesde, fechaHasta) {
        Promise.all([fetchOpData(fechaDesde, fechaHasta)])
            .then(([opData]) => {
                const wb = XLSX.utils.book_new(); // Crear un nuevo libro de trabajo

                // Encabezado de la tabla
                const headers = ['Paciente', 'Fecha', 'Op', 'Cant. Meses', 'Modalidad', 'Fecha Vencimiento'];

                // Crear un array para almacenar los datos
                const data = [];

                // Iterar sobre los datos
                for (const [modalidad, records] of Object.entries(opData)) {
                    // Agregar una fila para la modalidad
                    data.push([`MODALIDAD: ${modalidad}`]); // Título de modalidad

                    // Asegúrate de que records sea un array
                    if (Array.isArray(records) && records.length > 0) {
                        records.forEach(item => {
                            data.push([
                                item.nombre,
                                formatDate(item.fecha),
                                item.op,
                                item.cant,
                                item.descripcion,
                                formatDate(item.fecha_vencimiento)
                            ]);
                        });
                    }

                    data.push([]); // Agregar una fila vacía para separación
                }

                // Agregar encabezados y datos a la hoja
                const wsData = [headers, ...data];
                const ws = XLSX.utils.aoa_to_sheet(wsData);

                // Agregar la hoja al libro de trabajo
                XLSX.utils.book_append_sheet(wb, ws, 'Operaciones');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'Listado_de_Operaciones.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }


    function generateOpPdf(fechaDesde, fechaHasta) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        // Obtener los datos de operaciones
        fetchOpData(fechaDesde, fechaHasta)
            .then((opData) => {
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                // Título del PDF
                const title = 'LISTADO DE OPERACIONES';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                // Encabezado de la tabla
                const headers = ['Paciente', 'Fecha', 'Op', 'Cant. Meses', 'Modalidad', 'Fecha Vencimiento'];

                // Iterar sobre los datos agrupados por modalidad
                for (const [modalidad, records] of Object.entries(opData)) {
                    // Agregar título de modalidad
                    doc.setFontSize(14);
                    doc.text(`MODALIDAD: ${modalidad}`, pageWidth / 2, startY, { align: 'center' });
                    startY += 5;

                    // Asegúrate de que records sea un array
                    if (Array.isArray(records) && records.length > 0) {
                        const data = records.map(item => [
                            item.nombre,
                            formatDate(item.fecha),
                            item.op,
                            item.cant,
                            item.descripcion,
                            formatDate(item.fecha_vencimiento)
                        ]);

                        // Agregar tabla al PDF
                        doc.autoTable({
                            head: [headers],
                            body: data,
                            startY: startY,
                            margin: margin,
                            theme: 'striped',
                            styles: {
                                fontSize: 10,
                                cellPadding: 2,
                                overflow: 'linebreak'
                            },
                            columnStyles: {
                                0: { cellWidth: 50 }, // Paciente
                                1: { cellWidth: 30 }, // Fecha
                                2: { cellWidth: 40 }, // Op
                                3: { cellWidth: 30 }, // Cantidad
                                4: { cellWidth: 50 }, // Modalidad
                                5: { cellWidth: 50 }  // Fecha Vencimiento
                            },
                            didDrawCell: function (data) {
                                // Actualizar el Y para la siguiente tabla
                                startY = data.cursor.y; // Actualiza startY al final de la tabla
                            },
                            pageBreak: 'auto'
                        });

                        // Agregar un espacio después de la tabla
                        startY += 18; // Espacio adicional entre tablas
                    } else {
                        // Si no hay registros, aún actualizar el startY para evitar solapamientos
                        startY += 18; // Espacio adicional para un grupo vacío
                    }
                }

                // Agregar total de registros al final
                const totalRegistros = Object.values(opData).reduce((acc, records) => acc + (Array.isArray(records) ? records.length : 0), 0);
                doc.setFontSize(12);
                doc.text(`Total Registros: ${totalRegistros}`, pageWidth / 2, startY + 10, { align: 'center' });

                // Imagen de logo
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 25;
                    const imgHeight = 20;
                    const xImg = (pageWidth - imgWidth) / 2;

                    doc.addImage(img, 'PNG', xImg, startY + 12, imgWidth, imgHeight);
                    window.open(doc.output('bloburl'));
                };

                img.src = imgUrl;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }


    //OP MES

    function generateOpMesExcel(fechaDesde, fechaHasta) {
        fetch(`./gets/getOpMesData.php?desde=${fechaDesde}&hasta=${fechaHasta}`)
            .then(response => response.json())
            .then((opData) => {
                const wb = XLSX.utils.book_new(); // Libro de Excel

                for (const [modalidad, records] of Object.entries(opData)) {
                    if (Array.isArray(records) && records.length > 0) {
                        // Formatear cada fila
                        const data = records.map(item => ({
                            'Paciente': item.nombre,
                            'Fecha': formatDate(item.fecha),
                            'Op': item.op,
                            'Cant. Meses': item.cant,
                            'Modalidad': item.modalidad_op, // Podés traducirlo si tenés descripciones
                            'Fecha Vencimiento': formatDate(item.fecha_vencimiento)
                        }));

                        // Crear hoja
                        const ws = XLSX.utils.json_to_sheet(data);
                        XLSX.utils.book_append_sheet(wb, ws, `MODALIDAD ${modalidad}`);
                    }
                }

                // Guardar el archivo Excel
                const desdeStr = formatDate(fechaDesde).replace(/\//g, '-');
                const hastaStr = formatDate(fechaHasta).replace(/\//g, '-');
                const fileName = `OP_MES_${desdeStr}_AL_${hastaStr}.xlsx`;
                XLSX.writeFile(wb, fileName);
            })
            .catch(error => {
                console.error('Error generando Excel:', error);
                alert('Ocurrió un error al generar el Excel');
            });
    }


    function generateOpMesPdf(fechaDesde, fechaHasta) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        fetch(`./gets/getOpMesData.php?desde=${fechaDesde}&hasta=${fechaHasta}`)
            .then(response => response.json())
            .then((opData) => {
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'LISTADO DE CONTROL DE OP DEL MES';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                doc.setFontSize(14);
                doc.text(`DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                const headers = ['Paciente', 'Fecha', 'Op', 'Cant. Meses', 'Modalidad', 'Fecha Vencimiento'];

                for (const [modalidad, records] of Object.entries(opData)) {
                    doc.setFontSize(14);
                    doc.text(`MODALIDAD: ${modalidad}`, pageWidth / 2, startY, { align: 'center' });
                    startY += 5;

                    if (Array.isArray(records) && records.length > 0) {
                        const data = records.map(item => [
                            item.nombre,
                            formatDate(item.fecha),
                            item.op,
                            item.cant,
                            item.modalidad_op, // Podés cambiar esto por descripción si tenés otra tabla
                            formatDate(item.fecha_vencimiento)
                        ]);

                        doc.autoTable({
                            head: [headers],
                            body: data,
                            startY: startY,
                            margin: margin,
                            theme: 'striped',
                            styles: { fontSize: 10, cellPadding: 2, overflow: 'linebreak' },
                            columnStyles: {
                                0: { cellWidth: 50 },
                                1: { cellWidth: 30 },
                                2: { cellWidth: 40 },
                                3: { cellWidth: 30 },
                                4: { cellWidth: 50 },
                                5: { cellWidth: 50 }
                            },
                            didDrawCell: function (data) {
                                startY = data.cursor.y;
                            },
                            pageBreak: 'auto'
                        });

                        startY += 18;
                    } else {
                        startY += 18;
                    }
                }

                const totalRegistros = Object.values(opData).reduce((acc, records) => acc + (Array.isArray(records) ? records.length : 0), 0);
                doc.setFontSize(12);
                doc.text(`Total Registros: ${totalRegistros}`, pageWidth / 2, startY + 10, { align: 'center' });

                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 25;
                    const imgHeight = 20;
                    const xImg = (pageWidth - imgWidth) / 2;

                    doc.addImage(img, 'PNG', xImg, startY + 12, imgWidth, imgHeight);
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;
            })
            .catch(error => {
                console.error('Error generando PDF:', error);
                alert('Ocurrió un error al generar el PDF');
            });
    }




    // PACIENTES POR BOCA
    function generatePacientesBocaExcel(fechaDesde, fechaHasta, obraSocialId) {
        fetchPaciBoca(fechaDesde, fechaHasta, obraSocialId)
            .then((resumen) => {
                console.log('Datos de pacientes:', resumen); // Verificar datos en la consola

                // Definir las columnas para el Excel
                const columns = [
                    'AFILIADO',
                    'BENEFICIO',
                    'INGRESO',
                    'ULT. ATENCION',
                    'EGRESO',
                    'BOCA ATENCION',
                    'DIAGNOSTICO'
                ];

                // Usar un Set para registrar beneficios únicos
                const beneficiosUnicos = new Set();

                // Convertir datos en el formato que SheetJS necesita
                const data = resumen
                    .filter(item => {
                        const beneficio = `${item.benef}${item.parentesco}`;
                        // Incluir el item si el beneficio no está en el Set
                        if (!beneficiosUnicos.has(beneficio)) {
                            beneficiosUnicos.add(beneficio);
                            return true;
                        }
                        return false;
                    })
                    .map(item => {
                        const ingreso = item.admision === "0000-00-00" ? formatDate(item.ingreso_modalidad) : formatDate(item.admision);
                        return {
                            'AFILIADO': item.nombre,
                            'BENEFICIO': `${item.benef}${item.parentesco}`,
                            'INGRESO': ingreso,
                            'ULT. ATENCION': formatDate(item.ult_atencion),
                            'EGRESO': formatDate(item.egreso),
                            'BOCA ATENCION': item.boca_de_atencion,
                            'DIAGNOSTICO': item.diag
                        };
                    });

                // Crear libro y hoja de trabajo para Excel
                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.json_to_sheet(data, { header: columns });

                // Agregar hoja de trabajo al libro
                XLSX.utils.book_append_sheet(wb, ws, 'Pacientes');

                // Descargar el archivo Excel
                XLSX.writeFile(wb, 'PacientesBoca.xlsx');
            })
            .catch(error => {
                console.error('Error al generar Excel:', error);
            });
    }



    function normalizeName(name) {
        return name
            .replace(/ñ/g, 'n')
            .replace(/ÿ/g, 'y')
            .replace(/[ý]/g, '');
    }

    function generatePacientesBocaPDF(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        // Obtener los datos de pacientes
        Promise.all([fetchPaciBoca(fechaDesde, fechaHasta, obraSocialId)]).then(([resumen]) => {
            console.log('Datos de pacientes:', resumen); // Verifica los datos aquí

            const formattedFechaDesde = formatDate(fechaDesde);
            const formattedFechaHasta = formatDate(fechaHasta);

            // Título del PDF
            const title = 'LISTADO DE PACIENTES ATENDIDOS POR BOCA DE ATENCION';
            const pageWidth = doc.internal.pageSize.getWidth();

            doc.setFontSize(16);
            doc.setFont('Helvetica', 'bold');
            doc.text(title, pageWidth / 2, 10, { align: 'center' });
            doc.setFont('Helvetica', 'normal');

            // Subtítulo con fechas
            const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
            doc.setFontSize(14);
            doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

            doc.setFontSize(12);
            let startY = 30;
            const margin = { left: 15, right: 15 };

            // Agrupar por modalidad
            const groupedByModalidad = resumen.reduce((acc, item) => {
                const modalidad = item.modalidad_full; // Agrupar por modalidad
                const normalizedNombre = normalizeName(item.nombre);

                if (!acc[modalidad]) {
                    acc[modalidad] = { data: [], total: 0, beneficios: new Set() }; // Set para evitar duplicados
                }

                const admisionFormatted = item.admision == "0000-00-00" ? formatDate(item.ingreso_modalidad) : formatDate(item.admision);
                const beneficio = `${item.benef}${item.parentesco}`;

                // Solo agregar el beneficio si no ha sido agregado antes
                if (!acc[modalidad].beneficios.has(beneficio)) {
                    acc[modalidad].data.push([
                        normalizedNombre,
                        beneficio,
                        admisionFormatted,  // Usar el valor adecuado aquí
                        formatDate(item.ult_atencion),
                        formatDate(item.egreso),
                        item.boca_de_atencion, // Usar boca_de_atencion en lugar de modalidad
                        item.diag
                    ]);
                    acc[modalidad].beneficios.add(beneficio); // Agregar beneficio al Set
                    acc[modalidad].total += 1;
                }

                return acc;
            }, {});

            console.log('Datos agrupados por modalidad:', groupedByModalidad); // Verifica la agrupación aquí

            // Encabezado de la tabla
            const headers = ['AFILIADO', 'BENEFICIO', 'INGRESO', 'ULT. ATENCION', 'EGRESO', 'BOCA ATENCION', 'DIAGNOSTICO'];

            let totalRegistros = 0;

            // Iterar sobre los datos agrupados por modalidad
            for (const modalidad in groupedByModalidad) {
                startY += 10;

                // Agregar título de modalidad
                doc.setFontSize(14);
                doc.text(`MODALIDAD: ${modalidad}`, pageWidth / 2, startY, { align: 'center' });
                startY += 5;

                const { data, total } = groupedByModalidad[modalidad];
                totalRegistros += total;

                if (Array.isArray(data) && data.length > 0) {
                    doc.autoTable({
                        head: [headers],
                        body: data,
                        startY: startY,
                        margin: margin,
                        theme: 'striped',
                        styles: {
                            fontSize: 10,
                            cellPadding: 2,
                            overflow: 'linebreak',
                            minCellHeight: 10
                        },
                        columnStyles: {
                            0: { cellWidth: 50 },
                            1: { cellWidth: 50 },
                            2: { cellWidth: 50 },
                            3: { cellWidth: 30 },
                            4: { cellWidth: 30 },
                            5: { cellWidth: 30 }, // Boca de atención
                            6: { cellWidth: 29 }
                        },
                        pageBreak: 'auto'
                    });

                    // Ajusta startY después de cada tabla para el total por modalidad
                    const finalY = doc.lastAutoTable.finalY + 10;
                    doc.setFontSize(12);
                    const modalityName = modalidad.split(' - ').pop(); // Extrae la parte después del guion
                    doc.text(`TOTAL PACIENTES PARA ${modalityName}: ${total}`, pageWidth / 2, finalY, { align: 'center' });
                    startY = finalY + 10; // Espacio extra antes de la siguiente tabla
                }
            }

            // Agregar total de registros al final
            doc.setFontSize(12);
            doc.text(`TOTAL GENERAL DE REGISTROS: ${totalRegistros}`, pageWidth / 2, startY + 10, { align: 'center' });

            // Imagen de logo
            const imgUrl = '../img/logo.png';
            var img = new Image();
            img.onload = function () {
                const imgWidth = 25;
                const imgHeight = 20;
                const xImg = (pageWidth - imgWidth) / 2;

                doc.addImage(img, 'PNG', xImg, startY + 11, imgWidth, imgHeight);
                window.open(doc.output('bloburl'));
            };
            img.src = imgUrl;
        }).catch(error => {
            console.error('Error:', error);
        });
    }








    // FIN PACIENTES POR BOCA








    //MEDICACION
    function generatePlanExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchPlanData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {

                const data = resumen.map(item => {


                    return [
                        item.nombre,
                        `${item.benef}${item.parentesco}`,
                        formatDate(item.fecha),
                        item.hora,
                        item.dosis,
                        item.medicamento
                    ];
                });

                // Calcular la cantidad total
                const totalQuantity = resumen.reduce((total, item) => {
                    return total + (item.cantidad || 1); // Sumar la cantidad, o 1 si no está definida
                }, 0);
                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'FECHA', 'HORA', 'DOSIS', 'MEDICAMENTO'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'PACIENTES');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'LISTADO_PLAN_MEDICACION.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generatePlanPdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 };
            const rowHeight = 10;
            const headerHeight = 10;

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchPlanData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([response]) => {
                console.log('Respuesta de fetchPlanData:', response); // Añadir para depuración

                // Validar que response sea un objeto con una propiedad 'data' que sea un arreglo
                if (response && Array.isArray(response.data)) {
                    const { data: resumen, totalGeneral } = response;
                    console.log('Resumen:', resumen); // Añadir para depuración

                    const formattedFechaDesde = formatDate(fechaDesde);
                    const formattedFechaHasta = formatDate(fechaHasta);

                    const title = 'PLAN DE MEDICACION';
                    const pageWidth = doc.internal.pageSize.getWidth();

                    doc.setFontSize(16);
                    doc.setFont('Helvetica', 'bold');
                    doc.text(title, pageWidth / 2, 10, { align: 'center' });
                    doc.setFont('Helvetica', 'normal');

                    const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                    doc.setFontSize(14);
                    doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                    doc.setFontSize(12);
                    let startY = 30;
                    const margin = { left: 15 };

                    // Agrupar por paciente
                    const groupedByPaci = resumen.reduce((acc, item) => {
                        const key = `${item.benef} ${item.parentesco}`;
                        if (!acc[key]) {
                            acc[key] = [];
                        }
                        acc[key].push(item);
                        return acc;
                    }, {});

                    // Crear las secciones de paciente y tablas
                    for (const [paciente, items] of Object.entries(groupedByPaci)) {
                        doc.setFontSize(14);
                        doc.setFont('Helvetica', 'bold');
                        const firstItem = items[0];
                        const nombreBeneficiario = firstItem.nombre;
                        const beneficios = `${firstItem.benef} ${firstItem.parentesco}`;

                        const pacienteInfo = `PACIENTE: ${nombreBeneficiario} | Beneficio: ${beneficios}`;
                        doc.text(pacienteInfo, margin.left, startY);
                        doc.setFont('Helvetica', 'normal');

                        const headers = ['FECHA', 'HORA', 'DOSIS', 'MEDICAMENTO', 'CANTIDAD', 'VALOR'];
                        const data = items.map(item => [
                            formatDate(item.fecha),
                            item.hora,
                            item.dosis,
                            item.medicamento,
                            item.cantidad,
                            item.valor
                        ]);

                        const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);
                        let tableStartY = startY + 10;

                        for (let i = 0; i < data.length; i += maxRowsPerPage) {
                            const chunk = data.slice(i, i + maxRowsPerPage);
                            doc.autoTable({
                                head: [headers],
                                body: chunk,
                                startY: tableStartY,
                                margin: margin,
                                theme: 'striped',
                                styles: {
                                    fontSize: 10,
                                    cellPadding: 2,
                                    overflow: 'linebreak'
                                },
                                columnStyles: {
                                    0: { cellWidth: 30 },
                                    1: { cellWidth: 30 },
                                    2: { cellWidth: 30 },
                                    3: { cellWidth: 50 },
                                    4: { cellWidth: 30 },
                                    5: { cellWidth: 30 }
                                },
                                didDrawPage: function (data) {
                                    tableStartY = data.cursor.y;
                                },
                                pageBreak: 'auto'
                            });

                            if (i + maxRowsPerPage < data.length) {
                                doc.addPage();
                                tableStartY = 30;
                            }
                        }

                        const totalPorPaciente = items.reduce((total, item) => total + (parseFloat(item.valor) || 0), 0);
                        doc.setFontSize(12);
                        doc.text(`Total por paciente: ${totalPorPaciente.toFixed(2)}`, pageWidth / 2, tableStartY + 10, { align: 'center' });

                        startY = tableStartY + 20;
                    }

                    doc.setFontSize(12);
                    doc.text(`Total General: ${totalGeneral.toFixed(2)}`, pageWidth / 2, startY + 10, { align: 'center' });

                    const imgUrl = '../img/logo.png';
                    var img = new Image();
                    img.onload = function () {
                        const imgWidth = 29;
                        const imgHeight = 25;
                        const xImg = (pageWidth - imgWidth) / 2;
                        const yImg = startY + 15;

                        doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);
                        window.open(doc.output('bloburl'));
                    };
                    img.src = imgUrl;
                } else {
                    console.error('La respuesta no contiene datos en el formato esperado:', response);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }



    //FIN MEDICACION

    //DIAG
    function generateDiagExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchDiagData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {

                const data = resumen.map(item => {


                    return [
                        item.nombre,
                        `${item.benef}${item.parentesco}`,
                        formatDate(item.fecha_diag),
                        item.diag_full
                    ];
                });

                // Calcular la cantidad total
                const totalQuantity = resumen.reduce((total, item) => {
                    return total + (item.cantidad || 1); // Sumar la cantidad, o 1 si no está definida
                }, 0);
                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'FECHA', 'DIAG'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'PACIENTES');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'LISTADO_DIAGNÓSTICO.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generateDiagPdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 };
            const rowHeight = 10;
            const headerHeight = 10;

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchDiagData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'LISTADO DIAGNÓSTICO';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                // Agrupar por diagnóstico
                const groupedByDiag = resumen.reduce((acc, item) => {
                    const diag = item.diag_full;
                    if (!acc[diag]) {
                        acc[diag] = [];
                    }
                    acc[diag].push(item);
                    return acc;
                }, {});

                // Crear las secciones de diagnóstico y tablas
                for (const [diagnostico, items] of Object.entries(groupedByDiag)) {
                    // Añadir subtítulo del diagnóstico
                    doc.setFontSize(14);
                    doc.setFont('Helvetica', 'bold');
                    doc.text(`DIAGNOSTICO: ${diagnostico}`, margin.left, startY);
                    doc.setFont('Helvetica', 'normal');

                    // Datos de la tabla
                    const headers = ['AFILIADO', 'BENEFICIO', 'FECHA'];
                    const data = items.map(item => [
                        item.nombre,
                        `${item.benef}${item.parentesco}`,
                        formatDate(item.fecha_diag)
                    ]);

                    // Crear la tabla en el PDF
                    const tableWidth = pageWidth - margin.left * 2;
                    const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                    let tableStartY = startY + 10;

                    for (let i = 0; i < data.length; i += maxRowsPerPage) {
                        const chunk = data.slice(i, i + maxRowsPerPage);

                        doc.autoTable({
                            head: [headers],
                            body: chunk,
                            startY: tableStartY,
                            margin: margin,
                            theme: 'striped',
                            styles: {
                                fontSize: 10,
                                cellPadding: 2,
                                overflow: 'linebreak'
                            },
                            columnStyles: {
                                0: { cellWidth: 70 },
                                1: { cellWidth: 45 },
                                2: { cellWidth: 30 }
                            },
                            didDrawPage: function (data) {
                                tableStartY = data.cursor.y;
                            },
                            pageBreak: 'auto'
                        });

                        if (i + maxRowsPerPage < data.length) {
                            doc.addPage();
                            tableStartY = 30;
                        }
                    }

                    startY = tableStartY + 20;
                }

                const totalQuantity = resumen.length;
                doc.setFontSize(12);
                doc.text(`Total Cantidad: ${totalQuantity}`, pageWidth / 2, startY + 10, { align: 'center' });

                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = startY + 15;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;

            }).catch(error => {
                console.error('Error:', error);
            });
    }

    //FIN DIAG

    //INGRESO
    function generateIngresoPdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 }; // Ajusta los márgenes si es necesario
            const rowHeight = 10; // Altura de cada fila
            const headerHeight = 10; // Altura de la fila del encabezado

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchIngresoData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'LISTADO DE INGRESOS REALIZADOS';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                const headers = ['AFILIADO', 'BENEFICIO', 'FECHA ADMISION', 'MODALIDAD'];
                const data = resumen.map(item => [
                    item.nombre,
                    `${item.benef}${item.parentesco}`,
                    formatDate(item.admision),
                    item.modalidad_full
                ]);

                // Calcular la cantidad total
                const totalQuantity = data.length;

                // Crear la tabla en el PDF
                const tableWidth = pageWidth - margin.left * 2;

                if (data.length) {
                    let tableStartY = startY;
                    const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                    for (let i = 0; i < data.length; i += maxRowsPerPage) {
                        const chunk = data.slice(i, i + maxRowsPerPage);

                        doc.autoTable({
                            head: [headers],
                            body: chunk,
                            startY: tableStartY,
                            margin: margin,
                            theme: 'striped',
                            styles: {
                                fontSize: 10,
                                cellPadding: 2,
                                overflow: 'linebreak'
                            },
                            columnStyles: {
                                0: { cellWidth: 70 },
                                1: { cellWidth: 45 },
                                2: { cellWidth: 30 },
                                3: { cellWidth: 80 }
                            },
                            didDrawPage: function (data) {
                                tableStartY = data.cursor.y;
                            },
                            pageBreak: 'auto'
                        });

                        if (i + maxRowsPerPage < data.length) {
                            doc.addPage();
                            tableStartY = 30;
                        }
                    }

                    doc.setFontSize(12);
                    doc.text(`Total Cantidad: ${totalQuantity}`, pageWidth / 2, tableStartY + 10, { align: 'center' });
                    startY = tableStartY + 20;
                }

                doc.setFontSize(14);
                doc.text(`Total General: ${totalQuantity}`, pageWidth / 2, startY + 10, { align: 'center' });

                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = startY + 15;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;

            }).catch(error => {
                console.error('Error:', error);
            });
    }

    function generateIngresoExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchIngresoData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                // Formatear fechas
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const data = resumen.map(item => [
                    item.nombre,
                    `${item.benef}${item.parentesco}`,
                    formatDate(item.admision),
                    item.motivo_egreso
                ]);

                // Calcular la cantidad total
                const totalQuantity = data.length;

                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'FECHA', 'MOTIVO'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'Ingreso');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'Control_de_ingreso.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }



    //FIN INGRESO

    //EGRESO
    function generateEgresoPdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 }; // Ajusta los márgenes si es necesario
            const rowHeight = 10; // Altura de cada fila
            const headerHeight = 10; // Altura de la fila del encabezado

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchEgresoData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'LISTADO DE EGRESOS REALIZADOS';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                const headers = ['AFILIADO', 'BENEFICIO', 'FECHA', 'MOTIVO'];
                const data = resumen.map(item => [
                    item.nombre,
                    `${item.benef}${item.parentesco}`,
                    formatDate(item.fecha_egreso),
                    item.motivo_egreso
                ]);

                // Calcular la cantidad total
                const totalQuantity = data.length;

                // Crear la tabla en el PDF
                const tableWidth = pageWidth - margin.left * 2;

                if (data.length) {
                    let tableStartY = startY;
                    const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                    for (let i = 0; i < data.length; i += maxRowsPerPage) {
                        const chunk = data.slice(i, i + maxRowsPerPage);

                        doc.autoTable({
                            head: [headers],
                            body: chunk,
                            startY: tableStartY,
                            margin: margin,
                            theme: 'striped',
                            styles: {
                                fontSize: 10,
                                cellPadding: 2,
                                overflow: 'linebreak'
                            },
                            columnStyles: {
                                0: { cellWidth: 70 },
                                1: { cellWidth: 45 },
                                2: { cellWidth: 25 },
                                3: { cellWidth: 80 }
                            },
                            didDrawPage: function (data) {
                                tableStartY = data.cursor.y;
                            },
                            pageBreak: 'auto'
                        });

                        if (i + maxRowsPerPage < data.length) {
                            doc.addPage();
                            tableStartY = 30;
                        }
                    }

                    doc.setFontSize(12);
                    doc.text(`Total Cantidad: ${totalQuantity}`, pageWidth / 2, tableStartY + 10, { align: 'center' });
                    startY = tableStartY + 20;
                }

                doc.setFontSize(14);
                doc.text(`Total General: ${totalQuantity}`, pageWidth / 2, startY + 10, { align: 'center' });

                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = startY + 15;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;

            }).catch(error => {
                console.error('Error:', error);
            });
    }

    function generateEgresoExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchEgresoData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const data = resumen.map(item => [
                    item.nombre,
                    `${item.benef}${item.parentesco}`,
                    formatDate(item.fecha_egreso),
                    item.motivo_egreso
                ]);

                // Calcular la cantidad total
                const totalQuantity = data.length;

                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'FECHA', 'MOTIVO'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'Egresos');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'Control_de_Egresos.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    //FIN EGRESO

    //PRACTICAS
    function generatePracExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchPracData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {

                const data = resumen.map(item => {
                    const fechaPract = item.fecha_pract ? formatDate(item.fecha_pract) : '';
                    const fechaTurno = item.fecha_turno ? formatDate(item.fecha_turno) : '';

                    return [
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        fechaTurno || fechaPract,
                        item.turno_pract || item.pract_full,
                        item.cantidad || 1
                    ];
                });

                // Calcular la cantidad total
                const totalQuantity = resumen.reduce((total, item) => {
                    return total + (item.cantidad || 1); // Sumar la cantidad, o 1 si no está definida
                }, 0);
                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'FECHA', 'PRESTACION', 'CANTIDAD'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'PACIENTES');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'LISTADO_DE_PRESTACIONES_REALIZADAS.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generatePracPdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 }; // Adjust margins if needed
            const rowHeight = 10; // Height of each row
            const headerHeight = 10; // Height of header row

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchPracData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                // Agrupar solo por prestación
                const groupedByPract = resumen.reduce((acc, item) => {
                    const practKey = item.turno_pract || item.pract_full;
                    if (!practKey) return acc;

                    if (!acc[practKey]) {
                        acc[practKey] = { data: [], totalQuantity: 0 };
                    }

                    acc[practKey].data.push([
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        formatDate(item.fecha_turno || item.fecha_pract),
                        item.cantidad || 1
                    ]);
                    acc[practKey].totalQuantity += item.cantidad || 1;

                    return acc;
                }, {});

                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'LISTADO DE PRESTACIONES REALIZADAS';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                let grandTotal = 0;

                Object.keys(groupedByPract).forEach(practKey => {
                    const { data, totalQuantity } = groupedByPract[practKey];
                    const practSubtitle = `PRESTACION: ${practKey.toUpperCase()}`;

                    // Título de Prestación
                    doc.setFontSize(12);
                    doc.text(practSubtitle, pageWidth / 2, startY, { align: 'center' });

                    startY += 5; // Espacio para la tabla

                    const tableWidth = pageWidth - margin.left * 2;
                    const headers = ['AFILIADO', 'BENEFICIO', 'FECHA', 'CANTIDAD'];

                    if (data.length) {
                        let tableStartY = startY;
                        const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                        for (let i = 0; i < data.length; i += maxRowsPerPage) {
                            const chunk = data.slice(i, i + maxRowsPerPage);

                            doc.autoTable({
                                head: [headers],
                                body: chunk,
                                startY: tableStartY,
                                margin: margin,
                                theme: 'striped',
                                styles: {
                                    fontSize: 10,
                                    cellPadding: 2,
                                    overflow: 'linebreak'
                                },
                                columnStyles: {
                                    0: { cellWidth: 70 },
                                    1: { cellWidth: 45 },
                                    2: { cellWidth: 80 },
                                    3: { cellWidth: 25 }
                                },
                                didDrawPage: function (data) {
                                    tableStartY = data.cursor.y;
                                },
                                pageBreak: 'auto'
                            });

                            if (i + maxRowsPerPage < data.length) {
                                doc.addPage();
                                tableStartY = 30;
                            }
                        }

                        doc.setFontSize(12);
                        doc.text(`Total Cantidad ${practKey.toUpperCase()}: ${totalQuantity}`, pageWidth / 2, tableStartY + 10, { align: 'center' });

                        grandTotal += totalQuantity;
                        startY = tableStartY + 20;
                    }
                });

                doc.setFontSize(14);
                doc.text(`Total General: ${grandTotal}`, pageWidth / 2, startY + 10, { align: 'center' });

                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = startY + 15;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;

            }).catch(error => {
                console.error('Error:', error);
            });
    }
    //FIN PRACTICAS

    //PROFESIONALES
    function generateProfExcel(fechaDesde, fechaHasta, obraSocialId, profesional) {
        Promise.all([fetchProfData(fechaDesde, fechaHasta, obraSocialId, profesional)])
            .then(([resumen]) => {

                const data = resumen.map(item => {
                    const fechaPract = item.fecha_pract ? formatDate(item.fecha_pract) : '';
                    const fechaTurno = item.fecha_turno ? formatDate(item.fecha_turno) : '';

                    return [
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        item.pract_full || item.turno_pract, // Preferir pract_full si existe
                        fechaPract || fechaTurno, // Mostrar fecha_pract si existe, de lo contrario mostrar fecha_turno
                        item.prof,
                        item.cantidad || 1
                    ];
                });

                // Calcular la cantidad total
                const totalQuantity = resumen.reduce((total, item) => {
                    return total + (item.cantidad || 1); // Sumar la cantidad, o 1 si no está definida
                }, 0);
                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'PRACTICA/TURNO', 'FECHA', 'PROFESIONAL', 'CANTIDAD'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'PACIENTES');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'LISTADO DE PRESTACIONES REALIZADAS POR PROFESIONAL.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generateProfPdf(fechaDesde, fechaHasta, obraSocialId, profesional) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 }; // Ajusta los márgenes si es necesario
            const rowHeight = 10; // Altura de cada fila
            const headerHeight = 10; // Altura de la fila del encabezado

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchProfData(fechaDesde, fechaHasta, obraSocialId, profesional)])
            .then(([resumen]) => {
                const groupedByProfessional = resumen.reduce((acc, item) => {
                    if (!acc[item.prof]) {
                        acc[item.prof] = {}; // Crear un nuevo objeto para el profesional
                    }

                    if (!acc[item.prof][item.modalidad_full]) {
                        acc[item.prof][item.modalidad_full] = { data: [], totalQuantity: 0 };
                    }

                    if (item.pract_full) {
                        acc[item.prof][item.modalidad_full].data.push([
                            item.nombre,
                            (`${item.benef}${item.parentesco}`),
                            item.pract_full,
                            formatDate(item.fecha_pract),
                            item.cantidad || 1
                        ]);
                        acc[item.prof][item.modalidad_full].totalQuantity += item.cantidad || 1;
                    }

                    if (item.turno_pract) {
                        acc[item.prof][item.modalidad_full].data.push([
                            item.nombre,
                            (`${item.benef}${item.parentesco}`),
                            item.turno_pract,
                            formatDate(item.fecha_turno),
                            item.cantidad || 1
                        ]);
                        acc[item.prof][item.modalidad_full].totalQuantity += item.cantidad || 1;
                    }

                    return acc;
                }, {});

                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'LISTADO DE PRESTACIONES REALIZADAS POR PROFESIONAL';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                let grandTotal = 0; // Variable para acumular el total para todas las modalidades

                Object.keys(groupedByProfessional).forEach(prof => {
                    const modalities = groupedByProfessional[prof];
                    const profUpperCase = prof.toUpperCase();
                    const profSubtitle = `PROFESIONAL: ${profUpperCase}`;
                    doc.setFontSize(12);
                    doc.text(profSubtitle, pageWidth / 2, startY, { align: 'center' });

                    startY += 10; // Mover hacia abajo para las tablas

                    Object.keys(modalities).forEach(modality => {
                        const { data, totalQuantity } = modalities[modality];
                        const modalityUpperCase = modality.toUpperCase();
                        const subtitle = `MODALIDAD: ${modalityUpperCase}`;

                        doc.text(subtitle, pageWidth / 2, startY, { align: 'center' });

                        startY += 10; // Mover hacia abajo para la tabla

                        const tableWidth = pageWidth - margin.left * 2;
                        const headers = ['AFILIADO', 'BENEFICIO', 'PRACTICA/TURNO', 'FECHA', 'CANTIDAD'];

                        if (data.length) {
                            let tableStartY = startY;
                            const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                            // Dividir los datos en fragmentos dinámicamente
                            for (let i = 0; i < data.length; i += maxRowsPerPage) {
                                const chunk = data.slice(i, i + maxRowsPerPage);

                                doc.autoTable({
                                    head: [headers],
                                    body: chunk,
                                    startY: tableStartY,
                                    margin: margin,
                                    theme: 'striped',
                                    styles: {
                                        fontSize: 10,
                                        cellPadding: 2,
                                        overflow: 'linebreak'
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 70 },
                                        1: { cellWidth: 45 },
                                        2: { cellWidth: 80 },
                                        3: { cellWidth: 25 },
                                        4: { cellWidth: 23 }
                                    },
                                    didDrawPage: function (data) {
                                        tableStartY = data.cursor.y;
                                    },
                                    pageBreak: 'auto'
                                });

                                if (i + maxRowsPerPage < data.length) {
                                    doc.addPage(); // Agregar una nueva página si hay más datos
                                    tableStartY = 30; // Reiniciar la posición de inicio para la nueva página
                                }
                            }

                            // Agregar el total de cantidad para esta modalidad
                            doc.setFontSize(12);
                            doc.text(`Total Cantidad de ${profUpperCase}: ${totalQuantity}`, pageWidth / 2, tableStartY + 10, { align: 'center' });

                            grandTotal += totalQuantity; // Acumular al total general

                            startY = tableStartY + 20; // Actualizar startY después del último fragmento
                        }
                    });
                });

                // Agregar el total general al final del documento
                doc.setFontSize(14);
                doc.text(`Total General: ${grandTotal}`, pageWidth / 2, startY + 10, { align: 'center' });

                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = startY + 15;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;

            }).catch(error => {
                console.error('Error:', error);
            });
    }
    //FIN PROFESIONALES

    //RESUMEN ESTADISTICAS POR MODALIDAD
    function generateExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {

                const data = resumen.map(item => {
                    const fechaPract = item.fecha_pract ? formatDate(item.fecha_pract) : '';
                    const fechaTurno = item.fecha_turno ? formatDate(item.fecha_turno) : '';

                    return [
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        item.pract_full || item.turno_pract, // Preferir pract_full si existe
                        fechaPract || fechaTurno, // Mostrar fecha_pract si existe, de lo contrario mostrar fecha_turno
                        item.cantidad || 1,
                        item.modalidad_full
                    ];
                });

                // Calcular la cantidad total
                const totalQuantity = resumen.reduce((total, item) => {
                    return total + (item.cantidad || 1); // Sumar la cantidad, o 1 si no está definida
                }, 0);
                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'PRACTICA/TURNO', 'FECHA', 'CANTIDAD', 'MODALIDAD'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'PACIENTES');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'LISTADO_DE_PRESTACIONES_REALIZADAS_POR_PACIENTE_Y_MODALIDAD.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generatePdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 };
            const rowHeight = 10;
            const headerHeight = 10;

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            return Math.min(Math.floor(availableHeight / rowHeight), data.length);
        }

        Promise.all([fetchData(fechaDesde, fechaHasta, obraSocialId)]).then(([resumen]) => {
            const groupedByModality = resumen.reduce((acc, item) => {
                if (!acc[item.modalidad_full]) {
                    acc[item.modalidad_full] = { data: [], totalQuantity: 0, isInternacion: false };
                }

                // Extraer el código de modalidad desde modalidad_full
                const codigoModalidad = item.modalidad_full ? item.modalidad_full.split(' - ')[0] : 'sin modalidad';

                const isInternacion = ['11', '12'].includes(codigoModalidad); // Identifica internación por el código

                acc[item.modalidad_full].isInternacion = isInternacion;

                if (item.pract_full) {
                    acc[item.modalidad_full].data.push([
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        item.pract_full,
                        formatDate(item.fecha_pract),
                        item.cantidad || 1
                    ]);
                    acc[item.modalidad_full].totalQuantity += item.cantidad || 1;
                }

                if (item.turno_pract) {
                    acc[item.modalidad_full].data.push([
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        item.turno_pract,
                        formatDate(item.fecha_turno),
                        item.cantidad || 1
                    ]);
                    acc[item.modalidad_full].totalQuantity += item.cantidad || 1;
                }

                return acc;
            }, {});

            const formattedFechaDesde = formatDate(fechaDesde);
            const formattedFechaHasta = formatDate(fechaHasta);
            const title = 'LISTADO DE PRESTACIONES REALIZADAS POR PACIENTE Y MODALIDAD';
            const pageWidth = doc.internal.pageSize.getWidth();

            doc.setFontSize(16);
            doc.setFont('Helvetica', 'bold');
            doc.text(title, pageWidth / 2, 10, { align: 'center' });

            const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
            doc.setFontSize(14);
            doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

            doc.setFontSize(12);
            let startY = 30;
            const margin = { left: 15 };

            let grandTotal = 0;
            let totalAmbulatorio = 0;
            let totalInternacion = 0;

            // Función para imprimir un grupo (Ambulatorio o Internación)
            function printGroup(title, modalities, startY) {
                if (modalities.length > 0) {
                    doc.setFontSize(14);
                    doc.text(title, margin.left, startY); // Título del grupo
                    startY += 10;

                    modalities.forEach(modality => {
                        const { data, totalQuantity, isInternacion } = groupedByModality[modality];
                        const modalityUpperCase = modality.toUpperCase();
                        const subtitle = `MODALIDAD: ${modalityUpperCase}`;

                        doc.setFontSize(12);
                        doc.text(subtitle, pageWidth / 2, startY, { align: 'center' });
                        startY += 10;

                        const headers = ['AFILIADO', 'BENEFICIO', 'PRACTICA/TURNO', 'FECHA', 'CANTIDAD'];

                        if (data.length) {
                            let tableStartY = startY;
                            const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                            for (let i = 0; i < data.length; i += maxRowsPerPage) {
                                const chunk = data.slice(i, i + maxRowsPerPage);

                                doc.autoTable({
                                    head: [headers],
                                    body: chunk,
                                    startY: tableStartY,
                                    margin: margin,
                                    theme: 'striped',
                                    styles: {
                                        fontSize: 10,
                                        cellPadding: 2,
                                        overflow: 'linebreak'
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 50 }, // Ajusta el ancho de las columnas
                                        1: { cellWidth: 50 },
                                        2: { cellWidth: 125 },
                                        3: { cellWidth: 25 },
                                        4: { cellWidth: 25 }
                                    },
                                    didDrawPage: function (data) {
                                        tableStartY = data.cursor.y;
                                    },
                                    pageBreak: 'auto'
                                });

                                if (i + maxRowsPerPage < data.length) {
                                    doc.addPage();
                                    tableStartY = 30;
                                }
                            }

                            doc.setFontSize(12);
                            const temp = modalityUpperCase;
                            const modalityName = temp.split(' - ').pop(); // Extrae la parte después del guion
                            doc.text(`Total Cantidad ${modalityName}: ${totalQuantity}`, pageWidth / 2, tableStartY + 10, { align: 'center' });


                            grandTotal += totalQuantity;

                            if (isInternacion) {
                                totalInternacion += totalQuantity;
                            } else {
                                totalAmbulatorio += totalQuantity;
                            }

                            startY = tableStartY + 20;
                        }
                    });
                }
                return startY;
            }

            // Agrupar modalidades en ambulatorio e internación
            const ambulatorioModalities = Object.keys(groupedByModality).filter(modality => !groupedByModality[modality].isInternacion);
            const internacionModalities = Object.keys(groupedByModality).filter(modality => groupedByModality[modality].isInternacion);

            // Imprimir grupo de Ambulatorio
            startY = printGroup('AMBULATORIO', ambulatorioModalities, startY);

            // Imprimir grupo de Internación
            startY = printGroup('INTERNACION', internacionModalities, startY);

            // Mostrar los totales generales
            doc.setFontSize(14);

            // Verificar si hay espacio suficiente para los totales y el logo
            const pageHeight = doc.internal.pageSize.height;
            const remainingHeight = pageHeight - startY;

            const requiredHeightForTotalsAndLogo = 40; // Espacio necesario para totales y logo

            if (remainingHeight < requiredHeightForTotalsAndLogo) {
                doc.addPage(); // Añadir nueva página si no hay suficiente espacio
                startY = 30;   // Reiniciar el Y inicial en la nueva página
            }

            // Total Ambulatorio alineado a la izquierda
            doc.text(`Total Ambulatorio: ${totalAmbulatorio}`, margin.left, startY + 5);

            // Total Internación alineado a la izquierda
            doc.text(`Total Internación: ${totalInternacion}`, margin.left, startY + 10);

            // Total General centrado, debajo de los otros dos
            doc.text(`Total General: ${grandTotal}`, pageWidth / 2, startY + 15, { align: 'center' });

            // Imagen de logo
            const imgUrl = '../img/logo.png';
            var img = new Image();
            img.onload = function () {
                const imgWidth = 25;
                const imgHeight = 20;
                const xImg = (pageWidth - imgWidth) / 2;
                const yImg = startY + 25;

                // Verificar si hay suficiente espacio para el logo
                const remainingHeightForLogo = pageHeight - (startY + 25);
                if (remainingHeightForLogo < imgHeight) {
                    doc.addPage(); // Añadir nueva página si no hay suficiente espacio
                    startY = 30;   // Reiniciar el Y inicial en la nueva página para el logo
                }

                doc.addImage(img, 'PNG', xImg, startY + 25, imgWidth, imgHeight);
                window.open(doc.output('bloburl'));
            };
            img.src = imgUrl;


        }).catch(error => {
            console.error('Error:', error);
        });
    }
    //FIN RESUMEN ESTADISTICAS POR MODALIDAD

    //PACI UNICOS

    function generatePaciUnicosExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchPaciUnicos(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {

                const data = resumen.map(item => {
                    const fechaPract = item.fecha_pract ? formatDate(item.fecha_pract) : '';
                    const fechaTurno = item.fecha_turno ? formatDate(item.fecha_turno) : '';

                    return [
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        item.pract_full || item.turno_pract, // Preferir pract_full si existe
                        fechaPract || fechaTurno, // Mostrar fecha_pract si existe, de lo contrario mostrar fecha_turno
                        item.cantidad || 1
                    ];
                });

                // Calcular la cantidad total
                const totalQuantity = resumen.reduce((total, item) => {
                    return total + (item.cantidad || 1); // Sumar la cantidad, o 1 si no está definida
                }, 0);
                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de egresos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'PRACTICA/TURNO', 'FECHA', 'CANTIDAD'], // Encabezados
                    ...data,
                    [], // Fila vacía para separación
                    [`Total Cantidad: ${totalQuantity}`] // Fila de total
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'PACIENTES');

                // Agregar hoja de total general
                const grandTotalSheet = XLSX.utils.aoa_to_sheet([
                    ['Total General', totalQuantity]
                ]);
                XLSX.utils.book_append_sheet(wb, grandTotalSheet, 'Total General');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'PACIENTES UNICOS.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generatePaciUnicosPDF(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 };
            const rowHeight = 10;
            const headerHeight = 10;

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            return Math.min(Math.floor(availableHeight / rowHeight), data.length);
        }

        Promise.all([fetchPaciUnicos(fechaDesde, fechaHasta, obraSocialId)]).then(([resumen]) => {
            const groupedByModality = resumen.reduce((acc, item) => {
                if (!item.modalidad_full) return acc;

                if (!acc[item.modalidad_full]) {
                    acc[item.modalidad_full] = { data: [], totalQuantity: 0, isInternacion: false };
                }

                // Extraer el código de modalidad desde modalidad_full
                const codigoModalidad = item.modalidad_full.split(' - ')[0];
                const isInternacion = ['11', '12'].includes(codigoModalidad); // Identifica internación por el código

                acc[item.modalidad_full].isInternacion = isInternacion;

                if (item.pract_full) {
                    acc[item.modalidad_full].data.push([
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        item.pract_full,
                        formatDate(item.fecha_pract),
                        item.cantidad || 1
                    ]);
                    acc[item.modalidad_full].totalQuantity += item.cantidad || 1;
                }

                if (item.turno_pract) {
                    acc[item.modalidad_full].data.push([
                        item.nombre,
                        (`${item.benef}${item.parentesco}`),
                        item.turno_pract,
                        formatDate(item.fecha_turno),
                        item.cantidad || 1
                    ]);
                    acc[item.modalidad_full].totalQuantity += item.cantidad || 1;
                }

                return acc;
            }, {});

            const formattedFechaDesde = formatDate(fechaDesde);
            const formattedFechaHasta = formatDate(fechaHasta);
            const title = 'PACIENTES UNICOS';
            const pageWidth = doc.internal.pageSize.getWidth();

            doc.setFontSize(16);
            doc.setFont('Helvetica', 'bold');
            doc.text(title, pageWidth / 2, 10, { align: 'center' });

            const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
            doc.setFontSize(14);
            doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

            doc.setFontSize(12);
            let startY = 30;
            const margin = { left: 15 };

            let grandTotal = 0;
            let totalAmbulatorio = 0;
            let totalInternacion = 0;

            // Función para imprimir un grupo (Ambulatorio o Internación)
            function printGroup(title, modalities, startY) {
                if (modalities.length > 0) {
                    doc.setFontSize(14);
                    doc.text(title, margin.left, startY); // Título del grupo
                    startY += 10;

                    modalities.forEach(modality => {
                        const { data, totalQuantity, isInternacion } = groupedByModality[modality];
                        const modalityUpperCase = modality.toUpperCase();
                        const subtitle = `MODALIDAD: ${modalityUpperCase}`;

                        doc.setFontSize(12);
                        doc.text(subtitle, pageWidth / 2, startY, { align: 'center' });
                        startY += 10;

                        const headers = ['AFILIADO', 'BENEFICIO', 'PRACTICA/TURNO', 'FECHA', 'CANTIDAD'];

                        if (data.length) {
                            let tableStartY = startY;
                            const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                            for (let i = 0; i < data.length; i += maxRowsPerPage) {
                                const chunk = data.slice(i, i + maxRowsPerPage);

                                doc.autoTable({
                                    head: [headers],
                                    body: chunk,
                                    startY: tableStartY,
                                    margin: margin,
                                    theme: 'striped',
                                    styles: {
                                        fontSize: 10,
                                        cellPadding: 2,
                                        overflow: 'linebreak'
                                    },
                                    columnStyles: {
                                        0: { cellWidth: 50 }, // Ajusta el ancho de las columnas
                                        1: { cellWidth: 50 },
                                        2: { cellWidth: 125 },
                                        3: { cellWidth: 25 },
                                        4: { cellWidth: 25 }
                                    },
                                    didDrawPage: function (data) {
                                        tableStartY = data.cursor.y;
                                    },
                                    pageBreak: 'auto'
                                });

                                if (i + maxRowsPerPage < data.length) {
                                    doc.addPage();
                                    tableStartY = 30;
                                }
                            }

                            doc.setFontSize(12);
                            doc.text(`Total Cantidad: ${totalQuantity}`, pageWidth / 2, tableStartY + 10, { align: 'center' });

                            grandTotal += totalQuantity;

                            if (isInternacion) {
                                totalInternacion += totalQuantity;
                            } else {
                                totalAmbulatorio += totalQuantity;
                            }

                            startY = tableStartY + 20;
                        }
                    });
                }
                return startY;
            }

            // Agrupar modalidades en ambulatorio e internación
            const ambulatorioModalities = Object.keys(groupedByModality).filter(modality => !groupedByModality[modality].isInternacion);
            const internacionModalities = Object.keys(groupedByModality).filter(modality => groupedByModality[modality].isInternacion);

            // Imprimir grupo de Ambulatorio
            startY = printGroup('AMBULATORIO', ambulatorioModalities, startY);

            // Imprimir grupo de Internación
            startY = printGroup('INTERNACION', internacionModalities, startY);

            // Mostrar los totales generales
            doc.setFontSize(14);

            // Verificar si hay espacio suficiente para los totales y el logo
            const pageHeight = doc.internal.pageSize.height;
            const remainingHeight = pageHeight - startY;

            const requiredHeightForTotalsAndLogo = 40; // Espacio necesario para totales y logo

            if (remainingHeight < requiredHeightForTotalsAndLogo) {
                doc.addPage(); // Añadir nueva página si no hay suficiente espacio
                startY = 30;   // Reiniciar el Y inicial en la nueva página
            }

            // Total Ambulatorio alineado a la izquierda
            doc.text(`Total Ambulatorio: ${totalAmbulatorio}`, margin.left, startY + 5);

            // Total Internación alineado a la izquierda
            doc.text(`Total Internación: ${totalInternacion}`, margin.left, startY + 10);

            // Total General centrado, debajo de los otros dos
            doc.text(`Total General: ${grandTotal}`, pageWidth / 2, startY + 15, { align: 'center' });

            // Imagen de logo
            const imgUrl = '../img/logo.png';
            var img = new Image();
            img.onload = function () {
                const imgWidth = 25;
                const imgHeight = 20;
                const xImg = (pageWidth - imgWidth) / 2;
                const yImg = startY + 25;

                // Verificar si hay suficiente espacio para el logo
                const remainingHeightForLogo = pageHeight - (startY + 25);
                if (remainingHeightForLogo < imgHeight) {
                    doc.addPage(); // Añadir nueva página si no hay suficiente espacio
                    startY = 30;   // Reiniciar el Y inicial en la nueva página para el logo
                }

                doc.addImage(img, 'PNG', xImg, startY + 25, imgWidth, imgHeight);
                window.open(doc.output('bloburl'));
            };
            img.src = imgUrl;


        }).catch(error => {
            console.error('Error:', error);
        });
    }

    //FIN PACI UNICOS

    //PACIENTES
    function generatePatientExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchPatientData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const groupedData = {};

                // Agrupar datos por 'benef'
                resumen.forEach(item => {
                    const benefKey = item.benef; // Usar 'benef' como clave de agrupación

                    if (!groupedData[benefKey]) {
                        // Si no existe, inicializar el objeto
                        groupedData[benefKey] = {
                            nombre: item.nombre.trim(),
                            benef: item.benef,
                            parentesco: item.parentesco,
                            diag: item.diag,
                            admision: item.ingreso_modalidad,
                            op: item.op,
                            egreso: item.egreso,
                            cantidad: 0, // Sumar cantidades
                            ult_atencion: item.ult_atencion // Se actualiza después
                        };
                    }

                    // Sumar la cantidad
                    groupedData[benefKey].cantidad += parseInt(item.cantidad) || 1; // Sumar 1 si no hay cantidad definida

                    // Comparar y actualizar 'ult_atencion' si la actual es más reciente
                    if (!groupedData[benefKey].ult_atencion || new Date(item.ult_atencion) > new Date(groupedData[benefKey].ult_atencion)) {
                        groupedData[benefKey].ult_atencion = item.ult_atencion;
                    }
                });

                // Convertir el objeto agrupado a un array
                const data = Object.values(groupedData).map(item => {
                    const admision = item.admision ? formatDate(item.admision) : '';
                    const egreso = item.egreso ? formatDate(item.egreso) : '';
                    const ult_atencion = item.ult_atencion ? formatDate(item.ult_atencion) : '';

                    return [
                        item.nombre,
                        `${item.benef}${item.parentesco}`,
                        admision,
                        item.op,
                        item.diag,
                        egreso,
                        ult_atencion,
                        item.cantidad // Valor total de prácticas/turnos
                    ];
                });

                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja de trabajo con los datos de pacientes
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'INGRESO', 'OP', 'DIAG', 'EGRESO', 'ULT. ATENCION', 'CANTIDAD'], // Encabezados
                    ...data
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'PACIENTES');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'LISTADO_DE_PACIENTES_ATENDIDOS_POR_MODALIDAD.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }



    function generatePatientPdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 };
            const rowHeight = 10;
            const headerHeight = 10;

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchPatientData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const aggregatedData = {};

                // Agrupar por nombre y modalidad, solo la última atención se guarda
                resumen.forEach(item => {
                    const key = `${item.nombre}-${item.modalidad_full}`;
                    if (!aggregatedData[key] || new Date(item.ult_atencion) > new Date(aggregatedData[key].ult_atencion)) {
                        aggregatedData[key] = item;
                    }
                });

                const data = Object.values(aggregatedData);
                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);
                const title = 'LISTADO DE PACIENTES ATENDIDOS POR MODALIDAD';
                const pageWidth = doc.internal.pageSize.getWidth();

                // Título del reporte
                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                // Separar datos con modalidad definida e indefinida
                const definedData = data.filter(item => item.modalidad_full && item.modalidad_full.includes(' - '));
                const undefinedData = data.filter(item => !item.modalidad_full || !item.modalidad_full.includes(' - '));

                // Filtrar las modalidades por internación (11, 12) y ambulatorio (las demás)
                const internacionData = definedData.filter(item => {
                    const modalidad = item.modalidad_full;
                    return modalidad && ['11', '12'].includes(modalidad.split(' - ')[0]);
                });

                const ambulatorioData = definedData.filter(item => {
                    const modalidad = item.modalidad_full;
                    return modalidad && !['11', '12'].includes(modalidad.split(' - ')[0]);
                });


                // Variables para los totales
                let totalAmbulatorio = 0;
                let totalInternacion = 0;

                // Función para dibujar los datos de cada modalidad
                function drawModalityTable(modalityData, modality, startY) {
                    doc.setFontSize(12);

                    const modalityDescription = modality && modality.includes(' - ')
                        ? modality.split(' - ')[1]
                        : 'MODALIDAD INDEFINIDA';

                    doc.text(`MODALIDAD: ${modalityDescription.toUpperCase()}`, pageWidth / 2, startY, { align: 'center' });

                    startY += 5;

                    const headers = ['AFILIADO', 'BENEFICIO', 'INGRESO', 'OP', 'DIAG', 'EGRESO', 'Ult. ATENCION'];

                    if (modalityData.length) {
                        let tableStartY = startY;
                        const maxRowsPerPage = getMaxRowsPerPage(doc, headers, modalityData);

                        for (let i = 0; i < modalityData.length; i += maxRowsPerPage) {
                            const chunk = modalityData.slice(i, i + maxRowsPerPage);
                            doc.autoTable({
                                head: [headers],
                                body: chunk.map(item => [
                                    item.nombre,
                                    `${item.benef}${item.parentesco}`,
                                    formatDate(item.ingreso_modalidad),
                                    item.ops || '',
                                    item.diag || '',
                                    item.egreso ? formatDate(item.egreso) : ' ',
                                    formatDate(item.ult_atencion)
                                ]),
                                startY: tableStartY,
                                margin: margin,
                                theme: 'striped',
                                styles: {
                                    fontSize: 10,
                                    cellPadding: 2,
                                    overflow: 'linebreak'
                                },
                                columnStyles: {
                                    0: { cellWidth: 70 },
                                    1: { cellWidth: 45 },
                                    2: { cellWidth: 25 },
                                    3: { cellWidth: 25 },
                                    4: { cellWidth: 23 },
                                    5: { cellWidth: 30 }
                                },
                                didDrawPage: function (data) {
                                    tableStartY = data.cursor.y;
                                },
                                pageBreak: 'auto'
                            });

                            if (i + maxRowsPerPage < modalityData.length) {
                                doc.addPage();
                                tableStartY = 30;
                            }
                        }

                        // Agregar total de la modalidad debajo de la tabla
                        startY = doc.autoTable.previous.finalY + 10;
                        doc.setFontSize(12);
                        doc.text(`Total para ${modalityDescription.toUpperCase()}: ${modalityData.length}`, margin.left, startY);

                        startY += 10;
                    }

                    return startY;
                }


                // Dibujar modalidades indefinidas
                if (undefinedData.length) {
                    doc.setFontSize(14);
                    doc.text('MODALIDADES INDEFINIDAS', pageWidth / 2, startY, { align: 'center' });
                    startY += 10;

                    startY = drawModalityTable(undefinedData, 'INDEFINIDA', startY);
                }

                // Agregar sección de INTERNACIÓN (Modalidades 11 y 12)
                if (internacionData.length) {
                    doc.setFontSize(14);

                    // Texto "INTERNACIÓN"
                    doc.text('INTERNACIÓN', pageWidth / 2, startY, { align: 'center' });
                    // Establecer el grosor de la línea
                    doc.setLineWidth(0.5); // Cambia el valor para ajustar el grosor
                    // Dibujar una línea debajo del texto
                    const textWidth = doc.getTextWidth('INTERNACIÓN');
                    const lineY = startY + 2; // Ajusta la posición Y de la línea
                    const startX = (pageWidth - textWidth) / 2; // Centra la línea

                    doc.line(startX, lineY, startX + textWidth, lineY); // Dibuja la línea

                    startY += 10; // Aumenta el valor de startY para el siguiente texto

                    // Dibujar tabla para cada modalidad de internación
                    const modalitiesInternacion = [...new Set(internacionData.map(item => item.modalidad_full))];
                    modalitiesInternacion.forEach(modality => {
                        const modalityData = internacionData.filter(item => item.modalidad_full === modality);
                        startY = drawModalityTable(modalityData, modality, startY);
                    });

                    totalInternacion = internacionData.length;
                }

                // Agregar sección de AMBULATORIO (Otras modalidades)
                if (ambulatorioData.length) {
                    doc.setFontSize(14);
                    // Texto "AMBULATORIO"
                    doc.text('AMBULATORIO', pageWidth / 2, startY, { align: 'center' });
                    // Establecer el grosor de la línea
                    doc.setLineWidth(0.5); // Cambia el valor para ajustar el grosor
                    // Dibujar una línea debajo del texto
                    const textWidth = doc.getTextWidth('AMBULATORIO');
                    const lineY = startY + 2; // Ajusta la posición Y de la línea
                    const startX = (pageWidth - textWidth) / 2; // Centra la línea

                    doc.line(startX, lineY, startX + textWidth, lineY); // Dibuja la línea

                    startY += 10; // Aumenta el valor de startY para el siguiente texto


                    // Dibujar tabla para cada modalidad de ambulatorio
                    const modalitiesAmbulatorio = [...new Set(ambulatorioData.map(item => item.modalidad_full))];
                    modalitiesAmbulatorio.forEach(modality => {
                        const modalityData = ambulatorioData.filter(item => item.modalidad_full === modality);
                        startY = drawModalityTable(modalityData, modality, startY);
                    });

                    totalAmbulatorio = ambulatorioData.length;
                }

                // Agregar nueva página antes de los totales finales si es necesario
                if (startY + 50 > doc.internal.pageSize.height) {
                    doc.addPage();
                    startY = 30; // Reiniciar la posición Y al comienzo de la nueva página
                }

                // Totales finales
                doc.setFontSize(14);
                doc.text(`Total Ambulatorio: ${totalAmbulatorio}`, margin.left, startY + 2);
                doc.text(`Total Internación: ${totalInternacion}`, margin.left, startY + 12);
                doc.text(`Total General: ${data.length}`, pageWidth / 2, startY + 22, { align: 'center' });

                // Imagen de logo
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 25;
                    const imgHeight = 20;
                    const xImg = (pageWidth - imgWidth) / 2;

                    doc.addImage(img, 'PNG', xImg, startY + 25, imgWidth, imgHeight);
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;


            }).catch(error => {
                console.error('Error:', error);
            });
    }




    //FIN PACIENTES

    //OME
    function generateOmesPdf(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 }; // Adjust margins if needed
            const rowHeight = 10; // Height of each row
            const headerHeight = 10; // Height of header row

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        Promise.all([fetchOmesData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                const groupedByModality = resumen.reduce((acc, item) => {
                    if (!acc[item.modalidad_full]) {
                        acc[item.modalidad_full] = { data: [], totalQuantity: 0 };
                    }

                    acc[item.modalidad_full].data.push([
                        item.nombre,
                        `${item.benef}${item.parentesco}`,
                        item.turno_pract,
                        formatDate(item.fecha_turno),
                        item.prof
                    ]);

                    acc[item.modalidad_full].totalQuantity += 1;

                    return acc;
                }, {});

                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'CONTROL DE CARGAS OME';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                // Establecer la fuente en negrita
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });

                // Restablecer la fuente a la predeterminada (opcional)
                doc.setFont('Helvetica', 'normal');

                const dateRange = `Desde: ${formattedFechaDesde} Hasta: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                let grandTotal = 0; // Variable to accumulate total for all modalities

                Object.keys(groupedByModality).forEach(modality => {
                    const { data, totalQuantity } = groupedByModality[modality];
                    const modalityUpperCase = modality.toUpperCase(); // Convierte la variable a mayúsculas
                    const subtitle = `MODALIDAD: ${modalityUpperCase}`;
                    doc.setFontSize(12);
                    doc.text(subtitle, pageWidth / 2, startY, { align: 'center' });
                    startY += 5; // Move down for tables


                    const tableWidth = pageWidth - margin.left * 2;
                    const headers = ['AFILIADO', 'BENEFICIO', 'PRACT.', 'FECHA', 'PROF'];

                    if (data.length) {
                        let tableStartY = startY;
                        const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                        // Split data into chunks dynamically
                        for (let i = 0; i < data.length; i += maxRowsPerPage) {
                            const chunk = data.slice(i, i + maxRowsPerPage);

                            doc.autoTable({
                                head: [headers],
                                body: chunk,
                                startY: tableStartY,
                                margin: margin,
                                theme: 'striped',
                                styles: {
                                    fontSize: 10,
                                    cellPadding: 2,
                                    overflow: 'linebreak'
                                },
                                columnStyles: {
                                    0: { cellWidth: 70 },
                                    1: { cellWidth: 45 },
                                    2: { cellWidth: 80 },
                                    3: { cellWidth: 25 },
                                    4: { cellWidth: 23 },
                                    5: { cellWidth: 30 } // Adjust cell width as needed
                                },
                                didDrawPage: function (data) {
                                    tableStartY = data.cursor.y;
                                },
                                pageBreak: 'auto'
                            });

                            if (i + maxRowsPerPage < data.length) {
                                doc.addPage(); // Add a new page if there is more data
                                tableStartY = 30; // Reset start position for the new page
                            }
                        }

                        // Add total quantity for this modality
                        doc.setFontSize(12);
                        doc.text(`Total Cantidad: ${totalQuantity}`, pageWidth / 2, tableStartY + 10, { align: 'center' });

                        grandTotal += totalQuantity; // Accumulate to grandTotal

                        startY = tableStartY + 20; // Update startY after the last chunk
                    }
                });

                // Add grand total at the end of the document
                doc.setFontSize(14);
                doc.text(`Total General: ${grandTotal}`, pageWidth / 2, startY + 5, { align: 'center' });

                // Imagen de logo
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 25;
                    const imgHeight = 20;
                    const xImg = (pageWidth - imgWidth) / 2;

                    doc.addImage(img, 'PNG', xImg, startY + 6, imgWidth, imgHeight);
                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;


            }).catch(error => {
                console.error('Error:', error);
            });
    }

    function generateOmesExcel(fechaDesde, fechaHasta, obraSocialId) {
        Promise.all([fetchOmesData(fechaDesde, fechaHasta, obraSocialId)])
            .then(([resumen]) => {
                // Crear un nuevo libro de trabajo
                const wb = XLSX.utils.book_new();

                // Crear una hoja con los datos completos
                const wsData = [
                    ['AFILIADO', 'BENEFICIO', 'PRACT.', 'FECHA', 'PROF', 'MODALIDAD'], // Headers
                    ...resumen.map(item => [
                        item.nombre,
                        `${item.benef}${item.parentesco}`,
                        item.turno_pract,
                        formatDate(item.fecha_turno),
                        item.prof,
                        item.modalidad_full || 'Sin Modalidad' // Manejar caso de modalidad null
                    ])
                ];

                const ws = XLSX.utils.aoa_to_sheet(wsData);
                XLSX.utils.book_append_sheet(wb, ws, 'Datos Completo');

                // Generar el archivo Excel
                XLSX.writeFile(wb, 'Control_de_Cargas_OME.xlsx');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
    //FIN OME


    //PACI SIN DIAG
    function generatePaciSinDiagPDF(fechaDesde, fechaHasta, obraSocialId) {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');

        function getMaxRowsPerPage(doc, headers, data) {
            const pageHeight = doc.internal.pageSize.height;
            const margins = { top: 30, bottom: 20 }; // Ajusta los márgenes si es necesario
            const rowHeight = 10; // Altura de cada fila
            const headerHeight = 10; // Altura de la fila del encabezado

            const availableHeight = pageHeight - margins.top - margins.bottom - headerHeight;
            const maxRows = Math.floor(availableHeight / rowHeight);

            return Math.min(maxRows, data.length);
        }

        // Llamada al backend para obtener los datos
        fetch(`./gets/get_pacientes_sin_diagnostico.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
            .then(response => response.json())
            .then(resumen => {
                if (resumen.error) {
                    console.error(resumen.error);
                    return;
                }

                const formattedFechaDesde = formatDate(fechaDesde);
                const formattedFechaHasta = formatDate(fechaHasta);

                const title = 'PACIENTES SIN DIAGNÓSTICO';
                const pageWidth = doc.internal.pageSize.getWidth();

                doc.setFontSize(16);
                doc.setFont('Helvetica', 'bold');
                doc.text(title, pageWidth / 2, 10, { align: 'center' });
                doc.setFont('Helvetica', 'normal');

                const dateRange = `DESDE: ${formattedFechaDesde} HASTA: ${formattedFechaHasta}`;
                doc.setFontSize(14);
                doc.text(dateRange, pageWidth / 2, 20, { align: 'center' });

                doc.setFontSize(12);
                let startY = 30;
                const margin = { left: 15 };

                const headers = ['NOMBRE', 'OBRA SOCIAL', 'ADMISIÓN', 'BENEFICIO', 'PARENTESCO'];
                const data = resumen.map(item => [
                    item.nombre,
                    item.obra_social,
                    formatDate(item.admision),
                    item.benef,
                    item.parentesco
                ]);

                // Crear la tabla en el PDF
                const tableWidth = pageWidth - margin.left * 2;

                if (data.length) {
                    let tableStartY = startY;
                    const maxRowsPerPage = getMaxRowsPerPage(doc, headers, data);

                    for (let i = 0; i < data.length; i += maxRowsPerPage) {
                        const chunk = data.slice(i, i + maxRowsPerPage);

                        doc.autoTable({
                            head: [headers],
                            body: chunk,
                            startY: tableStartY,
                            margin: margin,
                            theme: 'striped',
                            styles: {
                                fontSize: 10,
                                cellPadding: 2,
                                overflow: 'linebreak'
                            },
                            columnStyles: {
                                0: { cellWidth: 60 },
                                1: { cellWidth: 25 },
                                2: { cellWidth: 40 },
                                3: { cellWidth: 35 },
                                4: { cellWidth: 25 }
                            },
                            didDrawPage: function (data) {
                                tableStartY = data.cursor.y;
                            },
                            pageBreak: 'auto'
                        });

                        if (i + maxRowsPerPage < data.length) {
                            doc.addPage();
                            tableStartY = 30;
                        }
                    }

                    doc.setFontSize(12);
                    doc.text(`Total Pacientes Sin Diagnóstico: ${data.length}`, pageWidth / 2, tableStartY + 10, { align: 'center' });
                } else {
                    doc.setFontSize(12);
                    doc.text('No se encontraron pacientes sin diagnóstico en el rango de fechas especificado.', pageWidth / 2, startY, { align: 'center' });
                }

                // Agregar un logo si es necesario
                const imgUrl = '../img/logo.png';
                var img = new Image();
                img.onload = function () {
                    const imgWidth = 29;
                    const imgHeight = 25;
                    const xImg = (pageWidth - imgWidth) / 2;
                    const yImg = doc.internal.pageSize.height - imgHeight - 10;

                    doc.addImage(img, 'PNG', xImg, yImg, imgWidth, imgHeight);

                    window.open(doc.output('bloburl'));
                };
                img.src = imgUrl;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function generatePaciSinDiagExcel(fechaDesde, fechaHasta, obraSocialId) {
        // Llamada al backend para obtener los datos
        fetch(`./gets/get_pacientes_sin_diagnostico.php?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}&obra_social=${obraSocialId}`)
            .then(response => response.json())
            .then(resumen => {
                if (resumen.error) {
                    console.error(resumen.error);
                    return;
                }

                // Crear una hoja de cálculo con los datos obtenidos
                const headers = ['NOMBRE', 'OBRA SOCIAL', 'ADMISIÓN', 'BENEFICIO', 'PARENTESCO'];
                const data = resumen.map(item => [
                    item.nombre,
                    item.obra_social,
                    formatDate(item.admision),
                    item.benef,
                    item.parentesco
                ]);

                // Insertar encabezados y datos
                const worksheetData = [headers, ...data];

                // Crear el libro de trabajo
                const wb = XLSX.utils.book_new();
                const ws = XLSX.utils.aoa_to_sheet(worksheetData);
                XLSX.utils.book_append_sheet(wb, ws, 'Pacientes Sin Diagnóstico');

                // Crear un archivo Excel
                const currentDate = new Date().toISOString().split('T')[0];
                const fileName = `Pacientes_Sin_Diagnostico_${currentDate}.xlsx`;

                // Descargar el archivo
                XLSX.writeFile(wb, fileName);
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }



    //FIN PACI SIN DIAG


});

//barra busqueda
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const cards = document.querySelectorAll('.card');

    searchInput.addEventListener('input', function () {
        const searchValue = searchInput.value.toLowerCase();

        cards.forEach(card => {
            const title = card.querySelector('h3').textContent.toLowerCase();
            if (title.includes(searchValue)) {
                card.parentElement.style.display = 'block';
            } else {
                card.parentElement.style.display = 'none';
            }
        });
    });
});

//carga de obras sociales
$(document).ready(function () {
    $.ajax({
        url: '../pacientes/dato/get_obras_sociales.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            data.forEach(function (item) {
                var optionText = item.siglas + ' - ' + item.razon_social;
                $('#obra_social').append(new Option(optionText, item.id));
                $('#obra_social_paciente').append(new Option(optionText, item.id));
                $('#obra_social_ome').append(new Option(optionText, item.id));
                $('#obra_social_prof').append(new Option(optionText, item.id));
                $('#obra_social_prac').append(new Option(optionText, item.id));
                $('#obra_social_egreso').append(new Option(optionText, item.id));
                $('#obra_social_ingreso').append(new Option(optionText, item.id));
                $('#obra_social_diag').append(new Option(optionText, item.id));
                $('#obra_social_plan').append(new Option(optionText, item.id));
                $('#obra_social_paci_unicos').append(new Option(optionText, item.id));
                $('#obra_social_paci_boca').append(new Option(optionText, item.id));
                $('#obra_social_paci_prestaciones').append(new Option(optionText, item.id));
                $('#obra_social_paci_sin_diag').append(new Option(optionText, item.id));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });

    $.ajax({
        url: '../pacientes/dato/get_profesional.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            // Agregar solo las opciones de profesionales
            data.forEach(function (item) {
                var optionText = item.nombreYapellido;
                $('#profesional').append(new Option(optionText, item.id_prof));
            });
        },
        error: function (error) {
            console.error("Error fetching data: ", error);
        }
    });


});

