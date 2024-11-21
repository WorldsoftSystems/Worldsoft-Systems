<?php
require_once '../controlador/control_paciente.php';
mysqli_set_charset($conn, "utf8");

// Verificar si el usuario está autenticado
if (!isset ($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit; // Asegura que el script se detenga después de redirigir
}

// Inicializar variables de filtro
$fecha_desde = isset ($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset ($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
$profesional = isset ($_GET['profesional']) ? $_GET['profesional'] : '';

// Obtener la lista de profesionales para el filtro
$profesionales = obtenerProfesionales();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'actualizar_estado_cargado') {
    // Asegura el formato JSON para la respuesta
    header('Content-Type: application/json');
    
    // Obtener y validar parámetros necesarios
    if (isset($_POST['cod_paci']) && isset($_POST['nuevo_estado'])) {
        $cod_paci = $_POST['cod_paci'];
        $nuevo_estado = $_POST['nuevo_estado'];
        actualizarEstadoCargado($cod_paci, $nuevo_estado);
    } else {
        echo json_encode(["success" => false, "message" => "Faltan parámetros"]);
    }
    exit; // Termina la ejecución para evitar conflictos
}




// Obtener el número de registros a mostrar
$numRegistros = isset($_GET['registros']) ? $_GET['registros'] : '250';

// Obtener pacientes con filtros aplicados y limitando el número de registros
$pacientes = obtenerPacientesConFiltroEstadisticas($fecha_desde, $fecha_hasta, $profesional, $numRegistros);

// Calcular el total de pacientes después de aplicar el filtro de fechas
$totalPacientes = count($pacientes);

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
    <title>Prestaciones <?php echo htmlspecialchars($nombre); ?> </title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link rel="shortcut icon" href="../img/logo.png" type="image/x-icon">
    <!-- Enlace al archivo de Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .image-top-right {
            position: absolute;
            top: 10px;
            /* Ajustar según la distancia desde la parte superior */
            right: 10px;
            /* Ajustar según la distancia desde el lado derecho */
            width: 5rem;
            /* Ancho deseado de la imagen */
            height: auto;
            /* Altura ajustada automáticamente según el ancho */
            border-radius: 2px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Botón para volver al panel -->
    <a href="../panelMain/panelMain.php"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">Volver</a>

    <!-- Lista de pacientes existentes -->
    <hr class="border-t border-gray-400 my-8">
    <div class="container mx-auto px-4 py-8 relative"> <!-- Añadir relative para el posicionamiento absoluto -->
        <img src="../img/exportar_excel.bmp" alt="Imagen" class="image-top-right hidden sm:block">
        <h2 class="text-3xl font-semibold mb-4">Control de prestaciones cargadas <?php echo htmlspecialchars($nombre); ?></h2>

        <div class="mb-4">
            <a href="generar_reporte_excel.php?fecha_desde=<?php echo $fecha_desde; ?>&fecha_hasta=<?php echo $fecha_hasta; ?>&profesional=<?php echo $profesional; ?>"
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Exportar a Excel</a>
        </div>

        <div class="mb-4">
            <a href="generar_reporte_pdf.php?fecha_desde=<?php echo $fecha_desde; ?>&fecha_hasta=<?php echo $fecha_hasta; ?>&profesional=<?php echo $profesional; ?>"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Exportar a PDF</a>
        </div>


        <!-- Formulario de filtro -->
        <form method="GET" class="mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <label for="fecha_desde" class="block font-semibold">Desde:</label>
                    <input type="date" id="fecha_desde" name="fecha_desde"
                        class="border border-gray-300 rounded px-3 py-2 w-full">
                </div>
                <div>
                    <label for="fecha_hasta" class="block font-semibold">Hasta:</label>
                    <input type="date" id="fecha_hasta" name="fecha_hasta"
                        class="border border-gray-300 rounded px-3 py-2 w-full">
                </div>

                <div>
                    <label for="profesional" class="block font-semibold">Profesional:</label>
                    <select name="profesional" id="profesional"
                        class="border border-gray-300 rounded px-3 py-2 w-full">
                        <option value="">Todos</option>
                        <?php 
                        // Función de comparación para ordenar alfabéticamente por apellido y luego por nombre
                        function compararProfesionales($a, $b) {
                            // Comparar por apellido
                            $resultado = strcmp($a['apellido'], $b['apellido']);

                            // Si los apellidos son iguales, comparar por nombre
                            if ($resultado == 0) {
                                $resultado = strcmp($a['nombre'], $b['nombre']);
                            }

                            return $resultado;
                        }

                        // Ordenar la lista de profesionales
                        usort($profesionales, 'compararProfesionales');

                        // Mostrar los profesionales ordenados
                        foreach ($profesionales as $profesional_item):
                        ?>
                        <option value="<?php echo $profesional_item['cod_prof']; ?>"
                            <?php echo ($profesional == $profesional_item['cod_prof']) ? 'selected' : ''; ?>>
                            <?php echo $profesional_item['apellido'] . ' ' . $profesional_item['nombre']; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>






                <div class="flex items-end">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Filtrar</button>
                </div>
                
                 <div>
                    <label for="registros" class="block font-semibold">Mostrar:</label>
                    <select name="registros" id="registros" class="border border-gray-300 rounded px-3 py-2 w-full">
                        <option value="250" <?php echo (!isset($_GET['registros']) || $_GET['registros'] == '250') ? 'selected' : ''; ?>>250</option>
                        <option value="500" <?php echo (isset($_GET['registros']) && $_GET['registros'] == '500') ? 'selected' : ''; ?>>500</option>
                        <option value="1000" <?php echo (isset($_GET['registros']) && $_GET['registros'] == '1000') ? 'selected' : ''; ?>>1000</option>
                        <option value="todos" <?php echo (isset($_GET['registros']) && $_GET['registros'] == 'todos') ? 'selected' : ''; ?>>Todos</option>
                    </select>
                </div>
                
            </div>
        </form>
        <p class="mb-4">Total de pacientes:
            <?php echo $totalPacientes; ?>
        </p>

        <div class="overflow-x-auto">
            <table class="table-auto w-full">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Nombre y Apellido</th>
                        <th class="px-4 py-2">Beneficio</th>
                        <th class="px-4 py-2">Profesional</th>
                        <th class="px-4 py-2">Especialidad</th>
                        <th class="px-4 py-2">Diagnostico</th>
                        <th class="px-4 py-2">Fecha</th>
                        <th class="px-4 py-2">Cargado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Obtener pacientes con filtros aplicados
                    $pacientes = obtenerPacientesConFiltroEstadisticas($fecha_desde, $fecha_hasta, $profesional, $numRegistros);
                    foreach ($pacientes as $paciente) {
                        echo "<tr>";
                        echo "<td class='border px-4 py-2'>" . $paciente["nombreYapellido"] . "</td>";
                        echo "<td class='border px-4 py-2'>" . $paciente["benef"] . "</td>";
                        echo "<td class='border px-4 py-2'>" . obtenerNombreProfesional($paciente["cod_prof"]) . "</td>";
                        echo "<td class='border px-4 py-2'>" . obtenerEspecialidadProfesional($paciente["cod_prof"]) . "</td>";
                        echo "<td class='border px-4 py-2'>" . $paciente["cod_diag"] . "</td>";
                        echo "<td class='border px-4 py-2'>" . date('d/m/Y H:i:s', strtotime($paciente["fecha"])) . "</td>";
                        echo "<td class='border px-4 py-2'>";

                        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'>";  // Asegúrate de usar la misma URL
                        echo "<input type='hidden' name='cod_paci' value='" . $paciente["cod_paci"] . "'>";
                        echo "<input type='hidden' name='action' value='actualizar_estado_cargado'>"; // Añadir identificador de acción
                        echo "<select name='nuevo_estado' onchange='this.form.submit()'>";
                        echo "<option value=' '" . ($paciente["cargado"] == 'no_cargado' ? " selected" : "") . ">No cargado</option>";
                        echo "<option value='cargado'" . ($paciente["cargado"] == 'cargado' ? " selected" : "") . ">Cargado</option>";
                        echo "</select>";
                        echo "</form>";


                        
                         // Botón para generar QR
                        echo "<td class='border px-4 py-2'>";
                        echo "<form method='post' action='qr.php' target='_blank'> ";
                        echo "<input type='hidden' name='beneficio' value='" . $paciente["benef"] . "'>"; // Pasar el número de beneficio como un campo oculto
                        echo "<button type='submit'>Generar QR</button>";
                        echo "</form>";
                        echo "</td>";

                        echo "</tr>";
                        
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
        </div>

</body>

</html>