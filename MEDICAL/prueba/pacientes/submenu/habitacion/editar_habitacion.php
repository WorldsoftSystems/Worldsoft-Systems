<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $habitacion = $_POST['habitacion_nro'];
    $fecha_ingreso= $_POST['habi_ingreso_fecha'];
    $fecha_egreso = $_POST['habi_egreso_fecha'];
    

    $sql = "UPDATE paci_habitacion SET habitacion = ?, fecha_ingreso = ?, fecha_egreso = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issi", $habitacion, $fecha_ingreso, $fecha_egreso, $id);

    if ($stmt->execute()) {
        echo "Habitacion actualizada correctamente";
    } else {
        echo "Error al actualizar la habitacion: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
