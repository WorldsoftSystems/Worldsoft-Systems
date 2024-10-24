<?php
require_once "../../conexion.php";

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID del paciente desde los parámetros GET
$pacienteId = isset($_GET['paciente_id']) ? $_GET['paciente_id'] : '';

if ($pacienteId === '') {
    die("ID del paciente no proporcionado.");
}

// Preparar y ejecutar la consulta para obtener las modalidades activas del paciente
$sqlModalidadesActivas = "SELECT pm.modalidad 
    FROM paci_modalidad pm 
    LEFT JOIN egresos e ON pm.id_paciente = e.id_paciente AND pm.modalidad = e.modalidad
    WHERE pm.id_paciente = ? AND e.id_paciente IS NULL
    GROUP BY pm.modalidad
    HAVING COUNT(e.id) = 0
";
$stmtModalidadesActivas = $conn->prepare($sqlModalidadesActivas);
$stmtModalidadesActivas->bind_param("i", $pacienteId);
$stmtModalidadesActivas->execute();
$resultModalidadesActivas = $stmtModalidadesActivas->get_result();

// Manejo de errores para la consulta de modalidades
if (!$resultModalidadesActivas) {
    die("Query failed: " . $conn->error);
}

// Obtener las modalidades activas
$modalidadesActivas = [];
if ($resultModalidadesActivas->num_rows > 0) {
    while ($row = $resultModalidadesActivas->fetch_assoc()) {
        $modalidadesActivas[] = $row['modalidad'];
    }
}

// Preparar y ejecutar la consulta para obtener las actividades filtradas por modalidad
if (!empty($modalidadesActivas)) {
    $modalidadesPlaceholders = implode(',', array_fill(0, count($modalidadesActivas), '?'));
    $sqlActividades = "SELECT * FROM actividades WHERE modalidad IN ($modalidadesPlaceholders)";
    $stmtActividades = $conn->prepare($sqlActividades);

    // Vincular los parámetros de las modalidades activas
    $types = str_repeat('i', count($modalidadesActivas)); // tipo de los parámetros (integer)
    $stmtActividades->bind_param($types, ...$modalidadesActivas);
    $stmtActividades->execute();
    $resultActividades = $stmtActividades->get_result();

    // Manejo de errores para la consulta de actividades
    if (!$resultActividades) {
        die("Query failed: " . $conn->error);
    }

    // Procesar los resultados de la consulta de actividades
    $actividades = [];
    if ($resultActividades->num_rows > 0) {
        while ($row = $resultActividades->fetch_assoc()) {
            $actividades[] = $row;
        }
    }

    // Cerrar la conexión a la base de datos
    $stmtActividades->close();
} else {
    $actividades = []; // No hay modalidades activas
}

// Cerrar la conexión a la base de datos
$stmtModalidadesActivas->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($actividades);
?>
