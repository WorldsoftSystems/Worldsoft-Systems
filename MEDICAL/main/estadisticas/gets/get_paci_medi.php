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
$sql = "
    SELECT 
        CONCAT(p.nombre,' - ', o.siglas) AS nombre,
        p.benef,
        p.parentesco,
        CONCAT(medi.descripcion,'-',medi.potencia) AS medicamento,
        mP.fecha,
        mP.hora,
        mP.dosis,
        mP.cantidad,
        mP.valor
    FROM paciente p
    LEFT JOIN medicacion_paci mP ON mP.id_paciente = p.id
    LEFT JOIN medicacion medi ON medi.id = mP.medicamento
    LEFT JOIN obra_social o ON o.id = p.obra_social
    WHERE (mP.fecha BETWEEN ? AND ?) AND p.obra_social = ?
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
$totalGeneral = 0;
while ($row = $result->fetch_assoc()) {
    $resumen[] = $row; // Almacenar cada fila en el array
    $totalGeneral += (float)$row['valor']; // Acumular el valor total
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();

// Devolver los resultados como JSON, incluyendo el total general
echo json_encode(['data' => $resumen, 'totalGeneral' => $totalGeneral]);
?>
