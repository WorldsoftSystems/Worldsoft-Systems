<?php
require_once "../../conexion.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica si el usuario ha iniciado sesión
if (isset($_SESSION['usuario'])) {
    // El usuario ha iniciado sesión, puedes mostrar contenido para usuarios autenticados o ejecutar acciones específicas
} else {
    header("Location: ../../index.php");
}

// Verificar si se ha enviado el parámetro "eliminar"
if (isset($_GET['eliminar'])) {
    // Recibir el ID de la especialidad a eliminar
    $id = $_GET['eliminar'];

    // Preparar la consulta SQL para eliminar la especialidad
    $sql = "DELETE FROM medicacion WHERE id = ?";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Vincular parámetros
    $stmt->bind_param("i", $id);

    try {
        // Ejecutar la sentencia
        if ($stmt->execute()) {
            // Redirigir a la página después de eliminar
            header("Location: ./medicacion.php");
            exit();
        } else {
            throw new Exception($stmt->error);
        }
    } catch (mysqli_sql_exception $e) {
        // Verificar si el error es de restricción de clave externa
        if ($e->getCode() == 1451) {
            // Error de restricción de clave externa
            echo "<script>
                    alert('Error al eliminar, actividad relacionada a un paciente');
                    window.location.href = './medicacion.php';
                  </script>";
        } else {
            // Otro error
            echo "Error al eliminar la especialidad: " . $e->getMessage();
        }
    }

    // Cerrar la sentencia
    $stmt->close();
}

// No cerrar la conexión aquí si planeas usarla después en el mismo script
// $conn->close();




// Verificar si se ha enviado el parámetro "id" para la edición
if (isset($_GET['id'])) {
    // Obtener el ID de la especialidad a editar
    $id = $_GET['id'];
    // Consultar la especialidad con el ID proporcionado
    $sql = "SELECT * FROM medicacion WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // Obtener los datos de la especialidad
        $row = $result->fetch_assoc();
        // Pasar los datos de la especialidad al modal de edición
        echo "<script>editarMedicacion(" . json_encode($row) . ");</script>";
    }
    $stmt->close();
}


// Parámetros de búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Limitar a 100 registros
$limit = 100;

// Consulta SQL con búsqueda
$sql = "SELECT * FROM medicacion";
if (!empty($search)) {
    $sql .= " WHERE descripcion LIKE '%" . $conn->real_escape_string($search) . "%'";
}
$sql .= " ORDER BY descripcion ASC LIMIT $limit";

$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicacion</title>
    <!--icono pestana-->
    <link rel="icon" href="../../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../../img/logo.png" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../../estilos/styleBotones.css">
    <link rel="stylesheet" href="../../estilos/styleGeneral.css">

    <style>
        .overflow-auto {
            overflow-x: auto;
            /* Permite el desplazamiento horizontal */
            white-space: nowrap;
            /* Evita que los elementos se envuelvan */
            max-width: 100%;
            /* Ajusta el ancho según sea necesario */
        }

        #pagination {
            display: inline-flex;
            /* Mantiene los elementos en una fila */
            padding: 0;
            /* Remueve el padding por defecto */
            list-style-type: none;
            /* Remueve los puntos de la lista */
        }

        #pagination .page-item {
            margin: 5px;
            /* Espaciado entre los elementos */
        }
    </style>
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

            <h2>Medicacion</h2>
            <!-- Botón para agregar profesional -->
            <button type="button" class="btn btn-custom btn-lg" data-bs-toggle="modal"
                data-bs-target="#agregarMedicacionModal">
                Agregar Medicacion <img src="../../img/medicacion.png" alt="Icono agregar medicacion"
                    style="width: 50px; height: 50px; margin-left: 8px;">
            </button>
        </div>

        <!-- Campo de búsqueda -->
        <input type="text" id="searchInput" placeholder="Buscar medicamento..." class="form-control"
            onkeydown="buscarMedicacion(event)">


        <table class="table table-striped table-bordered">
            <thead class="table-custom">
                <tr>
                    <th>ID</th>
                    <th>Descripcion</th>
                    <th>Potencia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["id"] ?></td>
                            <td><?= $row["descripcion"] ?></td>
                            <td><?= $row["potencia"] ?></td>
                            <td>
                                <button class="btn btn-custom-editar" onclick='editarMedicacion(<?= json_encode($row) ?>)'><i
                                        class="fas fa-pencil-alt"></i></button>
                                <a href="?eliminar=<?= $row['id'] ?>" class="btn btn-danger"
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta medicacion?');"><i
                                        class="fas fa-trash-alt"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No se encontraron resultados</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


    </div>

    <!-- Modal para agregar/editar especialidad -->
    <div class="modal fade" id="agregarMedicacionModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Medicacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formMedicacion" action="./agregarMedicacion.php" method="POST">
                        <input type="hidden" id="id" name="id">
                        <div class="form-group">
                            <label for="descripcion">Descripcion</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                        </div>
                        <div class="form-group">
                            <label for="potencia">Potencia</label>
                            <input type="text" class="form-control" id="potencia" name="potencia" required>
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

        // Función para buscar medicación al presionar Enter
        function buscarMedicacion(event) {
            if (event.key === 'Enter') {
                const searchValue = document.getElementById('searchInput').value;
                window.location.href = "?search=" + encodeURIComponent(searchValue);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('success') && urlParams.get('success') === 'true') {
                alert("La medicacion se ha editado correctamente.");
                // Eliminar el parámetro de la URL
                urlParams.delete('success');
                // Actualizar la URL sin recargar la página
                window.history.replaceState({}, document.title, window.location.pathname);
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            function editarMedicacion(medicacion) {
                document.getElementById('formMedicacion').action = './editarMedicacion.php'; // Asegúrate de que el formulario apunta a la URL correcta

                // Rellenar los campos del formulario con los datos de la especialidad
                // Rellenar los campos del formulario con los datos de la especialidad
                document.getElementById('id').value = medicacion.id;
                document.getElementById('descripcion').value = medicacion.descripcion;
                document.getElementById('potencia').value = medicacion.potencia;

                // Mostrar el modal de edición
                var modal = new bootstrap.Modal(document.getElementById('agregarMedicacionModal'));
                modal.show();
            }

            // Función para limpiar el formulario
            function limpiarFormulario() {
                document.getElementById('formMedicacion').action = './agregarMedicacion.php';
                document.getElementById('id').value = '';
                document.getElementById('descripcion').value = '';
                document.getElementById('potencia').value = '';
            }

            // Adjuntar la función de edición al alcance global
            window.editarMedicacion = editarMedicacion;

            // Limpiar el formulario al abrir el modal para agregar profesional
            var btnAgregarMedicacionModal = document.querySelector('button[data-bs-target="#agregarMedicacionModal"]');
            btnAgregarMedicacionModal.addEventListener('click', limpiarFormulario);


        });
    </script>


</body>

</html>