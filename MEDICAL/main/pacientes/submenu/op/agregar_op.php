<?php
// Verifica la ruta y ajusta según sea necesario
require_once "../../../conexion.php";

// Obtener los datos del POST
$idPaciente = $_POST['id_paciente'];
$fecha = $_POST['orden_fecha'];
$op = $_POST['op'];
$cant = $_POST['op_cant'];
$modalidad_op = $_POST['modalidad_op'];

if (!preg_match('/^\d{10}$/', $op)) {
    echo "Error: El número de orden debe tener exactamente 10 dígitos.";
    exit;
}


// Calcular la fecha de vencimiento en base a la cantidad
if ($modalidad_op == 2) {
    $fecha_vencimiento = $fecha;
} else {
    // Calcular vencimiento según cantidad
    if ($cant == 3) {
        $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 90 days'));
    } elseif ($cant == 6) {
        $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 180 days'));
    } elseif ($cant == 1) {
        $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 1 month'));
    } else {
        $fecha_vencimiento = '0000-00-00';
    }
}


// Verificar si la conexión se estableció correctamente
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Preparar y ejecutar la consulta para insertar la nueva práctica
$sql = "INSERT INTO paci_op (id_paciente, fecha, op, cant, modalidad_op, fecha_vencimiento)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('issiis', $idPaciente, $fecha, $op, $cant, $modalidad_op, $fecha_vencimiento);

if ($stmt->execute()) {
    echo "op agregada correctamente.";
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar la conexión
$stmt->close();
$conn->close();
?>