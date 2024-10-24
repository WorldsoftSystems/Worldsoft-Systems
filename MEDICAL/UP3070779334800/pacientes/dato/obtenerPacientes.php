<?php
require_once "../../conexion.php";

// Consulta para obtener todos los pacientes (sin paginación)
$sql = "SELECT p.*, u.descripcion AS ugl_descripcion 
        FROM paciente p 
        LEFT JOIN codigo_ugl u ON u.id = p.ugl_paciente 
        ORDER BY p.nombre ASC
        LIMIT 300"; // Limita los resultados a 300
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die(json_encode(['error' => 'Error en la consulta: ' . $conn->error]));
}

$pacientes = array();
while ($row = $result->fetch_assoc()) {
    $pacientes[] = $row;
}

$conn->close();

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode(['data' => $pacientes]);

?>
