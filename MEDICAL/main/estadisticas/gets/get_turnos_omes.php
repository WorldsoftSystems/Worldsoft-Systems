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
$sql = "SELECT 
    CONCAT(p.nombre,' - ', o.siglas) AS nombre,
    p.benef,
    p.parentesco,
    COALESCE(
        (
            SELECT m.descripcion
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha <= t.fecha
            ORDER BY pm.fecha DESC
            LIMIT 1
        ),
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
        )
    ) AS modalidad_full,
    CONCAT(act.codigo, ' - ', act.descripcion) AS turno_pract,
    prof.nombreYapellido AS prof,
    t.fecha AS fecha_turno
FROM paciente p
LEFT JOIN turnos t ON t.paciente = p.id
LEFT JOIN actividades act ON act.id = t.motivo
LEFT JOIN profesional prof ON prof.id_prof = t.id_prof
LEFT JOIN obra_social o   ON o.id = p.obra_social
WHERE (t.fecha BETWEEN ? AND ?) AND p.obra_social = ? AND (act.codigo = 520101 OR act.codigo=521001) AND t.atendido = 'SI'
ORDER BY t.fecha";

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