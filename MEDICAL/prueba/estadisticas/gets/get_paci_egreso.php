<?php
// Incluir el archivo de conexión
include ('../../conexion.php');

// Obtener los parámetros de la URL
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';
$obra_social = $_GET['obra_social'] ?? '';

// Verificar si los parámetros están definidos y no están vacíos
if (empty($fecha_desde) || empty($fecha_hasta)) {
    die(json_encode(['error' => 'Parámetros de fecha inválidos']));
}

// Consulta SQL
$sql = "SELECT DISTINCT
    CONCAT(p.nombre,' - ', o.siglas) AS nombre,
    p.benef,
    p.parentesco,
    e.fecha_egreso AS fecha_egreso,
    CONCAT(t.codigo,' - ',t.descripcion) AS motivo_egreso
FROM paciente p
LEFT JOIN egresos e ON e.id_paciente = p.id
LEFT JOIN obra_social o   ON o.id = p.obra_social
LEFT JOIN tipo_egreso t ON t.id = e.motivo
WHERE (e.fecha_egreso BETWEEN ? AND ?) AND p.obra_social = ?
";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Enlazar los parámetros
$stmt->bind_param("ssi", $fecha_desde, $fecha_hasta,$obra_social);

// Ejecutar la consulta
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

// Procesar los resultados de la consulta
$resumen = [];
while ($row = $result->fetch_assoc()) {
    $resumen[] = $row; // Almacenar cada fila en el array
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode($resumen);
?>