<?php
require_once '../controlador/control_paciente.php';

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
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesionales <?php echo htmlspecialchars($nombre); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Agregar el archivo CSS de Tailwind CSS -->
    <style>
        .image-top-right {
            position: absolute;
            top: 10px; /* Ajustar según la distancia desde la parte superior */
            right: 10px; /* Ajustar según la distancia desde el lado derecho */
            width: 9rem; /* Ancho deseado de la imagen */
            height: auto; /* Altura ajustada automáticamente según el ancho */
            
        }
    </style>
</head>

<body class="bg-gray-100">
     <!-- Botón para volver al panel -->
     <a href="../panelMain/panelMain.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Volver</a>

     <div class="container mx-auto px-4 py-8 relative"> <!-- Añadir relative para el posicionamiento absoluto -->
        <img src="../img/profesional.jpeg" alt="Imagen" class="image-top-right hidden sm:block">

        <h1 class="text-3xl font-bold mb-4">Profesionales <?php echo htmlspecialchars($nombre); ?></h1>

        <h2 class="text-2xl font-bold mb-2">Agregar Nuevo Profesionales</h2>
        <form method="post" class="mb-4">
            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad:</label>
                <select id="especialidad" name="especialidad" required
                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                    <option value="">Seleccionar Especialidad</option>
                    <option value="psiquiatria">Psiquiatría</option>
                    <option value="psicologia">Psicología</option>
                </select>
            </div>
            <button type="submit" name="agregar"
                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Agregar
            </button>
        </form>

        <hr class="border-t border-gray-400 my-8">

        <!-- Lista de profesionales existentes -->
        <hr class="border-t border-gray-400 my-8">
        <h2 class="text-2xl font-bold mb-2">Profesionales</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Apellido</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Especialidad</th>
                        <th class="px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once '../controlador/control_prof.php';
                    mysqli_set_charset($conn, "utf8");
                    $sql = "SELECT * FROM prof ORDER BY apellido,nombre";
                    $result = $conn->query($sql);
                    


                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                             echo "<td class='border px-4 py-2'>" . $row["apellido"] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row["nombre"] . "</td>";
                            echo "<td class='border px-4 py-2'>" . $row["especialidad"] . "</td>";
                            echo "<td class='border px-4 py-2'>
                            <a href='#' onclick='confirmDelete(" . $row["cod_prof"] . ")' class='text-red-600 hover:text-red-800'>Eliminar</a> | 
                            <a href='editarProfesional.php?editar=" . $row["cod_prof"] . "' target='_blank' class='text-blue-600 hover:text-blue-800'>Editar</a>
                          </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='border px-4 py-2'>No hay registros</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>



        <script>
            // Obtener el mensaje de alerta desde la sesión
            var alertMessage = '<?php echo isset($_SESSION["alert_message"]) ? $_SESSION["alert_message"] : ""; ?>';

            // Mostrar la alerta solo si hay un mensaje
            if (alertMessage !== "") {
                alert(alertMessage);
                // Limpiar el mensaje después de mostrarlo
                <?php unset($_SESSION["alert_message"]); ?>
            }

            function confirmDelete(id) {
                if (confirm("¿Estás seguro de que quieres eliminar este Profesional?")) {
                    window.location.href = "profesionalPanel.php?eliminar=" + id;
                }
            }

        </script>

    </div>
</body>

</html>