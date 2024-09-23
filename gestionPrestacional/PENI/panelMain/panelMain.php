<?php
session_start();

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


// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit; // Asegura que el script se detenga después de redirigir
}

// Cierra la conexión
$conn->close();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prestaciones Worldsoft-Systems <?php echo htmlspecialchars($nombre); ?></title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex">

    <!-- Navbar lateral -->
    <div class="bg-gray-800 text-white h-screen w-64 flex flex-col justify-between">
        <div class="p-4">

            <!-- Aquí van tus elementos del navbar -->
            <h1 class="text-2xl font-bold mb-8">Administracion <?php echo htmlspecialchars($nombre); ?></h1>
            <ul class="flex flex-col justify-center">

                <li class="mb-4 flex items-center text-xl">
                    <i class="fas fa-clipboard-list mr-3"></i>
                    <a href="../pacientePanel/pacientePanel.php" class="hover:text-gray-300">Prestaciónes</a>
                </li>
                <li class="mb-4 flex items-center text-xl">
                    <i class="fas fa-chart-line mr-2"></i>
                    <a href="../tablaOME/loginTablaOME.php" class="hover:text-gray-300"> Estadísticas</a>
                </li>
                <li class="mb-4 flex items-center text-xl">
                    <i class="fas fa-user-md mr-2"></i>
                    <a href="../profesionalPanel/profesionalPanel.php" class="hover:text-gray-300">Profesionales</a>
                </li>

                <li class="mb-4 flex items-center text-xl">
                    <i class="fas fa-stethoscope mr-2"></i>
                    <a href="../tipoPracticaPanel/tipoPracticaPanel.php" class="hover:text-gray-300">Cód. Práctica</a>
                </li>


                <li class="mb-4 flex items-center text-xl">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <a href="../inicioSesion/logout.php" class="hover:text-gray-300">Cerrar sesión</a>
                </li>
            </ul>
        </div>
        <div class="p-4">
            <!-- Aquí pueden ir otros elementos del navbar o del pie de página -->
            <p class="text-sm">© 2024 Worldsoft-Systems</p>
        </div>
    </div>


    <!-- Contenedor principal -->
    <div class="flex flex-col min-h-screen">
        <!-- Logo arriba a la izquierda -->
        <img src="../img/logo.png" alt="Logo" class="w-24 h-auto mt-4 ml-4">
    </div>




    <!-- Contenido principal -->
    <div class="flex-1 p-8 flex flex-col items-center justify-center">
    <!-- Contenido de la página -->
    <div class="flex items-center flex-col mb-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión prestacional <?php echo htmlspecialchars($nombre); ?></h1>
    </div>
    <div class="flex-shrink-0 flex justify-center items-center">
        <img src="../img/main.jpeg" alt="Imagen"
            class="border border-gray-400 rounded-lg w-full h-auto max-w-2xl mb-8">
    </div>
    </div>









</body>

</html>