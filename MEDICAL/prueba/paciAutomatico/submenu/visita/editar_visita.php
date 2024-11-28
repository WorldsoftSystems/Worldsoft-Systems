<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $nom = $_POST['visita_nom'];
    $tipo_familiar = $_POST['visita_parent'];
    $fecha = $_POST['visita_fecha'];
    $obs = $_POST['visita_obs'];

    $sql = "UPDATE visita SET nom = ?, tipo_familiar = ?, fecha = ?, obs = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sissi", $nom, $tipo_familiar, $fecha, $obs, $id);

    if ($stmt->execute()) {
        echo "Práctica actualizada correctamente";
    } else {
        echo "Error al actualizar la práctica: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
