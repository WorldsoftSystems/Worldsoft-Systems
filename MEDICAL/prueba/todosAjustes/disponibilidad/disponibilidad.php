<?php
require_once "../../conexion.php";

session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: ../../index.php");
    exit();
}

// Verificar si se ha enviado el parámetro "eliminar"
if (isset($_GET['eliminar'])) {
    $id_disponibilidad = $_GET['eliminar'];
    $sql = "DELETE FROM disponibilidad WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_disponibilidad);

    try {
        if ($stmt->execute()) {
            header("Location: ./disponibilidad.php");
            exit();
        } else {
            throw new Exception($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        echo "Error al eliminar la disponibilidad: " . $e->getMessage();
    }
    $stmt->close();
}

// Verificar si se ha enviado el parámetro "id" para la edición

if (isset($_GET['id'])) {
    $id_disponibilidad = $_GET['id'];
    $sql = "SELECT * FROM disponibilidad WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_disponibilidad);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Dividir la cadena de días en un array
        $dias_seleccionados = !empty($row["dia_semana"]) ? explode(",", $row["dia_semana"]) : [];
        // Convertir los días seleccionados en un objeto con los horarios correspondientes
        $horarios = [];
        foreach ($dias_seleccionados as $dia) {
            // Verificar si existe una hora de inicio y fin para este día
            if (!empty($row["hora_inicio_$dia"]) && !empty($row["hora_fin_$dia"])) {
                $horarios[$dia] = [
                    'enabled' => true,
                    'hora_inicio' => $row["hora_inicio_$dia"],
                    'hora_fin' => $row["hora_fin_$dia"]
                ];
            } else {
                // Si no hay hora de inicio y fin para este día, establecer el valor predeterminado
                $horarios[$dia] = [
                    'enabled' => false,
                    'hora_inicio' => "",
                    'hora_fin' => ""
                ];
            }
        }
        // Pasar los días seleccionados y sus horarios al formulario de edición
        echo "<script>editarDisponibilidad(" . json_encode($row) . ", " . json_encode($horarios) . ");</script>";
    }

    $stmt->close();
}





// Obtener todas las disponibilidades
$sql = "SELECT * FROM disponibilidad d 
        JOIN profesional p ON d.id_prof = p.id_prof 
        JOIN especialidad e ON p.id_especialidad = e.id_especialidad";

$result = $conn->query($sql);

// Obtener la lista de profesionales
$sqlProfesionales = "SELECT * FROM profesional p JOIN especialidad e ON p.id_especialidad=e.id_especialidad";
$resultProfesionales = $conn->query($sqlProfesionales);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disponibilidad</title>
    <link rel="icon" href="../../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../estilos/styleGeneral.css">
    <link rel="stylesheet" href="../../estilos/styleBotones.css">
</head>

<body>
    <button class="button" style="vertical-align:middle; margin-left:7rem"
        onclick="window.location.href = '../../seccionAjustes/ajustes.php';">
        <span>VOLVER</span>
    </button>

    <div class="text-center my-4">
        <img src="../../img/logo.png" alt="Logo MEDICAL" class="img-fluid" style="max-width: 120px;">
    </div>

    <div class="container">
        <div class="title-container">
            <h2>Disponibilidad</h2>
            <button type="button" class="btn btn-custom btn-lg" data-bs-toggle="modal"
                data-bs-target="#agregarDisponibilidadModal">
                Agregar Disponibilidad <img src="../../img/iconoCrearEsp.png" alt="Icono Crear Disponibilidad"
                    style="width: 50px; height: 50px; margin-left: 8px;">
            </button>
        </div>
        <table class="table table-striped table-bordered">
            <thead class="table-custom">
                <tr>
                    <th>ID</th>
                    <th>Profesional</th>
                    <th>Día de la Semana</th>
                    <th>Hora de Inicio</th>
                    <th>Hora de Fin</th>
                    <th>Intervalo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>

                        <tr>
                            <td><?= $row["id"] ?></td>
                            <td style="display: none;"><?= $row["id_prof"] ?></td>
                            <td><?= $row["nombreYapellido"] . ' - ' . $row["desc_especialidad"] ?></td>
                            <td><?= $row["dia_semana"] ?></td>
                            <td><?= $row["hora_inicio"] ?></td>
                            <td><?= $row["hora_fin"] ?></td>
                            <td><?= $row["intervalo"] ?></td>
                            <td>
                                <button class="btn btn-custom-editar"
                                    onclick='editarDisponibilidad(<?= json_encode($row) ?>)'><i
                                        class="fas fa-pencil-alt"></i></button>

                                <a href="?eliminar=<?= $row['id'] ?>" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta disponibilidad?');"><i
                                        class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No se encontraron resultados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="agregarDisponibilidadModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Disponibilidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formDisponibilidad" action="./agregarDisponibilidad.php" method="POST">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="id_prof">Profesional</label>
                            <select class="form-control" id="id_prof" name="id_prof" required>
                                <option value="">Seleccione un profesional</option>
                                <?php while ($rowProfesional = $resultProfesionales->fetch_assoc()): ?>
                                    <option value="<?= $rowProfesional['id_prof'] ?>">
                                        <?= $rowProfesional['nombreYapellido'] . ' - ' . $rowProfesional['desc_especialidad'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Desde</th>
                                        <th>Hasta</th>
                                        <th>Intervalo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Lunes</td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_inicio_lunes"
                                                name="horario_inicio_lunes">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_fin_lunes"
                                                name="horario_fin_lunes">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="intervalo_lunes"
                                                name="intervalo_lunes">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Martes</td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_inicio_martes"
                                                name="horario_inicio_martes">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_fin_martes"
                                                name="horario_fin_martes">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="intervalo_martes"
                                                name="intervalo_martes">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Miércoles</td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_inicio_miercoles"
                                                name="horario_inicio_miercoles">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_fin_miercoles"
                                                name="horario_fin_miercoles">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="intervalo_miercoles"
                                                name="intervalo_miercoles">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Jueves</td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_inicio_jueves"
                                                name="horario_inicio_jueves">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_fin_jueves"
                                                name="horario_fin_jueves">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="intervalo_jueves"
                                                name="intervalo_jueves">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Viernes</td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_inicio_viernes"
                                                name="horario_inicio_viernes">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_fin_viernes"
                                                name="horario_fin_viernes">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="intervalo_viernes"
                                                name="intervalo_viernes">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sábado</td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_inicio_sabado"
                                                name="horario_inicio_sabado">
                                        </td>
                                        <td>
                                            <input type="time" class="form-control" id="horario_fin_sabado"
                                                name="horario_fin_sabado">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" id="intervalo_sabado"
                                                name="intervalo_sabado">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary btn-custom-save">Guardar</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>








    <footer class="bg-dark text-white text-center py-4 mt-auto">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12 footer-logo-text">
                    <img src="../../img/logoWSS.png" alt="Logo" class="img-fluid" style="max-height: 50px;">
                    <p class="mb-0">&copy; 2024 WorldsoftSystems. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === 'true') {
                alert("La disponibilidad se ha editado correctamente.");
                urlParams.delete('success');
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            var dia;

            function editarDisponibilidad(disponibilidad) {
                // Limpiar campos del formulario
                document.getElementById('formDisponibilidad').reset();

                // Cambiar el título del modal
                document.getElementById('exampleModalLabel').innerText = 'Editar Disponibilidad';

                // Cambiar el texto del botón de guardar
                document.querySelector('.btn-custom-save').innerText = 'Guardar Cambios';

                // Rellenar el formulario con los datos de la disponibilidad seleccionada
                document.getElementById('id').value = disponibilidad.id;
                document.getElementById('id_prof').value = disponibilidad.id_prof;

                // Manipular las horas para eliminar los segundos
                var hora_inicio = disponibilidad.hora_inicio.split(':').slice(0, 2).join(':');
                var hora_fin = disponibilidad.hora_fin.split(':').slice(0, 2).join(':');
                dia = disponibilidad.dia_semana;

                // Asignar los valores de tiempo a los campos correspondientes
                document.getElementById('horario_inicio_' + dia).value = hora_inicio;
                document.getElementById('horario_fin_' + dia).value = hora_fin;
                document.getElementById('intervalo_' + dia).value = disponibilidad.intervalo;

                // Mostrar el modal
                var modal = new bootstrap.Modal(document.getElementById('agregarDisponibilidadModal'));
                modal.show();
            }

            function limpiarFormulario() {
                // Restaurar el título original del modal
                document.getElementById('exampleModalLabel').innerText = 'Agregar Disponibilidad';

                // Restaurar el texto original del botón de guardar
                document.querySelector('.btn-custom-save').innerText = 'Guardar';

                // Limpiar los campos del formulario
                document.getElementById('id').value = '';
                document.getElementById('id_prof').value = '';
                if (dia) {
                    document.getElementById('horario_inicio_' + dia).value = '';
                    document.getElementById('horario_fin_' + dia).value = '';
                    document.getElementById('intervalo_' + dia).value = '';
                }
            }

            window.editarDisponibilidad = editarDisponibilidad;

            var btnAgregarDisponibilidadModal = document.querySelector('button[data-bs-target="#agregarDisponibilidadModal"]');
            btnAgregarDisponibilidadModal.addEventListener('click', limpiarFormulario);
        });




    </script>
</body>

</html>