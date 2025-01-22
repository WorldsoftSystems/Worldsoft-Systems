<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$fecha_salida = $_POST['salida_fecha'];
$fecha_llegada = $_POST['llegada_fecha'];
$obs = $_POST['saliObs'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO salidas (id_paciente, fecha_salida, fecha_llegada, obs)
        VALUES (?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isss', $idPaciente, $fecha_salida, $fecha_llegada, $obs);

if ($stmt->execute()) {
    echo "Salida agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>