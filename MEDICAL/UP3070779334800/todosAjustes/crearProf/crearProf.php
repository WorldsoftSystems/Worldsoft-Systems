<?php
require_once "../../conexion.php";



session_start();

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    // El usuario ha iniciado sesión, puedes mostrar contenido para usuarios autenticados o ejecutar acciones específicas
} else {
    header("Location: ./../index.php");
}

// Comprobar si se ha enviado una solicitud de eliminación
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['eliminar'])) {
    // Obtener el ID del profesional a eliminar
    $id_profesional = $_GET['eliminar'];

    // Preparar la consulta SQL para eliminar el profesional
    $sql = "DELETE FROM profesional WHERE id_prof = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("i", $id_profesional);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        // Redireccionar a la misma página después de la eliminación
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error al intentar eliminar el profesional.";
    }
}


// Consulta SQL para obtener las especialidades
$sql_especialidades = "SELECT id_especialidad, desc_especialidad FROM especialidad";
$result_especialidades = $conn->query($sql_especialidades);

// Consulta SQL
$sql = "SELECT  p.*,e.id_especialidad, e.desc_especialidad
        FROM profesional p
        JOIN especialidad e ON p.id_especialidad = e.id_especialidad";
$result = $conn->query($sql);



?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesionales</title>
    <!--icono pestana-->
    <link rel="icon" href="../../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
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

            <h2>Profesionales</h2>
            <!-- Botón para agregar profesional -->
            <button type="button" class="btn btn-custom btn-lg" data-bs-toggle="modal"
                data-bs-target="#agregarProfesional">
                Agregar Profesional <img src="../../img/iconoCrearProf.png" alt="Icono Crear Profesional"
                    style="width: 50px; height: 50px; margin-left: 8px;">
            </button>


        </div>
        <table class="table table-striped table-bordered">
            <thead class="table-custom">
                <tr>
                    <th>Nombre y Apellido</th>
                    <th>Especialidad</th>
                    <th>Domicilio</th>
                    <th>Localidad</th>
                    <th>Código Postal</th>
                    <th>Matrícula Provincial</th>
                    <th>Matrícula Nacional</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Tipo doc.</th>
                    <th>Nro doc.</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Mostrar datos de cada fila
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                <td>" . $row["nombreYapellido"] . "</td>
                <td>" . $row["desc_especialidad"] . "</td>
                <td>" . $row["domicilio"] . "</td>
                <td>" . $row["localidad"] . "</td>
                <td>" . $row["codigo_pos"] . "</td>
                <td>" . $row["matricula_p"] . "</td>
                <td>" . $row["matricula_n"] . "</td>
                <td>" . $row["telefono"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["tipo_doc"] . "</td>
                <td>" . $row["nro_doc"] . "</td>
                <td>
                    <button type='button' class='btn btn-danger' onclick=\"if(confirm('¿Estás seguro de que deseas eliminar este profesional?')){ window.location.href='{$_SERVER['PHP_SELF']}?eliminar=" . $row['id_prof'] . "'; }\"><i class='fas fa-trash-alt'></i></button>
                    <button type='button' class='btn btn-custom-editar' onclick='editarProfesional(" . json_encode($row) . ")'><i class='fas fa-pencil-alt'></i></button>
                </td>
            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>No se encontraron resultados</td></tr>";
                }

                // Cerrar conexión
                $conn->close();
                ?>
            </tbody>
        </table>

    </div>

    <!-- Modal para agregar/editar profesional -->
    <div class="modal fade" id="agregarProfesional" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Profesional</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar/editar profesional -->
                    <form id="formProfesional" action="./agregarProf.php" method="POST">
                        <input type="hidden" id="id_prof" name="id_prof">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombreYapellido" class="form-label">Nombre y Apellido:</label>
                                    <input type="text" class="form-control" id="nombreYapellido" name="nombreYapellido">
                                </div>
                                <div class="mb-3">
                                    <label for="id_especialidad" class="form-label">Especialidad:</label>
                                    <select class="form-control" id="id_especialidad" name="id_especialidad">
                                        <option value="" disabled selected>Seleccionar especialidad...</option>
                                        <?php
                                        if ($result_especialidades->num_rows > 0) {
                                            while ($row_especialidad = $result_especialidades->fetch_assoc()) {
                                                echo "<option value='" . $row_especialidad['id_especialidad'] . "'>" . $row_especialidad['desc_especialidad'] . "</option>";
                                            }
                                        } else {
                                            echo "<option value=''>No hay especialidades disponibles</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="matricula_p" class="form-label">Matrícula Provincial:</label>
                                    <input type="text" class="form-control" id="matricula_p" name="matricula_p">
                                </div>
                                <div class="mb-3">
                                    <label for="matricula_n" class="form-label">Matrícula Nacional:</label>
                                    <input type="text" class="form-control" id="matricula_n" name="matricula_n">
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email:</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="localidad" class="form-label">Localidad:</label>
                                    <input type="text" class="form-control" id="localidad" name="localidad">
                                </div>
                                <div class="mb-3">
                                    <label for="domicilio" class="form-label">Domicilio:</label>
                                    <input type="text" class="form-control" id="domicilio" name="domicilio">
                                </div>
                                <div class="mb-3">
                                    <label for="codigo_pos" class="form-label">Código Postal:</label>
                                    <input type="text" class="form-control" id="codigo_pos" name="codigo_pos">
                                </div>
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono:</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono">
                                </div>

                                <div class="mb-3">
                                    <label for="tipo_doc">Tipo de Doc.:*</label>
                                    <select class="form-control" id="tipo_doc" name="tipo_doc" required>
                                        <option value="">Seleccione un tipo de documento</option>
                                        <option value="DNI">DNI (Documento Nacional de Identidad)</option>
                                        <option value="LC">LC (Libreta de Enrolamiento)</option>
                                        <option value="LE">LE (Libreta Cívica)</option>
                                        <option value="CI">CI (Cédula de Identidad)</option>
                                        <option value="PAS">PAS (Pasaporte)</option>
                                        <option value="OTRO">OTRO (Otro tipo de documento)</option>
                                    </select>

                                    <div class="mb-3">
                                        <label for="nro_doc">Número de Documento:*</label>
                                        <input type="number" class="form-control" id="nro_doc" name="nro_doc" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-custom-save">Guardar</button>
                </div>
                </form>
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


        <?php
        if (isset($_GET['editado']) && $_GET['editado'] == 'true' && isset($_SESSION['editado']) && $_SESSION['editado'] == true) {
            echo 'alert("¡Paciente editado correctamente!");';
            // Una vez mostrada la alerta, borramos la variable de sesión para que no se muestre nuevamente después de un nuevo reinicio de página
            unset($_SESSION['editado']);
        }
        ?>
        document.addEventListener("DOMContentLoaded", function () {
            function editarProfesional(profesional) {
                document.getElementById('formProfesional').action = './editarProf.php';
                document.getElementById('id_prof').value = profesional.id_prof;
                document.getElementById('nombreYapellido').value = profesional.nombreYapellido;
                document.getElementById('domicilio').value = profesional.domicilio;
                document.getElementById('localidad').value = profesional.localidad;
                document.getElementById('codigo_pos').value = profesional.codigo_pos;
                document.getElementById('matricula_p').value = profesional.matricula_p;
                document.getElementById('matricula_n').value = profesional.matricula_n;
                document.getElementById('telefono').value = profesional.telefono;
                document.getElementById('email').value = profesional.email;
                document.getElementById('tipo_doc').value = profesional.tipo_doc;
                document.getElementById('nro_doc').value = profesional.nro_doc;
                // Set the correct option in the select element
                let selectEspecialidad = document.getElementById('id_especialidad');
                for (let i = 0; i < selectEspecialidad.options.length; i++) {
                    if (selectEspecialidad.options[i].value == profesional.id_especialidad) {
                        selectEspecialidad.options[i].selected = true;
                        break;
                    }
                }

                var modal = new bootstrap.Modal(document.getElementById('agregarProfesional'));
                modal.show();
            }
            // Función para limpiar el formulario
            function limpiarFormulario() {
                document.getElementById('formProfesional').action = './agregarProf.php';
                document.getElementById('id_prof').value = '';
                document.getElementById('nombreYapellido').value = '';
                document.getElementById('domicilio').value = '';
                document.getElementById('localidad').value = '';
                document.getElementById('codigo_pos').value = '';
                document.getElementById('matricula_p').value = '';
                document.getElementById('matricula_n').value = '';
                document.getElementById('telefono').value = '';
                document.getElementById('email').value = '';
                document.getElementById('id_especialidad').selectedIndex = 0;
            }

            // Adjuntar la función de edición al alcance global
            window.editarProfesional = editarProfesional;

            // Limpiar el formulario al abrir el modal para agregar profesional
            var btnAgregarProfesional = document.querySelector('button[data-bs-target="#agregarProfesional"]');
            btnAgregarProfesional.addEventListener('click', limpiarFormulario);

        });




    </script>


</body>

</html>