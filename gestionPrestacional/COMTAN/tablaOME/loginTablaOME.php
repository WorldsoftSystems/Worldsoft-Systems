<?php

require_once '../conexion/conexion.php';

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener el nombre de la tabla 'parametros'
$sql = "SELECT nombre FROM parametros LIMIT 1";  // Ajusta la consulta si es necesario
$result = $conn->query($sql);

$nombre = "";  // Valor por defecto si no se encuentra en la base de datos

if ($result->num_rows > 0) {
    // Extrae el nombre de la tabla si se encuentra el resultado
    $row = $result->fetch_assoc();
    $nombre = $row['nombre'];
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión <?php echo htmlspecialchars($nombre); ?></title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <!-- Enlace al archivo CSS de Tailwind -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        img {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-gray-100 h-screen flex flex-col items-center justify-center">

    <img src="../img/logo.png" alt="Logo" class="h-24 w-auto mb-8">

    <div class="bg-white p-8 rounded shadow-md w-96">

        <h2 class="text-2xl font-bold mb-4">Estadisticas <?php echo htmlspecialchars($nombre); ?></h2>
        <form id="loginForm" action="../inicioSesion/loginPanelOme.php" method="post">
            <div class="mb-4">
                <label for="usuario" class="block text-sm font-medium text-gray-700">Usuario:</label>
                <input type="text" id="usuario" name="usuario" required
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                <span id="usuarioError" class="error text-red-500"></span>
            </div>
            <div class="mb-4">
                <label for="clave" class="block text-sm font-medium text-gray-700">Contraseña:</label>
                <input type="password" id="clave" name="clave" required
                    class="mt-1 p-2 border border-gray-300 rounded-md w-full">
                <span id="claveError" class="error text-red-500"></span>
            </div>
            <div class="mt-4">
                <input type="submit" value="Iniciar Sesión"
                    class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 cursor-pointer">
            </div>
        </form>

        <?php
        if (isset($_GET['error'])) {
            // No necesitamos mostrar nada aquí ya que manejaremos el error con JavaScript
        }
        ?>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var urlParams = new URLSearchParams(window.location.search);
            var error = urlParams.get('error');

            if (error) {
                alert("Usuario o contraseña incorrectos.");
            }
        });

        document.getElementById('loginForm').addEventListener('submit', function (event) {
            // Obtener valores de los campos de entrada
            var usuario = document.getElementById('usuario').value.trim();
            var clave = document.getElementById('clave').value.trim();

            // Validar el campo de usuario
            if (usuario === '') {
                document.getElementById('usuarioError').innerText = 'Por favor, introduce tu usuario.';
                event.preventDefault(); // Evitar el envío del formulario
            } else {
                document.getElementById('usuarioError').innerText = '';
            }

            // Validar el campo de contraseña
            if (clave === '') {
                document.getElementById('claveError').innerText = 'Por favor, introduce tu contraseña.';
                event.preventDefault(); // Evitar el envío del formulario
            } else {
                document.getElementById('claveError').innerText = '';
            }
        });
    </script>
</body>

</html>
