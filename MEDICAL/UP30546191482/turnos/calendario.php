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
            cursor: pointer;
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
                    <table id="schedule" class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Motivo de Consulta</th>
                                <th>Llegó</th>
                                <th>Atendido</th>
                                <th>Observaciones</th>
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
                                required readonly>
                            <input type="hidden" id="paciente_id_edit" name="paciente_id_edit">
                            <!-- Campo oculto para el ID -->
                            <button type="button" class="btn btn-custom" data-bs-toggle="modal"
                                data-bs-target="#buscarPacientesModal">
                                <i class="bi bi-search"></i>
                            </button>
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
                            <label for="fecha_input">Fecha</label>
                            <input type="text" class="form-control" id="fecha_input" name="fecha_input" required>
                        </div>
                        <div class="form-group">
                            <label for="hora_input">Hora</label>
                            <input type="text" class="form-control" id="hora_input" name="hora_input" required>
                        </div>

                        <div class="form-group d-flex align-items-center mt-3">
                            <label for="paciente_input" class="mr-2">Paciente</label>
                            <input type="text" class="form-control mr-2" id="paciente_input" name="paciente_input"
                                required readonly>
                            <input type="hidden" id="paciente_id" name="paciente_id">
                            <!-- Campo oculto para el ID -->
                            <button type="button" class="btn btn-custom" data-bs-toggle="modal"
                                data-bs-target="#buscarPacientesModal">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <div class="form-group">
                            <label for="motivo" class="form-label">Motivo</label>
                            <select class="form-control" name="motivo" id="motivo_input">
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
                            <input type="text" class="form-control" id="id_paciente_turno" name="id_paciente_turno"
                                readonly>
                        </div>
                        <button type="submit" class="btn btn-custom">Confirmar Turno</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL BUSCAR PACIENTES -->
    <div class="modal fade" id="buscarPacientesModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Buscar Pacientes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Barra de búsqueda -->
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="searchInput" placeholder="Buscar paciente..."
                                aria-label="Buscar paciente...">
                            <button class="btn btn-primary btn-custom" type="button">
                                <i class="bi bi-search "></i>
                            </button>
                        </div>
                    </div>
                    <!-- Lista de pacientes -->
                    <div id="patientList" class="patient-list">
                        <!-- La lista de pacientes se cargará dinámicamente aquí -->
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- Botón para cerrar y abrir otro modal -->
                    <button type="button" class="btn btn-secondary" id="closeAndOpenModal">Cerrar</button>
                    <button type="button" class="btn btn-primary btn-custom-save" id="addPatient">Agregar
                        Paciente</button>
                </div>
            </div>
        </div>
    </div>




    <!-- MODAL PACIENTE -->
    <div class="modal fade" id="agregarPacienteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="formPaciente" action="./ABM/agregarPaciente.php" method="POST">
                        <input type="hidden" id="id" name="id">

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="obra_social">Obra Social:*</label>
                                <select class="form-control" id="obra_social" name="obra_social" required>
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="benef">Beneficiario(12):*</label>
                                <input type="number" class="form-control" id="benef" name="benef" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="parentesco">Parentesco(2):*</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="parentesco" name="parentesco" required>
                                    <div class="input-group-append" id="btnBuscar">
                                        <span class="input-group-text">
                                            <i class="bi bi-search"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="nombre">Nombre Y Apellido:*</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" readonly required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="fecha_nac">Fecha de Nacimiento:*</label>
                                <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="sexo">Sexo:*</label>
                                <select class="form-control" id="sexo" name="sexo" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="Femenino">Femenino</option>
                                    <option value="Masculino">Masculino</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="tipo_afiliado">Tipo de Afiliado:*</label>
                                <select class="form-control" id="tipo_afiliado" name="tipo_afiliado" required>
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="tipo_doc">Tipo de Doc.:*</label>
                                <input type="text" class="form-control" id="tipo_doc" name="tipo_doc" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="nro_doc">Número de Documento:*</label>
                                <input type="number" class="form-control" id="nro_doc" name="nro_doc" required>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="admision">Fecha de Admisión:*</label>
                                <input type="date" class="form-control" id="admision" name="admision" required>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="modalidad">Modalidad:*</label>
                                <select class="form-control" id="modalidad" name="modalidad" required>
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="id_prof">Profesional:*</label>
                                <select class="form-control" id="id_prof" name="id_prof" required>
                                    <option value="">Seleccionar...</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="op">Nº de Orden de prestacion:</label>
                                <input type="text" class="form-control" id="op" name="op">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="ocupacion">Ocupación:</label>
                                <input type="text" class="form-control" id="ocupacion" name="ocupacion">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="hijos">Hijos:</label>
                                <input type="number" class="form-control" id="hijos" name="hijos">
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="telefono">Teléfono:</label>
                                <input type="text" class="form-control" id="telefono" name="telefono">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="c_postal">Código Postal:</label>
                                <input type="number" class="form-control" id="c_postal" name="c_postal">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 form-group">
                                <label for="localidad">Localidad:</label>
                                <input type="text" class="form-control" id="localidad" name="localidad">
                            </div>
                            <div class="col-md-4 form-group">
                                <label for="partido">Partido:</label>
                                <input type="text" class="form-control" id="partido" name="partido">
                            </div>

                            <div class="col-md-4 form-group">
                                <label for="domicilio">Domicilio</label>
                                <input type="text" class="form-control" id="domicilio" name="domicilio">
                            </div>
                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-custom-save"
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