<?php
require_once "../../conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        </div>
        <!-- Campo de búsqueda -->
        <input type="text" id="searchInput" class="form-control mb-3"
            placeholder="Buscar por profesional o especialidad" onkeyup="filtrarTabla()">

        <table class="table table-striped table-bordered">
            <thead class="table-custom">
                <tr>
                    <th>Profesional</th>
                    <th>Especialidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php if ($resultProfesionales->num_rows > 0): ?>
                    <?php while ($rowProfesional = $resultProfesionales->fetch_assoc()): ?>
                        <tr>
                            <td><?= $rowProfesional['nombreYapellido'] ?></td>
                            <td><?= $rowProfesional['desc_especialidad'] ?></td>
                            <td>
                                <button class="btn btn-primary"
                                    onclick="gestionarDisponibilidad(<?= $rowProfesional['id_prof'] ?>, '<?= $rowProfesional['nombreYapellido'] ?>')">
                                    Horarios de profesional
                                </button>

                                <button class="btn btn-primary"
                                    onclick="gestionarAusencias(<?= $rowProfesional['id_prof'] ?>, '<?= $rowProfesional['nombreYapellido'] ?>')">
                                    Dias NO laborables
                                </button>


                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No se encontraron profesionales.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para gestionar disponibilidad -->
    <div class="modal fade" id="gestionarDisponibilidadModal" tabindex="-1"
        aria-labelledby="gestionarDisponibilidadLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gestionarDisponibilidadLabel">Gestionar Disponibilidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formDisponibilidad" action="agregarDisponibilidad.php" method="POST">
                        <input type="hidden" id="id_prof" name="id_prof">

                        <div id="contenedor-disponibilidades-actuales" class="mb-3">
                            <h5>Disponibilidades actuales</h5>
                            <!-- Tabla para mostrar las disponibilidades -->
                            <table class="table table-bordered" id="tabla-disponibilidades">
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th>Desde</th>
                                        <th>Hasta</th>
                                        <th>Intervalo (min)</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="lista-disponibilidades">
                                    <!-- Las filas de disponibilidad se agregarán aquí dinámicamente -->
                                </tbody>
                            </table>
                        </div>

                        <div id="contenedor-horarios">
                            <!-- Aquí se agregarán dinámicamente los horarios -->
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal AUSENCIAS-->
    <div class="modal fade" id="ausenciasModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Gestión de Dias NO laborables</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6 id="profesionalInfo"></h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Motivo</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="ausenciasTableBody">
                            <!-- Las ausencias se cargarán aquí dinámicamente -->
                        </tbody>
                    </table>
                    <button class="btn btn-success mt-3" onclick="agregarAusencia()">Agregar Dia NO laborable</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Agregar Ausencia -->
    <div class="modal fade" id="agregarAusenciaModal" tabindex="-1" aria-labelledby="agregarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="agregarModalLabel">Agregar Dia NO laborable</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAgregarAusencia">
                        <input type="hidden" id="idProfAusencia" name="id_prof">
                        <div class="mb-3">
                            <label for="fechaInicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fechaInicio" name="fecha_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="fechaFin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fechaFin" name="fecha_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="motivo" class="form-label">Motivo</label>
                            <textarea class="form-control" id="motivo" name="motivo" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Ausencia -->
    <div class="modal fade" id="editarAusenciaModal" tabindex="-1" aria-labelledby="editarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarModalLabel">Editar Ausencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarAusencia">
                        <input type="hidden" id="idAusenciaEditar" name="id_ausencia">
                        <div class="mb-3">
                            <label for="fechaInicioEditar" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fechaInicioEditar" name="fecha_inicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="fechaFinEditar" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fechaFinEditar" name="fecha_fin" required>
                        </div>
                        <div class="mb-3">
                            <label for="motivoEditar" class="form-label">Motivo</label>
                            <textarea class="form-control" id="motivoEditar" name="motivo" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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

        function formatDate(dateString) {
            var parts = dateString.split('-');
            var year = parts[0];
            var month = parts[1];
            var day = parts[2];
            return day + "/" + month + "/" + year;
        }

        function gestionarAusencias(idProf, nombre) {
            // Actualiza información del profesional
            document.getElementById('profesionalInfo').innerText = `Ausencias de: ${nombre}`;
            document.getElementById('idProfAusencia').value = idProf;
            // Llama al servidor para obtener las ausencias
            fetch(`./ausencias/obtener_ausencias.php?id_prof=${idProf}`)
                .then(response => response.json())
                .then(data => {
                    const ausenciasTableBody = document.getElementById('ausenciasTableBody');
                    ausenciasTableBody.innerHTML = ''; // Limpia la tabla

                    // Llena la tabla con las ausencias
                    data.forEach(ausencia => {
                        ausenciasTableBody.innerHTML += `
                    <tr>
                        <td>${formatDate(ausencia.fecha_inicio)}</td>
                        <td>${formatDate(ausencia.fecha_fin)}</td>
                        <td>${ausencia.motivo}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editarAusencia(${ausencia.id})">Editar</button>
                            <button class="btn btn-danger btn-sm" onclick="eliminarAusencia(${ausencia.id})">Eliminar</button>
                        </td>
                    </tr>
                `;
                    });
                })
                .catch(error => console.error('Error al cargar ausencias:', error));

            // Muestra el modal
            const modal = new bootstrap.Modal(document.getElementById('ausenciasModal'));
            modal.show();
        }

        function agregarAusencia() {
            // Obtén el ID del profesional actual
            const idProf = document.getElementById('idProfAusencia').value;

            // Limpia el formulario
            document.getElementById('formAgregarAusencia').reset();

            // Muestra el modal
            const modal = new bootstrap.Modal(document.getElementById('agregarAusenciaModal'));
            modal.show();
        }

        // Manejar el envío del formulario
        document.getElementById('formAgregarAusencia').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('./ausencias/agregar_ausencia.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Muestra el resultado del servidor
                    const modal = bootstrap.Modal.getInstance(document.getElementById('agregarAusenciaModal'));
                    modal.hide();
                    document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove());

                    // Recarga la tabla de ausencias
                    const idProf = document.getElementById('idProfAusencia').value;
                    gestionarAusencias(idProf, document.getElementById('profesionalInfo').innerText.split(': ')[1]);
                })
                .catch(error => console.error('Error al agregar la ausencia:', error));
        });


        function editarAusencia(id) {
            // Llama al servidor para obtener los datos de la ausencia
            fetch(`./ausencias/obtener_ausencia.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    // Rellena el formulario del modal con los datos de la ausencia
                    document.getElementById('idAusenciaEditar').value = data.id;
                    document.getElementById('fechaInicioEditar').value = data.fecha_inicio;
                    document.getElementById('fechaFinEditar').value = data.fecha_fin;
                    document.getElementById('motivoEditar').value = data.motivo;

                    // Abre el modal
                    const modal = new bootstrap.Modal(document.getElementById('editarAusenciaModal'));
                    modal.show();
                })
                .catch(error => console.error('Error al cargar los datos de la ausencia:', error));
        }

        // Manejo del formulario para editar
        document.getElementById('formEditarAusencia').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('./ausencias/editar_ausencia.php', {
                method: 'POST',
                body: formData,
            })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Mensaje del servidor
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editarAusenciaModal'));
                    modal.hide(); // Oculta el modal después de guardar
                    location.reload(); // Recarga la lista de ausencias
                })
                .catch(error => console.error('Error al guardar los cambios:', error));
        });


        function eliminarAusencia(id) {
            if (confirm('¿Estás seguro de eliminar esta ausencia?')) {
                fetch(`./ausencias/eliminar_ausencias.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: id }),
                })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        location.reload();
                    })
                    .catch(error => console.error('Error al eliminar la ausencia:', error));
            }
        }



        document.addEventListener('DOMContentLoaded', function () {
            // El código de filtrado de tabla solo se ejecutará después de que el DOM esté cargado.
            function filtrarTabla() {
                var input = document.getElementById('searchInput');
                var filter = input.value.toLowerCase();

                var table = document.getElementById('tableBody'); // Aquí accedemos al tbody
                var rows = table.getElementsByTagName('tr'); // Obtenemos las filas de la tabla

                for (var i = 0; i < rows.length; i++) {
                    var tdProfesional = rows[i].getElementsByTagName('td')[0]; // Primer columna
                    var tdEspecialidad = rows[i].getElementsByTagName('td')[1]; // Segunda columna

                    if (tdProfesional && tdEspecialidad) {
                        var textProfesional = tdProfesional.textContent || tdProfesional.innerText;
                        var textEspecialidad = tdEspecialidad.textContent || tdEspecialidad.innerText;

                        // Comprobamos si el texto del profesional o de la especialidad coincide con el filtro
                        if (textProfesional.toLowerCase().indexOf(filter) > -1 || textEspecialidad.toLowerCase().indexOf(filter) > -1) {
                            rows[i].style.display = ''; // Mostramos la fila
                        } else {
                            rows[i].style.display = 'none'; // Ocultamos la fila
                        }
                    }
                }
            }

            // Asignar la función filtrarTabla al evento keyup
            document.getElementById('searchInput').addEventListener('keyup', filtrarTabla);
        });



        document.getElementById("formDisponibilidad").addEventListener("submit", function (e) {
            e.preventDefault(); // Evitar que se recargue la página

            // Crear un objeto FormData para enviar el formulario
            const formData = new FormData(this);

            // Usar fetch para enviar los datos al servidor
            fetch("agregarDisponibilidad.php", {
                method: "POST",
                body: formData
            })
                .then(response => response.json())  // Esperar respuesta JSON
                .then(data => {
                    console.log(data); // Para depurar la respuesta

                    // Iterar sobre la respuesta para mostrar los mensajes
                    data.forEach(item => {
                        if (item.success) {
                            alert(item.success);  // Mostrar mensaje de éxito
                        } else if (item.error) {
                            alert(item.error);  // Mostrar mensaje de error
                        }
                    });

                    // Si la respuesta tiene éxito, cerrar el modal y recargar la disponibilidad
                    if (data.some(item => item.success)) {
                        gestionarDisponibilidad(document.getElementById('id_prof').value);  // Recargar disponibilidades
                    }
                })
                .catch(error => {
                    console.error('Error:', error);  // Si hay un error en la solicitud
                    alert('Hubo un problema al procesar la solicitud.');
                });
        });


        function gestionarDisponibilidad(id_prof, nombreProfesional) {
            document.getElementById('id_prof').value = id_prof;
            document.getElementById('contenedor-horarios').innerHTML = '';
            document.getElementById('lista-disponibilidades').innerHTML = '';

            // Actualizar el título del modal con el nombre del profesional
            document.getElementById('gestionarDisponibilidadLabel').textContent = `Gestionar Disponibilidad de ${nombreProfesional}`;

            // Llamada AJAX para cargar disponibilidades existentes
            fetch(`./obtenerDisponibilidades.php?id_prof=${id_prof}`)
                .then(response => response.json())
                .then(data => {
                    const lista = document.getElementById('lista-disponibilidades');
                    if (data.length > 0) {
                        data.forEach(disponibilidad => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                        <td>${disponibilidad.dia_semana}</td>
                        <td>${disponibilidad.hora_inicio}</td>
                        <td>${disponibilidad.hora_fin}</td>
                        <td>${disponibilidad.intervalo} min</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="eliminarDisponibilidad(${disponibilidad.id})">Eliminar</button>
                        </td>
                    `;
                            lista.appendChild(row);
                        });
                    } else {
                        const row = document.createElement('tr');
                        row.innerHTML = `<td colspan="5" class="text-center">No hay disponibilidades registradas.</td>`;
                        lista.appendChild(row);
                    }
                })
                .catch(error => console.error('Error al cargar disponibilidades:', error));

            agregarHorario(); // Agregar un horario inicial para agregar nuevas disponibilidades
            $('#gestionarDisponibilidadModal').modal('show');
        }



        function eliminarDisponibilidad(id_disponibilidad) {
            if (!confirm("¿Estás seguro de que deseas eliminar esta disponibilidad?")) {
                return;
            }

            fetch('eliminarDisponibilidad.php', {
                method: 'POST',
                body: JSON.stringify({ id: id_disponibilidad }), // Enviar el id en el cuerpo de la solicitud
                headers: {
                    'Content-Type': 'application/json'  // Especificar que estamos enviando JSON
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                        gestionarDisponibilidad(document.getElementById('id_prof').value); // Recargar disponibilidades
                    } else {
                        alert(data.error || 'Error al eliminar la disponibilidad.');
                    }
                })
                .catch(error => console.error('Error al eliminar disponibilidad:', error));
        }


        function agregarHorario() {
            const contenedor = document.getElementById('contenedor-horarios');
            const index = contenedor.children.length;
            const html = `
        <div class="horario-group" style="margin-bottom: 20px;">
            <div class="form-group">
                <label for="dia_${index}">Día:</label>
                <select class="form-control" id="dia_${index}" name="horarios[${index}][dia]">
                    <option value="lunes">Lunes</option>
                    <option value="martes">Martes</option>
                    <option value="miercoles">Miércoles</option>
                    <option value="jueves">Jueves</option>
                    <option value="viernes">Viernes</option>
                    <option value="sabado">Sábado</option>
                </select>
            </div>
            <div class="form-group">
                <label for="inicio_${index}">Hora Inicio:</label>
                <input type="time" class="form-control" id="inicio_${index}" name="horarios[${index}][inicio]" required>
            </div>
            <div class="form-group">
                <label for="fin_${index}">Hora Fin:</label>
                <input type="time" class="form-control" id="fin_${index}" name="horarios[${index}][fin]" required>
            </div>
            <div class="form-group">
                <label for="intervalo_${index}">Intervalo (min):</label>
                <input type="number" class="form-control" id="intervalo_${index}" name="horarios[${index}][intervalo]" value="20" required>
            </div>
        </div>`;
            contenedor.insertAdjacentHTML('beforeend', html);
        }

        function eliminarHorario(button) {
            button.parentElement.remove();
        }





    </script>
</body>

</html>