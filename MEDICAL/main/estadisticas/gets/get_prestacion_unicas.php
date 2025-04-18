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

// Consulta SQL sin referencias
$sql = "SELECT DISTINCT
    CONCAT(p.nombre,' - ', o.siglas) AS nombre,
    p.benef,
    p.parentesco,
    COALESCE(
        (
            SELECT CONCAT(m.codigo , ' - ', m.descripcion)
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha <= pract.fecha
            AND m.codigo IN (1, 2, 3, 4) -- Filtrar modalidades específicas
            ORDER BY pm.fecha DESC
            LIMIT 1
        ),
        (
            SELECT CONCAT(m.codigo , ' - ', m.descripcion)
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha > (
                SELECT COALESCE(MAX(e.fecha_egreso), '9999-12-31')
                FROM egresos e
                WHERE e.id_paciente = p.id
            )
            AND m.codigo IN (1, 2, 3, 4) -- Filtrar modalidades específicas
            AND pm.fecha <= pract.fecha
            ORDER BY pm.fecha ASC
            LIMIT 1
        )
    ) AS modalidad_full,
    CONCAT(act.codigo, ' - ', act.descripcion) AS pract_full,
    MAX(pract.fecha) AS fecha_pract,
    pract.cant AS cantidad
FROM paciente p
LEFT JOIN obra_social o ON o.id = p.obra_social
LEFT JOIN practicas pract ON pract.id_paciente = p.id
LEFT JOIN actividades act ON act.id = pract.actividad
WHERE (pract.fecha BETWEEN ? AND ?)
AND p.obra_social = ?
GROUP BY p.benef
";

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
