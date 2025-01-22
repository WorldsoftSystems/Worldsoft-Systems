<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$fecha = $_POST['fecha'];
$juzgado = $_POST['juzgado'];
$secretaria = $_POST['secretaria'];
$curaduria = $_POST['curaduria'];
$t_juicio = $_POST['t_juicio'];
$obs = $_POST['judiObs'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO judiciales (id_paciente, juzgado, secre, cura, t_juicio, vencimiento, obs)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iiiiiss', $idPaciente, $juzgado, $secretaria, $curaduria, $t_juicio, $fecha, $obs);

if ($stmt->execute()) {
    echo "Judicial agregado correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
