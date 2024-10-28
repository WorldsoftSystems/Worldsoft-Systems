<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $fecha_salida = $_POST['salida_fecha'];
    $fecha_llegada = $_POST['llegada_fecha'];
    $obs = $_POST['saliObs'];

    $sql = "UPDATE salidas SET fecha_salida = ?, fecha_llegada = ?, obs = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $fecha_salida, $fecha_llegada, $obs, $id);

    if ($stmt->execute()) {
        echo "Salida actualizada correctamente";
    } else {
        echo "Error al actualizar salida: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
