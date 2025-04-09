<?php
require_once "../../conexion.php";

$idPaciente = $_GET['id_paciente']; // Recibe el ID del paciente desde AJAX

$sql = "SELECT id, codigo, fecha FROM paci_diag WHERE id_paciente = ? ORDER BY fecha DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idPaciente);
$stmt->execute();
$result = $stmt->get_result();

$max_diag = $result->fetch_assoc(); // Obtiene el diagn贸stico m谩s reciente

echo json_encode($max_diag);

$stmt->close();
$conn->close();
?>
