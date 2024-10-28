<?php
include('../../conexion.php');

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$id_prof = $_POST['id_prof_input'];
$fecha = $_POST['fecha_input']; // Fecha en formato DD/MM/YYYY
$hora = $_POST['hora_input'];
$paciente = $_POST['paciente_id'];
$motivo = $_POST['motivo'];
$llego = $_POST['llego'];
$atendido = $_POST['atendido'];
$observaciones = $_POST['observaciones'];

// Convertir la fecha al formato YYYY-MM-DD
$date = DateTime::createFromFormat('d/m/Y', $fecha);
$fechaFormateada = $date ? $date->format('Y-m-d') : null;

// Preparar y vincular
$stmt = $conn->prepare("INSERT INTO turnos (fecha, hora, paciente, id_prof, motivo, llego, atendido, observaciones) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssiiisss", $fechaFormateada, $hora, $paciente, $id_prof, $motivo, $llego, $atendido, $observaciones);

// Ejecutar la declaración
if ($stmt->execute()) {
    echo "Nuevo turno creado exitosamente";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();




$conn->close();
?>
