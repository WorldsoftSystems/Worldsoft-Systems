<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$fecha = $_POST['orden_fecha'];
$op = $_POST['op'];
$cant = $_POST['op_cant'];
$modalidad_op = $_POST['modalidad_op'];

// Calcular la fecha de vencimiento en base a la cantidad
if ($cant == 3) {
    $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 90 days'));
} elseif ($cant == 6) {
    $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 180 days'));
} else {
    $fecha_vencimiento = null; // O cualquier otro valor predeterminado si no se cumplen las condiciones
}

// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO paci_op (id_paciente, fecha, op, cant, modalidad_op, fecha_vencimiento)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('isiiis', $idPaciente, $fecha, $op, $cant, $modalidad_op, $fecha_vencimiento);

if ($stmt->execute()) {
    echo "op agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>
