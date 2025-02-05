<?php
require_once "../conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['up'])) {
    // El usuario ha iniciado sesión, puedes mostrar contenido para usuarios autenticados o ejecutar acciones específicas
} else {
    header("Location: ../index.php");
}
// Determinar cliente desde la sesión
$cliente = isset($_SESSION['up']) ? $_SESSION['up'] : null;
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Turnos</title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../estilos/styleGeneral.css">
    <link rel="stylesheet" href="../estilos/styleBotones.css">

    <!-- Enlace a Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- CSS para Bootstrap Datepicker -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- JavaScript para Bootstrap Datepicker -->
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>

    <!--REPORTES -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <!-- Incluye una biblioteca para generar QR (por ejemplo, qrcode.js) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script src="script.js"></script>
    <style>
        .container-custom {
            display: flex;
            flex-direction: row;
            width: 100%;
            max-width: 1200px;
            /* Ajusta según sea necesario */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            gap: 10px;
            margin: 0 auto;
            /* Centra el contenedor horizontalmente */
        }

        .calendar-container,
        .schedule-container {
            flex: 1;
            padding: 0;
            background: #fff;
        }

        .calendar-container {
            width: 35rem;
        }

        .schedule-container {
            flex: 1;
        }

        /* Estilos para el calendario */
        #calendar {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            width: 100%;
            margin: 0;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f0f0f0;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .calendar-body {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 1px;
            background: #ddd;
        }

        .calendar-day,
        .calendar-day-header {
            text-align: center;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            cursor: pointer;
            box-sizing: border-box;
        }

        .calendar-day-header {
            background: #ccc;
        }

        .calendar-day.available {
            background-color: #98e861;
            color: #fff;
        }

        .calendar-day.has-appointments {
            background-color: #d4ac0d !important;
        }

        .calendar-day.fully-booked {
            background-color: #fb7979 !important;
            /* Color que indique que el día está totalmente ocupado */
            color: white;
            /* Opcional: Cambiar color del texto para destacar el día */
        }



        .calendar-day.selected-day {
            background-color: var(--primary-color) !important;
            color: #fff;
        }

        .current-day {
            background-color: #FCF3CF !important;
        }

        #schedule {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        #schedule th,
        #schedule td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        #schedule td {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #schedule tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .clickable-cell {
            cursor: pointer !important;
        }

        #patientList {
            max-height: 400px;
            overflow-y: auto;
        }

        .select-custom {
            max-width: 35rem;
        }

        .custom-img {
            width: 15rem;
            max-width: 100%;
            margin-top: -7rem !important;
        }
    </style>
</head>

<body>

    <button class="button" style="vertical-align:middle; margin-left:7rem"
        onclick="window.location.href = '../inicio/home.php';">
        <span>VOLVER</span>
    </button>

    <div class="container my-5">
        <!-- Contenedor de la imagen -->
        <div class="row justify-content-center mb-3">
            <div class="col-auto text-center">
                <img src="../img/logo.png" alt="MEDICAL" class="img-fluid custom-img">
            </div>
        </div>
        <div class="row justify-content-center mb-4">
            <div class="col-auto text-center">
                <h1>Agenda de Turnos</h1>
            </div>
        </div>
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="d-flex align-items-center justify-content-center gap-3">
                    <select id="profesionalSelect" class="form-control select-custom">
                        <option selected disabled>Seleccionar profesional</option>
                        <option value="all">Todos los profesionales</option>
                        <!-- Opciones de profesionales se llenarán aquí -->
                    </select>
                    <input type="date" id="fechaDesde" class="form-control">
                    <input type="date" id="fechaHasta" class="form-control">

                    <button id="generatePdfBtn" class="btn  btn-custom">Turnos asignados</button>
                    <button id="recordatorioBtn" class="btn  btn-custom">Recordatorios →</button>
                    <!-- Botón de descarga -->
                    <a href="https://worldsoftsystems.com.ar/MEDICAL/descargas/recordatorio_medical.zip" download>
                        <button class="btn  btn-custom">Descargar Recordatorio</button>
                    </a>
                </div>
            </div>
        </div>

        <div class="container-custom mt-4">
            <div class="calendar-container">
                <div id="calendar"></div>
            </div>
            <div class="schedule-container">
                <div id="selected-date">Fecha seleccionada: Ninguna</div>
                <div style="height: 500px; overflow-y: auto;">
                    <table id="schedule" class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th class="clickable-th">Hora</th>
                                <th class="clickable-th">Paciente</th>
                                <th class="clickable-th">Motivo de Consulta</th>
                                <th class="clickable-th">Llegó</th>
                                <th class="clickable-th">Atendido</th>
                                <th class="clickable-th">Observaciones</th>
                                <th class="clickable-th">Teléfono</th>
                            </tr>
                        </thead>
                        <tbody id="schedule-body">
                            <!-- Las horas disponibles y los horarios del profesional se llenarán aquí -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar información del turno -->
    <div class="modal fade" id="editTurnoModal" tabindex="-1" role="dialog" aria-labelledby="editTurnoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTurnoModalLabel">Editar Turno</h5>
                    <button type="button" class="btn btn-custom ms-2" id="printTurnoButton">
                        Imprimir Turno
                    </button>
                    <?php if ($cliente === 'UP3069149922304'): ?>
                        <button type="button" class="btn btn-custom ms-2" id="qrPaciente">
                            Generar Qr
                        </button>
                    <?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editTurnoForm">

                        <!-- Campo oculto para el ID del turno -->
                        <input type="hidden" id="turno_id" name="turno_id">

                        <div class="form-group">
                            <label for="id_prof_edit">Profesional</label>
                            <input type="text" class="form-control" id="prof_name" name="prof_name" required readonly>
                            <input type="hidden" id="id_prof_edit" name="id_prof_edit">
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="text" class="form-control" id="fecha" name="fecha" required>
                        </div>
                        <div class="form-group">
                            <label for="hora">Hora</label>
                            <input type="text" class="form-control" id="hora" name="hora" required>
                        </div>

                        <div class="form-group d-flex align-items-center mt-3">
                            <label for="paciente_edit" class="mr-2">Paciente</label>
                            <input type="text" class="form-control mr-2" id="paciente_edit" name="paciente_edit"
                                required>
                            <input type="hidden" id="paciente_id_edit" name="paciente_id_edit">
                            <div id="pacientes_list_edit" class="list-group" style="position: absolute; z-index: 1000;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="motivo" class="form-label">Motivo</label>
                            <select class="form-control" name="motivo" id="motivo">
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="llego">¿Llegó?</label>
                            <select class="form-control" id="llego" name="llego" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="SI">Si</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="atendido">¿Atendido?</label>
                            <select class="form-control" id="atendido" name="atendido" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="SI">Si</option>
                                <option value="NO">No</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                        </div>
                        <!-- Nuevo campo solo lectura -->
                        <div class="form-group">
                            <label for="id_paciente_turno">Último Turno:</label>
                            <input type="text" class="form-control" id="id_paciente_turno_edit" name="id_paciente_turno"
                                readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-custom">Guardar Cambios</button>
                            <button type="button" class="btn btn-danger" id="btnEliminar">Eliminar</button>
                        </div>

                    </form>
                </div>

                <!-- Modal anidado para mostrar el QR -->
                <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="qrModalLabel">Código QR</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body d-flex justify-content-center align-items-center">
                                <!-- Contenedor del QR -->
                                <div id="qrContainer"></div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <!-- Modal para crear nuevo turno -->
    <div class="modal fade" id="createTurnoModal" tabindex="-1" role="dialog" aria-labelledby="createTurnoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createTurnoModalLabel">Asignacion de Turno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createTurnoForm">
                        <div class="form-group">
                            <label for="id_prof_input">Profesional</label>
                            <input type="text" class="form-control" id="prof_name_input" name="prof_name_input" required
                                readonly>
                            <input type="hidden" id="id_prof_input" name="id_prof_input">
                        </div>
                        <div class="form-group">
                            <label for="fechas_input">Fechas</label>
                            <input type="text" class="form-control" id="fechas_input" name="fechas_input" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_input">Hora</label>
                            <input type="text" class="form-control" id="hora_input" name="hora_input" required>
                        </div>

                        <div class="form-group d-flex align-items-center mt-3">
                            <label for="paciente_input" class="mr-2">Paciente</label>
                            <input type="text" class="form-control mr-2" id="paciente_input" name="paciente_input"
                                required>
                            <input type="hidden" id="paciente_id" name="paciente_id">
                            <div id="pacientes_list" class="list-group" style="position: absolute; z-index: 1000;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="motivo" class="form-label">Motivo</label>
                            <select class="form-control" name="motivo" id="motivo_input">
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="llego">¿Llegó?</label>
                            <select class="form-control" id="llego_input" name="llego" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="SI">Si</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="atendido">¿Atendido?</label>
                            <select class="form-control" id="atendido_input" name="atendido" required>
                                <option value="" disabled selected>Seleccione</option>
                                <option value="SI">Si</option>
                                <option value="NO">No</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="observaciones">Observaciones</label>
                            <textarea class="form-control" id="observaciones" name="observaciones"></textarea>
                        </div>

                        <!-- Nuevo campo solo lectura -->
                        <div class="form-group">
                            <label for="id_paciente_turno">Último Turno:</label>
                            <input type="text" class="form-control" id="id_paciente_turno" name="id_paciente_turno"
                                readonly>
                        </div>
                        <button type="submit" class="btn btn-custom">Confirmar Turno</button>
                        <button type="button" class="btn btn-custom" data-bs-toggle="modal"
                            data-bs-target="#agregarPacienteModal">
                            Nuevo Paciente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- MODAL PACIENTE -->
    <div class="modal fade" id="agregarPacienteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">

        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="formPaciente" action="./ABM/agregarPaciente.php" method="POST">
                        <input type="hidden" id="id" name="id">

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="obra_social">Obra Social:*</label>
                                <select class="form-control" id="obra_social" name="obra_social">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>

                            <div class="col-md-6 form-group mb-3">
                                <label for="boca_atencion">Sucursal:*</label>
                                <select class="form-control" id="boca_atencion" name="boca_atencion">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="benef">Afiliado N°:*</label>
                                <input type="number" class="form-control" id="benef" name="benef">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="nombre">Nombre Y Apellido:*</label>
                                <input type="text" class="form-control" id="nombre" name="nombre">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="fecha_nac">Fecha de Nacimiento:*</label>
                                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="sexo">Sexo:*</label>
                                <select class="form-control" id="sexo" name="sexo">
                                    <option value="">Seleccionar...</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Masculino">Masculino</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="tipo_afiliado">Tipo de Afiliado:*</label>
                                <select class="form-control" id="tipo_afiliado" name="tipo_afiliado">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group mb-3">
                                <label for="tipo_doc">Tipo de Documento:*</label>
                                <select class="form-control" id="tipo_doc" name="tipo_doc">
                                    <option value="">Seleccione un tipo de documento</option>
                                    <option value="DNI">DNI</option>
                                    <option value="LC">LC</option>
                                    <option value="LE">LE</option>
                                    <option value="CI">CI</option>
                                    <option value="PAS">Pasaporte</option>
                                    <option value="OTRO">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="nro_doc">Número de Documento:*</label>
                                <input type="number" class="form-control" id="nro_doc" name="nro_doc">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="admision">Fecha de Admisión:*</label>
                                <input type="date" class="form-control" id="admision" name="admision">
                            </div>
                            <div class="col-md-2 form-group mb-3">
                                <label for="hora_admision">Hora*:</label>
                                <input type="time" class="form-control" id="hora_admision" name="hora_admision">
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label for="modalidad_act">Modalidad Activa:*</label>
                                <select class="form-control" id="modalidad_act" name="modalidad_act">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="id_prof">Profesional:*</label>
                                <select class="form-control" id="id_prof" name="id_prof">
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>


                            <button type="button" class="btn btn-primary btn-custom-save"
                                id="guardarPacienteBtn">Guardar</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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


</body>

</html>