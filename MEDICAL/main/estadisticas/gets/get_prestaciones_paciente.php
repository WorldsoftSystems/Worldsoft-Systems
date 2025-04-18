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

// Consulta SQL para agrupar prestaciones por paciente
$sql = "SELECT 
    p.id AS paciente_id,
    CONCAT(p.nombre,' - ', o.siglas) AS nombre,
    p.benef,
    p.parentesco,
    CONCAT(act.codigo, ' - ', act.descripcion) AS pract_full,
    pract.fecha AS fecha_pract,
    pract.cant AS cantidad
FROM paciente p
LEFT JOIN practicas pract ON pract.id_paciente = p.id
LEFT JOIN profesional prof ON prof.id_prof = pract.profesional
LEFT JOIN actividades act ON act.id = pract.actividad
LEFT JOIN obra_social o ON o.id = p.obra_social
WHERE (pract.fecha BETWEEN ? AND ?) AND p.obra_social = ? 
ORDER BY p.id, pract.fecha";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Enlazar los parámetros
$stmt->bind_param("ssi", $fecha_desde, $fecha_hasta, $obra_social);

// Ejecutar la consulta
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();

// Procesar los resultados de la consulta agrupándolos por paciente
$resumen = [];
while ($row = $result->fetch_assoc()) {
    $pacienteId = $row['paciente_id'];
    if (!isset($resumen[$pacienteId])) {
        $resumen[$pacienteId] = [
            'nombre' => $row['nombre'],
            'benef' => $row['benef'],
            'parentesco' => $row['parentesco'],
            'prestaciones' => [],
            'totalCantidad' => 0
        ];
    }

    $resumen[$pacienteId]['prestaciones'][] = [
        'pract_full' => $row['pract_full'],
        'fecha_pract' => $row['fecha_pract'],
        'cantidad' => $row['cantidad']
    ];
    $resumen[$pacienteId]['totalCantidad'] += $row['cantidad'];
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON
echo json_encode(array_values($resumen));
?>
