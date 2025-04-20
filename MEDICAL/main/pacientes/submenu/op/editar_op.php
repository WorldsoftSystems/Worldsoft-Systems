<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
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
            $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 182 days'));
        } elseif ($cant == 1) {
            $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 1 month'));
        }
        elseif ($cant == 5) {
            $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 152 days'));
        }
        else {
            $fecha_vencimiento = date('Y-m-d', strtotime($fecha . ' + 182 days'));
        }
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