<?php
require_once "../../../conexion.php";

$id_prof = $_GET['id_prof'];

// Consulta las ausencias del profesional
$query = "SELECT id, fecha_inicio, fecha_fin, motivo FROM ausencias WHERE id_prof = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_prof);
$stmt->execute();
$result = $stmt->get_result();

$ausencias = [];
while ($row = $result->fetch_assoc()) {
    $ausencias[] = $row;
}

echo json_encode($ausencias);
?>
