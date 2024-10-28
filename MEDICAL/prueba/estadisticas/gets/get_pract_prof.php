<?php
// Incluir el archivo de conexión
include ('../../conexion.php');

// Obtener los parámetros de la URL
$fecha_desde = $_GET['fecha_desde'] ?? '';
$fecha_hasta = $_GET['fecha_hasta'] ?? '';
$obra_social = $_GET['obra_social'] ?? '';
$profesional = $_GET['profesional'] ?? '';

// Verificar si los parámetros están definidos y no están vacíos
if (empty($fecha_desde) || empty($fecha_hasta)) {
    die(json_encode(['error' => 'Parámetros de fecha inválidos']));
}

// Inicializar la consulta SQL base
$sql = "SELECT DISTINCT
    CONCAT(p.nombre, ' - ', o.siglas) AS nombre,
    p.benef,
    p.parentesco,
    COALESCE(
        (
            SELECT m.descripcion
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha > (
                SELECT COALESCE(MAX(e.fecha_egreso), '9999-12-31')
                FROM egresos e
                WHERE e.id_paciente = p.id
            )
            AND pm.fecha <= t.fecha
            ORDER BY pm.fecha ASC
            LIMIT 1
        ),
        (
            SELECT m.descripcion
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha <= t.fecha
            ORDER BY pm.fecha DESC
            LIMIT 1
        )
    ) AS modalidad_full,
    CONCAT(act.codigo, ' - ', act.descripcion) AS turno_pract,
    t.fecha AS fecha_turno,
    d_id.codigo AS diag,
    prof.nombreYapellido AS prof,
    NULL AS cantidad
FROM paciente p
LEFT JOIN turnos t ON t.paciente = p.id
LEFT JOIN actividades act ON act.id = t.motivo
LEFT JOIN profesional prof ON prof.id_prof = t.id_prof
LEFT JOIN obra_social o ON o.id = p.obra_social
LEFT JOIN paci_diag d ON d.id_paciente = p.id
LEFT JOIN diag d_id ON d_id.id = d.codigo
WHERE (t.fecha BETWEEN ? AND ?) AND p.obra_social = ?";

if ($profesional) {
    $sql .= " AND t.id_prof = ?";
}

$sql .= " GROUP BY nombre, p.benef, p.parentesco, modalidad_full, turno_pract, t.fecha, d_id.codigo, prof.nombreYapellido";

$sql .= " UNION SELECT DISTINCT
    CONCAT(p.nombre, ' - ', o.siglas) AS nombre,
    p.benef,
    p.parentesco,
    COALESCE(
        (
            SELECT m.descripcion
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha > (
                SELECT COALESCE(MAX(e.fecha_egreso), '9999-12-31')
                FROM egresos e
                WHERE e.id_paciente = p.id
            )
            AND pm.fecha <= pract.fecha
            ORDER BY pm.fecha ASC
            LIMIT 1
        ),
        (
            SELECT m.descripcion
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha <= pract.fecha
            ORDER BY pm.fecha DESC
            LIMIT 1
        )
    ) AS modalidad_full,
    CONCAT(act.codigo, ' - ', act.descripcion) AS pract_full,
    pract.fecha AS fecha_pract,
    d_id.codigo AS diag,
    prof.nombreYapellido AS prof,
    pract.cant AS cantidad
FROM paciente p
LEFT JOIN practicas pract ON pract.id_paciente = p.id
LEFT JOIN actividades act ON act.id = pract.actividad
LEFT JOIN obra_social o ON o.id = p.obra_social
LEFT JOIN paci_diag d ON d.id_paciente = p.id
LEFT JOIN diag d_id ON d_id.id = d.codigo
LEFT JOIN profesional prof ON prof.id_prof = pract.profesional
WHERE (pract.fecha BETWEEN ? AND ?) AND p.obra_social = ?";

if ($profesional) {
    $sql .= " AND pract.profesional = ?";
}

$sql .= " GROUP BY nombre, p.benef, p.parentesco, modalidad_full, pract_full, pract.fecha, d_id.codigo, prof.nombreYapellido";


// Preparar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Determinar los tipos de parámetros
if ($profesional) {
    $stmt->bind_param("ssiissii", $fecha_desde, $fecha_hasta, $obra_social, $profesional, $fecha_desde, $fecha_hasta, $obra_social, $profesional);
} else {
    $stmt->bind_param("ssissi", $fecha_desde, $fecha_hasta, $obra_social, $fecha_desde, $fecha_hasta, $obra_social);
}

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
