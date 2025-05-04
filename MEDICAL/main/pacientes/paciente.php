<?php
require_once "../componentes/auth.php";
require_once "../componentes/init.php";

// Verificar si se ha enviado el parámetro "eliminar"
if (isset($_GET['eliminar'])) {
    // Recibir el ID del paciente a eliminar
    $id = $_GET['eliminar'];

    // Preparar la consulta SQL para eliminar el paciente
    $sql = "DELETE FROM paciente WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("i", $id);

    try {
        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Redirigir a la página después de eliminar
            header("Location: ./paciente.php");
            exit();
        } else {
            throw new Exception($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        // Verificar si el error es de restricción de clave externa
        if ($e->getCode() == 1451) {
            // Error de restricción de clave externa
            echo "<script>
                    alert('Error al eliminar, el paciente está relacionado con otra tabla');
                    window.location.href = './paciente.php';
                  </script>";
        } else {
            // Otro error
            echo "Error al eliminar el paciente: " . $e->getMessage();
        }
    }

    // Cerrar la sentencia
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes | <?php echo htmlspecialchars($title); ?></title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">

    <?php include "../componentes/head-resources.php"; ?>

    <!-- Scripts adicionales de la vista paciente.php -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

    <!--<script src="./script/scriptPaciente.js" defer></script>-->
    <!-- Cargar solo el JS modularizado -->
    <script type="module" src="./script/pacientes/init.js"></script>

    <?php include './modals/practicas/crear-modal-practica.php'; ?>

    <script type="module" src="../componentes/formatDate.js" defer></script>

    <script type="module" src="./modals/practicas/practicas.js"></script>
</head>

<body>
    <!-- NAVBAR -->
    <?php
    include '../componentes/navbar.php';
    ?>
    <!-- FIN NAVBAR -->

    <!-- TOOL BAR -->
    <div class="container-fluid my-4">
        <h2 class="m-0 text-truncate" style="max-width: 600px;">
            Pacientes | <?= htmlspecialchars($title) ?>
        </h2>
        <div class="d-flex align-items-center justify-content-between position-relative mb-3">

            <div class="d-flex align-items-center flex-nowrap gap-3">
                <?php
                $href = '../inicio/home.php';
                $icon = 'fas fa-arrow-left';
                include '../componentes/boton-volver.php';
                ?>
            </div>
            <!-- Bloque central (BUSCADOR + BOTONES) -->
            <div class="d-flex align-items-center flex-nowrap gap-2 position-absolute start-50 translate-middle-x">
                <input type="text" id="searchInput" class="form-control form-control-lg rounded-pill"
                    placeholder="Buscar por nombre o beneficio" style="max-width: 360px; min-width: 300px;">

                <button type="button" id="searchButton" class="button">
                    <i class="fas fa-search"></i>
                </button>
                <button type="button" id="reloadButton" class="btn btn-outline-secondary">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <!-- Bloque derecho (AGREGAR) -->
            <button type="button" class="button btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal"
                data-bs-target="#agregarPacienteModal">
                <span>Agregar</span>
                <img src="../img/home/pacientes.png" alt="Icono agregar paciente" style="width: 24px; height: 24px;">
            </button>

        </div>
    </div>
    <!-- FIN TOOL BAR -->

    <!-- TABLA PACIENTE -->
    <div class="container-fluid my-4 px-4">
        <div id="pacientesTable"></div> <!-- 👈 Aquí se inyecta todo dinámicamente -->
    </div>
    <!-- FIN TABLA PACIENTE -->


    <!-- tools-modal -->
    <div class="modal fade" id="agregarPacienteModal" tabindex="-1" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog modal-xl custom-modal-paciente">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Icons for different sections -->
                    <div class="d-flex flex-wrap align-items-center">
                        <?php require_once 'tools-modal.php'; ?>

                        <div class="d-flex flex-wrap align-items-center">
                            <?php foreach ($botonesModal as $btn): ?>
                                <a href="#" class="btn btn-link text-decoration-none fw-semibold"
                                    title="<?= htmlspecialchars($btn['title']) ?>" data-bs-toggle="modal"
                                    data-bs-target="<?= htmlspecialchars($btn['target']) ?>"
                                    onclick="<?= htmlspecialchars($btn['onclick']) ?>">

                                    <i class="<?= htmlspecialchars($btn['icon']) ?> custom-icon"></i>
                                    <span class="icon-text"><?= htmlspecialchars($btn['texto']) ?></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        
                    </div>
                    <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close"
                        style="color: #dc3545; font-size: 1.25rem; border: none; background: transparent;">
                        <i class="fas fa-times"></i>
                    </button>

                </div>
                <div class="modal-body">
                    <div id="bajaMensaje"></div>
                    <form id="formPaciente" method="POST" data-mode="add">
                        <input type="hidden" id="id" name="id">
                        <div class="container" style="max-height: 65vh; overflow-y: auto; padding-right: 1rem;">
                            <?php include './form-secciones/beneficiario.php'; ?>
                            <?php include './form-secciones/personal.php'; ?>
                            <?php include './form-secciones/afiliado.php'; ?>
                            <?php include './form-secciones/admision.php'; ?>
                            <?php include './form-secciones/contacto.php'; ?>
                        </div>
                        <?php include './form-secciones/footer.php'; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN tools-modal -->

    <!-- Modal para generar el reporte -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Generar Reporte De Turnos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fechaDesde_paci_turno" class="form-label">Fecha Desde</label>
                        <input type="date" class="form-control" id="fechaDesde_paci_turno">
                    </div>
                    <div class="mb-3">
                        <label for="fechaHasta_paci_turno" class="form-label">Fecha Hasta</label>
                        <input type="date" class="form-control" id="fechaHasta_paci_turno">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="button-form-cancel" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="button-form" id="btnGenerarPDF">Generar PDF</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN Modal para generar el reporte -->


    <!-- Modal de MODALIDADES -->
    <div class="modal fade" id="modaModal" tabindex="-1" aria-labelledby="modaModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modaModalLabel">Modalidades</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modaModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div id="listaModa" class="scrollable-content">
                            <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="nuevaModali">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarModaliModal" tabindex="-1" aria-labelledby="agregarModaliModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarModaliModalLabel">Agregar Modalidad</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarModali" class="row g-3">
                        <input type="hidden" id="modaliIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="modaliId">

                        <div class="col-md-4">
                            <label for="modaliNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="modaliNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="modaliFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="modaliFecha" name="fecha" required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="modalidad_paci">Modalidad:*</label>
                            <select class="form-control" id="modalidad_paci" name="modalidad_paci" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarModali">Guardar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- FIN MODALIDADES -->

    <!-- Modal de Egreso -->
    <div class="modal fade" id="egresoModal" tabindex="-1" aria-labelledby="egresoModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="egresoModalLabel">Egresos</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="egresoModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div id="listaEgresos" class="scrollable-content">
                            <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="nuevoEgreso">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarEgresoModal" tabindex="-1" aria-labelledby="agregarEgresoModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarEgresoModalLabel">Agregar Egreso</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarEgreso" class="row g-3">
                        <input type="hidden" id="egresoIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="egresoId">

                        <div class="col-md-4">
                            <label for="egresoNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="egresoNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="egresoFecha" class="form-label">Fecha Egreso</label>
                            <input type="date" class="form-control" id="egresoFecha" name="fecha">
                        </div>

                        <div class="col-md-2">
                            <label for="egresoHora" class="form-label">Hora Egreso</label>
                            <input type="time" class="form-control" id="egresoHora" name="hora_egreso">
                        </div>

                        <div class="col-md-6">
                            <label for="egreso_diag" class="form-label">Diagnóstico:*</label>
                            <select class="form-control" id="egreso_diag" name="egreso_diag" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="egreso_modalidad" class="form-label">Modalidad:*</label>
                            <select class="form-control" id="egreso_modalidad" name="egreso_modalidad" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="egreso_motivo" class="form-label">Motivo:</label>
                            <select class="form-control" id="egreso_motivo" name="egreso_motivo">
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarEgreso">Guardar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- FIN EGRESO -->

    <!-- Modal de EVOLUCIONES -->
    <div class="modal fade" id="evoModal" tabindex="-1" aria-labelledby="evoModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="evoModalLabel">Evoluciones Historia Clinica</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="evoModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaEvo" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaEvo">Agregar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarEvoModal" tabindex="-1" aria-labelledby="agregarEvoModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarEvoModalLabel">Agregar Evolucion</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarEvolucion" class="row g-3">
                        <input type="hidden" id="evoIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="evoId">

                        <div class="col-md-4">
                            <label for="evoNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="evoNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="evoProf">Profesional:*</label>
                            <select class="form-control" id="evoProf" name="evoProf" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="evoDiag">Diagnostico:</label>
                            <input type="text" class="form-control" id="evoDiag" name="evoDiag" readonly>
                        </div>


                        <div class="col-12">
                            <label for="frecuencia">Evolucion Historia Clinica:</label>
                            <textarea class="form-control" id="frecuencia" name="frecuencia" rows="5"
                                style="resize: vertical;"></textarea>
                        </div>


                        <div class="col-md-4">
                            <label for="evoFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="evoFecha" name="evoFecha" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarEvo">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- FIN EVOLUCIONES-->

    <!-- Modal de EVOLUCIONES INTERNACION-->
    <div class="modal fade" id="evoIntModal" tabindex="-1" aria-labelledby="evoIntModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="IntModalLabel">Evoluciones Int.</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="evoIntModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaEvoInt" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaEvoInt">Agregar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarEvoIntModal" tabindex="-1" aria-labelledby="agregarEvoIntModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarEvoIntModalLabel">Agregar Evolucion Int.</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarEvolucionInt" class="row g-3">
                        <input type="hidden" id="evoIntIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="evoIntId">

                        <div class="col-md-4">
                            <label for="evoIntNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="evoIntNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="antecedentes_int">Antecedentes:</label>
                            <input type="text" class="form-control" id="antecedentes_int" name="antecedentes_int">
                        </div>

                        <div class="col-md-4">
                            <label for="motivo_evo_int">Motivo de consulta:</label>
                            <input type="text" class="form-control" id="motivo_evo_int" name="motivo_evo_int">
                        </div>


                        <div class="col-md-4">
                            <label for="estado_actual_int">Estado Actual:</label>
                            <input type="text" class="form-control" id="estado_actual_int" name="estado_actual_int">
                        </div>

                        <div class="col-md-4">
                            <label for="familia_int">Familia:</label>
                            <input type="text" class="form-control" id="familia_int" name="familia_int">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="evo_diag_int">Diagnostico:*</label>
                            <select class="form-control" id="evo_diag_int" name="evo_diag_int" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="objetivo_int">Objetivo del tratamiento:</label>
                            <input type="text" class="form-control" id="objetivo_int" name="objetivo_int">
                        </div>

                        <div class="col-md-4">
                            <label for="duracion_int">Duracion estimada del tratamiento:</label>
                            <input type="text" class="form-control" id="duracion_int" name="duracion_int">
                        </div>

                        <div class="col-md-4">
                            <label for="frecuencia_int">Frecuencia de entrevista:</label>
                            <textarea class="form-control" id="frecuencia_int" name="frecuencia_int" rows="5"
                                style="resize: vertical;"></textarea>
                        </div>

                        <div class="col-md-4">
                            <label for="evoFecha_int" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="evoFecha_int" name="evoFecha_int" required>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarEvoInt">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- FIN EVOLUCIONES INTERNACION-->

    <!--ADMISION AMBULATORIO -->
    <div class="modal fade" id="admiAmbModal" tabindex="-1" aria-labelledby="admiAmbModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AmbModalLabel">Admision</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="admiAmbModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaAdmiAmb" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaAdmiAmb">Agregar</button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarAdmiAmbModal" tabindex="-1" aria-labelledby="agregarAdmiAmbModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarAdmiAmbModalLabel">Agregar Admision Ambulatorio</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarAdmiAmb" class="row g-3">
                        <input type="hidden" id="admiAmbIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="admiAmbId">

                        <div class="col-md-4">
                            <label for="admiAmbNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="admiAmbNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>PROFESIONAL:</h5>
                                <div class="col-md-4 form-group">
                                    <label for="hc_prof">Profesional:*</label>
                                    <select class="form-control" id="hc_prof" name="hc_prof" required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                </div>

                                <h5>1-Aspecto Psíquico</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="normal" value="Normal"
                                        name="aspectoPsiquico">
                                    <label class="form-check-label" for="normal">Normal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="indiferente" value="Indiferente"
                                        name="aspectoPsiquico">
                                    <label class="form-check-label" for="indiferente">Indiferente</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="excitado" value="Excitado"
                                        name="aspectoPsiquico">
                                    <label class="form-check-label" for="excitado">Excitado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="obnubilado" value="Obnubilado"
                                        name="aspectoPsiquico">
                                    <label class="form-check-label" for="obnubilado">Obnubilado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="deprimido" value="Deprimido"
                                        name="aspectoPsiquico">
                                    <label class="form-check-label" for="deprimido">Deprimido</label>
                                </div>


                                <h5>2-Actitud Psíquica</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="activa" value="Activa"
                                        name="act_psiquica">
                                    <label class="form-check-label" for="activa">Activa</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pasiva" value="Pasiva"
                                        name="act_psiquica">
                                    <label class="form-check-label" for="pasiva">Pasiva</label>
                                </div>


                                <h5>3-Actividad</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="actividad-normal" value="Normal"
                                        name="act">
                                    <label class="form-check-label" for="actividad-normal">Normal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="abulia" value="Abulia" name="act">
                                    <label class="form-check-label" for="abulia">Abulia</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="hiperbulia" value="Hiperbulia"
                                        name="act">
                                    <label class="form-check-label" for="hiperbulia">Hiperbulia</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="hipobulia" value="Hipobulia"
                                        name="act">
                                    <label class="form-check-label" for="hipobulia">Hipobulia</label>
                                </div>

                                <h5>4-Orientación</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="global" value="Global"
                                        name="orientacion">
                                    <label class="form-check-label" for="global">Global</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="desorientacion-autosiquica"
                                        value="Desorientación Autosíquica" name="orientacion">
                                    <label class="form-check-label" for="desorientacion-autosiquica">Desorientación
                                        Autosíquica</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="desorientacion-tiempo"
                                        value="Desorientación en el Tiempo" name="orientacion">
                                    <label class="form-check-label" for="desorientacion-tiempo">Desorientación en el
                                        Tiempo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="desorientacion-lugar"
                                        value="Desorientación en el Lugar" name="orientacion">
                                    <label class="form-check-label" for="desorientacion-lugar">Desorientación en el
                                        Lugar</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="desorientacion-global"
                                        value="Desorientación Global" name="orientacion">
                                    <label class="form-check-label" for="desorientacion-global">Desorientación
                                        Global</label>
                                </div>

                                <h5>5-Conciencia</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="lucida" value="Lúcida"
                                        name="conciencia">
                                    <label class="form-check-label" for="lucida">Lúcida</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="sin-conciencia-enfermedad"
                                        value="Sin Conciencia de Enfermedad ni de Situación" name="conciencia">
                                    <label class="form-check-label" for="sin-conciencia-enfermedad">Sin Conciencia
                                        de Enfermedad ni de Situación</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="sin-conciencia-enfermedad2"
                                        value="Sin Conciencia de Enfermedad" name="conciencia">
                                    <label class="form-check-label" for="sin-conciencia-enfermedad2">Sin Conciencia
                                        de Enfermedad</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="sin-conciencia-situacion"
                                        value="Sin Conciencia de Situación" name="conciencia">
                                    <label class="form-check-label" for="sin-conciencia-situacion">Sin Conciencia de
                                        Situación</label>
                                </div>

                                <h5>6-Memoria</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="memoria-normal" value="Normal"
                                        name="memoria">
                                    <label class="form-check-label" for="memoria-normal">Normal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="fallas-anterogradas"
                                        value="Fallas Anterógradas" name="memoria">
                                    <label class="form-check-label" for="fallas-anterogradas">Fallas
                                        Anterógradas</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="fallas-globales"
                                        value="Fallas Globales" name="memoria">
                                    <label class="form-check-label" for="fallas-globales">Fallas Globales</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="amnesia-lacunar"
                                        value="Fallas Globales" name="memoria">
                                    <label class="form-check-label" for="fallas-globales">Amnesia lacunar</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="amnesia" value="Fallas Retrógradas"
                                        name="memoria">
                                    <label class="form-check-label" for="fallas-retrogradas">Amnesia</label>
                                </div>

                                <h5>7-Atención</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="atencion-normal" value="Normal"
                                        name="atencion">
                                    <label class="form-check-label" for="atencion-normal">Normal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="hiperprosexia"
                                        value="hiperprosexia" name="atencion">
                                    <label class="form-check-label" for="hiperprosexia">Hiperprosexia</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="paraprosexia" value="paraprosexia"
                                        name="atencion">
                                    <label class="form-check-label" for="paraprosexia">Paraprosexia</label>
                                </div>

                                <h5>8-Curso del pensamiento</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pensamiento-normal" value="Normal"
                                        name="pensamiento" required>
                                    <label class="form-check-label" for="pensamiento-normal">Normal</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pensamiento-acelerado"
                                        value="acelerado" name="pensamiento">
                                    <label class="form-check-label" for="pensamiento-acelerado">Acelerado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pensamiento-interceptado"
                                        value="interceptado" name="pensamiento" required>
                                    <label class="form-check-label" for="pensamiento-interceptado">Interceptado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pensamiento-retardado"
                                        value="retardado" name="pensamiento" required>
                                    <label class="form-check-label" for="pensamiento-retardado">Retardado</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="pensamiento-disgregado"
                                        value="disgregado" name="pensamiento">
                                    <label class="form-check-label" for="pensamiento-disgregado">Disgregado</label>
                                </div>

                                <label for="hc_diag">Diagnostico:*</label>
                                <select class="form-control" id="hc_diag" name="hc_diag" required>
                                    <option value="">Seleccionar...</option>
                                </select>
                                <hr>
                                <div class="col-md-6">
                                    <label for="hc_medi">Que medicación está tomando el paciente:</label>
                                    <input type="text" id="searchMedicacionHc" placeholder="Buscar..."
                                        class="form-control mb-2">
                                    <select class="form-control" id="hc_medi" name="hc_medi" required>
                                        <option value="">Seleccionar...</option>
                                    </select>
                                </div>
                                <button id="prevPageHc" class="btn btn-secondary">Página Anterior</button>
                                <button id="nextPageHc" class="btn btn-secondary">Página Siguiente</button>
                                <hr>


                                <div class="col-md-6">
                                    <label for="hc_desc_medi">Para que lo toma:</label>
                                    <input type="text" class="form-control" id="hc_desc_medi" name="hc_desc_medi"
                                        placeholder="Completar">
                                </div>

                                <div class="col-md-6">
                                    <label for="hc_cada_medi">Cada cuanto lo toma:</label>
                                    <input type="text" class="form-control" id="hc_cada_medi" name="hc_cada_medi"
                                        placeholder="Completar">
                                </div>

                                <div class="col-md-6">
                                    <label for="admi_familiar">Antecedentes Familiares:</label>
                                    <input type="text" class="form-control" id="admi_familiar" name="admi_familiar"
                                        placeholder="Completar">
                                </div>

                                <div class="col-md-4">
                                    <label for="hc_fecha" class="form-label">Fecha:</label>
                                    <input type="date" class="form-control" id="hc_fecha" name="hc_fecha" required>
                                </div>


                            </div>
                            <div class="col-md-6">
                                <h5>9-Contenido del pensamiento</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="contenido-coherente"
                                        value="coherente" name="cont_pensamiento">
                                    <label class="form-check-label" for="contenido-coherente">Coherente</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="contenido-incoherente"
                                        value="incoherente" name="cont_pensamiento">
                                    <label class="form-check-label" for="contenido-incoherente">Incoherente</label>
                                </div>

                                <div class="form-check">
                                    <input type="radio" id="contenid-delirante" name="cont_pensamiento"
                                        value="Delirante">
                                    <label for="contenid-delirante"> Delirante</label><br>
                                </div>

                                <div class="form-check">
                                    <input type="radio" id="contenido-autoeliminacion" name="cont_pensamiento"
                                        value="Ideas de autoeliminación">
                                    <label for="contenido-autoeliminacion"> Ideas de autoeliminación</label><br>
                                </div>


                                <h5>10-Sensopercepción</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="senso-alteraciones"
                                        name="sensopercepcion" value="Sin alteraciones">
                                    <label class="form-check-label" for="senso-alteraciones">Sin
                                        alteraciones</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="senso-ilusiones"
                                        name="sensopercepcion" value="Ilusiones">
                                    <label class="form-check-label" for="senso-ilusiones">Ilusiones</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="senso-alucinaciones"
                                        value="Alucinaciones auditivas/visuales Cenestésicas" name="sensopercepcion">
                                    <label class="form-check-label" for="senso-alucinaciones">Alucinaciones
                                        auditivas/visuales
                                        Cenestésicas</label>
                                </div>

                                <h5>11-Afectividad</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="afectividad-sin-alteracion"
                                        name="afectividad" value="Sin alteración">
                                    <label class="form-check-label" for="afectividad-sin-alteracion">Sin
                                        alteración</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="afectividad-hipertimia-placentera"
                                        name="afectividad" value="Hipertimia placentera">
                                    <label class="form-check-label" for="afectividad-hipertimia-placentera">Hipertimia
                                        placentera</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio"
                                        id="afectividad-hipertimia-displacentera" name="afectividad"
                                        value="Hipertimia displacentera">
                                    <label class="form-check-label"
                                        for="afectividad-hipertimia-displacentera">Hipertimia displacentera</label>
                                </div>

                                <h5>12-Inteligencia</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="inteligencia-normal"
                                        name="inteligencia" value="Normal">
                                    <label class="form-check-label" for="inteligencia-normal">Normal</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="inteligencia-superior"
                                        name="inteligencia" value="Superior">
                                    <label class="form-check-label" for="inteligencia-superior">Superior</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="inteligencia-inferior"
                                        name="inteligencia" value="Inferior">
                                    <label class="form-check-label" for="inteligencia-inferior">Inferior</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="inteligencia-marcada-deficiencia"
                                        name="inteligencia" value="Marcada deficiencia">
                                    <label class="form-check-label" for="inteligencia-marcada-deficiencia">Marcada
                                        deficiencia</label>
                                </div>

                                <h5>13-Juicio</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="juicio-normal" name="juicio"
                                        value="Normal">
                                    <label class="form-check-label" for="juicio-normal">Normal</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="juicio-insuficiencia" name="juicio"
                                        value="Insuficiencia">
                                    <label class="form-check-label" for="juicio-insuficiencia">Insuficiencia</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="juicio-debilitado" name="juicio"
                                        value="Debilitado">
                                    <label class="form-check-label" for="juicio-debilitado">Debilitado</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="juicio-suspendido" name="juicio"
                                        value="Suspendido">
                                    <label class="form-check-label" for="juicio-suspendido">Suspendido</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="juicio-desviado" name="juicio"
                                        value="Desviado">
                                    <label class="form-check-label" for="juicio-desviado">Desviado</label>
                                </div>

                                <h5>14-Control de esfínteres</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="control-esfinteres-normal"
                                        name="esfinteres" value="Normal">
                                    <label class="form-check-label" for="control-esfinteres-normal">Normal</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="control-esfinteres-incontinencia"
                                        name="esfinteres" value="Incontinencia Vesical/Rectal/Vésico-rectal">
                                    <label class="form-check-label" for="control-esfinteres-incontinencia">Incontinencia
                                        Vesical/Rectal/Vésico-rectal</label>
                                </div>

                                <h5>15-Tratamiento</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="tratamiento-clinico"
                                        name="tratamiento" value="Clínico">
                                    <label class="form-check-label" for="tratamiento-clinico">Clínico</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="tratamiento-psicofarmacologico"
                                        name="tratamiento" value="Psicofarmacológico reflejos">
                                    <label class="form-check-label"
                                        for="tratamiento-psicofarmacologico">Psicofarmacológico reflejos</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="tratamiento-biologico"
                                        name="tratamiento" value="Biológico">
                                    <label class="form-check-label" for="tratamiento-biologico">Biológico</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="tratamiento-ech-insulina"
                                        name="tratamiento" value="ECH/c.Insulina">
                                    <label class="form-check-label"
                                        for="tratamiento-ech-insulina">ECH/c.Insulina</label>
                                </div>

                                <h5>16-Evolución</h5>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="evolucion-buena" name="evolucion"
                                        value="Buena">
                                    <label class="form-check-label" for="evolucion-buena">Buena</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="evolucion-regular" name="evolucion"
                                        value="Regular">
                                    <label class="form-check-label" for="evolucion-regular">Regular</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="evolucion-mala" name="evolucion"
                                        value="Mala">
                                    <label class="form-check-label" for="evolucion-mala">Mala</label>
                                </div>


                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save"
                        id="btnGuardarAdmiAmb">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!--FIN ADMISION AMBULATORIO -->

    <!-- Modal de DIAGNOSTICOS -->
    <div class="modal fade" id="diagModal" tabindex="-1" aria-labelledby="diagModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="diagModalLabel">Diagnosticos</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="diagModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaDiag" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaDiag">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarDiagModal" tabindex="-1" aria-labelledby="agregarDiagModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarDiagModalLabel">Agregar Diagnostico</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarDiag" class="row g-3">
                        <input type="hidden" id="diagIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="diagId">

                        <div class="col-md-4">
                            <label for="diagNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="diagNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="diagFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="diagFecha" name="fecha" required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="paci_diag">Diagnostico:*</label>
                            <select class="form-control" id="paci_diag" name="paci_diag" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarDiag">Guardar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- FIN DIAGNOSTICOS -->

    <!-- Modal de MEDICACION -->
    <div class="modal fade" id="mediModal" tabindex="-1" aria-labelledby="mediModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mediModalLabel">Medicamentos</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mediModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaMedi" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnMedicacionPacientePDF" class="btn btn-success" type="button"
                            data-bs-toggle="modal" data-bs-target="#modalFechas">Generar PDF</button>

                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaMedi">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF -->
    <!-- Modal para seleccionar fechas -->
    <div class="modal fade" id="modalFechas" tabindex="-1" aria-labelledby="modalFechasLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFechasLabel">Seleccionar Fechas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fechaDesde" class="form-label">Fecha Desde:</label>
                        <input type="date" class="form-control" id="fechaDesde" required>
                    </div>
                    <div class="mb-3">
                        <label for="fechaHasta" class="form-label">Fecha Hasta:</label>
                        <input type="date" class="form-control" id="fechaHasta" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" id="btnConfirmarFechas">Generar PDF</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="agregarMediModal" tabindex="-1" aria-labelledby="agregarMediModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarMediModalLabel">Agregar Medicamento</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarMedi" class="row g-3">
                        <input type="hidden" id="mediIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="mediId">

                        <div class="col-md-4">
                            <label for="mediNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="mediNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="medi_fecha" class="form-label">Fecha:</label>
                            <input type="date" class="form-control" id="medi_fecha" name="medi_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label for="medi_hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="medi_hora" name="medi_hora" required>
                        </div>

                        <div class="col-md-4">
                            <label for="dosis">Dosis:</label>
                            <input type="number" class="form-control" id="dosis" name="dosis" required>
                        </div>

                        <div class="col-md-4">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad">
                        </div>

                        <div class="col-md-4">
                            <label for="valor">Valor:</label>
                            <input type="number" class="form-control" id="valor" name="valor" step="0.01">
                        </div>

                        <hr>
                        <hr>
                        <div class="col-md-4 form-group">
                            <label for="mediDesc">Medicamento:*</label>
                            <input type="text" id="searchMedicacion" placeholder="Buscar..." class="form-control mb-2">
                            <select class="form-control" id="mediDesc" name="mediDesc" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                        <button id="prevPage" class="btn btn-secondary" type="button">Página Anterior</button>
                        <button id="nextPage" class="btn btn-secondary" type="button">Página Siguiente</button>
                        <hr>

                        <hr>


                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarMedi">Guardar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- FIN MEDICACION -->

    <!-- Modal de Responsable -->
    <div class="modal fade" id="responModal" tabindex="-1" aria-labelledby="responModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responModalLabel">Responsables</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="responModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaRespon" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevoRespon">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarResponModal" tabindex="-1" aria-labelledby="agregarResponModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarResponModalLabel">Agregar Responsable</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarRespon" class="row g-3">
                        <input type="hidden" id="responIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="responId">

                        <div class="col-md-4">
                            <label for="responNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="responNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="respon_nombre">Nombre:</label>
                            <input type="text" class="form-control" id="respon_nombre" name="respon_nombre">
                        </div>

                        <div class="col-md-4">
                            <label for="respon_tel">tel:</label>
                            <input type="text" class="form-control" id="respon_tel" name="respon_tel">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="respon_parent">Parentesco:*</label>
                            <select class="form-control" id="respon_parent" name="respon_parent" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="respon_dni">DNI:</label>
                            <input type="number" class="form-control" id="respon_dni" name="respon_dni">
                        </div>

                        <div class="col-md-4">
                            <label for="respon_dom">Domicilio:</label>
                            <input type="text" class="form-control" id="respon_dom" name="respon_dom">
                        </div>

                        <div class="col-md-4">
                            <label for="respon_locali">Localidad:</label>
                            <input type="text" class="form-control" id="respon_locali" name="respon_locali">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarRespon">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- FIN RESPONSABLES -->

    <!-- Modal de JUDICIALES -->
    <div class="modal fade" id="judiModal" tabindex="-1" aria-labelledby="judiModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="judiModalLabel">Judiciales</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="judiModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaJudi" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevoJudi">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarJudiModal" tabindex="-1" aria-labelledby="agregarJudiModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarJudiModalLabel">Agregar Judiacial</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarJudi" class="row g-3">
                        <input type="hidden" id="judiIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="judiId">

                        <div class="col-md-4">
                            <label for="judiNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="judiNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="juzgado">Juzgado:*</label>
                            <select class="form-control" id="juzgado" name="juzgado" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="secretaria">Secretaria:*</label>
                            <select class="form-control" id="secretaria" name="secretaria" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="curaduria">Curaduria:*</label>
                            <select class="form-control" id="curaduria" name="curaduria" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="t_juicio">Tipo de juicio:*</label>
                            <select class="form-control" id="t_juicio" name="t_juicio" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="judiFecha" class="form-label">Vencimiento:</label>
                            <input type="date" class="form-control" id="judiFecha" name="fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label for="judiObs">Observaciones:</label>
                            <input type="text" class="form-control" id="judiObs" name="judiObs">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarJudi">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- FIN JUDICIALES -->

    <!-- Modal de SALIDAS -->
    <div class="modal fade" id="saliModal" tabindex="-1" aria-labelledby="saliModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="saliModalLabel">Salidas</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="saliModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaSali" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevoSali">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarSaliModal" tabindex="-1" aria-labelledby="agregarSaliModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarSaliModalLabel">Agregar Salida</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarSali" class="row g-3">
                        <input type="hidden" id="saliIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="saliId">

                        <div class="col-md-4">
                            <label for="saliNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="saliNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="salida_fecha" class="form-label">Fecha Salida:</label>
                            <input type="date" class="form-control" id="salida_fecha" name="salida_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label for="llegada_fecha" class="form-label">Fecha Llegada:</label>
                            <input type="date" class="form-control" id="llegada_fecha" name="llegada_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label for="saliObs">Observaciones:</label>
                            <input type="text" class="form-control" id="saliObs" name="saliObs">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarSali">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- FIN SALIDAS -->

    <!-- Modal de HABITACIONES -->
    <div class="modal fade" id="habiModal" tabindex="-1" aria-labelledby="habiModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="habiModalLabel">Habitaciones</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="habiModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaHabi" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <span class="me-2">Habitaciones disponibles:</span>
                                <span id="habitacionesDisponibles" class="fw-bold"></span>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary btn-volver me-2">Volver</button>
                                <button type="button" class="btn btn-primary btn-custom-save"
                                    id="nuevaHabi">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarHabiModal" tabindex="-1" aria-labelledby="agregarHabiModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarHabiModalLabel">Agregar Habitacion</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarHabi" class="row g-3">
                        <input type="hidden" id="habiIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="habiId">

                        <div class="col-md-4">
                            <label for="habiNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="habiNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="habitacion_nro">Habitacion:*</label>
                            <select class="form-control" id="habitacion_nro" name="habitacion_nro" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="habi_ingreso_fecha" class="form-label">Fecha ingreso:</label>
                            <input type="date" class="form-control" id="habi_ingreso_fecha" name="habi_ingreso_fecha"
                                required>
                        </div>


                        <div class="col-md-4">
                            <label for="habi_egreso_fecha" class="form-label">Fecha egreso:</label>
                            <input type="date" class="form-control" id="habi_egreso_fecha" name="habi_egreso_fecha"
                                required>
                        </div>



                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarHabi">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- FIN HABITACIONES -->

    <!-- Modal de VISITAS -->
    <div class="modal fade" id="visiModal" tabindex="-1" aria-labelledby="visiModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="visiModalLabel">Visitas</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="visiModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaVisi" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaVisi">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarVisiModal" tabindex="-1" aria-labelledby="agregarVisiModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarVisiModalLabel">Agregar Visita</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarVisi" class="row g-3">
                        <input type="hidden" id="visiIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="visiId">

                        <div class="col-md-4">
                            <label for="visiNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="visiNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="visita_fecha" class="form-label">Fecha visita:</label>
                            <input type="date" class="form-control" id="visita_fecha" name="visita_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label for="visita_nom">Nombre de visita:</label>
                            <input type="text" class="form-control" id="visita_nom" name="visita_nom">
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="visita_parent">Parentesco de visita:*</label>
                            <select class="form-control" id="visita_parent" name="visita_parent" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="visita_obs">Observaciones:</label>
                            <input type="text" class="form-control" id="visita_obs" name="visita_obs">
                        </div>



                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarVisi">Guardar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- FIN VISITAS -->

    <!-- Modal de TRASLADOS -->
    <div class="modal fade" id="trasModal" tabindex="-1" aria-labelledby="trasModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trasModalLabel">Traslados</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="trasModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaTras" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevoTras">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarTrasModal" tabindex="-1" aria-labelledby="agregarTrasModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarTrasModalLabel">Agregar Traslado</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarTras" class="row g-3">
                        <input type="hidden" id="trasIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="trasId">

                        <div class="col-md-4">
                            <label for="trasNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="trasNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="tras_fecha" class="form-label">Fecha traslado:</label>
                            <input type="date" class="form-control" id="tras_fecha" name="tras_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label for="tras_hora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="tras_hora" name="tras_hora" required>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="tras_importe">Importe:</label>
                            <input type="number" class="form-control" id="tras_importe" name="tras_importe" required>
                        </div>

                        <div class="col-md-4">
                            <label for="tras_obs">Observaciones:</label>
                            <input type="text" class="form-control" id="tras_obs" name="tras_obs">
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarTras">Guardar</button>

                </div>
            </div>
        </div>
    </div>

    <!--FIN TRASLADOS-->

    <!-- ORDENES DE PRESTACION -->
    <div class="modal fade" id="ordenModal" tabindex="-1" aria-labelledby="ordenModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ordenModalLabel">Ordenes de prestacion</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="ordenModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaOrden" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaOrden">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarOrdenModal" tabindex="-1" aria-labelledby="agregarOrdenModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarOrdenModalLabel">Agregar Orden</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarOrden" class="row g-3">
                        <input type="hidden" id="ordenIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="ordenId">

                        <div class="col-md-4">
                            <label for="ordenNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="ordenNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="orden_fecha" class="form-label">Fecha:</label>
                            <input type="date" class="form-control" id="orden_fecha" name="orden_fecha" required>
                        </div>

                        <div class="col-md-4">
                            <label for="op" class="form-label">Nro Orden:</label>
                            <input type="number" class="form-control" id="op" name="op" required>
                        </div>

                        <div class="col-md-4">
                            <label for="op_cant" class="form-label">Cantidad de Meses:</label>
                            <input type="number" class="form-control" id="op_cant" name="op_cant" required>
                        </div>

                        <div class="col-md-6">
                            <label for="modalidad_op" class="form-label">Modalidad:*</label>
                            <select class="form-control" id="modalidad_op" name="modalidad_op" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>


                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarOrden">Guardar</button>

                </div>
            </div>
        </div>
    </div>
    <!-- FIN ORDENES DE PRESTACION -->



    <!-- FIN TRASLADOS -->

    <!--NUTRICION ADMISION -->
    <!-- Modal de NUTRICION -->
    <div class="modal fade" id="nutriModal" tabindex="-1" aria-labelledby="nutriModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="nutriModalLabel">Admision Nutricion</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="nutriModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaNutri" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevanutri">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarNutriModal" tabindex="-1" aria-labelledby="agregarNutriModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarNutriModalLabel">NUTRICION ADMISION</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="agregarNutri" class="row g-3">
                        <input type="hidden" id="nutriIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="nutriId">

                        <div class="col-md-4">
                            <label for="nutriNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="nutriNombreCarga" name="nombre" readonly>
                        </div>

                        <!-- Patologías -->
                        <div class="col-md-4 form-group">
                            <label for="patologias" class="form-label">Patologías (Diabetes, HTA, Obesidad,
                                etc.)</label>
                            <input type="text" class="form-control" id="patologias" name="patologias" required>
                        </div>

                        <!-- Indicación de Dieta -->
                        <div class="col-md-4 form-group">
                            <label for="indicacion_dieta" class="form-label">Indicación de Dieta</label>
                            <input type="text" class="form-control" id="indicacion_dieta" name="indicacion_dieta"
                                required>
                        </div>

                        <!-- Actitud frente a la comida -->
                        <div class="col-md-4 form-group">
                            <label for="actitud_comida" class="form-label">Actitud frente a la comida</label>
                            <input type="text" class="form-control" id="actitud_comida" name="actitud_comida" required>
                        </div>

                        <!-- Antropometría: Peso, Talla, IMC -->
                        <div class="col-md-4 form-group">
                            <label for="peso" class="form-label">Peso:</label>
                            <input type="text" step="0.1" class="form-control" id="peso" name="peso" required>

                            <label for="talla" class="form-label">Talla:</label>
                            <input type="text" step="0.01" class="form-control" id="talla" name="talla" required>

                            <label for="imc" class="form-label">IMC:</label>
                            <input type="text" step="0.01" class="form-control" id="imc" name="imc" required>
                        </div>

                        <!-- Plan de Hidratación, Complemento Alimentario, Requiere, No Requiere, Especificar -->
                        <div class="col-md-4 form-group">
                            <label for="plan_hidratacion" class="form-label">Plan de hidratación</label>

                            <label for="requiere" class="form-label">Complemento Alimentario - Requiere:</label>
                            <input type="text" class="form-control" id="requiere" name="requiere">

                            <label for="no_requiere" class="form-label">No Requiere:</label>
                            <input type="text" class="form-control" id="no_requiere" name="no_requiere">

                            <label for="especificar" class="form-label">En caso de SI, especificar cuál:</label>
                            <input type="text" class="form-control" id="especificar" name="especificar">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarNutricion">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!--FIN NUTRICION ADMISION -->

    <!--Edu fisica ADMISION -->
    <div class="modal fade" id="fisicaModal" tabindex="-1" aria-labelledby="fisicaModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fisicaModalLabel">agregar admision Fisica</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fisicaModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaFisica" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save" id="nuevaFisica">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Admision Educacion Fisica -->
    <div class="modal fade" id="agregarFisicaModal" tabindex="-1" aria-labelledby="agregarfisicaModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fisicaModalLabel">ADMISIÓN EDUCACIÓN FÍSICA</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarFisica" class="row g-3">
                        <input type="hidden" id="fisicaIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="fisicaId">

                        <div class="col-md-4">
                            <label for="fisicaNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="fisicaNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label for="medico_tratante">Médico Tratante:*</label>
                            <select class="form-control" id="medico_tratante" name="medico_tratante" required>
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="objetivos_generales" class="form-label"><strong>Objetivos
                                    Generales</strong></label>
                            <input type="text" class="form-control" id="objetivos_generales" name="objetivos_generales">
                        </div>
                        <div class="col-md-4">
                            <label for="examen_postural" class="form-label"><strong>Examen Postural y
                                    Marcha</strong></label>
                            <input type="text" class="form-control" id="examen_postural" name="examen_postural">
                        </div>
                        <div class="col-md-4">
                            <label for="examen_muscular" class="form-label"><strong>Examen Muscular</strong></label>
                            <input type="text" class="form-control" id="examen_muscular" name="examen_muscular">
                        </div>
                        <div class="col-md-4">
                            <label for="examen_flexibilidad" class="form-label"><strong>Examen de la
                                    Flexibilidad</strong></label>
                            <input type="text" class="form-control" id="examen_flexibilidad" name="examen_flexibilidad">
                        </div>
                        <div class="col-md-4">
                            <label for="fuerza_miembros_inferiores" class="form-label"><strong>Examen de la Fuerza:
                                    Miembros Inferiores</strong></label>
                            <input type="text" class="form-control" id="fuerza_miembros_inferiores"
                                name="fuerza_miembros_inferiores">
                        </div>
                        <div class="col-md-4">
                            <label for="fuerza_miembros_superiores" class="form-label"><strong>Examen de la Fuerza:
                                    Miembros Superiores</strong></label>
                            <input type="text" class="form-control" id="fuerza_miembros_superiores"
                                name="fuerza_miembros_superiores">
                        </div>

                        <div class="col-md-12">
                            <label class="form-label"><strong>Examen del Equilibrio</strong></label>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="equilibrio_normal" class="form-label">Normal</label>
                                    <input type="text" class="form-control" id="equilibrio_normal"
                                        name="equilibrio_normal">
                                </div>
                                <div class="col-md-4">
                                    <label for="equilibrio_ojos_cerrados" class="form-label">Ojos Cerrados</label>
                                    <input type="text" class="form-control" id="equilibrio_ojos_cerrados"
                                        name="equilibrio_ojos_cerrados">
                                </div>
                                <div class="col-md-4">
                                    <label for="equilibrio_base_sustentacion" class="form-label">Variando Base de
                                        Sustentación</label>
                                    <input type="text" class="form-control" id="equilibrio_base_sustentacion"
                                        name="equilibrio_base_sustentacion">
                                </div>
                                <div class="col-md-4">
                                    <label for="movimiento_ms" class="form-label">Moviendo MS</label>
                                    <input type="text" class="form-control" id="movimiento_ms" name="movimiento_ms">
                                </div>
                                <div class="col-md-4">
                                    <label for="movimiento_ml" class="form-label">Moviendo ML</label>
                                    <input type="text" class="form-control" id="movimiento_ml" name="movimiento_ml">
                                </div>
                                <div class="col-md-4">
                                    <label for="movimiento_tronco" class="form-label">Moviendo Tronco</label>
                                    <input type="text" class="form-control" id="movimiento_tronco"
                                        name="movimiento_tronco">
                                </div>
                                <div class="col-md-4">
                                    <label for="caminando_giros" class="form-label">Caminando con Giros</label>
                                    <input type="text" class="form-control" id="caminando_giros" name="caminando_giros">
                                </div>
                                <div class="col-md-4">
                                    <label for="observaciones_generales" class="form-label">Observaciones
                                        Generales</label>
                                    <input type="text" class="form-control" id="observaciones_generales"
                                        name="observaciones_generales">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="btnGuardarFisica">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!--FIN Edu fisica ADMISION -->

    <!-- IMPRESION DIAGNOSTICO -->

    <div class="modal fade" id="admiDiagModal" tabindex="-1" aria-labelledby="admiDiagModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="admiDiagModalLabel">AGREGAR IMPRESION DIAGNOSTICA</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="admiDiagModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaAdmiDiag" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save"
                            id="nuevaAdmiDiag">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarImpresionDiagnosticaModal" tabindex="-1"
        aria-labelledby="agregarImpresionDiagnosticaModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="impresionDiagnosticaModalLabel">IMPRESIÓN DIAGNÓSTICA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formImpresionDiagnostica" class="row g-3">

                        <input type="hidden" id="admiDiagIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="admiDiagId">

                        <div class="col-md-4">
                            <label for="admiDiagNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="admiDiagNombreCarga" name="nombre" readonly>
                        </div>

                        <div class="col-md-5">
                            <label for="impresion_naturaleza" class="form-label"><strong>1 - La Naturaleza de los
                                    problemas del paciente, Defensas prevalentes</strong></label>
                            <input type="text" class="form-control" id="impresion_naturaleza"
                                name="impresion_naturaleza">
                        </div>

                        <div class="col-md-5">
                            <label for="impresion_situacion" class="form-label"><strong>2 - La situación de la
                                    entrevista</strong></label>
                            <input type="text" class="form-control" id="impresion_situacion" name="impresion_situacion">
                        </div>

                        <div class="col-md-5">
                            <label for="impresion_conciencia" class="form-label"><strong>3 - Conciencia de
                                    enfermedad</strong></label>
                            <input type="text" class="form-control" id="impresion_conciencia"
                                name="impresion_conciencia">
                        </div>

                        <div class="col-md-5">
                            <label for="impresion_expectativas" class="form-label"><strong>4 - Expectativas y deseos del
                                    paciente respecto al tratamiento (literal)</strong></label>
                            <input type="text" class="form-control" id="impresion_expectativas"
                                name="impresion_expectativas">
                        </div>

                        <div class="col-md-5">
                            <label for="diagnostico_clinico" class="form-label"><strong>1 - Diagnóstico clínico
                                    (clasificación OMS)</strong></label>
                            <input type="text" class="form-control" id="diagnostico_clinico" name="diagnostico_clinico">
                        </div>

                        <div class="col-md-5">
                            <label for="diagnostico_gravedad" class="form-label"><strong>2 - Gravedad
                                    (leve-moderada-grave)</strong></label>
                            <input type="text" class="form-control" id="diagnostico_gravedad"
                                name="diagnostico_gravedad">
                        </div>

                        <div class="col-md-5">
                            <label for="factores_desencadenantes" class="form-label"><strong>3 - Factores
                                    desencadenantes</strong></label>
                            <input type="text" class="form-control" id="factores_desencadenantes"
                                name="factores_desencadenantes">
                        </div>

                        <div class="col-md-5">
                            <label for="personalidad_premorbida" class="form-label"><strong>4 - Personalidad premórbida
                                    y predisposición</strong></label>
                            <input type="text" class="form-control" id="personalidad_premorbida"
                                name="personalidad_premorbida">
                        </div>

                        <div class="col-md-5">
                            <label for="incapacidad_social" class="form-label"><strong>5 - Grado de incapacidad social
                                    (sin incapacidad-leve-moderada-grave-temporal-definitiva)</strong></label>
                            <input type="text" class="form-control" id="incapacidad_social" name="incapacidad_social">
                        </div>

                        <div class="col-md-5">
                            <label for="indicaciones" class="form-label"><strong>Indicaciones</strong></label>
                            <input type="text" class="form-control" id="indicaciones" name="indicaciones">
                        </div>

                        <div class="col-md-5">
                            <label for="pronostico" class="form-label"><strong>Pronóstico</strong></label>
                            <input type="text" class="form-control" id="pronostico" name="pronostico">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save"
                        id="btnGuardarAdmiDiag">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN IMPRESION DIAGNOSTICO -->

    <!-- EXAMEN PSIQUIATRICO -->

    <div class="modal fade" id="exPsiquiatricoModal" tabindex="-1" aria-labelledby="exPsiquiatricoModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exPsiquiatricoModalLabel">AGREGAR EXAMEN PSIQUIATRICO</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="exPsiquiatricoModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaExPsiquiatrico" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save"
                            id="nuevoExPsiquiatrico">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarExamenPsiquiatricoModal" tabindex="-1"
        aria-labelledby="agregarExamenPsiquiatricoModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarExamenPsiquiatricoModalLabel">EXAMEN PSIQUIÁTRICO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formExPsiquiatrico" class="row g-3">

                        <input type="hidden" id="exPsiquiatricoIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="exPsiquiatricoId">

                        <div class="col-md-4">
                            <label for="exPsiquiatricoNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="exPsiquiatricoNombreCarga" name="nombre"
                                readonly>
                        </div>

                        <div class="col-md-5">
                            <label for="apariencia_personal" class="form-label"><strong>1 - APARIENCIA
                                    PERSONAL</strong></label>
                            <br>
                            <label for="forma_presentarse" class="form-label"><strong>a) - Forma de presentarse:
                                    características salientes</strong></label>
                            <input type="text" class="form-control" id="forma_presentarse" name="forma_presentarse">

                            <label for="vestimenta" class="form-label"><strong>b) - Vestimenta: término medio, atildado,
                                    descuidado, bizarro, otro (describir)</strong></label>
                            <input type="text" class="form-control" id="vestimenta" name="vestimenta">

                            <label for="peso_psiquiatrico" class="form-label"><strong>c) - Peso: normal, obeso,
                                    delgado</strong></label>
                            <input type="text" class="form-control" id="peso_psiquiatrico" name="peso_psiquiatrico">
                        </div>

                        <div class="col-md-5">
                            <label for="actividad_psicomotora" class="form-label"><strong>2 - ACTIVIDAD
                                    PSICOMOTORA</strong></label>
                            <br>
                            <label for="grado_actividad" class="form-label"><strong>a) - Grado: Abúlico, normal,
                                    hiperactivo, excitado</strong></label>
                            <input type="text" class="form-control" id="grado_actividad" name="grado_actividad">

                            <label for="cualidad_formal" class="form-label"><strong>2 - Cualidad formal: actos
                                    repetitivos, manerismos, tics, posturas, etc.</strong></label>
                            <input type="text" class="form-control" id="cualidad_formal" name="cualidad_formal">
                        </div>

                        <div class="col-md-5">
                            <label for="conducta_expresiva" class="form-label"><strong>3 - Conducta
                                    expresiva</strong></label>
                            <br>
                            <label for="pertinente" class="form-label"><strong>a) - Pertinente</strong></label>
                            <input type="text" class="form-control" id="pertinente" name="pertinente">

                            <label for="signos_ansiedad" class="form-label"><strong>4 - Signos de
                                    ansiedad</strong></label>
                            <input type="text" class="form-control" id="signos_ansiedad" name="signos_ansiedad">
                        </div>

                        <div class="col-md-5">
                            <label for="actividad_verbal" class="form-label"><strong>5 - Actividad verbal
                                    (ritmo)</strong></label>
                            <label for="bradilalia" class="form-label"><strong>a) - Bradilalia</strong></label>
                            <input type="text" class="form-control" id="bradilalia" name="bradilalia">
                        </div>

                        <div class="col-md-5">
                            <label for="actitudes_paciente" class="form-label"><strong>3 - ACTITUDES DEL PACIENTE
                                    DURANTE LA ENTREVISTA</strong></label>
                            <label for="cooperativo" class="form-label"><strong>a) - Cooperativo: 1-2-3</strong></label>
                            <input type="text" class="form-control" id="cooperativo" name="cooperativo">
                            <label for="comunicativo" class="form-label"><strong>b) - Comunicativo:
                                    1-2-3</strong></label>
                            <input type="text" class="form-control" id="comunicativo" name="comunicativo">
                        </div>

                        <div class="col-md-5">
                            <label for="escala_actitudes" class="form-label"><strong>Escala 1-Nada o muy poco,
                                    2-Medianamente, 3-Extremadamente</strong></label>
                            <input type="text" class="form-control" id="escala_actitudes" name="escala_actitudes">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarExPsiquiatrico">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- FIN EXAMEN PSIQUIATRICO -->

    <!-- ANCEDENTES FAMILIARES-->

    <div class="modal fade" id="antecedentesFamiliaresModal" tabindex="-1"
        aria-labelledby="antecedentesFamiliaresModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="antecedentesFamiliaresModalLabel">AGREGAR ANTECEDENTE FAMILIAR</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="antecedentesFamiliaresModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaAntecendesFamiliares" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save"
                            id="nuevoAntecendesFamiliares">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregarAntecedentesFamiliaresModal" tabindex="-1"
        aria-labelledby="agregarAntecedentesFamiliaresModalLabel">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarAntecedentesFamiliaresModalLabel">Antecedentes Familiares
                    </h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAntecedenteFamiliar" class="row g-3">
                        <input type="hidden" id="antecedentesFamiliaresIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="antecedentesFamiliaresId">

                        <div class="col-md-4">
                            <label for="antecedentesFamiliaresNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="antecedentesFamiliaresNombreCarga" name="nombre"
                                readonly>
                        </div>

                        <div class="col-md-12">
                            <label for="antecedentesFamiliar1" class="form-label"><strong>1 - Grupo familiar de origen
                                    (padres, hermanos, composición)</strong></label>
                            <input type="text" class="form-control" id="antecedentesFamiliar1"
                                name="antecedentesFamiliar1">
                        </div>

                        <div class="col-md-12">
                            <label for="antecedentesFamiliar2" class="form-label"><strong>2 - Interrelación ambiente
                                    psicológico del hogar</strong></label>
                            <input type="text" class="form-control" id="antecedentesFamiliar2"
                                name="antecedentesFamiliar2">
                        </div>

                        <div class="col-md-12">
                            <label for="antecedentesFamiliar3" class="form-label"><strong>3 - Estado cultural, social y
                                    económico de la familia</strong></label>
                            <input type="text" class="form-control" id="antecedentesFamiliar3"
                                name="antecedentesFamiliar3">
                        </div>

                        <div class="col-md-12">
                            <label for="antecedentesFamiliar4" class="form-label"><strong>4 - Cambios y eventos
                                    importantes</strong></label>
                            <input type="text" class="form-control" id="antecedentesFamiliar4"
                                name="antecedentesFamiliar4">
                        </div>

                        <div class="col-md-12">
                            <label for="antecedentesFamiliar5" class="form-label"><strong>5 - Antecedentes
                                    psiquiátricos, psicosomáticos y neurológicos</strong></label>
                            <input type="text" class="form-control" id="antecedentesFamiliar5"
                                name="antecedentesFamiliar5">
                        </div>

                        <div class="col-md-12">
                            <label for="antecedentesFamiliar6" class="form-label"><strong>6 - Conclusiones del
                                    entrevistador sobre el tipo de familia</strong></label>
                            <input type="text" class="form-control" id="antecedentesFamiliar6"
                                name="antecedentesFamiliar6">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary btn-custom-save"
                        id="btnGuardarAntecedentes">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!-- FIN ANCEDENTES FAMILIARES -->

    <!--ANTECENDESTER PERSONALES -->
    <div class="modal fade" id="antecedentesPersonalesModal" tabindex="-1"
        aria-labelledby="antecedentesPersonalesModalLabel">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="antecedentesPersonalesModalLabel">AGREGAR ANTECEDENTE PERSONAL</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="antecedentesPersonalesModalBody">
                    <!-- Aquí se cargará el contenido del formulario -->
                </div>
                <div class="row">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div id="listaAntecendesPersonales" class="scrollable-content">
                                <!-- Aquí se cargará dinámicamente la lista de prácticas -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-volver">Volver</button>
                        <button type="button" class="btn btn-primary btn-custom-save"
                            id="nuevoAntecendesPersonales">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agregar_antecedentes_modal" tabindex="-1"
        aria-labelledby="agregar_antecedentes_modal_label">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregar_antecedentes_modal_label">Antecedentes Personales</h5>
                    <div class="modal-header-center">
                        <img src="../img/logo.png" alt="Logo" class="modal-logo">
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAntecedentesPersonales" class="row g-3">

                        <input type="hidden" id="antecedentesPersonalesIdPaciente" name="id_paciente">
                        <input type="hidden" name="id" id="antecedentePersonalesId">

                        <div class="col-md-4">
                            <label for="antecedentesFamiliaresNombreCarga" class="form-label">Nombre y Apellido</label>
                            <input type="text" class="form-control" id="antecedentesPersonalesNombreCarga" name="nombre"
                                readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="complicaciones_nacimiento" class="form-label"><strong>1 - Complicaciones en el
                                    nacimiento</strong></label>
                            <input type="text" class="form-control" id="complicaciones_nacimiento"
                                name="complicaciones_nacimiento">
                        </div>

                        <div class="col-md-6">
                            <label for="desarrollo_ninez" class="form-label"><strong>2 - Desarrollo y enfermedades de la
                                    niñez</strong></label>
                            <input type="text" class="form-control" id="desarrollo_ninez" name="desarrollo_ninez">
                        </div>

                        <div class="col-md-6">
                            <label for="enfermedades_principales" class="form-label"><strong>3 - Enfermedades,
                                    operaciones y traumatismos</strong></label>
                            <input type="text" class="form-control" id="enfermedades_principales"
                                name="enfermedades_principales">
                        </div>

                        <div class="col-md-6">
                            <label for="sistema_nervioso" class="form-label"><strong>4 - Sistema nervioso e
                                    infecciones</strong></label>
                            <input type="text" class="form-control" id="sistema_nervioso" name="sistema_nervioso">
                        </div>

                        <div class="col-md-6">
                            <label for="estudios" class="form-label"><strong>5 - Estudios</strong></label>
                            <input type="text" class="form-control" id="estudios" name="estudios">
                        </div>

                        <div class="col-md-6">
                            <label for="actividad_sexual" class="form-label"><strong>6 - Actividad
                                    sexual</strong></label>
                            <input type="text" class="form-control" id="actividad_sexual" name="actividad_sexual">
                        </div>

                        <div class="col-md-6">
                            <label for="historial_marital" class="form-label"><strong>7 - Historial
                                    marital</strong></label>
                            <input type="text" class="form-control" id="historial_marital" name="historial_marital">
                        </div>

                        <div class="col-md-6">
                            <label for="embarazos_hijos" class="form-label"><strong>8 - Embarazos e
                                    hijos</strong></label>
                            <input type="text" class="form-control" id="embarazos_hijos" name="embarazos_hijos">
                        </div>

                        <div class="col-md-6">
                            <label for="interrelacion_familiar" class="form-label"><strong>9 - Interrelación
                                    familiar</strong></label>
                            <input type="text" class="form-control" id="interrelacion_familiar"
                                name="interrelacion_familiar">
                        </div>

                        <div class="col-md-6">
                            <label for="actividades_laborales" class="form-label"><strong>10 - Actividades
                                    laborales</strong></label>
                            <input type="text" class="form-control" id="actividades_laborales"
                                name="actividades_laborales">
                        </div>

                        <div class="col-md-6">
                            <label for="habitos" class="form-label"><strong>11 - Hábitos</strong></label>
                            <input type="text" class="form-control" id="habitos" name="habitos">
                        </div>

                        <div class="col-md-6">
                            <label for="intereses" class="form-label"><strong>12 - Intereses</strong></label>
                            <input type="text" class="form-control" id="intereses" name="intereses">
                        </div>

                        <div class="col-md-6">
                            <label for="actividad_social" class="form-label"><strong>13 - Actividad
                                    social</strong></label>
                            <input type="text" class="form-control" id="actividad_social" name="actividad_social">
                        </div>

                        <div class="col-md-6">
                            <label for="creencias_religiosas" class="form-label"><strong>14 - Creencias
                                    religiosas</strong></label>
                            <input type="text" class="form-control" id="creencias_religiosas"
                                name="creencias_religiosas">
                        </div>

                        <div class="col-md-6">
                            <label for="toxicomanias" class="form-label"><strong>15 - Toxicomanías</strong></label>
                            <input type="text" class="form-control" id="toxicomanias" name="toxicomanias">
                        </div>

                        <div class="col-md-6">
                            <label for="rasgos_personalidad" class="form-label"><strong>16 - Rasgos de
                                    personalidad</strong></label>
                            <input type="text" class="form-control" id="rasgos_personalidad" name="rasgos_personalidad">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardarAntecedentesPersonales">Guardar</button>
                </div>
            </div>
        </div>
    </div>


    <!--ANTECENDESTER PERSONALES -->

    <!-- Pie de página -->
    <?php include "../componentes/footer.php"; ?>
    <!-- Fin Pie de página -->

</body>

</html>