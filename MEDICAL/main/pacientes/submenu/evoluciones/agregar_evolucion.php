<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$prof = $_POST['evoProf'];
$frecuencia = $_POST['frecuencia'];
$fecha = $_POST['evoFecha'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO evoluciones_amb (id_paciente,frecuencia, fecha, id_prof )
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('issi', $idPaciente,$frecuencia,$fecha, $prof);

if ($stmt->execute()) {
    echo "Evolucion agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
