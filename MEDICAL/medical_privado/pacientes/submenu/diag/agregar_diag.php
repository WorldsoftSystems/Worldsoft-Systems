<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$fecha = $_POST['fecha'];
$diag = $_POST['paci_diag'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO paci_diag (id_paciente, fecha, codigo)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isi', $idPaciente, $fecha, $diag);

if ($stmt->execute()) {
    echo "Diagnostico agregado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
