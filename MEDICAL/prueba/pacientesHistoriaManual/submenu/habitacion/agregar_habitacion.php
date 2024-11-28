<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$habitacion = $_POST['habitacion_nro'];
$fecha_ingreso = $_POST['habi_ingreso_fecha'];
$fecha_egreso = $_POST['habi_egreso_fecha'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO paci_habitacion (id_paciente, habitacion, fecha_ingreso, fecha_egreso)
        VALUES (?,?,?,?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iiss', $idPaciente, $habitacion,$fecha_ingreso,$fecha_egreso);

if ($stmt->execute()) {
    echo "Habitacion agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>