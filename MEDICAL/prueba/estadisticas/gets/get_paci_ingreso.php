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
    CONCAT(m.codigo,' - ',m.descripcion) AS modalidad_full,
    p.admision
FROM paciente p
LEFT JOIN obra_social o   ON o.id = p.obra_social
LEFT JOIN paci_modalidad pM ON pM.id_paciente = p.id
LEFT JOIN modalidad m ON m.id = pM.modalidad
WHERE (p.admision BETWEEN ? AND ?) AND p.obra_social = ? AND pM.fecha = p.admision

ORDER BY p.admision ASC
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