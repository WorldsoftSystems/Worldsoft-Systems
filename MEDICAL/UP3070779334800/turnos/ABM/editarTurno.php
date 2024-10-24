<?php
// Conectar a la base de datos
include('../../conexion.php');

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Recibir datos del formulario
$id = $_POST['turno_id'];
$id_prof = $_POST['id_prof_edit'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];
$paciente = $_POST['paciente_id_edit'];
$motivo = $_POST['motivo'];
$llego = $_POST['llego'];
$atendido = $_POST['atendido'];
$observaciones = $_POST['observaciones'];

// Convertir la fecha al formato YYYY-MM-DD
$date = DateTime::createFromFormat('d/m/Y', $fecha);
$fechaFormateada = $date ? $date->format('Y-m-d') : null;

$stmt = $conn->prepare("UPDATE turnos SET fecha = ?, hora = ?, paciente = ?, id_prof = ?, motivo = ?, llego = ?, atendido = ?, observaciones = ? WHERE id = ?");
$stmt->bind_param("ssiiisssi", $fechaFormateada, $hora, $paciente, $id_prof, $motivo, $llego, $atendido, $observaciones, $id);

// Ejecutar la declaración
if ($stmt->execute()) {
    echo "Turno actualizado exitosamente";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
