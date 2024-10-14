<?php
// Inicia la sesión
require_once "../conexion.php";

session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
  // El usuario ha iniciado sesión, puedes mostrar contenido para usuarios autenticados o ejecutar acciones específicas
} else {
  header("Location: ../index.php");
}

// Lógica para cerrar sesión
if (isset($_GET['cerrar_sesion'])) {
  // Destruye todas las variables de sesión
  session_destroy();
  // Redirige al usuario a la página de inicio o a donde desees
  header("Location: ../index.php");
  exit;
}

// Obtener todos los pacientes
$sql = "SELECT p.*
        FROM paciente p
        ORDER BY p.nombre ASC";
$result = $conn->query($sql);

// Consultar el valor de 'inst'
$sqlTitle = "SELECT inst FROM parametro_sistema LIMIT 1";
$resultTitle = $conn->query($sqlTitle);

// Obtener el valor
$title = "Iniciar sesión"; // Valor por defecto
if ($resultTitle && $resultTitle->num_rows > 0) {
  $row = $resultTitle->fetch_assoc();
  $title = $row['inst'];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Estadisticas|<?php echo htmlspecialchars($title); ?></title>
  <!--icono pestana-->
  <link rel="icon" href="../img/logo.png" type="image/x-icon">
  <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <!-- Font Awesome para los iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../estilos/styleGeneral.css">
  <link rel="stylesheet" href="../estilos/styleBotones.css">

  <!--REPORTE-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

  <!--EXCEL -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <script src="script.js"></script>
  <style>
    body {
      overflow-x: hidden !important;
    }
  </style>
</head>

<body>
  <!-- Just an image -->
  <nav class="navbar bg-body-tertiary">
    <div class="container d-flex justify-content-center">
      <a class="navbar-brand" href="#">
        <img src="../img/logoBlanco.png" height="160rem" alt="Medical Logo" loading="lazy" />
      </a>
    </div>
  </nav>

  <button class="button" style="vertical-align:middle; margin-left:7rem"
    onclick="window.location.href = '../inicio/home.php';">
    <span>VOLVER</span>
  </button>

  <div class="row mb-4 justify-content-center">
    <div class="col-md-6">
      <div class="input-group">
        <input type="text" class="form-control" id="searchInput" placeholder="Buscar por título...">
      </div>
    </div>
  </div>

  <!-- Cards Container -->
  <div class="container my-5">
    <div class="row row-cols-1 row-cols-md-3 g-3 justify-content-center">
      <!-- Reducir el valor de g a 2 para reducir la distancia entre las tarjetas -->
      <!--PRIMERA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="#" id="openOmesModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/ome.png" alt="Icono Crear Profesional">
            </div>
            <div class="third-content">
              <h3 class="mt-3">OME</h3>
            </div>
          </div>
        </a>

      </div>
      <!--CARD -->

      <!--PRIMERA CARD -->
      <div class="col d-flex justify-content-center">
        <a href="#" id="openOrdenModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/modalidad.png" alt="Ordenes de Prestacion">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Vencimiento de Ordenes de Prestacion</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/modalidad.png" alt="Resumen de Estadisticas">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Prestaciones por Modalidad</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openPatientModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/reporte_resum_estadisticas.png" alt="Resumen de Estadisticas">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Resumen de estadisticas</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openProfModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/prof.png" alt="Prestaciones por profesional">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Prestaciones por profesional</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openPracModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/prestaciones.png" alt="Prestaciones por profesional">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Estadisticas por prestaciones</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openEgresoModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/egresos.png" alt="Egresos">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Egresos</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openIngresoModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/ingreso.png" alt="Ingresos">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Ingresos</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openDiagModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/diag.png" alt="Ingresos">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Diagnostico por paciente</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openPlanModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/plan.png" alt="Ingresos">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Plan de medicacion</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openPaciUnicosModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/plan.png" alt="Ingresos">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Pacientes unicos</h3>
            </div>
          </div>
        </a>
      </div>

      <div class="col d-flex justify-content-center">
        <a href="#" id="openPacientesBocaModalLink">
          <div class="card h-100">
            <div class="first-content">
              <img src="../img/reportes/plan.png" alt="Ingresos">
            </div>
            <div class="third-content">
              <h3 class="mt-3">Pacientes por Boca de Atecion</h3>
            </div>
          </div>
        </a>
      </div>



    </div>
  </div>
  <!-- FIN Cards Container -->

  <!--PACI BOCA FIN-->
  <div class="modal fade" id="openPacientesBocaModal" tabindex="-1" aria-labelledby="pacientesBocaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pacientesBocaModalLabel">Pacientes Unicos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="pacientesBocaForm">
            <div class="mb-3">
              <label for="fechaDesdePacientesBoca" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdePacientesBoca" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaPacientesBoca" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaPacientesBoca" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_paci_boca">Obra Social:*</label>
              <select class="form-control" id="obra_social_paci_boca" name="obra_social_paci_boca" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generatePacientesBocaBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generatePacientesBocaExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--PACI BOCA FIN-->


  <!--PACI UNICOS-->
  <div class="modal fade" id="openPaciUnicosModal" tabindex="-1" aria-labelledby="paciUnicosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="paciUnicosModalLabel">Pacientes Unicos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="paciUnicosForm">
            <div class="mb-3">
              <label for="fechaDesdePaciUnicos" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdePaciUnicos" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaPaciUnicos" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaPaciUnicos" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_paci_unicos">Obra Social:*</label>
              <select class="form-control" id="obra_social_paci_unicos" name="obra_social_paci_unicos" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generatePaciUnicosBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generatePaciUnicosExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--PACI UNICOS FIN-->

  <!--OP-->
  <div class="modal fade" id="openOrdenModal" tabindex="-1" aria-labelledby="opModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="opModalLabel">Vencimiento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="planForm">
            <div class="mb-3">
              <label for="fechaDesdeOp" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdeOp" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaOp" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaOp" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generateOpBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generateOpExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!-- FIN-->



  <!--PLAN MEDICACION-->
  <div class="modal fade" id="openPlanModal" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ingplanModalLabel">Plan de medicacion</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="planForm">
            <div class="mb-3">
              <label for="fechaDesdePlan" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdePlan" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaPlan" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaPlan" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_plan">Obra Social:*</label>
              <select class="form-control" id="obra_social_plan" name="obra_social_plan" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generatePlanBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generatePlanExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--PLAN MEDICACION FIN-->

  <!--DIAG-->
  <div class="modal fade" id="openDiagModal" tabindex="-1" aria-labelledby="diagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="diagModalLabel">Diagnostico</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="diagForm">
            <div class="mb-3">
              <label for="fechaDesdeDiag" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdeDiag" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaDiag" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaDiag" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_diag">Obra Social:*</label>
              <select class="form-control" id="obra_social_diag" name="obra_social_diag" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generateDiagBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generateDiagExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--DIAG FIN-->

  <!--INGRESO-->
  <div class="modal fade" id="openIngresoModal" tabindex="-1" aria-labelledby="ingresoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ingresoModalLabel">Ingreso</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="ingresoForm">
            <div class="mb-3">
              <label for="fechaDesdeIngreso" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdeIngreso" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaIngreso" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaIngreso" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_ingreso">Obra Social:*</label>
              <select class="form-control" id="obra_social_ingreso" name="obra_social_ingreso" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generateIngresoBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generateIngresoExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--INGRESO FIN-->

  <!--EGRESOS-->
  <div class="modal fade" id="openEgresoModal" tabindex="-1" aria-labelledby="egresoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="egresoModalLabel">Egresos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="egresoForm">
            <div class="mb-3">
              <label for="fechaDesdeEgreso" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdeEgreso" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaEgreso" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaEgreso" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_egreso">Obra Social:*</label>
              <select class="form-control" id="obra_social_egreso" name="obra_social_egreso" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generateEgresoBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generateEgresoExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--EGRESOS FIN-->

  <!--OMES-->
  <div class="modal fade" id="openOmesModal" tabindex="-1" aria-labelledby="OmesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="OmesModalLabel">OMES</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="OmesForm">
            <div class="mb-3">
              <label for="fechaDesdeOmes" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdeOmes" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaOmes" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaOmes" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_ome">Obra Social:*</label>
              <select class="form-control" id="obra_social_ome" name="obra_social_ome" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generateOmesBtn">Generar PDF</button>
          <button type="button" class="btn btn-success" id="generateOmesExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--OMES FIN-->

  <!--PRACTICAS-->
  <div class="modal fade" id="openPracModal" tabindex="-1" aria-labelledby="pracModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pracModalLabel">Estadisticas por prestaciones</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="pracForm">
            <div class="mb-3">
              <label for="fechaDesdePrac" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdePrac" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaPrac" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaPrac" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_prac">Obra Social:*</label>
              <select class="form-control" id="obra_social_prac" name="obra_social_prac" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generatePracBtn">Generar Reporte</button>
          <button type="button" class="btn btn-success" id="generatePracExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!--PRACTICAS FIN -->



  <!-- Resumen de Estadisticas MODALIDAD-->
  <div class="modal fade" id="resumenModal" tabindex="-1" aria-labelledby="resumenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="resumenModalLabel">Prestaciones por Modalidad</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="resumenForm">
            <div class="mb-3">
              <label for="fechaDesde" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesde" required>
            </div>
            <div class="mb-3">
              <label for="fechaHasta" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHasta" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social">Obra Social:*</label>
              <select class="form-control" id="obra_social" name="obra_social" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generatePdfBtn">Generar Reporte</button>
          <button type="button" class="btn btn-success" id="generateExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!-- FIN Resumen de Estadisticas MODALIDAD -->

  <!-- Resumen de Estadisticas por paciente MODAL-->
  <div class="modal fade" id="patientResumenModal" tabindex="-1" aria-labelledby="patientResumenModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="patientResumenModalLabel">Resumen de Estadisticas por Paciente Atendidos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="patientResumenForm">
            <div class="mb-3">
              <label for="fechaDesdePaciente" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdePaciente" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaPaciente" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaPaciente" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_paciente">Obra Social:*</label>
              <select class="form-control" id="obra_social_paciente" name="obra_social" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generatePatientPdfBtn">Generar Reporte</button>
          <button type="button" class="btn btn-success" id="generatePatientExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!-- FIN Resumen de Estadisticas por paciente MODAL -->

  <!-- Prestaciones por profesional -->
  <div class="modal fade" id="openProfModal" tabindex="-1" aria-labelledby="profModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="profModalLabel">Prestaciones por profesional</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="profForm">
            <div class="mb-3">
              <label for="fechaDesdeProf" class="form-label">Fecha Desde</label>
              <input type="date" class="form-control" id="fechaDesdeProf" required>
            </div>
            <div class="mb-3">
              <label for="fechaHastaProf" class="form-label">Fecha Hasta</label>
              <input type="date" class="form-control" id="fechaHastaProf" required>
            </div>
            <div class="col-md-4 form-group">
              <label for="obra_social_prof">Obra Social:*</label>
              <select class="form-control" id="obra_social_prof" name="obra_social_prof" required>
                <option value="">Seleccionar...</option>
              </select>
            </div>
            <div class="col-md-4 form-group">
              <label for="profesional">Profesional:*</label>
              <select class="form-control" id="profesional" name="profesional" required>
                <option value="">Todos</option>
                <!-- Las opciones dinámicas se agregarán aquí -->>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-primary" id="generateProfBtn">Generar Reporte</button>
          <button type="button" class="btn btn-success" id="generateProfExcelBtn">Generar Excel</button>
        </div>
      </div>
    </div>
  </div>
  <!-- FIN Prestaciones por profesional -->

  <!-- Pie de página -->
  <footer class="bg-dark text-white text-center py-4 mt-auto">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 footer-logo-text">
          <img src="../img/logoWSS.png" alt="Logo" class="img-fluid" style="max-height: 50px;">
          <p class="mb-0">&copy; 2024 WorldsoftSystems. Todos los derechos reservados.</p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS (opcional) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>