<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$hora = $_POST['tras_hora'];
$importe = $_POST['tras_importe'];
$obs = $_POST['tras_obs'];
$fecha = $_POST['tras_fecha'];
// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO traslados (id_paciente, hora, importe, obs, fecha)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isiss', $idPaciente, $hora, $importe, $obs, $fecha);

if ($stmt->execute()) {
    echo "traslado agregado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
