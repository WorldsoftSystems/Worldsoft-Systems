<?php
require_once "../../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id']; // Asegúrate de que el formulario incluya el campo 'id'
    $fecha = $_POST['fecha'];
    $codigo = $_POST['paci_diag'];

    $sql = "UPDATE paci_diag SET fecha = ?, codigo = ? WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $fecha, $codigo, $id);

    if ($stmt->execute()) {
        echo "Diagnostico actualizado correctamente";
    } else {
        echo "Error al actualizar el diagnostico: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>