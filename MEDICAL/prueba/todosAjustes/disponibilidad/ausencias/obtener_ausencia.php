<?php
require_once "../../../conexion.php";

$id = $_GET['id'];

// Consulta para obtener los datos de la ausencia
$query = "SELECT id, fecha_inicio, fecha_fin, motivo FROM ausencias WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(['error' => 'Ausencia no encontrada.']);
}
?>
