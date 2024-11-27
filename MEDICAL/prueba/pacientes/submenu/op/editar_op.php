<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
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

    $sql = "UPDATE paci_op SET fecha = ?, op = ?, cant = ?, modalidad_op = ?, fecha_vencimiento = ?  WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiisi", $fecha, $op, $cant, $modalidad_op, $fecha_vencimiento, $id);

    if ($stmt->execute()) {
        echo "op actualizada correctamente";
    } else {
        echo "Error al actualizar op: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>