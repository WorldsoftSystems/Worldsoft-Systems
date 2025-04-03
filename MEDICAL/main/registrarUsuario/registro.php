<?php
// Conexión a la base de datos
require_once "../conexion.php";

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar el valor de 'inst'
$sql = "SELECT inst FROM parametro_sistema LIMIT 1";
$result = $conn->query($sql);

// Obtener el valor
$title = "Iniciar sesión"; // Valor por defecto
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = $row['inst'];
}

// Determinar cliente desde la sesión
$cliente = isset($_SESSION['up']) ? $_SESSION['up'] : null;

// Cerrar conexión
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <!--icono pestana-->
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        /* Estilo para centrar los botones */
        .center-buttons {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            gap: 20px;
        }
    </style>
</head>

<body>
    <div class="text-center">
        <h1><?php echo htmlspecialchars($title); ?></h1>
    </div>

    <!-- Contenedor de botones -->
    <!-- Contenedor de botones -->
    <div class="center-buttons">
        <?php if ($cliente === 'UP3054610431800'): ?>
            <button class="btn btn-primary" id="txt_ugl6.php">Generar TXT DE UGL 6</button>
            <button class="btn btn-primary" id="txt_ugl10.php">Generar TXT DE UGL 10</button>
            <button class="btn btn-primary" id="txt_ugl37.php">Generar TXT DE UGL 37</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#registroModal">Registrar Usuarios</button>

        <?php elseif ($cliente === 'UP3063207857500'): ?>
            <div class="columns-container">
                <!-- Columna de botones INT -->
                <div class="column">
                    <h3>INT</h3>
                    <button class="btn btn-primary" id="txt_ugl35_INT.php">Generar TXT DE UGL 35_INT</button>
                    <button class="btn btn-primary" id="txt_ugl08_INT.php">Generar TXT DE UGL 08_INT</button>
                    <button class="btn btn-primary" id="txt_ugl06_INT.php">Generar TXT DE UGL 06_INT</button>
                </div>

                <!-- Columna de botones AMB -->
                <div class="column">
                    <h3>AMB</h3>
                    <button class="btn btn-primary" id="txt_ugl35_AMB.php">Generar TXT DE UGL 35_AMB</button>
                    <button class="btn btn-primary" id="txt_ugl08_AMB.php">Generar TXT DE UGL 08_AMB</button>
                    <button class="btn btn-primary" id="txt_ugl06_AMB.php">Generar TXT DE UGL 06_AMB</button>
                </div>
            </div>
            <button class="btn btn-success" data-toggle="modal" data-target="#registroModal">Registrar Usuarios</button>

        <?php elseif ($cliente === 'UP3066408967600'): ?>
            <div class="columns-container">
                <!-- Columna de botones INT -->
                <div class="column">
                    <h3>INT</h3>
                    <button class="btn btn-primary" id="txt_pq0352_int.php">Generar TXT INT</button>
                </div>

                <!-- Columna de botones AMB -->
                <div class="column">
                    <h3>AMB</h3>
                    <button class="btn btn-primary" id="txt_pq0352_amb.php">Generar TXT AMB</button>
                </div>
            </div>
            <button class="btn btn-success" data-toggle="modal" data-target="#registroModal">Registrar Usuarios</button>

        <?php elseif ($cliente === 'UP3060454669500'): ?>
            <div class="columns-container">
                <div class="column">
                    <h3>06</h3>
                    <button class="btn btn-primary" id="txt_pq0231_ugl_6.php">Generar TXT UGL 06</button>
                </div>

                <div class="column">
                    <h3>37</h3>
                    <button class="btn btn-primary" id="txt_pq0231_ugl_37.php">Generar TXT UGL 37</button>
                </div>
            </div>
            <button class="btn btn-success" data-toggle="modal" data-target="#registroModal">Registrar Usuarios</button>

        <?php elseif ($cliente == 'UP3069149922304'): ?>
            <div class="columns-container">
                <!-- Columna de botones INT -->
                <div class="column">
                    <h3>INT</h3>
                    <button class="btn btn-primary" id="txt_pq0303_int.php">Generar TXT INT</button>
                </div>

                <!-- Columna de botones AMB -->
                <div class="column">
                    <h3>AMB</h3>
                    <button class="btn btn-primary" id="txt_pq0303_amb.php">Generar TXT AMB</button>
                </div>
            </div>

        <?php elseif ($cliente == 'UP3058060423000'): ?>
            <div class="columns-container">
                <!-- Columna de botones INT -->
                <div class="column">
                    <h3>UGL 06</h3>
                    <button class="btn btn-primary" id="pq0236_ugl_6.php">Generar TXT UGL_06</button>
                </div>

                <!-- Columna de botones AMB -->
                <div class="column">
                    <h3>UGL 10</h3>
                    <button class="btn btn-primary" id="pq0236_ugl_10.php">Generar TXT UGL_10</button>
                </div>
            </div>

        <?php elseif ($cliente == 'UP3060909879800'): ?>
            <div class="columns-container">
                <!-- Columna de botones INT -->
                <div class="column">
                    <h3>INT</h3>
                    <button class="btn btn-primary" id="txt_pq0106_int.php">Generar TXT INT</button>
                </div>

                <!-- Columna de botones AMB -->
                <div class="column">
                    <h3>AMB</h3>
                    <button class="btn btn-primary" id="txt_pq0106_amb.php">Generar TXT AMB</button>
                </div>
            </div>

        <?php else: ?>
            <button class="btn btn-primary" id="generate_txt.php">Generar TXT</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#registroModal">Registrar Usuarios</button>
        <?php endif; ?>
    </div>

    <!-- Tabla para mostrar los usuarios -->
    <div class="container mt-4">
        <h2>Lista de Usuarios</h2>
        <table class="table table-striped" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuario</th>
                    <th>Clave</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se llenarán los usuarios -->
            </tbody>
        </table>
    </div>

    <!-- Modal para el registro de usuarios -->
    <div class="modal fade" id="registroModal" tabindex="-1" role="dialog" aria-labelledby="registroModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registroModalLabel">Registro de un nuevo usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Formulario de registro de usuario -->
                    <form action="registrar.php" method="POST">
                        <div class="form-group">
                            <label for="usuario">Usuario:</label>
                            <input type="text" name="usuario" id="usuario" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="clave">Contraseña:</label>
                            <input type="password" name="clave" id="clave" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Crear usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        //TXT
        document.addEventListener('DOMContentLoaded', function () {

            const txtUgl = document.getElementById('generate_txt.php');
            if (txtUgl) {
                txtUgl.addEventListener('click', function () {
                    // Hacer la solicitud al servidor para obtener los datos
                    fetch('./gets/generate_txt.php')
                        .then(response => response.json())  // Convertir la respuesta en JSON
                        .then(data => {
                            // Crear un enlace temporal para descargar el archivo
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;  // Usar el nombre de archivo dinámico
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            //PQ0222
            const txtUgl10 = document.getElementById('txt_ugl10.php');
            if (txtUgl10) {
                txtUgl10.addEventListener('click', function () {
                    fetch('./gets/pq0222/txt_ugl10.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            // Comprobar si los botones de Generar TXT existen antes de agregar event listeners
            const txtUgl6 = document.getElementById('txt_ugl6.php');
            if (txtUgl6) {
                txtUgl6.addEventListener('click', function () {
                    fetch('./gets/pq0222/txt_ugl6.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txtUgl37 = document.getElementById('txt_ugl37.php');
            if (txtUgl37) {
                txtUgl37.addEventListener('click', function () {
                    fetch('./gets/pq0222/txt_ugl37.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN PQ0222


            //PQ2041 INT
            const txt_ugl35_INT = document.getElementById('txt_ugl35_INT.php');
            if (txt_ugl35_INT) {
                txt_ugl35_INT.addEventListener('click', function () {
                    fetch('./gets/pq0241/txt_ugl35_INT.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txt_ugl08_INT = document.getElementById('txt_ugl08_INT.php');
            if (txt_ugl08_INT) {
                txt_ugl08_INT.addEventListener('click', function () {
                    fetch('./gets/pq0241/txt_ugl08_INT.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txt_ugl06_INT = document.getElementById('txt_ugl06_INT.php');
            if (txt_ugl06_INT) {
                txt_ugl06_INT.addEventListener('click', function () {
                    fetch('./gets/pq0241/txt_ugl06_INT.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN PQ2041 INT

            //PQ2041 AMB
            const txt_ugl35_AMB = document.getElementById('txt_ugl35_AMB.php');
            if (txt_ugl35_AMB) {
                txt_ugl35_AMB.addEventListener('click', function () {
                    fetch('./gets/pq0241/txt_ugl35_AMB.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            const txt_ugl08_AMB = document.getElementById('txt_ugl08_AMB.php');
            if (txt_ugl08_AMB) {
                txt_ugl08_AMB.addEventListener('click', function () {
                    fetch('./gets/pq0241/txt_ugl08_AMB.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txt_ugl06_AMB = document.getElementById('txt_ugl06_AMB.php');
            if (txt_ugl06_AMB) {
                txt_ugl06_AMB.addEventListener('click', function () {
                    fetch('./gets/pq0241/txt_ugl06_AMB.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN PQ2041 AMB

            //PQ0352
            const txt_pq0352_amb = document.getElementById('txt_pq0352_amb.php');
            if (txt_pq0352_amb) {
                txt_pq0352_amb.addEventListener('click', function () {
                    fetch('./gets/pq0352/txt_pq0352_amb.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txt_pq0352_int = document.getElementById('txt_pq0352_int.php');
            if (txt_pq0352_int) {
                txt_pq0352_int.addEventListener('click', function () {
                    fetch('./gets/pq0352/txt_pq0352_int.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN PQ0352

            //PQ0231
            const txt_pq0231_ugl_6 = document.getElementById('txt_pq0231_ugl_6.php');
            if (txt_pq0231_ugl_6) {
                txt_pq0231_ugl_6.addEventListener('click', function () {
                    fetch('./gets/pq0231/txt_pq0231_ugl_6.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txt_pq0231_ugl_37 = document.getElementById('txt_pq0231_ugl_37.php');
            if (txt_pq0231_ugl_37) {
                txt_pq0231_ugl_37.addEventListener('click', function () {
                    fetch('./gets/pq0231/txt_pq0231_ugl_37.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN PQ0231

            //PQ0303
            const txt_pq0303_amb = document.getElementById('txt_pq0303_amb.php');
            if (txt_pq0303_amb) {
                txt_pq0303_amb.addEventListener('click', function () {
                    fetch('./gets/pq0303/txt_pq0303_amb.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txt_pq0303_int = document.getElementById('txt_pq0303_int.php');
            if (txt_pq0303_int) {
                txt_pq0303_int.addEventListener('click', function () {
                    fetch('./gets/pq0303/txt_pq0303_int.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN PQ0303

            //PQ0236
            const pq0236_ugl_6 = document.getElementById('pq0236_ugl_6.php');
            if (pq0236_ugl_6) {
                pq0236_ugl_6.addEventListener('click', function () {
                    fetch('./gets/pq0236/pq0236_ugl_6.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const pq0236_ugl_10 = document.getElementById('pq0236_ugl_10.php');
            if (pq0236_ugl_10) {
                pq0236_ugl_10.addEventListener('click', function () {
                    fetch('./gets/pq0236/pq0236_ugl_10.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN PQ0236

            //pq0106
            const txt_pq0106_amb = document.getElementById('txt_pq0106_amb.php');
            if (txt_pq0106_amb) {
                txt_pq0106_amb.addEventListener('click', function () {
                    fetch('./gets/pq0106/txt_pq0106_amb.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }

            const txt_pq0106_int = document.getElementById('txt_pq0106_int.php');
            if (txt_pq0106_int) {
                txt_pq0106_int.addEventListener('click', function () {
                    fetch('./gets/pq0106/txt_pq0106_int.php')
                        .then(response => response.json())
                        .then(data => {
                            const element = document.createElement('a');
                            const file = new Blob([data.content], { type: 'text/plain' });
                            element.href = URL.createObjectURL(file);
                            element.download = data.filename;
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        });
                });
            }
            //FIN pq0106

        });

    </script>

</body>

</html>