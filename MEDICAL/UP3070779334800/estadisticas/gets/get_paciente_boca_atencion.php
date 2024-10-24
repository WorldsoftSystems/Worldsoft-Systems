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
$sql = "WITH ValidRecords AS (
    SELECT
        p.id AS paciente_id,
        p.nombre,
        o.siglas,
        p.benef,
        p.parentesco,
        COALESCE(
        (
            SELECT pm.fecha
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha <= pract.fecha
            ORDER BY pm.fecha DESC
            LIMIT 1
        ),
        (
            SELECT pm.fecha
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
        )
        ) AS ingreso_modalidad,
        p.sexo,
        COALESCE(
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
            AND pm.fecha <= pract.fecha
            ORDER BY pm.fecha ASC
            LIMIT 1
        ),
        (
            SELECT CONCAT(m.codigo , ' - ', m.descripcion)
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad
            WHERE pm.id_paciente = p.id
            AND pm.fecha <= pract.fecha
            ORDER BY pm.fecha DESC
            LIMIT 1
        )
        ) AS modalidad_full,
        t.fecha AS fecha_turno,
        pract.fecha AS fecha_pract,
        (
            SELECT e.fecha_egreso
            FROM egresos e
            WHERE e.id_paciente = p.id
            AND e.modalidad = (
                SELECT pm.modalidad
                FROM paci_modalidad pm
                WHERE pm.id_paciente = p.id
                AND pm.fecha <= pract.fecha
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
            ORDER BY e.fecha_egreso DESC
            LIMIT 1
        ) AS egreso,
        d_id.codigo AS diag,
        b.boca AS boca_de_atencion,
        p.admision,
        COALESCE(pract.cant, 0) AS cantidad,
        CASE
            WHEN t.fecha IS NOT NULL AND act_t.codigo NOT IN ('520101', '521001') THEN t.fecha
            WHEN pract.fecha IS NOT NULL AND act_pract.codigo NOT IN ('520101', '521001') THEN pract.fecha
            ELSE NULL
        END AS valid_date
    FROM paciente p
    LEFT JOIN turnos t ON t.paciente = p.id
    LEFT JOIN practicas pract ON pract.id_paciente = p.id
    LEFT JOIN actividades act_t ON t.motivo = act_t.id
    LEFT JOIN actividades act_pract ON pract.actividad = act_pract.id
    LEFT JOIN obra_social o ON o.id = p.obra_social
    LEFT JOIN egresos e ON e.id_paciente = p.id
    LEFT JOIN modalidad m ON m.id = e.modalidad
    LEFT JOIN paci_diag d ON d.id_paciente = p.id
    LEFT JOIN diag d_id ON d_id.id = d.codigo
    LEFT JOIN bocas_atencion b ON b.id = p.boca_atencion
    WHERE (t.fecha BETWEEN ? AND ? OR pract.fecha BETWEEN ? AND ?)
      AND p.obra_social = ?
)

SELECT
    nombre,
    benef,
    parentesco,
    ingreso_modalidad,
    sexo,
    modalidad_full,
    admision,
    MAX(valid_date) AS ult_atencion,
    MAX(fecha_turno) AS fecha_turno,
    egreso,
    diag,
    boca_de_atencion,
    SUM(cantidad) AS cantidad
FROM ValidRecords
GROUP BY nombre, benef, parentesco, ingreso_modalidad, sexo, modalidad_full, egreso, diag
ORDER BY ult_atencion DESC;

";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Enlazar los parámetros
$stmt->bind_param("ssssi", $fecha_desde, $fecha_hasta,$fecha_desde, $fecha_hasta,$obra_social);

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