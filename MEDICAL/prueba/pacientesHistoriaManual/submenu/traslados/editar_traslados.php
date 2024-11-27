<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $hora = $_POST['tras_hora'];
    $importe = $_POST['tras_importe'];
    $obs = $_POST['tras_obs'];
    $fecha = $_POST['tras_fecha'];

    $sql = "UPDATE traslados SET hora = ?, importe = ?, obs = ?, fecha = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $hora, $importe, $obs, $fecha, $id);

    if ($stmt->execute()) {
        echo "traslado actualizada correctamente";
    } else {
        echo "Error al actualizar traslado: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>