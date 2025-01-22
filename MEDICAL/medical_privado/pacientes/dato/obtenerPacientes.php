<?php
require_once "../../conexion.php";

// Consulta para obtener todos los pacientes (sin paginación)
$sql = "SELECT DISTINCT  p.*,  
       m.descripcion AS modalidad_actual
       FROM paciente p
       LEFT JOIN paci_modalidad pM ON pM.id_paciente = p.id
       LEFT JOIN modalidad m ON m.id = (
           SELECT modalidad 
           FROM paci_modalidad 
           WHERE id_paciente = p.id 
           ORDER BY fecha DESC 
           LIMIT 1
       )
       ORDER BY p.nombre ASC
       LIMIT 25
";

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
