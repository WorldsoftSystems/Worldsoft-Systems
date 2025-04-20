<?php
// Incluir el archivo de conexión
include('../../conexion.php');

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
        p.ugl_paciente,
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
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
        ) AS modalidad_full,
        COALESCE(
            (
                SELECT m.codigo
                FROM paci_modalidad pm
                JOIN modalidad m ON m.id = pm.modalidad 
                LEFT JOIN actividades a ON a.id = pract.actividad
                WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
                ORDER BY pm.fecha DESC
                LIMIT 1
            )
        ) AS modalidad_codigo,
        pract.fecha AS fecha_pract,
        (
        SELECT e.fecha_egreso
        FROM egresos e
        WHERE e.id_paciente = p.id
            AND e.modalidad = (
            SELECT pm.modalidad
            FROM paci_modalidad pm
            JOIN modalidad m ON m.id = pm.modalidad 
            LEFT JOIN actividades a ON a.id = pract.actividad
            WHERE pm.id_paciente = p.id
                AND pm.modalidad = a.modalidad
            ORDER BY pm.fecha DESC
            LIMIT 1
            )
            AND e.fecha_egreso >= (
            SELECT pm.fecha
            FROM paci_modalidad pm
            WHERE pm.id_paciente = p.id
                AND pm.fecha <= pract.fecha
            ORDER BY pm.fecha DESC
            LIMIT 1
            )
            AND e.fecha_egreso >= pract.fecha
        ORDER BY e.fecha_egreso DESC
        LIMIT 1
        ) AS egreso,
        d_id.codigo AS diag,
        COALESCE(pract.cant, 0) AS cantidad,
        po.op AS ops,
        CASE
            WHEN pract.fecha IS NOT NULL AND act_pract.codigo NOT IN ('520101', '521001') THEN pract.fecha
            ELSE NULL
        END AS valid_date
    FROM paciente p
    LEFT JOIN practicas pract ON pract.id_paciente = p.id
    LEFT JOIN actividades act_pract ON pract.actividad = act_pract.id
    LEFT JOIN obra_social o ON o.id = p.obra_social
    LEFT JOIN egresos e ON e.id_paciente = p.id
    LEFT JOIN modalidad m ON m.id = e.modalidad
    LEFT JOIN paci_diag d ON d.id_paciente = p.id
    LEFT JOIN diag d_id ON d_id.id = d.codigo
    LEFT JOIN paci_op po
  ON po.id_paciente = p.id
  AND po.modalidad_op = (
      SELECT m.id
      FROM paci_modalidad pm
      JOIN modalidad m ON m.id = pm.modalidad
      LEFT JOIN actividades a ON a.id = pract.actividad
      WHERE pm.id_paciente = p.id
        AND pm.modalidad = a.modalidad
      ORDER BY pm.fecha DESC
      LIMIT 1
  )
  AND po.fecha <= pract.fecha
  AND po.fecha_vencimiento >= pract.fecha

    WHERE pract.fecha BETWEEN ? AND ?
      AND p.obra_social = ?
),
MatchedOps AS (
    SELECT
        nombre,
        benef,
        ugl_paciente,
        parentesco,
        ingreso_modalidad,
        ops,
        modalidad_codigo,
        sexo,
        modalidad_full,
        valid_date AS ult_atencion,
        egreso,
        diag,
        cantidad
    FROM ValidRecords
)
SELECT *
FROM MatchedOps
ORDER BY nombre ASC, ingreso_modalidad ASC, modalidad_full ASC;




";

// Preparar la consulta
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

// Enlazar los parámetros
$stmt->bind_param(
    "ssi",
    $fecha_desde,
    $fecha_hasta, // prácticas
    $obra_social
);


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