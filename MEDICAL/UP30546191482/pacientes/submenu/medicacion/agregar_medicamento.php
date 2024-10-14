<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$desc = $_POST['mediDesc'];
$fecha = $_POST['medi_fecha'];
$hora = $_POST['medi_hora'];
$dosis = $_POST['dosis'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener la fecha de admisión del paciente
$sql_admision = "SELECT admision FROM paciente WHERE id = ?";
$stmt_admision = $conn->prepare($sql_admision);
$stmt_admision->bind_param('i', $idPaciente);
$stmt_admision->execute();
$stmt_admision->bind_result($fecha_admision);
$stmt_admision->fetch();
$stmt_admision->close();

// Verificar si la fecha de la medicación es menor que la fecha de admisión
if (strtotime($fecha) < strtotime($fecha_admision)) {
    echo "Error: La fecha de la medicación no puede ser menor a la fecha de admisión del paciente.";
} else {
    // Preparar y ejecutar la consulta para insertar la nueva medicación
    $sql = "INSERT INTO medicacion_paci (id_paciente, medicamento, fecha, hora, dosis) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iissi', $idPaciente, $desc, $fecha, $hora, $dosis);

    if ($stmt->execute()) {
        echo "Medicamento agregado correctamente.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Cerrar la conexión
    $stmt->close();
}

$conn->close();
?>
