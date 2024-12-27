<?php
require_once "../../conexion.php";

$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT DISTINCT p.*, u.descripcion AS ugl_descripcion,m.descripcion AS modalidad_actual
        FROM paciente p 
        LEFT JOIN codigo_ugl u ON u.id = p.ugl_paciente 
        LEFT JOIN paci_modalidad pM ON pM.id_paciente = p.id
       LEFT JOIN modalidad m ON m.id = (
           SELECT modalidad 
           FROM paci_modalidad 
           WHERE id_paciente = p.id 
           ORDER BY fecha DESC 
           LIMIT 1
       )
        WHERE LOWER(p.nombre) LIKE ? OR LOWER(p.benef) LIKE ? 
        ORDER BY p.nombre ASC";

$stmt = $conn->prepare($sql);
$likeSearch = "%" . strtolower($search) . "%";
$stmt->bind_param("ss", $likeSearch, $likeSearch); // Un bind para cada LIKE
$stmt->execute();
$result = $stmt->get_result();

$pacientes = array();
while ($row = $result->fetch_assoc()) {
    $pacientes[] = $row;
}

$stmt->close();
$conn->close();

// Devolver los resultados en formato JSON
header('Content-Type: application/json');
echo json_encode(['data' => $pacientes]);
?>