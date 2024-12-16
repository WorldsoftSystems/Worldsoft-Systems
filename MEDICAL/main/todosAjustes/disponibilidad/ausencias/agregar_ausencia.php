<?php
require_once "../../../conexion.php";

// Captura los datos enviados
$id_prof = $_POST['id_prof'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$motivo = $_POST['motivo'];

// Validación básica de datos
if (empty($id_prof) || empty($fecha_inicio) || empty($fecha_fin) || empty($motivo)) {
    echo "Todos los campos son obligatorios.";
    exit;
}

// Inserta los datos en la tabla de ausencias
$query = "INSERT INTO ausencias (id_prof, fecha_inicio, fecha_fin, motivo) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("isss", $id_prof, $fecha_inicio, $fecha_fin, $motivo);

if ($stmt->execute()) {
    echo "Ausencia agregada correctamente.";
} else {
    echo "Error al agregar la ausencia: " . $stmt->error;
}
?>
