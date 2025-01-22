<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$nom = $_POST['visita_nom'];
$tipo_familiar = $_POST['visita_parent'];
$fecha = $_POST['visita_fecha'];
$obs = $_POST['visita_obs'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO visita (id_paciente, nom, tipo_familiar, fecha, obs )
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isiss', $idPaciente, $nom, $tipo_familiar, $fecha, $obs);

if ($stmt->execute()) {
    echo "visita agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>