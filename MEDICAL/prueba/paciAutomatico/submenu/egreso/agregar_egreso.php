<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$fecha = $_POST['fecha'];
$diag = $_POST['egreso_diag'];
$modalidad = $_POST['egreso_modalidad'];
$motivo = !empty($_POST['egreso_motivo']) ? $_POST['egreso_motivo'] : NULL;
$hora_egreso = $_POST['hora_egreso'];

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit;
}

// Obtener la fecha máxima de las prácticas del paciente
$sqlMaxFecha = "SELECT MAX(fecha) AS max_fecha FROM practicas WHERE id_paciente = ?";
$stmtMaxFecha = $conn->prepare($sqlMaxFecha);
$stmtMaxFecha->bind_param('i', $idPaciente);
$stmtMaxFecha->execute();
$resultMaxFecha = $stmtMaxFecha->get_result();
$row = $resultMaxFecha->fetch_assoc();
$maxFecha = $row['max_fecha'];

// Verificar si la fecha del egreso es mayor que la fecha máxima de las prácticas
if ($fecha <= $maxFecha) {
    echo json_encode(['status' => 'error', 'message' => 'La fecha de egreso debe ser mayor que la fecha máxima de las prácticas del paciente.']);
} else {
    // Preparar la consulta para insertar el egreso
    $sqlInsert = "INSERT INTO egresos (id_paciente, fecha_egreso, diag, modalidad, motivo, hora_egreso)
                  VALUES (?, ?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param('isiiis', $idPaciente, $fecha, $diag, $modalidad, $motivo, $hora_egreso);

    if ($stmtInsert->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Egreso agregado correctamente.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar el egreso: ' . $stmtInsert->error]);
    }

    // Cerrar la sentencia de inserción
    $stmtInsert->close();
}

// Cerrar la conexión
$stmtMaxFecha->close();
$conn->close();
?>
