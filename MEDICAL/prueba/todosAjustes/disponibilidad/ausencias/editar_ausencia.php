<?php
require_once "../../../conexion.php";

// Captura los datos enviados
$id = $_POST['id_ausencia'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$motivo = $_POST['motivo'];

// Validación básica
if (empty($id) || empty($fecha_inicio) || empty($fecha_fin) || empty($motivo)) {
    echo "Todos los campos son obligatorios.";
    exit;
}

// Actualiza los datos en la tabla de ausencias
$query = "UPDATE ausencias SET fecha_inicio = ?, fecha_fin = ?, motivo = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sssi", $fecha_inicio, $fecha_fin, $motivo, $id);

if ($stmt->execute()) {
    echo "Ausencia actualizada correctamente.";
} else {
    echo "Error al actualizar la ausencia: " . $stmt->error;
}
?>
